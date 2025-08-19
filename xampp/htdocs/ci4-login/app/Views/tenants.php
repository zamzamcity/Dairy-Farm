<!DOCTYPE html>
<html lang="en">

<?= $this->include('components/head') ?>

<!-- Edit Tenant Modal -->
<?php foreach ($tenants as $tenant): ?>
    <div class="modal fade" id="editTenantModal<?= $tenant['id'] ?>" tabindex="-1" role="dialog" aria-labelledby="editTenantModalLabel<?= $tenant['id'] ?>" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <form action="<?= base_url('tenants/edit/' . $tenant['id']) ?>" method="post">
                <?= csrf_field() ?>
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editTenantModalLabel<?= $tenant['id'] ?>">Edit Tenant</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>

                    <div class="modal-body">
                        <!-- Tenant Name -->
                        <div class="form-group">
                            <label for="tenantName<?= $tenant['id'] ?>">Tenant Name *</label>
                            <input type="text" class="form-control" id="tenantName<?= $tenant['id'] ?>" name="name" value="<?= esc($tenant['name']) ?>" required>
                        </div>

                        <!-- Status -->
                        <div class="form-group">
                            <label for="tenantStatus<?= $tenant['id'] ?>">Status *</label>
                            <select name="status" id="tenantStatus<?= $tenant['id'] ?>" class="form-control" required>
                                <option value="active" <?= $tenant['status'] === 'active' ? 'selected' : '' ?>>Active</option>
                                <option value="inactive" <?= $tenant['status'] === 'inactive' ? 'selected' : '' ?>>Inactive</option>
                            </select>
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
<?php endforeach; ?>

<!-- Delete Tenant Modal -->
<?php foreach ($tenants as $tenant): ?>
    <div class="modal fade" id="deleteTenantModal<?= $tenant['id'] ?>" tabindex="-1" role="dialog" aria-labelledby="deleteTenantModalLabel<?= $tenant['id'] ?>" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <form action="<?= base_url('tenants/delete/' . $tenant['id']) ?>" method="post">
                <?= csrf_field() ?>
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="deleteTenantModalLabel<?= $tenant['id'] ?>">Confirm Delete</h5>
                        <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                    </div>
                    <div class="modal-body">
                        Are you sure you want to delete <strong><?= esc($tenant['name']) ?></strong>?
                        <br><small class="text-muted">This action cannot be undone.</small>
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

    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Tenants</h1>
    </div>

    <div class="mb-3 text-right">
        <a href="<?= base_url('tenants/export') ?>" class="btn btn-success mb-3">
            <i class="fas fa-file-excel"></i> Download Excel
        </a>
    </div>

    <!-- Add Tenant Button -->
    <div class="mb-3 text-right">
        <a href="#" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#addTenantModal">+ Add Tenant</a>
    </div>

    <!-- Tenants Table -->
    <div class="card shadow mb-4">
        <div class="card-body">
            <?php if (session()->getFlashdata('success')): ?>
                <div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
            <?php endif; ?>

            <div class="table-responsive">
                <table class="table table-bordered table-sm" id="tenantTable">
                    <thead class="thead-dark">
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Status</th>
                            <th>Created At</th>
                            <th>Updated At</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($tenants as $tenant): ?>
                            <tr>
                                <td><?= $tenant['id'] ?></td>
                                <td><?= esc($tenant['name']) ?></td>
                                <td>
                                    <?php if (!empty($tenant['status']) && $tenant['status'] === 'inactive'): ?>
                                        <span class="badge badge-danger">Inactive</span>
                                    <?php else: ?>
                                        <span class="badge badge-success">Active</span>
                                    <?php endif; ?>
                                </td>
                                <td><?= esc($tenant['created_at']) ?></td>
                                <td><?= esc($tenant['updated_at']) ?></td>
                                <td>
                                    <a href="#" class="btn btn-sm btn-info" data-toggle="modal" data-target="#editTenantModal<?= $tenant['id'] ?>">Edit</a>
                                    <a href="#" class="btn btn-sm btn-danger" data-toggle="modal" data-target="#deleteTenantModal<?= $tenant['id'] ?>">Delete</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>

                        <?php if (empty($tenants)): ?>
                            <tr><td colspan="6" class="text-center">No tenants found.</td></tr>
                        <?php endif ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>


<!-- /.container-fluid -->

<!-- Add Tenant Modal -->
<div class="modal fade" id="addTenantModal" tabindex="-1" role="dialog" aria-labelledby="addTenantModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <form action="<?= base_url('tenants/add') ?>" method="post">
      <?= csrf_field() ?>
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="addTenantModalLabel">Add Tenant</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        
        <div class="modal-body">
          <div class="form-group">
            <label for="tenantName">Tenant Name *</label>
            <input type="text" class="form-control" id="tenantName" name="name" required>
          </div>

          <div class="form-group">
            <label for="tenantStatus">Status *</label>
            <select class="form-control" id="tenantStatus" name="status" required>
              <option value="active" selected>Active</option>
              <option value="inactive">Inactive</option>
            </select>
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
        $('#tenantTable').DataTable();
    });
</script>

</body>

</html>