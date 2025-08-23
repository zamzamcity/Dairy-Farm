<!DOCTYPE html>
<html lang="en">

<?= $this->include('components/head') ?>

<!-- Edit Receipt Voucher Modal -->
<?php foreach ($vouchers as $voucher): ?>
    <div class="modal fade" id="editReceiptVoucherModal<?= $voucher['id'] ?>" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-xl" role="document">
            <form action="<?= base_url('vouchers/receiptVoucher/edit/' . $voucher['id']) ?>" method="post">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Edit Receipt Voucher</h5>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    <div class="modal-body">
                        <div class="form-row mb-3">
                            <div class="col-md-3">
                                <label>Voucher Number</label>
                                <input type="text" class="form-control" value="<?= esc($voucher['voucher_number']) ?>" readonly>
                            </div>
                            <div class="col-md-3">
                                <label>Date *</label>
                                <input type="date" name="date" class="form-control" value="<?= esc($voucher['date']) ?>" required>
                            </div>
                            <?php if (isSuperAdmin()): ?>
                                <div class="form-group col-md-3">
                                    <label for="tenant_id<?= $voucher['id'] ?>">Tenant</label>
                                    <select name="tenant_id" id="tenant_id<?= $voucher['id'] ?>" class="form-control">
                                        <option value="">Select Tenant</option>
                                        <?php foreach ($tenants as $tenant): ?>
                                            <option value="<?= $tenant['id'] ?>" <?= $voucher['tenant_id'] == $tenant['id'] ? 'selected' : '' ?>>
                                                <?= esc($tenant['name']) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            <?php endif; ?>
                            <div class="col-md-3">
                                <label>Reference No *</label>
                                <input type="text" name="reference_no" class="form-control" value="<?= esc($voucher['reference_no']) ?>">
                            </div>
                            <div class="<?= isSuperAdmin() ? 'col-md-12' : 'col-md-3' ?>">
                                <label>Description</label>
                                <input type="text" name="description" class="form-control" value="<?= esc($voucher['description']) ?>">
                            </div>
                        </div>

                        <table class="table table-bordered entry-table">
                            <thead>
                                <tr>
                                    <th>Account Head *</th>
                                    <th>Type *</th>
                                    <th>Amount *</th>
                                    <th>Narration *</th>
                                    <th><button type="button" class="btn btn-sm btn-success add-row">+</button></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($voucher['entries'] as $i => $entry): ?>
                                    <tr>
                                        <td>
                                            <select name="entries[<?= $i ?>][account_head_id]" class="form-control" required>
                                                <option value="">Select Account</option>
                                                <?php foreach ($account_heads as $head): ?>
                                                    <option value="<?= $head['id'] ?>" <?= $head['id'] == $entry['account_head_id'] ? 'selected' : '' ?>>
                                                        <?= esc($head['name']) ?>
                                                    </option>
                                                <?php endforeach; ?>
                                            </select>
                                        </td>
                                        <td>
                                            <select name="entries[<?= $i ?>][type]" class="form-control" required>
                                                <option value="debit" <?= $entry['type'] == 'debit' ? 'selected' : '' ?>>Debit</option>
                                                <option value="credit" <?= $entry['type'] == 'credit' ? 'selected' : '' ?>>Credit</option>
                                            </select>
                                        </td>
                                        <td><input type="number" name="entries[<?= $i ?>][amount]" class="form-control" value="<?= esc($entry['amount']) ?>" step="0.01" required></td>
                                        <td><input type="text" name="entries[<?= $i ?>][narration]" class="form-control" value="<?= esc($entry['narration']) ?>"></td>
                                        <td><button type="button" class="btn btn-sm btn-danger remove-row">×</button></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Update Voucher</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

