<?php

namespace Myth\Auth\Controllers;

use CodeIgniter\Controller;
use CodeIgniter\Session\Session;
use Daycry\Encryption\CryptoJsAes;
use Myth\Auth\Config\Auth as AuthConfig;
use Myth\Auth\Entities\User;
use Myth\Auth\Models\UserModel;

class AuthController extends Controller
{
    protected $auth;

    /**
     * @var AuthConfig
     */
    protected $config;

    /**
     * @var Session
     */
    protected $session;

    public function __construct()
    {
        // Most services in this controller require
        // the session to be started - so fire it up!
        $this->session = service('session');

        $this->config = config('Auth');
        $this->auth = service('authentication');
    }

    //--------------------------------------------------------------------
    // Login/out
    //--------------------------------------------------------------------

    /**
     * Displays the login form, or redirects
     * the user to their destination/home if
     * they are already logged in.
     */
    public function login()
    {
        // No need to show a login form if the user
        // is already logged in.   
        if ($this->auth->check()) {
            $redirectURL = session('redirect_url') ?? site_url('/');
            unset($_SESSION['redirect_url']);

            return redirect()->to($redirectURL);
        }

        // Set a return URL if none is specified
        $_SESSION['key'] = $this->str_rand();
        $_SESSION['redirect_url'] = session('redirect_url') ?? previous_url() ?? site_url('/');
        return $this->_render($this->config->views['login'], ['config' => $this->config, 'key' => $_SESSION['key']]);
    }

