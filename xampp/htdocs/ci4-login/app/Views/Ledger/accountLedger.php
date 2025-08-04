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
        <h1 class="h3 mb-0 text-gray-800">Account Ledger</h1>
    </div>

    <div class="card shadow mb-4">
        <div class="card-body">

            <!-- Flash Messages -->
            <?php if (session()->getFlashdata('success')): ?>
            <div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
            <?php endif; ?>
            <?php if (session()->getFlashdata('error')): ?>
            <div class="alert alert-danger"><?= session()->getFlashdata('error') ?></div>
            <?php endif; ?>

            <!-- Filter Form -->
            <form method="get" action="<?= base_url('ledger/accountLedger') ?>" class="form-inline mb-3">
                <div class="form-group mr-2">
                    <select name="account_head_id" class="form-control" required>
                        <option value="">Select Account Head</option>
                        <?php foreach ($account_heads as $head): ?>
                        <option value="<?= $head['id'] ?>" <?= $selected_head == $head['id'] ? 'selected' : '' ?>>
                            <?= esc($head['name']) ?>
                        </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-group mr-2">
                    <input type="date" name="from_date" class="form-control" value="<?= esc($from_date) ?>">
                </div>

                <div class="form-group mr-2">
                    <input type="date" name="to_date" class="form-control" value="<?= esc($to_date) ?>">
                </div>

                <button type="submit" class="btn btn-primary btn-sm">Filter</button>
            </form>

            <div class="mb-3 text-right">
                <a href="<?= base_url('ledger/accountLedgerExport') . '?' . $_SERVER['QUERY_STRING'] ?>" class="btn btn-success mb-3">
                    <i class="fas fa-file-excel"></i> Download Excel
                </a>
            </div>

            <!-- Ledger Table -->
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead class="thead-dark">
                        <tr>
                            <th>Date</th>
                            <th>Voucher #</th>
                            <th>Type</th>
                            <th>Description</th>
                            <th>Narration</th>
                            <th>Debit</th>
                            <th>Credit</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                            $balance = 0;
                            if (!empty($ledger)):
                            foreach ($ledger as $entry): 
                            $debit = $entry['type'] === 'debit' ? $entry['amount'] : 0;
                            $credit = $entry['type'] === 'credit' ? $entry['amount'] : 0;
                            $balance += ($debit - $credit);
                        ?>
                        <tr>
                            <td><?= esc($entry['date']) ?></td>
                            <td><?= esc($entry['voucher_number']) ?></td>
                            <td><?= ucfirst($entry['voucher_type']) ?></td>
                            <td><?= esc($entry['description']) ?></td>
                            <td><?= esc($entry['narration']) ?></td>
                            <td><?= $debit ? number_format($debit, 2) : '' ?></td>
                            <td><?= $credit ? number_format($credit, 2) : '' ?></td>
                        </tr>
                        <?php 
                            endforeach;
                            else: ?>
                            <tr>
                                <td colspan="7" class="text-center">No transactions found.</td>
                            </tr>
                            <?php endif; ?>
                        </tbody>
                        <tfoot>
                            <tr style="background-color: #f1f1f1;">
                                <th colspan="5" class="text-center">Closing Balance:</th>
                                <th colspan="2"><?= number_format($balance, 2) ?></th>
                            </tr>
                        </tfoot>
                    </table>
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