<?= $this->extend($config->viewLayout) ?>
<?= $this->section('main') ?>

<div class="container">

    <div class="row">
        <div class="col-sm-4 offset-sm-3 d-flex justify-content-center mt-5">
            <h2>A DCPCR Initiative</h2>
        </div>
        <div class="col-sm-4 offset-sm-3 d-flex justify-content-center mb-4">
            <p>in collaboration with DOE</p>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-4 offset-sm-3">
            <div>
                <div class="d-flex justify-content-center mb-1">

                    <div class=" ">
                        <img src="/assets-adminlte/dist/img/dcpcrlogo.png" alt="DCPCR Logo" width="100%"
                             height="100%">
                    </div>
                </div>
                <div class=" d-flex justify-content-center ">
                    <h4 style="color:#d71515;  font-weight: bold;">Early Warning System</h4>
                </div>
            </div>

            <div>

                <div class="card-body">

                    <?= view('App\Views\_message_block') ?>

                    <form action="<?= route_to('login') ?>" method="post">
                        <?= csrf_field() ?>

                        <?php if ($config->validFields === ['email']): ?>
                            <div class="form-group">
                                <label for="login"><?= lang('Auth.email') ?></label>
                                <input type="email"
                                       class="form-control <?php if (session('errors.login')) : ?>is-invalid<?php endif ?>"
                                       name="login" placeholder="<?= lang('Auth.email') ?>">
                                <div class="invalid-feedback">
                                    <?= session('errors.login') ?>
                                </div>
                            </div>
                        <?php else: ?>
                            <div class="form-group">
                                <label for="login"><?= lang('Auth.username') ?></label>
                                <input type="text"
                                       class="form-control <?php if (session('errors.login')) : ?>is-invalid<?php endif ?>"
                                       name="login" placeholder="<?= lang('Auth.username') ?>">
                                <div class="invalid-feedback">
                                    <?= session('errors.login') ?>
                                </div>
                            </div>
                        <?php endif; ?>

                        <div class="form-group">
                            <label for="password"><?= lang('Auth.password') ?></label>
                            <input type="password" name="password"
                                   class="form-control  <?php if (session('errors.password')) : ?>is-invalid<?php endif ?>"
                                   placeholder="<?= lang('Auth.password') ?>">
                            <div class="invalid-feedback">
                                <?= session('errors.password') ?>
                            </div>
                        </div>

                        <?php if ($config->allowRemembering): ?>
                            <div class="form-check">
                                <label class="form-check-label">
                                    <input type="checkbox" name="remember"
                                           class="form-check-input" <?php if (old('remember')) : ?> checked <?php endif ?>>
                                    <?= lang('Auth.rememberMe') ?>
                                </label>
                            </div>
                        <?php endif; ?>
                        <br>
                        <div class="d-flex justify-content-center">
                            <button type="submit"
                                    class="btn btn-primary btn-md"><?= lang('Auth.loginAction') ?></button></div>

                    </form>

                    <hr>

                    <?php if ($config->allowRegistration) : ?>
                        <p><a href="<?= route_to('register') ?>"><?= lang('Auth.needAnAccount') ?></a></p>
                    <?php endif; ?>
                    <?php if ($config->activeResetter): ?>
                        <p><a href="<?= route_to('forgot') ?>"><?= lang('Auth.forgotYourPassword') ?></a></p>
                    <?php endif; ?>
                </div>
            </div>

        </div>
    </div>
</div>

<?= $this->endSection() ?>