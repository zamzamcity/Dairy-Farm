<!DOCTYPE html>
<html lang="en">

<?= $this->include('components/head') ?>

<?php foreach ($milk_consumption as $record): ?>
<!-- Edit Milk Consumption Modal -->
<div class="modal fade" id="editMilkConsumptionModal<?= $record['id'] ?>" tabindex="-1" role="dialog" aria-labelledby="editMilkConsumptionModalLabel<?= $record['id'] ?>" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form action="<?= base_url('milk-consumption/milkConsumption/edit/' . $record['id']) ?>" method="post">
            <?= csrf_field() ?>
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editMilkConsumptionModalLabel<?= $record['id'] ?>">Edit Milk Consumption</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body">
                    <!-- Date -->
                    <div class="form-group">
                        <label for="date">Date *</label>
                        <input type="date" name="date" class="form-control" value="<?= esc($record['date']) ?>" required>
                    </div>

                    <!-- Head -->
                    <div class="form-group">
                        <label for="farm_head_id">Head *</label>
                        <select name="farm_head_id" class="form-control" required>
                            <option value="">-- Select Head --</option>
                            <?php foreach ($farm_heads as $head): ?>
                                <option value="<?= esc($head['id']) ?>" <?= $head['id'] == $record['farm_head_id'] ? 'selected' : '' ?>>
                                    <?= esc($head['head_name']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <!-- Milk Litres -->
                    <div class="form-group">
                        <label for="milk_litres">Milk (L) *</label>
                        <input type="number" step="0.01" name="milk_litres" class="form-control" value="<?= esc($record['milk_litres']) ?>" required>
                    </div>
                    <?php if (isSuperAdmin()): ?>
                        <div class="form-group">
                            <label for="tenant_id<?= $record['id'] ?>">Tenant</label>
                            <select name="tenant_id" id="tenant_id<?= $record['id'] ?>" class="form-control">
                                <option value="">Select Tenant</option>
                                <?php foreach ($tenants as $tenant): ?>
                                    <option value="<?= $tenant['id'] ?>" <?= $record['tenant_id'] == $tenant['id'] ? 'selected' : '' ?>>
                                        <?= esc($tenant['name']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    <?php endif; ?>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Update</button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Delete Milk Consumption Modal -->
<div class="modal fade" id="deleteMilkConsumptionModal<?= $record['id'] ?>" tabindex="-1" role="dialog" aria-labelledby="deleteMilkConsumptionModalLabel<?= $record['id'] ?>" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form action="<?= base_url('milk-consumption/milkConsumption/delete/' . $record['id']) ?>" method="post">
            <?= csrf_field() ?>
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteMilkConsumptionModalLabel<?= $record['id'] ?>">Delete Confirmation</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body">
                   Are you sure you want to delete <strong><?= esc($record['head_name']) ?></strong>?
               </div>

               <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-danger">Yes, Delete</button>
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
        <h1 class="h3 mb-0 text-gray-800">Milk Consumption</h1>
    </div>

    
    <form method="get" class="form-inline mb-4">

        <!-- Tenant Filter -->
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
        <!-- Date Filter -->
        <label for="date" class="mr-2">Date:</label>
        <input type="date" id="date" name="date" value="<?= esc($selected_date) ?>" class="form-control mr-3" required>

        <!-- Buttons -->
        <button type="submit" class="btn btn-primary">Filter</button>
    </form>
    

    <div class="mb-3 text-right">
        <a href="<?= base_url('milk-consumption/milkConsumption/export')
        . (!empty($_SERVER['QUERY_STRING']) ? '?' . $_SERVER['QUERY_STRING'] : '')
        . (!empty($selectedTenantId) 
            ? (empty($_SERVER['QUERY_STRING']) ? '?' : '&') . 'tenant_id=' . $selectedTenantId 
            : '') ?>" 
            class="btn btn-success mb-3">
            <i class="fas fa-file-excel"></i> Download Excel
        </a>
    </div>

    <!-- Add Milk Consumption Button -->
    <?php if (hasPermission('CanAddMilkConsumption')): ?>
        <div class="mb-3 text-right">
            <a href="#" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#addMilkConsumptionModal">+ Add Milk Consumption</a>
        </div>
    <?php endif; ?>

<!-- Milk Consumption Table -->
<div class="card shadow mb-4">
    <div class="card-body">
        <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
    <?php endif; ?>

    <div class="table-responsive">
        <table class="table table-bordered"  id="milkConsumptionTable">
            <thead class="thead-dark">
                <tr>
                    <th>ID</th>
                    <th>Date</th>
                    <th>Head Name</th>
                    <th>Milk (L)</th>
                    <th>Tenant</th>
                    <?php if (hasPermission('CanUpdateMilkConsumption') || hasPermission('CanDeleteMilkConsumption')): ?>
                    <th>Actions</th>
                <?php endif; ?>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($milk_consumption)): ?>
                <?php foreach ($milk_consumption as $consumption): ?>
                    <tr>
                        <td><?= esc($consumption['id']) ?></td>
                        <td><?= esc($consumption['date']) ?></td>
                        <td><?= esc($consumption['head_name']) ?></td>
                        <td><?= esc($consumption['milk_litres']) ?></td>
                        <td><?= esc($consumption['tenant_name'] ?? 'N/A') ?></td>
                        <?php if (hasPermission('CanUpdateMilkConsumption') || hasPermission('CanDeleteMilkConsumption')): ?>
                        <td>
                            <?php if (hasPermission('CanUpdateMilkConsumption')): ?>
                                <a href="#" class="btn btn-sm btn-info" data-toggle="modal" data-target="#editMilkConsumptionModal<?= $consumption['id'] ?>">Edit</a>
                            <?php endif; ?>
                            <?php if (hasPermission('CanDeleteMilkConsumption')): ?>
                                <a href="#" class="btn btn-sm btn-danger" data-toggle="modal" data-target="#deleteMilkConsumptionModal<?= $consumption['id'] ?>">Delete</a>
                            <?php endif; ?>
                        </td>
                    <?php endif; ?>
                </tr>
            <?php endforeach; ?>
            <tfoot>
                <tr style="font-weight: bold; background-color: #f1f1f1;">
                    <td colspan="3" class="text-center">Grand Total (Litres):</td>
                    <td><?= number_format($total_milk, 2) ?></td>
                    <td></td>
                    <?php if (hasPermission('CanUpdateMilkConsumption') || hasPermission('CanDeleteMilkConsumption')): ?>
                    <td></td>
                <?php endif; ?>
            </tr>
        </tfoot>
    <?php endif; ?>
</tbody>
</table>
</div>
</div>
</div>
</div>

<!-- Add Milk Consumption Modal -->
<div class="modal fade" id="addMilkConsumptionModal" tabindex="-1" role="dialog" aria-labelledby="addMilkConsumptionModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form action="<?= base_url('milk-consumption/milkConsumption/add') ?>" method="post">
            <?= csrf_field() ?>
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addMilkConsumptionModalLabel">Add Milk Consumption</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body">
                    <!-- Date -->
                    <div class="form-group">
                        <label for="date">Date *</label>
                        <input type="date" name="date" id="date" class="form-control" required>
                    </div>

                    <!-- Head -->
                    <div class="form-group">
                        <label for="farm_head_id">Head *</label>
                        <select name="farm_head_id" id="farm_head_id" class="form-control" required>
                            <option value="">-- Select Head --</option>
                            <?php foreach ($farm_heads as $head): ?>
                                <option value="<?= esc($head['id']) ?>"><?= esc($head['head_name']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <!-- Milk Litres -->
                    <div class="form-group">
                        <label for="milk_litres">Milk (L) *</label>
                        <input type="number" step="0.01" name="milk_litres" id="milk_litres" class="form-control" placeholder="Enter milk in litres" required>
                    </div>

                    <?php if (isSuperAdmin()): ?>
                        <div class="form-group">
                          <label>Tenant</label>
                          <select name="tenant_id" class="form-control">
                            <option value="">Select Tenant</option>
                            <?php foreach ($tenants as $tenant): ?>
                              <option value="<?= $tenant['id'] ?>"><?= esc($tenant['name']) ?></option>
                          <?php endforeach; ?>
                      </select>
                  </div>
              <?php endif; ?>
          </div>

          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
            <button type="submit" class="btn btn-primary">Add</button>
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
        $('#milkConsumptionTable').DataTable();
    });
</script>

</body>

</html>