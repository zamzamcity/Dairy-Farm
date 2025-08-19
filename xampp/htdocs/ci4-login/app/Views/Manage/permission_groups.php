<!DOCTYPE html>
<html lang="en">

<?= $this->include('components/head') ?>

<!-- Edit Modal -->
<?php foreach ($groups as $group): ?>
    <div class="modal fade" id="editGroupModal<?= $group['id'] ?>" tabindex="-1" role="dialog" aria-labelledby="editGroupModalLabel<?= $group['id'] ?>" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <form action="<?= base_url('manage/permission_groups/edit/' . $group['id']) ?>" method="post">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="editGroupModalLabel<?= $group['id'] ?>">Edit Permission Group</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span>&times;</span></button>
          </div>
          <div class="modal-body">
              <div class="form-group">
                <label>Group Name *</label>
                <input type="text" name="name" class="form-control" value="<?= $group['name'] ?>" required>
            </div>

            <div class="form-group">
              <label for="permissions">Select Permissions *</label>
              <div class="border rounded p-2" style="max-height: 200px; overflow-y: auto;">
                <?php foreach ($permissions as $perm): ?>
                    <div class="form-check">
                      <input class="form-check-input"
                      type="checkbox"
                      name="permissions[]"
                      value="<?= $perm['id'] ?>"
                      id="permEdit<?= $group['id'] ?>_<?= $perm['id'] ?>"
                      <?= in_array($perm['id'], $group['assigned_permissions']) ? 'checked' : '' ?>>
                      <label class="form-check-label" for="permEdit<?= $group['id'] ?>_<?= $perm['id'] ?>">
                        <?= esc($perm['name']) ?>
                    </label>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

</div>

<div class="modal-footer">
  <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
  <button class="btn btn-primary" type="submit">Update</button>
</div>
</div>
</form>
</div>
</div>
<?php endforeach; ?>

<?php foreach ($groups as $group): ?>
<!-- Delete Modal -->
<div class="modal fade" id="deleteGroupModal<?= $group['id'] ?>" tabindex="-1" role="dialog" aria-labelledby="deleteGroupModalLabel<?= $group['id'] ?>" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <form action="<?= base_url('manage/permission_groups/delete/' . $group['id']) ?>" method="post">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="deleteGroupModalLabel<?= $group['id'] ?>">Confirm Delete</h5>
          <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
      </div>
      <div class="modal-body">
          Are you sure you want to delete the permission group <strong><?= esc($group['name']) ?></strong>?
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
        <h1 class="h3 mb-0 text-gray-800">Permission Groups</h1>
    </div>

    <div class="mb-3 text-right">
        <a href="<?= base_url('manage/permission_groups/export') ?>" class="btn btn-success mb-3">
            <i class="fas fa-file-excel"></i> Download Excel
        </a>
    </div>

    <!-- Add Group Button -->
    <div class="mb-3 text-right">
        <a href="#" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#addGroupModal">+ Add Permission Group</a>
    </div>

    <!-- Permission Groups Table -->
    <div class="card shadow mb-4">
        <div class="card-body">
            <?php if (session()->getFlashdata('success')): ?>
            <div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
        <?php endif; ?>

        <div class="table-responsive">
            <table class="table table-bordered" id="permissionGroupTable">
                <thead class="thead-dark">
                    <tr>
                        <th>ID</th>
                        <th>Group Name</th>
                        <th>Created At</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($groups as $group): ?>
                        <tr>
                            <td><?= $group['id'] ?></td>
                            <td><?= esc($group['name']) ?></td>
                            <td><?= $group['created_at'] ?></td>
                            <td>
                                <a href="#" class="btn btn-sm btn-info" data-toggle="modal" data-target="#editGroupModal<?= $group['id'] ?>">Edit</a>
                                <a href="#" class="btn btn-sm btn-danger" data-toggle="modal" data-target="#deleteGroupModal<?= $group['id'] ?>">Delete</a>
                            </td>
                        </tr>
                    <?php endforeach ?>
                    <?php if (empty($groups)): ?>
                        <tr><td colspan="4" class="text-center">No permission groups found.</td></tr>
                    <?php endif ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

</div>
<!-- /.container-fluid -->

<!-- Add Group Modal -->
<div class="modal fade" id="addGroupModal" tabindex="-1" role="dialog" aria-labelledby="addGroupModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <form action="<?= base_url('manage/permission_groups/add') ?>" method="post">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Add Permission Group</h5>
          <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
      </div>
      <div class="modal-body">

        <!-- Group Name -->
        <div class="form-group">
            <label for="group_name">Group Name *</label>
            <input type="text" name="name" class="form-control" required>
        </div>

        <!-- Permissions -->
        <div class="form-group">
            <label for="permissions">Select Permissions *</label>
            <div class="border rounded p-2" style="max-height: 200px; overflow-y: auto;">
                <?php foreach ($permissions as $permission): ?>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="permissions[]" value="<?= $permission['id'] ?>" id="perm<?= $permission['id'] ?>">
                        <label class="form-check-label" for="perm<?= $permission['id'] ?>">
                            <?= esc($permission['name']) ?> (<?= esc($permission['slug']) ?>)
                        </label>
                    </div>
                <?php endforeach; ?>
                <?php if (empty($permissions)): ?>
                    <p class="text-muted">No permissions available.</p>
                <?php endif; ?>
            </div>
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
        $('#permissionGroupTable').DataTable();
    });
</script>

</body>

</html>