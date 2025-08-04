<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Medicine_Consumption UI Page">
    <meta name="author" content="">

    <title>SB Admin 2 - Medicine_Consumption</title>

    <!-- Custom fonts for this template-->
    <link href="<?= base_url('assets/sb-admin-2/vendor/fontawesome-free/css/all.min.css') ?>" rel="stylesheet" type="text/css">
    <link
    href="https://fonts.googleapis.com/css?family=Nunito:200,300,400,600,700,800,900"
    rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="<?= base_url('assets/sb-admin-2/css/sb-admin-2.min.css') ?>" rel="stylesheet">

</head>


<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">

        <!-- Sidebar -->
        <?= view('components/sidebar') ?>
        <!-- End of Sidebar -->

<!-- Content Wrapper -->
<div id="content-wrapper" class="d-flex flex-column">

    <!-- Main Content -->
    <div id="content">

        <!-- Topbar -->
        <?= $this->include('components/header') ?>
        <!-- End of Topbar -->

<!-- Begin Page Content -->
<div class="container mt-5">
    <h1 class="h3 mb-4 text-gray-800">My Profile</h1>

    <div class="card shadow mb-4">
        <div class="card-header py-3 bg-primary text-white">
            <h6 class="m-0 font-weight-bold">User Details</h6>
        </div>
        <div class="card-body">
            <div class="row mb-3">
                <div class="col-sm-3 font-weight-bold">ID</div>
                <div class="col-sm-9"><?= esc($user->id) ?></div>
            </div>
            <div class="row mb-3">
                <div class="col-sm-3 font-weight-bold">First Name</div>
                <div class="col-sm-9"><?= esc($user->firstname) ?></div>
            </div>
            <div class="row mb-3">
                <div class="col-sm-3 font-weight-bold">Last Name</div>
                <div class="col-sm-9"><?= esc($user->lastname) ?></div>
            </div>
            <div class="row mb-3">
                <div class="col-sm-3 font-weight-bold">Email</div>
                <div class="col-sm-9"><?= esc($user->email) ?></div>
            </div>
            <div class="row mb-3">
                <div class="col-sm-3 font-weight-bold">Role</div>
                <div class="col-sm-9"><?= esc($user->role) ?></div>
            </div>
            <div class="row mb-3">
                <div class="col-sm-3 font-weight-bold">Designation</div>
                <div class="col-sm-9"><?= esc($user->designation) ?></div>
            </div>
            <div class="row mb-3">
                <div class="col-sm-3 font-weight-bold">Salary Type</div>
                <div class="col-sm-9"><?= esc($user->salary_type) ?></div>
            </div>
            <div class="row mb-3">
                <div class="col-sm-3 font-weight-bold">Salary Amount</div>
                <div class="col-sm-9"><?= esc($user->salary_amount) ?></div>
            </div>
            <div class="row mb-3">
                <div class="col-sm-3 font-weight-bold">Joining Date</div>
                <div class="col-sm-9"><?= esc($user->joining_date) ?></div>
            </div>
            <div class="row mb-3">
                <div class="col-sm-3 font-weight-bold">Status</div>
                <div class="col-sm-9">
                    <?= $user->is_active ? '<span class="badge badge-success">Active</span>' : '<span class="badge badge-danger">Inactive</span>' ?>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- /.container-fluid -->

</div>
<!-- End of Main Content -->

<!-- Footer -->
<?= $this->include('components/footer') ?>
<!-- End of Footer -->

</div>
<!-- End of Content Wrapper -->

</div>
<!-- End of Page Wrapper -->

<!-- Scroll to Top Button-->
<a class="scroll-to-top rounded" href="#page-top">
    <i class="fas fa-angle-up"></i>
</a>

<!-- Logout Modal-->
<div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
aria-hidden="true">
<div class="modal-dialog" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Ready to Leave?</h5>
            <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">×</span>
            </button>
        </div>
        <div class="modal-body">Select "Logout" below if you are ready to end your current session.</div>
        <div class="modal-footer">
            <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
            <a class="btn btn-primary" href="<?= base_url('login/logout') ?>">Logout</a>
        </div>
    </div>
</div>
</div>

<!-- Bootstrap core JavaScript-->
<script src="<?= base_url('assets/sb-admin-2/vendor/jquery/jquery.min.js') ?>"></script>
<script src="<?= base_url('assets/sb-admin-2/vendor/bootstrap/js/bootstrap.bundle.min.js') ?>"></script>

<!-- Core plugin JavaScript-->
<script src="<?= base_url('assets/sb-admin-2/vendor/jquery-easing/jquery.easing.min.js') ?>"></script>

<!-- Custom scripts for all pages-->
<script src="<?= base_url('assets/sb-admin-2/js/sb-admin-2.min.js') ?>"></script>

</body>

</html>