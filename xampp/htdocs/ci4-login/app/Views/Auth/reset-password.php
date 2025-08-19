<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Reset Password</title>
    <link href="<?= base_url('assets/sb-admin-2/css/sb-admin-2.min.css') ?>" rel="stylesheet">
    <link rel="icon" type="image/png" href="<?= base_url('assets/sb-admin-2/img/cow.png') ?>">
</head>
<body class="bg-gradient-primary">

<div class="container">
    <div class="row justify-content-center">

        <div class="col-xl-6 col-lg-6 col-md-9">
            <div class="card o-hidden border-0 shadow-lg my-5">
                <div class="card-body p-4">
                    <div class="text-center">
                        <h1 class="h4 text-gray-900 mb-4">Reset Your Password</h1>
                    </div>
                    <form class="user" action="<?= base_url('auth/update-password') ?>" method="post">
                        <input type="hidden" name="token" value="<?= esc($token) ?>">
                        <div class="form-group">
                            <input type="password" name="password" class="form-control form-control-user" placeholder="New Password" required>
                        </div>
                        <div class="form-group">
                            <input type="password" name="password_confirm" class="form-control form-control-user" placeholder="Confirm Password" required>
                        </div>
                        <button type="submit" class="btn btn-primary btn-user btn-block">Update Password</button>
                    </form>
                    <hr>
                    <div class="text-center">
                        <a class="small" href="<?= base_url('login') ?>">Back to Login</a>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

</body>
</html>