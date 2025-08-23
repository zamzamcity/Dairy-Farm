<!DOCTYPE html>
<html lang="en">

<?= $this->include('components/head') ?>

<?php foreach ($deworming_schedules as $entry): ?>
<!-- Edit Deworming Schedule Modal -->
<div class="modal fade" id="editDewormingModal<?= $entry['id'] ?>" tabindex="-1" role="dialog" aria-labelledby="editDewormingModalLabel<?= $entry['id'] ?>" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <form action="<?= base_url('schedule-events/dewormingSchedule/edit/' . $entry['id']) ?>" method="post">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="editDewormingModalLabel<?= $entry['id'] ?>">Edit Deworming Schedule</h5>
          <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
      </div>

      <div class="modal-body">
          <div class="form-group">
            <label for="month<?= $entry['id'] ?>">Month *</label>
            <select name="month" class="form-control" required>
              <option value="">-- Select Month --</option>
              <?php
              $months = [
                'January', 'February', 'March', 'April', 'May', 'June',
                'July', 'August', 'September', 'October', 'November', 'December'
            ];
            foreach ($months as $month): ?>
                <option value="<?= $month ?>" <?= $month == $entry['month'] ? 'selected' : '' ?>><?= $month ?></option>
            <?php endforeach; ?>
        </select>
    </div>

    <div class="form-group">
        <label for="date<?= $entry['id'] ?>">Date *</label>
        <input type="date" class="form-control" name="date" value="<?= esc($entry['date']) ?>" required>
    </div>

    <div class="form-group">
        <label for="deworming_id<?= $entry['id'] ?>">Deworming *</label>
        <select name="deworming_id" class="form-control" required>
          <option value="">-- Select Deworming --</option>
          <?php foreach ($dewormings as $deworming): ?>
            <option value="<?= $deworming['id'] ?>" <?= $deworming['id'] == $entry['deworming_id'] ? 'selected' : '' ?>>
              <?= esc($deworming['name']) ?>
          </option>
      <?php endforeach; ?>
  </select>
</div>

<?php if (isSuperAdmin()): ?>
    <div class="form-group">
        <label for="tenant_id<?= $entry['id'] ?>">Tenant</label>
        <select name="tenant_id" id="tenant_id<?= $entry['id'] ?>" class="form-control">
            <option value="">Select Tenant</option>
            <?php foreach ($tenants as $tenant): ?>
                <option value="<?= $tenant['id'] ?>" <?= $entry['tenant_id'] == $tenant['id'] ? 'selected' : '' ?>>
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
<?php endforeach; ?>


<?php foreach ($deworming_schedules as $entry): ?>
<!-- Delete Deworming Schedule Modal -->
<div class="modal fade" id="deleteDewormingModal<?= $entry['id'] ?>" tabindex="-1" role="dialog" aria-labelledby="deleteDewormingModalLabel<?= $entry['id'] ?>" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <form action="<?= base_url('schedule-events/dewormingSchedule/delete/' . $entry['id']) ?>" method="post">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="deleteDewormingModalLabel<?= $entry['id'] ?>">Delete Deworming Schedule</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>

    <div class="modal-body">
      Are you sure you want to delete the deworming schedule for
      <strong>"<?= esc($entry['deworming_name'] ?? 'Unknown Deworming') ?>"</strong>
      on <strong><?= esc($entry['date']) ?></strong>
      (<?= esc($entry['month']) ?>)?
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
        <h1 class="h3 mb-0 text-gray-800">Deworming Schedule List</h1>
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
        <a href="<?= base_url('schedule-events/dewormingSchedule/export') . (!empty($selectedTenantId) ? '?tenant_id='.$selectedTenantId : '') ?>" 
         class="btn btn-success mb-3">
         <i class="fas fa-file-excel"></i> Download Excel
     </a>
 </div>

 <!-- Add Deworming Schedule Button -->
 <?php if (hasPermission('CanAddDewormingSchedule')): ?>
    <div class="mb-3 text-right">
        <a href="#" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#addDewormingModal">+ Add Deworming Schedule</a>
    </div>
<?php endif; ?>

<!-- Deworming Schedule Table -->
<div class="card shadow mb-4">
    <div class="card-body">
        <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
    <?php endif; ?>

    <div class="table-responsive">
        <table class="table table-bordered" id="dewormingTable">
            <thead class="thead-dark">
                <tr>
                    <th>ID</th>
                    <th>Month</th>
                    <th>Date</th>
                    <th>Deworming</th>
                    <th>Tenant</th>
                    <?php if (hasPermission('CanUpdateDewormingSchedule') || hasPermission('CanDeleteDewormingSchedule')): ?>
                    <th>Actions</th>
                <?php endif; ?>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($deworming_schedules)) : ?>
                <?php foreach ($deworming_schedules as $schedule): ?>
                    <tr>
                        <td><?= esc($schedule['id']) ?></td>
                        <td><?= esc($schedule['month']) ?></td>
                        <td><?= esc($schedule['date']) ?></td>
                        <td><?= esc($schedule['deworming_name']) ?></td>
                        <td><?= esc($schedule['tenant_name'] ?? 'N/A') ?></td>
                        <?php if (hasPermission('CanUpdateDewormingSchedule') || hasPermission('CanDeleteDewormingSchedule')): ?>
                        <td>
                            <?php if (hasPermission('CanUpdateDewormingSchedule')): ?>
                                <a href="#" class="btn btn-sm btn-info" data-toggle="modal" data-target="#editDewormingModal<?= $schedule['id'] ?>">Edit</a>
                            <?php endif; ?>
                            <?php if (hasPermission('CanDeleteDewormingSchedule')): ?>
                                <a href="#" class="btn btn-sm btn-danger" data-toggle="modal" data-target="#deleteDewormingModal<?= $schedule['id'] ?>">Delete</a>
                            <?php endif; ?>
                        </td>
                    <?php endif; ?>
                </tr>
            <?php endforeach; ?>
        <?php endif ?>
    </tbody>
</table>
</div>
</div>
</div>

</div>

<!-- Add Deworming Schedule Modal -->
<div class="modal fade" id="addDewormingModal" tabindex="-1" role="dialog" aria-labelledby="addDewormingModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <form action="<?= base_url('schedule-events/dewormingSchedule/add') ?>" method="post">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="addDewormingModalLabel">Add Deworming Schedule</h5>
          <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
      </div>

      <div class="modal-body">
          <div class="form-group">
            <label for="month">Month *</label>
            <select name="month" id="month" class="form-control" required>
              <option value="">-- Select Month --</option>
              <?php
              $months = [
                  'January', 'February', 'March', 'April', 'May', 'June',
                  'July', 'August', 'September', 'October', 'November', 'December'
              ];
              foreach ($months as $month): ?>
                <option value="<?= $month ?>"><?= $month ?></option>
            <?php endforeach; ?>
        </select>
    </div>

    <div class="form-group">
        <label for="date">Date *</label>
        <input type="date" class="form-control" name="date" id="date" required>
    </div>

    <div class="form-group">
        <label for="deworming_id">Deworming *</label>
        <select name="deworming_id" id="deworming_id" class="form-control" required>
          <option value="">-- Select Deworming --</option>
          <?php foreach ($dewormings as $deworming): ?>
              <option value="<?= $deworming['id'] ?>"><?= esc($deworming['name']) ?></option>
          <?php endforeach; ?>
      </select>
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
        $('#dewormingTable').DataTable();
    });
</script>

</body>

</html>