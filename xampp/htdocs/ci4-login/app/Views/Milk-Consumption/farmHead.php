<!DOCTYPE html>
<html lang="en">

<?= $this->include('components/head') ?>

<?php foreach ($farm_head as $head): ?>
<!-- Edit Farm Head Modal -->
<div class="modal fade" id="editFarmHeadModal<?= $head['id'] ?>" tabindex="-1" role="dialog" aria-labelledby="editFarmHeadLabel<?= $head['id'] ?>" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <form action="<?= base_url('milk-consumption/farmHead/edit/' . $head['id']) ?>" method="post">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="editFarmHeadLabel<?= $head['id'] ?>">Edit Farm Head</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    <div class="modal-body">
      <div class="form-group">
        <label>Head Name *</label>
        <input type="text" name="head_name" class="form-control" value="<?= esc($head['head_name']) ?>" required>
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

<!-- Delete Farm Head Modal -->
<div class="modal fade" id="deleteFarmHeadModal<?= $head['id'] ?>" tabindex="-1" role="dialog" aria-labelledby="deleteFarmHeadLabel<?= $head['id'] ?>" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <form action="<?= base_url('milk-consumption/farmHead/delete/' . $head['id']) ?>" method="post">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="deleteFarmHeadLabel<?= $head['id'] ?>">Confirm Delete</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    <div class="modal-body">
      Are you sure you want to delete <strong><?= esc($head['head_name'] ?? 'Unknown Tag') ?></strong>?
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
        <h1 class="h3 mb-0 text-gray-800">Farm Milk Head List</h1>
    </div>

    <div class="mb-3 text-right">
        <a href="<?= base_url('milk-consumption/farmHead/farmHeadExport') ?>" class="btn btn-success mb-3">
            <i class="fas fa-file-excel"></i> Download Excel
        </a>
    </div>

    <!-- Add Farm Head Button -->
    <?php if (hasPermission('CanAddFarmHead')): ?>
        <div class="mb-3 text-right">
            <a href="#" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#addFarmHeadModal">+ Add Head</a>
        </div>
    <?php endif; ?>

<!-- Farm Head Table -->
<div class="card shadow mb-4">
    <div class="card-body">
        <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
    <?php endif; ?>

    <div class="table-responsive">
        <table class="table table-bordered" id="farmHeadTable">
            <thead class="thead-dark">
                <tr>
                    <th>ID</th>
                    <th>Head Name</th>
                    <?php if (hasPermission('CanUpdateFarmHead') || hasPermission('CanDeleteFarmHead')): ?>
                    <th>Actions</th>
                <?php endif; ?>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($farm_head as $head): ?>
                <tr>
                    <td><?= esc($head['id']) ?></td>
                    <td><?= esc($head['head_name']) ?></td>
                    <?php if (hasPermission('CanUpdateFarmHead') || hasPermission('CanDeleteFarmHead')): ?>
                    <td>
                        <?php if (hasPermission('CanUpdateFarmHead')): ?>
                            <a href="#" class="btn btn-sm btn-info" data-toggle="modal" data-target="#editFarmHeadModal<?= $head['id'] ?>">Edit</a>
                        <?php endif; ?>
                        <?php if (hasPermission('CanDeleteFarmHead')): ?>
                            <a href="#" class="btn btn-sm btn-danger" data-toggle="modal" data-target="#deleteFarmHeadModal<?= $head['id'] ?>">Delete</a>
                        <?php endif; ?>
                    </td>
                <?php endif; ?>
            </tr>
        <?php endforeach; ?>

        <?php if (empty($farm_head)): ?>
            <tr>
                <td colspan="4" class="text-center">No farm head records found.</td>
            </tr>
        <?php endif; ?>
    </tbody>
</table>
</div>
</div>
</div>
</div>

<!-- Add Farm head Modal -->
<div class="modal fade" id="addFarmHeadModal" tabindex="-1" role="dialog" aria-labelledby="addFarmHeadLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <form action="<?= base_url('milk-consumption/farmHead/add') ?>" method="post">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="addFarmHeadLabel">Add Farm Head</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    <div class="modal-body">
      <div class="form-group">
        <label>Head Name *</label>
        <input type="text" name="head_name" class="form-control" required>
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
        $('#farmHeadTable').DataTable();
    });
</script>

</body>

</html>