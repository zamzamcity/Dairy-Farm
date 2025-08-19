<!DOCTYPE html>
<html lang="en">

<?= $this->include('components/head') ?>

<!-- Add Permission Modal -->
<div class="modal fade" id="addPermissionModal" tabindex="-1" role="dialog" aria-labelledby="addPermissionModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <form action="<?= base_url('manage/permissions/add') ?>" method="post">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="addPermissionModalLabel">Add Permission</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    
    <div class="modal-body">
      <div class="form-group">
        <label for="name">Name *</label>
        <input type="text" class="form-control" name="name" required>
    </div>
    <div class="form-group">
        <label for="slug">Description *</label>
        <input type="text" class="form-control" name="slug" required>
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

<?php foreach ($permissions as $permission): ?>
<!-- Edit Permission Modal -->
<div class="modal fade" id="editPermissionModal<?= $permission['id'] ?>" tabindex="-1" role="dialog" aria-labelledby="editPermissionModalLabel<?= $permission['id'] ?>" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <form action="<?= base_url('manage/permissions/update/' . $permission['id']) ?>" method="post">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="editPermissionModalLabel<?= $permission['id'] ?>">Edit Permission</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    
    <div class="modal-body">
      <div class="form-group">
        <label for="name<?= $permission['id'] ?>">Name *</label>
        <input type="text" class="form-control" name="name" value="<?= esc($permission['name']) ?>" required>
    </div>
    <div class="form-group">
        <label for="slug<?= $permission['id'] ?>">Description *</label>
        <input type="text" class="form-control" name="slug" value="<?= esc($permission['slug']) ?>" required>
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

<?php foreach ($permissions as $permission): ?>
<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deletePermissionModal<?= $permission['id'] ?>" tabindex="-1" role="dialog" aria-labelledby="deletePermissionModalLabel<?= $permission['id'] ?>" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <form action="<?= base_url('manage/permissions/delete/' . $permission['id']) ?>" method="post">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="deletePermissionModalLabel<?= $permission['id'] ?>">Delete Permission</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    
    <div class="modal-body">
      Are you sure you want to delete the permission <strong>"<?= esc($permission['name']) ?>"</strong>?
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

<!-- Page Heading -->
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Permissions List</h1>
</div>

<div class="mb-3 text-right">
    <a href="<?= base_url('manage/permissions/export') ?>" class="btn btn-success mb-3">
        <i class="fas fa-file-excel"></i> Download Excel
    </a>
</div>

<!-- Add Group Button -->
<div class="mb-3 text-right">
    <a href="#" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#addPermissionModal">+ Add Permission</a>
</div>
<div class="card shadow mb-4">
    <div class="card-body">
        <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
    <?php endif; ?>

    <div class="table-responsive">
        <table class="table table-bordered" width="100%" cellspacing="0" id="permissionTable">
            <thead  class="thead-dark">
                <tr>
                    <th>ID</th>
                    <th>Permission Name</th>
                    <th>Description</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($permissions)) : ?>
                    <?php foreach ($permissions as $permission): ?>
                        <tr>
                            <td><?= $permission['id'] ?></td>
                            <td><?= esc($permission['name']) ?></td>
                            <td><?= esc($permission['slug']) ?></td>
                            <td>
                                <a href="#" class="btn btn-sm btn-info" data-toggle="modal" data-target="#editPermissionModal<?= $permission['id'] ?>">Edit</a>
                                <a href="#" class="btn btn-sm btn-danger" data-toggle="modal" data-target="#deletePermissionModal<?= $permission['id'] ?>">Delete</a>
                            </td> 
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                  <tr><td colspan="4" class="text-center">No permissions found</td></tr>
              <?php endif; ?>
          </tbody>
      </table>
  </div>
</div>
</div>


<div class="row">

    <div class="col-lg-6">



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
        $('#permissionTable').DataTable();
    });
</script>

</body>

</html>