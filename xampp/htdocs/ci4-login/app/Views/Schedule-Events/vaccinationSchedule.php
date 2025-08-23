<!DOCTYPE html>
<html lang="en">

<?= $this->include('components/head') ?>

<?php foreach ($vaccination_schedules as $entry): ?>
<!-- Edit Vaccination Schedule Modal -->
<div class="modal fade" id="editScheduleModal<?= $entry['id'] ?>" tabindex="-1" role="dialog" aria-labelledby="editScheduleModalLabel<?= $entry['id'] ?>" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <form action="<?= base_url('schedule-events/vaccinationSchedule/edit/' . $entry['id']) ?>" method="post">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="editScheduleModalLabel<?= $entry['id'] ?>">Edit Vaccination Schedule</h5>
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
            <label for="vaccination_id<?= $entry['id'] ?>">Vaccination *</label>
            <select name="vaccination_id" class="form-control" required>
              <option value="">-- Select Vaccination --</option>
              <?php foreach ($vaccinations as $vaccination): ?>
                <option value="<?= $vaccination['id'] ?>" <?= $vaccination['id'] == $entry['vaccination_id'] ? 'selected' : '' ?>>
                  <?= esc($vaccination['name']) ?>
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

          <div class="form-group">
            <label for="comments<?= $entry['id'] ?>">Comments</label>
            <textarea name="comments" class="form-control" rows="3"><?= esc($entry['comments']) ?></textarea>
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


<?php foreach ($vaccination_schedules as $entry): ?>
<!-- Delete Vaccination Schedule Modal -->
<div class="modal fade" id="deleteScheduleModal<?= $entry['id'] ?>" tabindex="-1" role="dialog" aria-labelledby="deleteScheduleModalLabel<?= $entry['id'] ?>" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <form action="<?= base_url('schedule-events/vaccinationSchedule/delete/' . $entry['id']) ?>" method="post">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="deleteScheduleModalLabel<?= $entry['id'] ?>">Delete Vaccination Schedule</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>

        <div class="modal-body">
          Are you sure you want to delete the vaccination schedule for
          <strong>"<?= esc($entry['vaccination_name'] ?? 'Unknown Vaccination') ?>"</strong>
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
    <h1 class="h3 mb-0 text-gray-800">Vaccination Schedule List</h1>
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
    <a href="<?= base_url('schedule-events/vaccinationSchedule/export') . (!empty($selectedTenantId) ? '?tenant_id='.$selectedTenantId : '') ?>" 
     class="btn btn-success mb-3">
     <i class="fas fa-file-excel"></i> Download Excel
   </a>
 </div>

 <!-- Add Vaccination Schedule Button -->
 <?php if (hasPermission('CanAddVaccinationSchedule')): ?>
  <div class="mb-3 text-right">
    <a href="#" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#addScheduleModal">+ Add Vaccination Schedule</a>
  </div>
<?php endif; ?>

<!-- Vaccination Schedule Table -->
<div class="card shadow mb-4">
  <div class="card-body">
    <?php if (session()->getFlashdata('success')): ?>
    <div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
  <?php endif; ?>

  <div class="table-responsive">
    <table class="table table-bordered" id="vaccinationTable">
      <thead class="thead-dark">
        <tr>
          <th>ID</th>
          <th>Month</th>
          <th>Date</th>
          <th>Vaccination</th>
          <th>Comments</th>
          <th>Tenant</th>
          <?php if (hasPermission('CanUpdateVaccinationSchedule') || hasPermission('CanDeleteVaccinationSchedule')): ?>
          <th>Actions</th>
        <?php endif; ?>
      </tr>
    </thead>
    <tbody>
      <?php if (!empty($vaccination_schedules)) : ?>
        <?php foreach ($vaccination_schedules as $schedule): ?>
          <tr>
            <td><?= esc($schedule['id']) ?></td>
            <td><?= esc($schedule['month']) ?></td>
            <td><?= esc($schedule['date']) ?></td>
            <td><?= esc($schedule['vaccination_name']) ?></td>
            <td><?= esc($schedule['comments']) ?></td>
            <td><?= esc($schedule['tenant_name'] ?? 'N/A') ?></td>
            <?php if (hasPermission('CanUpdateVaccinationSchedule') || hasPermission('CanDeleteVaccinationSchedule')): ?>
            <td>
              <?php if (hasPermission('CanUpdateVaccinationSchedule')): ?>
                <a href="#" class="btn btn-sm btn-info" data-toggle="modal" data-target="#editScheduleModal<?= $schedule['id'] ?>">Edit</a>
              <?php endif; ?>
              <?php if (hasPermission('CanDeleteVaccinationSchedule')): ?>
                <a href="#" class="btn btn-sm btn-danger" data-toggle="modal" data-target="#deleteScheduleModal<?= $schedule['id'] ?>">Delete</a>
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

<!-- Add Vaccination Schedule Modal -->
<div class="modal fade" id="addScheduleModal" tabindex="-1" role="dialog" aria-labelledby="addScheduleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <form action="<?= base_url('schedule-events/vaccinationSchedule/add') ?>" method="post">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="addScheduleModalLabel">Add Vaccination Schedule</h5>
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
            <label for="vaccination_id">Vaccination *</label>
            <select name="vaccination_id" id="vaccination_id" class="form-control" required>
              <option value="">-- Select Vaccination --</option>
              <?php foreach ($vaccinations as $vaccination): ?>
                <option value="<?= $vaccination['id'] ?>"><?= esc($vaccination['name']) ?></option>
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

          <div class="form-group">
            <label for="comments">Comments</label>
            <textarea name="comments" id="comments" class="form-control" rows="3" placeholder="Enter comments..."></textarea>
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
    $('#vaccinationTable').DataTable();
  });
</script>

</body>

</html>