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
    <h1 class="h3 mb-4 text-gray-800">Stock Ledger</h1>

    <!-- Filters -->
    <form method="get" class="form-inline mb-4">
        <?php if (isSuperAdmin()): ?>
          <label class="mr-2">Tenant:</label>
          <select name="tenant_id" class="form-control mr-3">
            <option value="">-- All Tenants --</option>
            <?php foreach ($tenants as $tenant): ?>
              <option value="<?= esc($tenant['id']) ?>" 
                <?= ($selectedTenantId == $tenant['id']) ? 'selected' : '' ?>>
                <?= esc($tenant['name']) ?>
            </option>
        <?php endforeach; ?>
    </select>
<?php endif; ?>

<label for="from_date" class="mr-2">From:</label>
<input type="date" name="from_date" class="form-control mr-2" value="<?= $fromDate ?>">
<label for="to_date" class="mr-2">To:</label>
<input type="date" name="to_date" class="form-control mr-2" value="<?= $toDate ?>">

<label for="head_id" class="mr-2">Head:</label>
<select name="head_id" class="form-control mr-2">
    <?php foreach ($heads as $head): ?>
        <option value="<?= $head['id'] ?>" <?= $selectedHead == $head['id'] ? 'selected' : '' ?>>
            <?= esc($head['name']) ?>
        </option>
    <?php endforeach; ?>
</select>

<button type="submit" class="btn btn-primary">Filter</button>
</form>
<div class="mb-3 text-right">
    <a href="<?= base_url('stock/exportStockLedger') 
    . (!empty($_SERVER['QUERY_STRING']) ? '?' . $_SERVER['QUERY_STRING'] : '') 
    . (!empty($selectedTenantId) 
        ? (empty($_SERVER['QUERY_STRING']) ? '?' : '&') . 'tenant_id=' . $selectedTenantId 
        : '') ?>" 
        class="btn btn-success mb-3">
        <i class="fas fa-file-excel"></i> Download Excel
    </a>
</div>

<!-- Ledger Table -->
<div class="card shadow">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered" id="stockLedgerTable">
                <thead class="thead-dark">
                    <tr>
                        <th>ID</th>
                        <th>Product</th>
                        <th>Head</th>
                        <th>Unit</th>
                        <th>Opening Qty</th>
                        <th>Rate/Unit</th>
                        <th>Date</th>
                        <th>Consumed Qty</th>
                        <th>Remaining Qty</th>
                        <th>Tenant</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $totalOpening = 0;
                    $totalConsumed = 0;
                    $totalRemaining = 0;
                    ?>
                    <?php foreach ($ledgerData as $row): 
                        $totalOpening += $row['opening_qty'];
                        $totalConsumed += $row['consumed_qty'];
                        $totalRemaining += $row['remaining_qty'];
                        ?>
                        <tr>
                            <td><?= esc($row['id']) ?></td>
                            <td><?= esc($row['product_name']) ?></td>
                            <td><?= esc($row['head_name']) ?></td>
                            <td><?= esc($row['unit_name']) ?></td>
                            <td><?= esc($row['opening_qty']) ?></td>
                            <td><?= esc($row['rate_per_unit']) ?></td>
                            <td>
                                <?php if (!empty($row['consumed_records'])): ?>

                                    <?php foreach ($row['consumed_records'] as $rec): ?>
                                        <?= esc($rec['date']) ?>
                                    <?php endforeach; ?>

                                <?php else: ?>
                                    —
                                <?php endif; ?>
                            </td>
                            <td><?= esc($row['consumed_qty']) ?></td>
                            <td><?= esc($row['remaining_qty']) ?></td>
                            <td><?= esc($row['tenant_name'] ?? 'N/A') ?></td>
                        </tr>
                    <?php endforeach; ?>

                    <!-- Totals Row -->
                        <!-- <tr class="font-weight-bold text-primary">
                            <td colspan="4" class="text-right">Total</td>
                            <td><?= $totalOpening ?></td>
                            <td>—</td>
                            <td>—</td>
                            <td><?= $totalConsumed ?></td>
                            <td><?= $totalRemaining ?></td>
                        </tr> -->

                        <?php if (empty($ledgerData)): ?>
                            <tr><td colspan="9" class="text-center">No data found.</td></tr>
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
    $(document).ready(function () {
        $('#stockLedgerTable').DataTable();
    });
</script>

</body>

</html>