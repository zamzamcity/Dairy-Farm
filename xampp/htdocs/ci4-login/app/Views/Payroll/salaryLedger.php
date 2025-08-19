<!DOCTYPE html>
<html lang="en">

<?= $this->include('components/head') ?>

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
        <h1 class="h3 mb-0 text-gray-800">Salary Ledger</h1>
    </div>

    <form method="get" class="form-inline mb-3">
        <div class="form-group mr-2">
            <label for="employee_id">Employee:&nbsp;</label>
            <select name="employee_id" id="employee_id" class="form-control">
                <option value="">All</option>
                <?php foreach ($employees as $emp): ?>
                <option value="<?= esc($emp['id']) ?>"
                    <?= (isset($filter_employee_id) && $filter_employee_id == $emp['id']) ? 'selected' : '' ?>>
                    <?= esc(($emp['firstname'] ?? '') . ' ' . ($emp['lastname'] ?? '')) ?>
                </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="form-group mr-2">
            <label for="salary_month">Month:&nbsp;</label>
            <input type="month" name="salary_month" id="salary_month" class="form-control"
            value="<?= esc($filter_salary_month ?? '') ?>">
        </div>

        <div class="form-group mr-2">
            <label>Status:&nbsp;</label>
            <select name="status" class="form-control">
                <option value="">All</option>
                <option value="paid" <?= ($filter_status == 'paid') ? 'selected' : '' ?>>Paid</option>
                <option value="unpaid" <?= ($filter_status == 'unpaid') ? 'selected' : '' ?>>Unpaid</option>
            </select>
        </div>

        <button type="submit" class="btn btn-primary">Filter</button>
    </form>

    <div class="mb-3 text-right">
        <a href="<?= site_url('payroll/salaryLedger/export?employee_id=' . esc($filter_employee_id) . '&salary_month=' . esc($filter_salary_month) . '&status=' . esc($filter_status)) ?>" class="btn btn-success mb-3">
            <i class="fas fa-file-excel"></i> Download Excel
        </a>
    </div>

    <div class="card shadow mb-4">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="salaryLedgerTable">
                    <thead class="thead-dark">
                        <tr>
                            <th>Employee</th>
                            <th>Month</th>
                            <th>Salary Type</th>
                            <th>Working Days</th>
                            <th>Total Salary</th>
                            <th>Total Paid Amount</th>
                            <th>Voucher No</th> 
                            <th>Voucher Date</th>   
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($payrolls)): ?>
                        <?php foreach ($payrolls as $pay): ?>
                        <tr>
                            <td><?= esc($pay['firstname'] . ' ' . $pay['lastname']) ?></td>
                            <td><?= esc($pay['salary_month'] ?? '-') ?></td>
                            <td><?= esc(ucfirst($pay['salary_type'] ?? '-')) ?></td>
                            <td><?= esc($pay['working_days'] ?? '-') ?></td>
                            <td><?= number_format($pay['base_salary']) ?></td>
                            <td><?= number_format($pay['salary_amount']) ?></td>
                            <td><?= $pay['voucher_number'] ?? '-' ?></td>
                            <td><?= isset($pay['voucher_date']) ? date('Y-m-d', strtotime($pay['voucher_date'])) : '-' ?></td>
                            <td>
                                <?php if (!empty($pay['status'])): ?>
                                <span class="badge badge-success"><?= esc($pay['status']) ?></span>
                                <?php else: ?>
                                <span class="badge badge-secondary">Unpaid</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                        <?php else: ?>
                        <tr><td colspan="9" class="text-center text-muted">No salary history found.</td></tr>
                        <?php endif; ?>
                    </tbody>
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

<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script>
    $.fn.dataTable.ext.errMode = 'none';
    $(document).ready(function () {
        $('#salaryLedgerTable').DataTable();
    });
</script>

</body>

</html>