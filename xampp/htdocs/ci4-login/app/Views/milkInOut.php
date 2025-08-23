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
    <h1 class="h3 mb-4 text-gray-800">Milk In / Out</h1>

    <form method="get" class="form-inline mb-4">
        <?php if (isSuperAdmin()): ?>
            <!-- Tenant Filter -->
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

        <label class="mr-2">Date:</label>
        <input type="date" name="date" 
        value="<?= esc($_GET['date'] ?? date('Y-m-d')) ?>" 
        class="form-control mr-3">

        <button type="submit" class="btn btn-primary">Filter</button>
    </form>

    <div class="mb-3 text-right">
        <a href="<?= base_url('milkInOut/export')
        . (!empty($_SERVER['QUERY_STRING']) ? '?' . $_SERVER['QUERY_STRING'] : '')
        . (!empty($selectedTenantId) 
            ? (empty($_SERVER['QUERY_STRING']) ? '?' : '&') . 'tenant_id=' . $selectedTenantId 
            : '') ?>" 
            class="btn btn-success mb-3">
            <i class="fas fa-file-excel"></i> Download Excel
        </a>
    </div>

    <div class="row">
        <div class="col-md-6">
            <h5>Daily Milking</h5>
            <table class="table table-bordered">
                <thead class="thead-dark">
                    <tr>
                        <th>Tenant</th>
                        <th>Product</th>
                        <th>Milk 1 (L)</th>
                        <th>Milk 2 (L)</th>
                        <th>Milk 3 (L)</th>
                        <th>Total (L)</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($daily_milking as $m): ?>
                        <tr>
                            <td><?= esc($m['tenant_name'] ?? 'N/A') ?></td>
                            <td><?= esc($m['milk_product']) ?></td>
                            <td><?= esc($m['milk_1']) ?></td>
                            <td><?= esc($m['milk_2']) ?></td>
                            <td><?= esc($m['milk_3']) ?></td>
                            <td><?= esc($m['total_milk']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                    <tr style="font-weight: bold;  background-color: #f1f1f1;">
                        <td colspan="4" class="text-center">Total Milking</td>
                        <td><?= number_format($total_milking, 2) ?></td>
                        <td></td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="col-md-6">
            <h5>Milk Consumption</h5>
            <table class="table table-bordered">
                <thead class="thead-dark">
                    <tr>
                        <th>Tenant</th>
                        <th>Head</th>
                        <th>Milk (L)</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($milk_consumption as $c): ?>
                        <tr>
                            <td><?= esc($c['tenant_name'] ?? 'N/A') ?></td>
                            <td><?= esc($c['head_name']) ?></td>
                            <td><?= esc($c['milk_litres']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                    <tr style="font-weight: bold;  background-color: #f1f1f1;">
                        <td class="text-center">Total Consumption</td>
                        <td><?= number_format($total_consumption, 2) ?></td>
                        <td></td>
                    </tr>
                </tbody>
            </table>
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