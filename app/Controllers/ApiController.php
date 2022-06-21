<?php

namespace App\Controllers;

use App\Models\ApiUserModel;
use App\Models\CaseModel;
use App\Models\MitraModel;
use CodeIgniter\API\ResponseTrait;
use CodeIgniter\HTTP\IncomingRequest;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\RESTful\ResourceController;
use Config\Services;
use Exception;


class ApiController extends ResourceController
{
    use ResponseTrait;

    protected function getRequestInput(IncomingRequest $request)
    {
        $input = $request->getPost();
        if (empty($input)) {
            //convert request body to associative array
            $input = json_decode($request->getBody(), true);
        }
        return $input;
    }

    protected function validateRequest($input, array $rules, array $messages = []): bool
    {
        $this->validator = Services::Validation()->setRules($rules);
        return $this->validator->setRules($rules, $messages)->run($input);
    }

    public function login(): \CodeIgniter\HTTP\Response
    {
        $rules = [
            'username' => 'required|min_length[6]|max_length[100]|alpha_numeric',
            'password' => 'required|min_length[8]|max_length[20]|validateUser[username, password]'
        ];

        $errors = [
            'password' => [
                'validateUser' => 'Invalid login credentials provided'
            ]
        ];

        $input = $this->getRequestInput($this->request);
        if (!$this->validateRequest($input, $rules, $errors)) {
            return $this
                ->getResponse(
                    $this->validator->getErrors(),
                    ResponseInterface::HTTP_BAD_REQUEST
                );
        }
        return $this->getJWTForUser($input['username']);
    }

    protected function getJWTForUser(string $username, int $responseCode = ResponseInterface::HTTP_OK): \CodeIgniter\HTTP\Response
    {
        try {
            $model = new ApiUserModel();
            $user = $model->findUser($username);
            unset($user['password']);
            unset($user['updated_at']);
            unset($user['created_at']);
            helper('jwt');
            return $this
                ->getResponse(
                    [
                        'message' => 'User authenticated successfully',
                        'user' => $user,
                        'access_token' => getSignedJWTForUser($username)
                    ]
                );
        } catch (Exception $exception) {
            return $this
                ->getResponse(
                    [
                        'error' => $exception->getMessage(),
                    ],
                    $responseCode
                );
        }
    }

    public function getResponse(array $responseBody, int $code = ResponseInterface::HTTP_OK): \CodeIgniter\HTTP\Response
    {
        return $this
            ->response
            ->setStatusCode($code)
            ->setJSON($responseBody);
    }

    // date format yyyy-mm-dd
    // test url for case api request => /api/case/?fromdate=2022-01-01&todate=2022-05-25&limit=1&pageno=1
    public function getCases(): \CodeIgniter\HTTP\Response
    {

        $rules = [
            'fromdate' => 'trim|required|regex_match[/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/]',
            'todate' => 'trim|required|regex_match[/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/]',
            'limit' => 'permit_empty|trim|numeric|greater_than[0]',
            'pageno' => 'permit_empty|trim|numeric|greater_than[0]'
        ];

        if (!$this->validateRequest($_GET, $rules)) {
            return $this
                ->getResponse(
                    $this->validator->getErrors(),
                    ResponseInterface::HTTP_BAD_REQUEST
                );
        }

        if (!isset($_GET['limit'])) {
            $no_of_records_per_page = 1000;
        } else {
            $no_of_records_per_page = $_GET['limit'];
        }
        if (!isset($_GET['pageno'])) {
            $page_no = 1;
        } else {
            $page_no = $_GET['pageno'];
        }

        $case_model = new CaseModel();
        $total_rows = $case_model->getTotalNumberOfCaseData($_GET['fromdate'], $_GET['todate']);
        $total_pages = ceil($total_rows / $no_of_records_per_page);
        $duration = $_GET['fromdate'] . " to " . $_GET['todate'];
        $offset = ($page_no - 1) * $no_of_records_per_page;
        $data = $case_model->getDetectedCaseForAPI($_GET['fromdate'], $_GET['todate'], $offset, $no_of_records_per_page);

        return $this
            ->getResponse(
                [
                    'no_of_records' => $total_rows,
                    'duration' => $duration,
                    'pageno' => $page_no,
                    'total_pages' => $total_pages,
                    'limit' => $no_of_records_per_page,
                    'data' => $data,
                ]
            );
    }

    // test url for mitra api request => /api/mitra/?student_id=20150371728
    public function getMitra(): \CodeIgniter\HTTP\Response
    {
        $rules = [
            'student_id' => 'trim|required|numeric|greater_than[0]|exact_length[11]',
        ];

        if (!$this->validateRequest($_GET, $rules)) {
            return $this
                ->getResponse(
                    $this->validator->getErrors(),
                    ResponseInterface::HTTP_BAD_REQUEST
                );
        }

        $student_id = $_GET['student_id'];
        $mitra_model = new MitraModel();
        $mitra_details = $mitra_model->getMitraDetailsForStudent($student_id);

        return $this
            ->getResponse(
                [
                    'data' => $mitra_details,
                ]
            );
    }
}