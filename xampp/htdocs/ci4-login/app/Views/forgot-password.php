<!DOCTYPE html>
<html lang="en">

<?= $this->include('components/head') ?>

<body class="bg-gradient-primary">

    <div class="container">

        <!-- Outer Row -->
        <div class="row justify-content-center">

            <div class="col-xl-10 col-lg-12 col-md-9">

                <div class="card o-hidden border-0 shadow-lg my-5">
                    <div class="card-body p-0">
                        <!-- Nested Row within Card Body -->
                        <div class="row">
                            <div class="col-lg-6 d-none d-lg-block bg-password-image"></div>
                            <div class="col-lg-6">
                                <div class="p-5">
                                    <div class="text-center">
                                        <h1 class="h4 text-gray-900 mb-2">Forgot Your Password?</h1>
                                        <p class="mb-4">We get it, stuff happens. Just enter your email address below
                                        and we'll send you a link to reset your password!</p>
                                    </div>
                                    <?php if (session()->getFlashdata('message')): ?>
                                    <div class="alert alert-success"><?= session()->getFlashdata('message') ?></div>
                                <?php endif; ?>
                                <?php if (session()->getFlashdata('error')): ?>
                                <div class="alert alert-danger"><?= session()->getFlashdata('error') ?></div>
                            <?php endif; ?>
                            <form class="user" method="post" action="<?= base_url('auth/send-reset-link') ?>">
                                <div class="form-group">
                                    <input type="email" name="email" class="form-control form-control-user"
                                    placeholder="Enter Email Address..." required>
                                </div>
                                <button type="submit" class="btn btn-primary btn-user btn-block">
                                    Reset Password
                                </button>
                            </form>
                            <hr>
                            <!-- <div class="text-center">
                                <a class="small" href="<?= base_url('pages/register') ?>">Create an Account!</a>
                            </div> -->
                            <div class="text-center">
                                <a class="small" href="<?= base_url('login') ?>">Already have an account? Login!</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

</div>

</div>

<script src="<?= base_url('assets/sb-admin-2/vendor/jquery/jquery.min.js') ?>"></script>
<script src="<?= base_url('assets/sb-admin-2/vendor/bootstrap/js/bootstrap.bundle.min.js') ?>"></script>
<script src="<?= base_url('assets/sb-admin-2/vendor/jquery-easing/jquery.easing.min.js') ?>"></script>
<script src="<?= base_url('assets/sb-admin-2/js/sb-admin-2.min.js') ?>"></script>

</body>

</html>