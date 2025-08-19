<!DOCTYPE html>
<html lang="en">

<?= $this->include('components/head') ?>

<?php foreach ($stock_registration as $stock): ?>
<!-- Edit Stock Registration Modal -->
<div class="modal fade" id="editStockRegistrationModal<?= $stock['id'] ?>" tabindex="-1" role="dialog" aria-labelledby="editStockRegistrationModalLabel<?= $stock['id'] ?>" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form action="<?= base_url('stock/stockList/edit/' . $stock['id']) ?>" method="post">
            <?= csrf_field() ?>
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Stock Registration</h5>
                    <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                </div>

                <div class="modal-body">
                    <!-- Product Name -->
                    <div class="form-group">
                        <label for="product_name">Product Name *</label>
                        <input type="text" name="product_name" class="form-control" value="<?= esc($stock['product_name']) ?>" required>
                    </div>

                    <!-- Head Dropdown with Add Button -->
                    <div class="form-group">
                        <label for="head_id">Choose Head *</label>
                        <div class="input-group">
                            <select name="head_id" class="form-control" required>
                                <option value="">-- Select Head --</option>
                                <?php foreach ($stock_heads as $head): ?>
                                    <option value="<?= $head['id'] ?>" <?= $head['id'] == $stock['head_id'] ? 'selected' : '' ?>>
                                        <?= esc($head['name']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <div class="input-group-append">
                                <button type="button" class="btn btn-outline-secondary" data-toggle="modal" data-target="#addHeadModal">+</button>
                            </div>
                        </div>
                    </div>

                    <!-- Unit Dropdown with Add Button -->
                    <div class="form-group">
                        <label for="unit_id">Choose Unit *</label>
                        <div class="input-group">
                            <select name="unit_id" class="form-control" required>
                                <option value="">-- Select Unit --</option>
                                <?php foreach ($stock_units as $unit): ?>
                                    <option value="<?= $unit['id'] ?>" <?= $unit['id'] == $stock['unit_id'] ? 'selected' : '' ?>>
                                        <?= esc($unit['name']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <div class="input-group-append">
                                <button type="button" class="btn btn-outline-secondary" data-toggle="modal" data-target="#addUnitModal">+</button>
                            </div>
                        </div>
                    </div>

                    <!-- Is Stock Item -->
                    <div class="form-group">
                        <label>Is Stock Item?</label><br>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="is_stock_item" id="stockYes<?= $stock['id'] ?>" value="1"
                            <?= (isset($stock['is_stock_item']) && $stock['is_stock_item'] == 1) ? 'checked' : '' ?>
                            onclick="toggleStockFields<?= $stock['id'] ?>(true)">
                            <label class="form-check-label" for="stockYes<?= $stock['id'] ?>">Yes</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="is_stock_item" id="stockNo<?= $stock['id'] ?>" value="0"
                            <?= (isset($stock['is_stock_item']) && $stock['is_stock_item'] == 0) ? 'checked' : '' ?>
                            onclick="toggleStockFields<?= $stock['id'] ?>(false)">
                            <label class="form-check-label" for="stockNo<?= $stock['id'] ?>">No</label>
                        </div>
                    </div>

                    <!-- Conditional Stock Fields -->
                    <div id="stockFields<?= $stock['id'] ?>" style="display: <?= $stock['is_stock_item'] ? 'block' : 'none' ?>;">
                        <div class="form-group">
                            <label for="opening_stock_qty">Opening Stock Quantity</label>
                            <input type="number" step="0.01" name="opening_stock_qty" class="form-control" value="<?= esc($stock['opening_stock_qty']) ?>">
                        </div>

                        <div class="form-group">
                            <label for="opening_stock_rate_per_unit">Opening Stock Rate/Unit</label>
                            <input type="number" step="0.01" name="opening_stock_rate_per_unit" class="form-control" value="<?= esc($stock['opening_stock_rate_per_unit']) ?>">
                        </div>
                    </div>

                    <!-- Rate Per Unit -->
                    <div class="form-group">
                        <label for="rate_per_unit">Rate w.r.t 1 Unit *</label>
                        <input type="number" step="0.01" name="rate_per_unit" class="form-control" value="<?= esc($stock['rate_per_unit']) ?>" required>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Update</button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- JavaScript to toggle stock fields -->
<script>
    function toggleStockFields<?= $stock['id'] ?>(show) {
        const fieldDiv = document.getElementById('stockFields<?= $stock['id'] ?>');
        fieldDiv.style.display = show ? 'block' : 'none';

        // Update hidden input
        const hiddenInput = document.getElementById('is_stock_item_hidden_<?= $stock['id'] ?>');
        hiddenInput.value = show ? '1' : '0';
    }
</script>


<!-- Delete Stock Registration Modal -->
<div class="modal fade" id="deleteStockRegistrationModal<?= $stock['id'] ?>" tabindex="-1" role="dialog" aria-labelledby="deleteStockRegistrationModalLabel<?= $stock['id'] ?>" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form action="<?= base_url('stock/stockList/delete/' . $stock['id']) ?>" method="post">
            <?= csrf_field() ?>
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Delete Stock Registration</h5>
                    <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                </div>

                <div class="modal-body">
                    Are you sure you want to delete "<strong><?= esc($stock['product_name']) ?></strong>"?
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger">Delete</button>
                </div>
            </div>
        </form>
    </div>
</div>
<?php endforeach; ?>


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
        <h1 class="h3 mb-0 text-gray-800">Stock Registration</h1>
    </div>

        <div class="mb-3 text-right">
            <a href="<?= base_url('stock/stockList/export') ?>" class="btn btn-success mb-3">
                <i class="fas fa-file-excel"></i> Download Excel
            </a>
        </div>

    <!-- Stock Registration Button -->
    <?php if (hasPermission('CanAddStockRegistration')): ?>
        <div class="mb-3 text-right">
            <a href="#" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#addStockRegistrationModal">+ Stock Registration</a>
        </div>
    <?php endif; ?>

    <!-- Stock Registration Table -->
    <div class="card shadow mb-4">
        <div class="card-body">
            <?php if (session()->getFlashdata('success')): ?>
            <div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
        <?php endif; ?>

        <div class="table-responsive">
            <table class="table table-bordered" id="stockListTable">
                <thead class="thead-dark">
                    <tr>
                        <th>ID</th>
                        <th>Product Name</th>
                        <th>Head</th>
                        <th>Unit</th>
                        <th>Stock Item</th>
                        <th>Opening Qty</th>
                        <th>Opening Rate</th>
                        <th>Rate/Unit</th>
                        <?php if (hasPermission('CanUpdateStockRegistration') || hasPermission('CanDeleteStockRegistration')): ?>
                        <th>Actions</th>
                    <?php endif; ?>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($stock_registration as $stock): ?>
                    <tr>
                        <td><?= esc($stock['id']) ?></td>
                        <td><?= esc($stock['product_name']) ?></td>
                        <td><?= esc($stock['head_name']) ?></td>
                        <td><?= esc($stock['unit_name']) ?></td>
                        <td><?= esc($stock['is_stock_item'] ? 'Yes' : 'No') ?></td>
                        <td><?= esc($stock['opening_stock_qty']) ?></td>
                        <td><?= esc($stock['opening_stock_rate_per_unit']) ?></td>
                        <td><?= esc($stock['rate_per_unit']) ?></td>
                        <?php if (hasPermission('CanUpdateStockRegistration') || hasPermission('CanDeleteStockRegistration')): ?>
                        <td>
                            <?php if (hasPermission('CanUpdateStockRegistration')): ?>
                                <a href="#" class="btn btn-sm btn-info" data-toggle="modal" data-target="#editStockRegistrationModal<?= $stock['id'] ?>">Edit</a>
                            <?php endif; ?>
                            <?php if (hasPermission('CanDeleteStockRegistration')): ?>
                                <a href="#" class="btn btn-sm btn-danger" data-toggle="modal" data-target="#deleteStockRegistrationModal<?= $stock['id'] ?>">Delete</a>
                            <?php endif; ?>
                        </td>
                    <?php endif; ?>
                </tr>
            <?php endforeach; ?>

            <?php if (empty($stock_registration)): ?>
                <tr>
                    <td colspan="9" class="text-center">No stock records found.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>
</div>
</div>
</div>

<!-- Add Stock Registration Modal -->
<div class="modal fade" id="addStockRegistrationModal" tabindex="-1" role="dialog" aria-labelledby="addStockRegistrationModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form action="<?= base_url('stock/stockList/add') ?>" method="post">
            <?= csrf_field() ?>
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Stock Registration</h5>
                    <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                </div>

                <div class="modal-body">
                    <!-- Product Name -->
                    <div class="form-group">
                        <label for="product_name">Product Name *</label>
                        <input type="text" name="product_name" id="product_name" class="form-control" placeholder="Enter product name" required>
                    </div>

                    <!-- Head Dropdown with Add Button -->
                    <div class="form-group">
                        <label for="head_id">Choose Head *</label>
                        <div class="input-group">
                            <select name="head_id" id="head_id" class="form-control" required>
                                <option value="">-- Select Head --</option>
                                <?php foreach ($stock_heads as $head): ?>
                                    <option value="<?= esc($head['id']) ?>"><?= esc($head['name']) ?></option>
                                <?php endforeach; ?>
                            </select>
                            <div class="input-group-append">
                                <button type="button" class="btn btn-outline-secondary" data-toggle="modal" data-target="#addHeadModal">+</button>
                            </div>
                        </div>
                    </div>

                    <!-- Unit Dropdown with Add Button -->
                    <div class="form-group">
                        <label for="unit_id">Choose Unit *</label>
                        <div class="input-group">
                            <select name="unit_id" id="unit_id" class="form-control" required>
                                <option value="">-- Select Unit --</option>
                                <?php foreach ($stock_units as $unit): ?>
                                    <option value="<?= esc($unit['id']) ?>"><?= esc($unit['name']) ?></option>
                                <?php endforeach; ?>
                            </select>
                            <div class="input-group-append">
                                <button type="button" class="btn btn-outline-secondary" data-toggle="modal" data-target="#addUnitModal">+</button>
                            </div>
                        </div>
                    </div>

                    <!-- Stock Item: Yes/No -->
                    <div class="form-group">
                        <label>Is Stock Item?</label><br>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="is_stock_item" id="stockYes" value="1" checked onclick="toggleStockFields(true)">
                            <label class="form-check-label" for="stockYes">Yes</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="is_stock_item" id="stockNo" value="0" onclick="toggleStockFields(false)">
                            <label class="form-check-label" for="stockNo">No</label>
                        </div>
                    </div>

                    <!-- Conditional Stock Fields -->
                    <div id="stockFields">
                        <div class="form-group">
                            <label for="opening_stock_qty">Opening Stock Quantity</label>
                            <input type="number" step="0.01" name="opening_stock_qty" id="opening_stock_qty" class="form-control">
                        </div>

                        <div class="form-group">
                            <label for="opening_stock_rate_per_unit">Opening Stock Rate/Unit</label>
                            <input type="number" step="0.01" name="opening_stock_rate_per_unit" id="opening_stock_rate_per_unit" class="form-control">
                        </div>
                    </div>

                    <!-- Rate Per Unit -->
                    <div class="form-group">
                        <label for="rate_per_unit">Rate w.r.t 1 Unit *</label>
                        <input type="number" step="0.01" name="rate_per_unit" id="rate_per_unit" class="form-control" required>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Add</button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Add Head Modal -->
<div class="modal fade" id="addHeadModal" tabindex="-1" role="dialog" aria-labelledby="addHeadModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form action="<?= base_url('stock/stockList/add-head') ?>" method="post">
            <?= csrf_field() ?>
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add New Head</h5>
                    <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="head_name">Head Name:</label>
                        <input type="text" name="head_name" id="head_name" class="form-control" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Add</button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Add Unit Modal -->
<div class="modal fade" id="addUnitModal" tabindex="-1" role="dialog" aria-labelledby="addUnitModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form action="<?= base_url('stock/stockList/add-unit') ?>" method="post">
            <?= csrf_field() ?>
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add New Unit</h5>
                    <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="unit_name">Unit Name:</label>
                        <input type="text" name="unit_name" id="unit_name" class="form-control" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Add</button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- JavaScript to toggle stock fields -->
<script>
    function toggleStockFields(show) {
        document.getElementById('stockFields').style.display = show ? 'block' : 'none';
    }
    
    document.addEventListener('DOMContentLoaded', function() {
        toggleStockFields(document.getElementById('stockYes').checked);
    });
</script>

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
        $('#stockListTable').DataTable();
    });
</script>

</body>

</html>