<!-- Delete Receipt Voucher Modal -->
<div class="modal fade" id="deleteReceiptVoucherModal<?= $voucher['id'] ?>" tabindex="-1" role="dialog" aria-labelledby="deleteReceiptVoucherModalLabel<?= $voucher['id'] ?>" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form action="<?= base_url('vouchers/receiptVoucher/delete/' . $voucher['id']) ?>" method="post">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Delete Voucher</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    Are you sure you want to delete this voucher?
                    <br><strong>Voucher #:</strong> <?= esc($voucher['voucher_number']) ?>
                    <br><strong>Date:</strong> <?= esc($voucher['date']) ?>
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
        <h1 class="h3 mb-0 text-gray-800">Receipt Vouchers</h1>
    </div>

    <?php if (isSuperAdmin()): ?>
        <form method="get" class="form-inline mb-4">
            <label class="mr-2">Tenant:</label>
            <select name="tenant_id" class="form-control mr-2">
                <option value="">-- All Tenants --</option>
                <?php foreach ($tenants as $tenant): ?>
                    <option value="<?= esc($tenant['id']) ?>" 
                        <?= ($selectedTenantId == $tenant['id']) ? 'selected' : '' ?>>
                        <?= esc($tenant['name']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <button type="submit" class="btn btn-primary">Filter</button>
        </form>
    <?php endif; ?>

    <div class="mb-3 text-right">
        <a href="<?= base_url('vouchers/receiptVoucher/export') . (!empty($selectedTenantId) ? '?tenant_id='.$selectedTenantId : '') ?>" 
         class="btn btn-success mb-3">
         <i class="fas fa-file-excel"></i> Download Excel
     </a>
 </div>

 <?php if (hasPermission('CanAddReceiptVoucher')): ?>
    <div class="mb-3 text-right">
        <a href="#" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#addReceiptVoucherModal">+ Add Receipt Voucher</a>
    </div>
<?php endif; ?>

<div class="card shadow mb-4">
    <div class="card-body">
        <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
    <?php endif; ?>
    <?php if (session()->getFlashdata('error')): ?>
    <div class="alert alert-danger"><?= session()->getFlashdata('error') ?></div>
<?php endif; ?>

<div class="table-responsive">
    <table class="table table-bordered" id="rVoucherTable">
        <thead class="thead-dark">
            <tr>
                <th>Date</th>
                <th>Voucher No</th>
                <th>Reference No</th>
                <th>Description</th>
                <th>Total Amount</th>
                <th>Tenant</th>
                <?php if (hasPermission('CanUpdateReceiptVoucher') || hasPermission('CanDeleteReceiptVoucher')): ?>
                <th>Actions</th>
            <?php endif; ?>
        </tr>
    </thead>
    <tbody>
        <?php if (!empty($vouchers)): ?>
            <?php foreach ($vouchers as $voucher): ?>
                <tr>
                    <td><?= esc($voucher['date']) ?></td>
                    <td><?= esc($voucher['voucher_number']) ?></td>
                    <td><?= esc($voucher['reference_no']) ?></td>
                    <td><?= esc($voucher['description']) ?></td>
                    <td>
                        <?= number_format(array_sum(array_column($voucher['entries'], 'amount')), 2) ?>
                    </td>
                    <td><?= esc($voucher['tenant_name'] ?? 'N/A') ?></td>
                    <?php if (hasPermission('CanUpdateReceiptVoucher') || hasPermission('CanDeleteReceiptVoucher')): ?>
                    <td>
                        <?php if (hasPermission('CanUpdateReceiptVoucher')): ?>
                            <a href="#" class="btn btn-sm btn-info" data-toggle="modal" data-target="#editReceiptVoucherModal<?= $voucher['id'] ?>">Edit</a>
                        <?php endif; ?>
                        <?php if (hasPermission('CanDeleteReceiptVoucher')): ?>
                            <a href="#" class="btn btn-sm btn-danger" data-toggle="modal" data-target="#deleteReceiptVoucherModal<?= $voucher['id'] ?>">Delete</a>
                        <?php endif; ?>
                    </td>
                <?php endif; ?>
            </tr>
        <?php endforeach; ?>
    <?php endif; ?>
</tbody>
</table>
</div>
</div>
</div>
</div>


<!-- Add Receipt Voucher Modal -->
<div class="modal fade" id="addReceiptVoucherModal" tabindex="-1" role="dialog" aria-labelledby="addReceiptVoucherModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <form action="<?= base_url('vouchers/receiptVoucher/add') ?>" method="post">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add Receipt Voucher</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>

                <div class="modal-body">
                    <div class="form-row mb-3">
                        <div class="col-md-3">
                            <label>Date *</label>
                            <input type="date" name="date" class="form-control" value="<?= date('Y-m-d') ?>" required>
                        </div>
                        <?php if (isSuperAdmin()): ?>
                            <div class="form-group col-md-3">
                              <label>Tenant</label>
                              <select name="tenant_id" class="form-control">
                                <option value="">Select Tenant</option>
                                <?php foreach ($tenants as $tenant): ?>
                                  <option value="<?= $tenant['id'] ?>"><?= esc($tenant['name']) ?></option>
                              <?php endforeach; ?>
                          </select>
                      </div>
                  <?php endif; ?>
                  <div class="col-md-3">
                    <label>Reference No *</label>
                    <input type="text" name="reference_no" class="form-control">
                </div>
                <div class="<?= isSuperAdmin() ? 'col-md-3' : 'col-md-6' ?>">
                    <label>Description</label>
                    <input type="text" name="description" class="form-control">
                </div>
            </div>

            <table class="table table-bordered entry-table">
                <thead>
                    <tr>
                        <th>Account Head *</th>
                        <th>Type *</th>
                        <th>Amount *</th>
                        <th>Narration *</th>
                        <th><button type="button" class="btn btn-sm btn-success add-row">+</button></th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>
                            <select name="entries[0][account_head_id]" class="form-control" required>
                                <option value="">Select Account</option>
                                <?php foreach ($account_heads as $head): ?>
                                    <option value="<?= $head['id'] ?>"><?= esc($head['name']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </td>
                        <td>
                            <select name="entries[0][type]" class="form-control" required>
                                <option value="debit">Debit</option>
                                <option value="credit">Credit</option>
                            </select>
                        </td>
                        <td><input type="number" name="entries[0][amount]" class="form-control" step="0.01" required></td>
                        <td><input type="text" name="entries[0][narration]" class="form-control"></td>
                        <td><button type="button" class="btn btn-sm btn-danger remove-row">×</button></td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="modal-footer">
            <button type="submit" class="btn btn-primary">Save Voucher</button>
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

<script>
    $(document).on('click', '.add-row', function () {
        var $table = $(this).closest('table');
        var index = $table.find('tbody tr').length;
        var $clone = $table.find('tbody tr:first').clone();

        $clone.find('input, select').each(function () {
            var name = $(this).attr('name');
            var newName = name.replace(/\[\d+\]/, '[' + index + ']');
            $(this).attr('name', newName).val('');
        });

        $table.find('tbody').append($clone);
    });

    $(document).on('click', '.remove-row', function () {
        var $rows = $(this).closest('table').find('tbody tr');
        if ($rows.length > 1) {
            $(this).closest('tr').remove();
        }
    });
</script>

<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script>
    $(document).ready(function () {
        $('#rVoucherTable').DataTable();
    });
</script>

</body>

</html>