<!DOCTYPE html>
<html lang="en">

<?= $this->include('components/head') ?>

<?php foreach ($technicians as $entry): ?>
<!-- Edit Technician Modal -->
<div class="modal fade" id="editTechnicianModal<?= $entry['id'] ?>" tabindex="-1" role="dialog" aria-labelledby="editTechnicianModalLabel<?= $entry['id'] ?>" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <form action="<?= base_url('pen-semen-tech/technician/edit/' . $entry['id']) ?>" method="post">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="editTechnicianModalLabel<?= $entry['id'] ?>">Edit Technician</h5>
          <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
      </div>

      <div class="modal-body">
          <div class="form-group">
            <label for="technicianName<?= $entry['id'] ?>">Technician Name *</label>
            <input type="text" class="form-control" name="name" value="<?= esc($entry['name']) ?>" required>
        </div>

        <div class="form-group">
            <label for="status<?= $entry['id'] ?>">Status *</label>
            <select name="status" class="form-control" required>
              <option value="">-- Select Status --</option>
              <option value="Active" <?= $entry['status'] == 'Active' ? 'selected' : '' ?>>Active</option>
              <option value="Inactive" <?= $entry['status'] == 'Inactive' ? 'selected' : '' ?>>Inactive</option>
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


<?php foreach ($technicians as $entry): ?>
<!-- Delete Technician Modal -->
<div class="modal fade" id="deleteTechnicianModal<?= $entry['id'] ?>" tabindex="-1" role="dialog" aria-labelledby="deleteTechnicianModalLabel<?= $entry['id'] ?>" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <form action="<?= base_url('pen-semen-tech/technician/delete/' . $entry['id']) ?>" method="post">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="deleteTechnicianModalLabel<?= $entry['id'] ?>">Delete Technician</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>

    <div class="modal-body">
      Are you sure you want to delete the technician record for <strong>"<?= esc($entry['name']) ?>"</strong>?
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
        <h1 class="h3 mb-0 text-gray-800">Technician List</h1>
    </div>

    <div class="mb-3 text-right">
        <a href="<?= base_url('pen-semen-tech/technician/export') ?>" class="btn btn-success mb-3">
            <i class="fas fa-file-excel"></i> Download Excel
        </a>
    </div>

    <!-- Add Technician Button -->
    <?php if (hasPermission('CanAddTechnician')): ?>
        <div class="mb-3 text-right">
            <a href="#" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#addTechnicianModal">+ Add Technician</a>
        </div>
    <?php endif; ?>

    <!-- Technician Table -->
    <div class="card shadow mb-4">
        <div class="card-body">
            <?php if (session()->getFlashdata('success')): ?>
            <div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
        <?php endif; ?>

        <div class="table-responsive">
            <table class="table table-bordered" id="technicianTable">
                <thead class="thead-dark">
                    <tr>
                        <th>ID</th>
                        <th>Technician Name</th>
                        <th>Status</th>
                        <?php if (hasPermission('CanUpdateTechnician') || hasPermission('CanDeleteTechnician')): ?>
                        <th>Actions</th>
                    <?php endif; ?>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($technicians as $technician): ?>
                    <tr>
                        <td><?= esc($technician['id']) ?></td>
                        <td><?= esc($technician['name']) ?></td>
                        <td><?= esc($technician['status']) ?></td>
                        <?php if (hasPermission('CanUpdateTechnician') || hasPermission('CanDeleteTechnician')): ?>
                        <td>
                            <?php if (hasPermission('CanUpdateTechnician')): ?>
                                <a href="#" class="btn btn-sm btn-info" data-toggle="modal" data-target="#editTechnicianModal<?= $technician['id'] ?>">Edit</a>
                            <?php endif; ?>
                            <?php if (hasPermission('CanDeleteTechnician')): ?>
                                <a href="#" class="btn btn-sm btn-danger" data-toggle="modal" data-target="#deleteTechnicianModal<?= $technician['id'] ?>">Delete</a>
                            <?php endif; ?>
                        </td>
                    <?php endif; ?>
                </tr>
            <?php endforeach; ?>

            <?php if (empty($technicians)): ?>
                <tr><td colspan="4" class="text-center">No technician records found.</td></tr>
            <?php endif ?>
        </tbody>
    </table>
</div>
</div>
</div>

</div>

<!-- Add Technician Modal -->
<div class="modal fade" id="addTechnicianModal" tabindex="-1" role="dialog" aria-labelledby="addTechnicianModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <form action="<?= base_url('pen-semen-tech/technician/add') ?>" method="post">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="addTechnicianModalLabel">Add Technician</h5>
          <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
      </div>

      <div class="modal-body">
          <div class="form-group">
            <label for="technicianName">Technician Name *</label>
            <input type="text" class="form-control" name="name" id="technicianName" required>
        </div>

        <div class="form-group">
            <label for="status">Status *</label>
            <select name="status" id="status" class="form-control" required>
              <option value="">-- Select Status --</option>
              <option value="Active">Active</option>
              <option value="Inactive">Inactive</option>
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
        $('#technicianTable').DataTable();
    });
</script>

</body>

</html>