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
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Salary Payments</h1>
    </div>

    <div class="mb-3 text-right">
        <a href="#" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#addSalaryPaymentModal">+ Pay Salary</a>
    </div>

    <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
    <?php endif; ?>
    <?php if (session()->getFlashdata('error')): ?>
        <div class="alert alert-danger"><?= session()->getFlashdata('error') ?></div>
    <?php endif; ?>

    <div class="card shadow mb-4">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="salaryTable">
                    <thead class="thead-dark">
                        <tr>
                            <th>Employee</th>
                            <th>Month</th>
                            <th>Working Days</th>
                            <th>Salary Type</th>
                            <th>Amount</th>
                            <th>Status</th>
                            <th>Voucher No</th>
                            <th>Paid On</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($salaryPayments as $row): ?>
                        <tr>
                            <td><?= esc($row['firstname'] . ' ' . $row['lastname']) ?></td>
                            <td><?= esc($row['salary_month']) ?></td>
                            <td><?= esc($row['working_days']) ?></td>
                            <td><?= ucfirst(esc($row['salary_type'])) ?></td>
                            <td><?= number_format($row['salary_amount']) ?></td>
                            <td><span class="badge badge-success"><?= esc($row['status']) ?></span></td>
                            <td>
                                <?php if ($row['voucher_id']): ?>
                                <?php
                                    $voucher = db_connect()
                                    ->table('vouchers')
                                    ->select('voucher_number')
                                    ->where('id', $row['voucher_id'])
                                    ->get()
                                    ->getRow();
                                ?>
                                <?= $voucher ? esc($voucher->voucher_number) : 'N/A' ?>
                                <?php else: ?>
                                N/A
                                <?php endif; ?>
                            </td>
                            <td><?= date('d M Y', strtotime($row['created_at'])) ?></td>
                        </tr>
                        <?php endforeach; ?>
                        <?php if (empty($salaryPayments)): ?>
                        <tr><td colspan="5" class="text-center">No salary payments found.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="addSalaryPaymentModal" tabindex="-1" role="dialog" aria-labelledby="addSalaryPaymentLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <form action="<?= base_url('payroll/addSalaryPayment') ?>" method="post">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Pay Salary</h5>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        <div class="modal-body">
            <div class="form-group">
                <label>Employee</label>
                <select name="user_id" class="form-control" required>
                    <option value="">-- Select --</option>
                    <?php foreach ($employees as $e): ?>
                        <option value="<?= $e['id'] ?>"><?= esc($e['firstname'] . ' ' . $e['lastname']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label>Salary Month</label>
                <input type="month" name="salary_month" class="form-control" required>
            </div>
            <div class="form-group">
                <label>Salary Type</label>
                <select name="salary_type" class="form-control" required>
                    <option value="monthly">Monthly</option>
                    <option value="daily">Daily</option>
                </select>
            </div>
            <div class="form-group">
                <label>Working Days (optional)</label>
                <input type="number" name="working_days" class="form-control">
            </div>
            <div class="form-group">
                <label>Salary Amount</label>
                <input type="number" name="salary_amount" class="form-control" required>
            </div>
            
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-primary">Pay</button>
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
        </div>
      </div>
    </form>
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

<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script>
    $(document).ready(function () {
        $('#salaryTable').DataTable();
    });
</script>

</body>

</html>