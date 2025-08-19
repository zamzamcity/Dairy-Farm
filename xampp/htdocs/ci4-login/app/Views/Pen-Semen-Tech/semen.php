<!DOCTYPE html>
<html lang="en">

<?= $this->include('components/head') ?>

<?php foreach ($semen as $entry): ?>
<!-- Edit Semen Modal -->
<div class="modal fade" id="editSemenModal<?= $entry['id'] ?>" tabindex="-1" role="dialog" aria-labelledby="editSemenModalLabel<?= $entry['id'] ?>" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <form action="<?= base_url('pen-semen-tech/semen/edit/' . $entry['id']) ?>" method="post">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="editSemenModalLabel<?= $entry['id'] ?>">Edit Semen</h5>
          <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
      </div>

      <div class="modal-body">
          <div class="form-group">
            <label for="sireName<?= $entry['id'] ?>">Sire Name *</label>
            <input type="text" class="form-control" name="sire_name" value="<?= esc($entry['sire_name']) ?>" required>
        </div>

        <div class="form-group">
            <label for="rate<?= $entry['id'] ?>">Rate per Semen *</label>
            <input type="number" step="0.01" class="form-control" name="rate_per_semen" value="<?= esc($entry['rate_per_semen']) ?>" required>
        </div>

        <div class="form-group">
            <label for="company<?= $entry['id'] ?>">Company</label>
            <select name="company_id" class="form-control" required>
              <option value="">-- Select Company --</option>
              <?php foreach ($companies as $company): ?>
                <option value="<?= $company['id'] ?>" <?= $entry['company_id'] == $company['id'] ? 'selected' : '' ?>>
                  <?= esc($company['name']) ?>
              </option>
          <?php endforeach; ?>
      </select>
  </div>

  <div class="form-group">
    <label>Type</label><br>
    <label class="mr-3">
      <input type="radio" name="type" value="Conventional" <?= $entry['type'] == 'Conventional' ? 'checked' : '' ?>> Conventional
  </label>
  <label>
      <input type="radio" name="type" value="Sexed" <?= $entry['type'] == 'Sexed' ? 'checked' : '' ?>> Sexed
  </label>
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


<?php foreach ($semen as $entry): ?>
<!-- Delete Semen Modal -->
<div class="modal fade" id="deleteSemenModal<?= $entry['id'] ?>" tabindex="-1" role="dialog" aria-labelledby="deleteSemenModalLabel<?= $entry['id'] ?>" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <form action="<?= base_url('pen-semen-tech/semen/delete/' . $entry['id']) ?>" method="post">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="deleteSemenModalLabel<?= $entry['id'] ?>">Delete Semen</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>

    <div class="modal-body">
      Are you sure you want to delete the semen record for <strong>"<?= esc($entry['sire_name']) ?>"</strong>?
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
        <h1 class="h3 mb-0 text-gray-800">Semen List</h1>
    </div>

    <div class="mb-3 text-right">
        <a href="<?= base_url('pen-semen-tech/semen/export') ?>" class="btn btn-success mb-3">
            <i class="fas fa-file-excel"></i> Download Excel
        </a>
    </div>

    <!-- Add Pen Button -->
    <?php if (hasPermission('CanAddSemen')): ?>
        <div class="mb-3 text-right">
            <a href="#" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#addSemenModal">+ Add Semen</a>
        </div>
    <?php endif; ?>

    <!-- Semen Table -->
    <div class="card shadow mb-4">
        <div class="card-body">
            <?php if (session()->getFlashdata('success')): ?>
            <div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
        <?php endif; ?>

        <div class="table-responsive">
            <table class="table table-bordered" id="semenTable">
                <thead class="thead-dark">
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Company</th>
                        <th>Type</th>
                        <th>Rate per Semen</th>
                        <?php if (hasPermission('CanUpdateSemen') || hasPermission('CanDeleteSemen')): ?>
                        <th>Actions</th>
                    <?php endif; ?>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($semen as $semen): ?>
                    <tr>
                        <td><?= esc($semen['id']) ?></td>
                        <td><?= esc($semen['sire_name']) ?></td>
                        <td><?= esc($semen['company_name']) ?></td>
                        <td><?= esc($semen['type']) ?></td>
                        <td><?= number_format($semen['rate_per_semen'], 2) ?></td>
                        <?php if (hasPermission('CanUpdateSemen') || hasPermission('CanDeleteSemen')): ?>
                        <td>
                            <?php if (hasPermission('CanUpdateSemen')): ?>
                                <a href="#" class="btn btn-sm btn-info" data-toggle="modal" data-target="#editSemenModal<?= $semen['id'] ?>">Edit</a>
                            <?php endif; ?>
                            <?php if (hasPermission('CanDeleteSemen')): ?>
                                <a href="#" class="btn btn-sm btn-danger" data-toggle="modal" data-target="#deleteSemenModal<?= $semen['id'] ?>">Delete</a>
                            <?php endif; ?>
                        </td>
                    <?php endif; ?>
                </tr>
            <?php endforeach; ?>

            <?php if (empty($semen)): ?>
                <tr><td colspan="6" class="text-center">No semen records found.</td></tr>
            <?php endif ?>
        </tbody>
    </table>
</div>
</div>
</div>
</div>

<!-- Add Semen Modal -->
<div class="modal fade" id="addSemenModal" tabindex="-1" role="dialog" aria-labelledby="addSemenModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <form action="<?= base_url('pen-semen-tech/semen/add') ?>" method="post">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="addSemenModalLabel">Add Semen</h5>
          <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
      </div>

      <div class="modal-body">
          <div class="form-group">
            <label for="sireName">Sire Name *</label>
            <input type="text" class="form-control" name="sire_name" id="sireName" required>
        </div>

        <div class="form-group">
            <label for="ratePerSemen">Rate per Semen *</label>
            <input type="number" step="0.01" class="form-control" name="rate_per_semen" id="ratePerSemen" required>
        </div>

        <div class="form-group">
            <label for="companyId">Company</label>
            <select name="company_id" id="companyId" class="form-control">
              <option value="">-- Select Company --</option>
              <?php foreach ($companies as $company): ?>
                <option value="<?= $company['id'] ?>"><?= esc($company['name']) ?></option>
            <?php endforeach; ?>
        </select>
    </div>

    <div class="form-group">
        <label>Type</label><br>
        <label class="mr-3">
          <input type="radio" name="type" value="Conventional" required> Conventional
      </label>
      <label>
          <input type="radio" name="type" value="Sexed" required> Sexed
      </label>
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
        $('#semenTable').DataTable();
    });
</script>

</body>

</html>