    /**
     * Attempts to verify the user's credentials
     * through a POST request.
     */
    public function attemptLogin()
    {
        $rules = [
            'login' => 'required',
            'password' => 'required',
        ];
        // rate limiter
        $throttler = \Config\Services::throttler();
        $allowed = $throttler->check('login', 4, MINUTE);
        if ($allowed) {

            // Verify the reCAPTCHA API response
            $recaptcha = $this->request->getPost('g-recaptcha-response');
            $recaptcha_secret_key = "6LdBgpskAAAAAClZI-BVN_BBeB2PtPCAl795BHuN";
            $verifyResponse = file_get_contents('https://www.google.com/recaptcha/api/siteverify?secret=' . $recaptcha_secret_key . '&response=' . $recaptcha);
            $responseData = json_decode($verifyResponse);
            if ($responseData->success === true) {
                if ($this->config->validFields === ['email']) {
                    $rules['login'] .= '|valid_email';
                }

                if (!$this->validate($rules)) {
                    return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
                }

                $login = $this->request->getPost('login');
                $encrypted_password = $this->request->getPost('password');
                var_dump($encrypted_password);
                die();
                $password = CryptoJsAes::decrypt($encrypted_password, $_SESSION['key']);
                $remember = (bool)$this->request->getPost('remember');
                // Determine credential type
                $type = filter_var($login, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';

                // Try to log them in...
                if (!$this->auth->attempt([$type => $login, 'password' => $password], $remember)) {
                    return redirect()->back()->withInput()->with('error', $this->auth->error() ?? lang('Auth.badAttempt'));
                }

                // Is the user being forced to reset their password?
                if ($this->auth->user()->force_pass_reset === true) {
                    return redirect()->to(route_to('reset-password') . '?token=' . $this->auth->user()->reset_hash)->withCookies();
                }

                $redirectURL = session('redirect_url') ?? site_url('/');
                unset($_SESSION['redirect_url']);

                return redirect()->to($redirectURL)->withCookies()->with('message', lang('Auth.loginSuccess'));

            } elseif ($responseData->success === false) {
                $redirectURL = session('redirect_url') ?? site_url('/');
                unset($_SESSION['redirect_url']);
                return redirect()->to($redirectURL)->withCookies()->with('error', lang('Auth.captchaError'));
            }
        } else {
            $redirectURL = session('redirect_url') ?? site_url('/');
            unset($_SESSION['redirect_url']);
            return redirect()->to($redirectURL)->withCookies()->with('error', lang('Auth.tooManyAttempts'));

        }
    }

    /**
     * Log the user out.
     */
    public function logout()
    {
        if ($this->auth->check()) {
            $this->auth->logout();
            session_destroy();
        }

        return redirect()->to(site_url('/'));
    }

    public function timeout()
    {
        if ($this->auth->check()) {
            $this->auth->logout();
        }
        $redirectURL = session('redirect_url') ?? site_url('/');
        unset($_SESSION['redirect_url']);
        return redirect()->to($redirectURL)->withCookies()->with('message', lang('Auth.timeout'));
    }

    //--------------------------------------------------------------------
    // Register
    //--------------------------------------------------------------------

    /**
     * Displays the user registration page.
     */
    public function register()
    {
        // check if already logged in.
        if ($this->auth->check()) {
            return redirect()->back();
        }

        // Check if registration is allowed
        if (!$this->config->allowRegistration) {
            return redirect()->back()->withInput()->with('error', lang('Auth.registerDisabled'));
        }

        return $this->_render($this->config->views['register'], ['config' => $this->config]);
    }

    /**
     * Attempt to register a new user.
     */
    public function attemptRegister()
    {
        // Check if registration is allowed
        if (!$this->config->allowRegistration) {
            return redirect()->back()->withInput()->with('error', lang('Auth.registerDisabled'));
        }

        $users = model(UserModel::class);

        // Validate basics first since some password rules rely on these fields
        $rules = [
            'username' => 'required|alpha_numeric_space|min_length[3]|max_length[30]|is_unique[users.username]',
            'email' => 'required|valid_email|is_unique[users.email]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // Validate passwords since they can only be validated properly here
        $rules = [
            'password' => 'required|strong_password',
            'pass_confirm' => 'required|matches[password]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // Save the user
        $allowedPostFields = array_merge(['password'], $this->config->validFields, $this->config->personalFields);
        $user = new User($this->request->getPost($allowedPostFields));

        $this->config->requireActivation === null ? $user->activate() : $user->generateActivateHash();

        // Ensure default group gets assigned if set
        if (!empty($this->config->defaultUserGroup)) {
            $users = $users->withGroup($this->config->defaultUserGroup);
        }

        if (!$users->save($user)) {
            return redirect()->back()->withInput()->with('errors', $users->errors());
        }

        if ($this->config->requireActivation !== null) {
            $activator = service('activator');
            $sent = $activator->send($user);

            if (!$sent) {
                return redirect()->back()->withInput()->with('error', $activator->error() ?? lang('Auth.unknownError'));
            }

            // Success!
            return redirect()->route('login')->with('message', lang('Auth.activationSuccess'));
        }

        // Success!
        return redirect()->route('login')->with('message', lang('Auth.registerSuccess'));
    }

    //--------------------------------------------------------------------
    // Forgot Password
    //--------------------------------------------------------------------

    /**
     * Displays the forgot password form.
     */
    public function forgotPassword()
    {
        if ($this->config->activeResetter === null) {
            return redirect()->route('login')->with('error', lang('Auth.forgotDisabled'));
        }

        return $this->_render($this->config->views['forgot'], ['config' => $this->config]);
    }

    /**
     * Attempts to find a user account with that password
     * and send password reset instructions to them.
     */
    public function attemptForgot()
    {
        if ($this->config->activeResetter === null) {
            return redirect()->route('login')->with('error', lang('Auth.forgotDisabled'));
        }

        $rules = [
            'email' => [
                'label' => lang('Auth.emailAddress'),
                'rules' => 'required|valid_email',
            ],
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $users = model(UserModel::class);

        $user = $users->where('email', $this->request->getPost('email'))->first();

        if (null === $user) {
            return redirect()->back()->with('error', lang('Auth.forgotNoUser'));
        }

        // Save the reset hash /
        $user->generateResetHash();
        $users->save($user);

        $resetter = service('resetter');
        $sent = $resetter->send($user);

        if (!$sent) {
            return redirect()->back()->withInput()->with('error', $resetter->error() ?? lang('Auth.unknownError'));
        }

        return redirect()->route('reset-password')->with('message', lang('Auth.forgotEmailSent'));
    }

    /**
     * Displays the Reset Password form.
     */
    public function resetPassword()
    {
        if ($this->config->activeResetter === null) {
            return redirect()->route('login')->with('error', lang('Auth.forgotDisabled'));
        }

        $token = $this->request->getGet('token');

        return $this->_render($this->config->views['reset'], [
            'config' => $this->config,
            'token' => $token,
        ]);
    }

    /**
     * Verifies the code with the email and saves the new password,
     * if they all pass validation.
     *
     * @return mixed
     */
    public function attemptReset()
    {
        if ($this->config->activeResetter === null) {
            return redirect()->route('login')->with('error', lang('Auth.forgotDisabled'));
        }

        $users = model(UserModel::class);

        // First things first - log the reset attempt.
        $users->logResetAttempt(
            $this->request->getPost('email'),
            $this->request->getPost('token'),
            $this->request->getIPAddress(),
            (string)$this->request->getUserAgent()
        );

        $rules = [
            'token' => 'required',
            'email' => 'required|valid_email',
            'password' => 'required|strong_password',
            'pass_confirm' => 'required|matches[password]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $user = $users->where('email', $this->request->getPost('email'))
            ->where('reset_hash', $this->request->getPost('token'))
            ->first();

        if (null === $user) {
            return redirect()->back()->with('error', lang('Auth.forgotNoUser'));
        }

        // Reset token still valid?
        if (!empty($user->reset_expires) && time() > $user->reset_expires->getTimestamp()) {
            return redirect()->back()->withInput()->with('error', lang('Auth.resetTokenExpired'));
        }

        // Success! Save the new password, and cleanup the reset hash.
        $user->password = $this->request->getPost('password');
        $user->reset_hash = null;
        $user->reset_at = date('Y-m-d H:i:s');
        $user->reset_expires = null;
        $user->force_pass_reset = false;
        $users->save($user);

        return redirect()->route('login')->with('message', lang('Auth.resetSuccess'));
    }

    /**
     * Activate account.
     *
     * @return mixed
     */
    public function activateAccount()
    {
        $users = model(UserModel::class);

        // First things first - log the activation attempt.
        $users->logActivationAttempt(
            $this->request->getGet('token'),
            $this->request->getIPAddress(),
            (string)$this->request->getUserAgent()
        );

        $throttler = service('throttler');

        if ($throttler->check(md5($this->request->getIPAddress()), 2, MINUTE) === false) {
            return service('response')->setStatusCode(429)->setBody(lang('Auth.tooManyRequests', [$throttler->getTokentime()]));
        }

        $user = $users->where('activate_hash', $this->request->getGet('token'))
            ->where('active', 0)
            ->first();

        if (null === $user) {
            return redirect()->route('login')->with('error', lang('Auth.activationNoUser'));
        }

        $user->activate();

        $users->save($user);

        return redirect()->route('login')->with('message', lang('Auth.registerSuccess'));
    }

    /**
     * Resend activation account.
     *
     * @return mixed
     */
    public function resendActivateAccount()
    {
        if ($this->config->requireActivation === null) {
            return redirect()->route('login');
        }

        $throttler = service('throttler');

        if ($throttler->check(md5($this->request->getIPAddress()), 2, MINUTE) === false) {
            return service('response')->setStatusCode(429)->setBody(lang('Auth.tooManyRequests', [$throttler->getTokentime()]));
        }

        $login = urldecode($this->request->getGet('login'));
        $type = filter_var($login, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';

        $users = model(UserModel::class);

        $user = $users->where($type, $login)
            ->where('active', 0)
            ->first();

        if (null === $user) {
            return redirect()->route('login')->with('error', lang('Auth.activationNoUser'));
        }

        $activator = service('activator');
        $sent = $activator->send($user);

        if (!$sent) {
            return redirect()->back()->withInput()->with('error', $activator->error() ?? lang('Auth.unknownError'));
        }

        // Success!
        return redirect()->route('login')->with('message', lang('Auth.activationSuccess'));
    }

    protected function _render(string $view, array $data = [])
    {
        return view($view, $data);
    }

    protected function str_rand(int $length = 64)
    { // 64 = 32
        $length = ($length < 4) ? 4 : $length;
        return bin2hex(random_bytes(($length - ($length % 2)) / 2));
    }
}