<!DOCTYPE html>
<html lang="en">

<?= $this->include('components/head') ?>

<?php foreach ($schedules as $entry): ?>
<!-- Edit Schedule Modal -->
<div class="modal fade" id="editScheduleModal<?= $entry['id'] ?>" tabindex="-1" role="dialog" aria-labelledby="editScheduleModalLabel<?= $entry['id'] ?>" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <form action="<?= base_url('schedule-events/schedule/edit/' . $entry['id']) ?>" method="post">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="editScheduleModalLabel<?= $entry['id'] ?>">Edit Schedule</h5>
          <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
      </div>

      <div class="modal-body">
          <div class="form-group">
            <label for="tag_id<?= $entry['id'] ?>">Animal Tag *</label>
            <select name="tag_id" class="form-control" required>
              <option value="">-- Select Tag --</option>
              <?php foreach ($animals as $animal): ?>
                <option value="<?= $animal['id'] ?>" <?= $animal['id'] == $entry['tag_id'] ? 'selected' : '' ?>>
                  <?= esc($animal['tag_id']) ?>
              </option>
          <?php endforeach; ?>
      </select>
  </div>

  <div class="form-group">
    <label for="date<?= $entry['id'] ?>">Date *</label>
    <input type="date" class="form-control" name="date" value="<?= esc($entry['date']) ?>" required>
</div>

<div class="form-group">
    <label for="time<?= $entry['id'] ?>">Time *</label>
    <input type="time" class="form-control" name="time" value="<?= esc($entry['time']) ?>" required>
</div>

<div class="form-group">
    <label for="event_id<?= $entry['id'] ?>">Event *</label>
    <select name="event_id" class="form-control" required>
      <option value="">-- Select Event --</option>
      <?php foreach ($events as $event): ?>
        <option value="<?= $event['id'] ?>" <?= $event['id'] == $entry['event_id'] ? 'selected' : '' ?>>
          <?= esc($event['name']) ?>
      </option>
  <?php endforeach; ?>
</select>
</div>

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


<?php foreach ($schedules as $entry): ?>
<!-- Delete Schedule Modal -->
<div class="modal fade" id="deleteScheduleModal<?= $entry['id'] ?>" tabindex="-1" role="dialog" aria-labelledby="deleteScheduleModalLabel<?= $entry['id'] ?>" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <form action="<?= base_url('schedule-events/schedule/delete/' . $entry['id']) ?>" method="post">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="deleteScheduleModalLabel<?= $entry['id'] ?>">Delete Schedule</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>

    <div class="modal-body">
      Are you sure you want to delete the schedule for 
      <strong>"<?= esc($entry['animal_tag'] ?? $entry['tag_id'] ?? 'Unknown') ?>"</strong> 
      on <strong><?= esc($entry['date']) ?></strong>?
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
        <h1 class="h3 mb-0 text-gray-800">Schedule List</h1>
    </div>

    <div class="mb-3 text-right">
        <a href="<?= base_url('schedule-events/schedule/export') ?>" class="btn btn-success mb-3">
            <i class="fas fa-file-excel"></i> Download Excel
        </a>
    </div>

    <!-- Add New Schedule Button -->
    <?php if (hasPermission('CanAddSchedule')): ?>
        <div class="mb-3 text-right">
            <a href="#" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#addScheduleModal">+ Add New Schedule</a>
        </div>
    <?php endif; ?>

    <!-- Schedule Table -->
    <div class="card shadow mb-4">
        <div class="card-body">
            <?php if (session()->getFlashdata('success')): ?>
            <div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
        <?php endif; ?>

        <div class="table-responsive">
            <table class="table table-bordered" id="scheduleTable">
                <thead class="thead-dark">
                    <tr>
                        <th>ID</th>
                        <th>Tag ID</th>
                        <th>Date</th>
                        <th>Time</th>
                        <th>Event</th>
                        <th>Comments</th>
                        <?php if (hasPermission('CanUpdateSchedule') || hasPermission('CanDeleteSchedule')): ?>
                        <th>Actions</th>
                    <?php endif; ?>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($schedules as $schedule): ?>
                    <tr>
                        <td><?= esc($schedule['id']) ?></td>
                        <td><?= esc($schedule['animal_tag']) ?></td>
                        <td><?= esc($schedule['date']) ?></td>
                        <td><?= esc($schedule['time']) ?></td>
                        <td><?= esc($schedule['event_name']) ?></td>
                        <td><?= esc($schedule['comments']) ?></td>
                        <?php if (hasPermission('CanUpdateSchedule') || hasPermission('CanDeleteSchedule')): ?>
                        <td>
                            <?php if (hasPermission('CanUpdateSchedule')): ?>
                                <a href="#" class="btn btn-sm btn-info" data-toggle="modal" data-target="#editScheduleModal<?= $schedule['id'] ?>">Edit</a>
                            <?php endif; ?>
                            <?php if (hasPermission('CanDeleteSchedule')): ?>
                                <a href="#" class="btn btn-sm btn-danger" data-toggle="modal" data-target="#deleteScheduleModal<?= $schedule['id'] ?>">Delete</a>
                            <?php endif; ?>
                        </td>
                    <?php endif; ?>
                </tr>
            <?php endforeach; ?>

            <?php if (empty($schedules)): ?>
                <tr><td colspan="7" class="text-center">No schedule records found.</td></tr>
            <?php endif ?>
        </tbody>
    </table>
</div>
</div>
</div>
</div>

<!-- Add Schedule Modal -->
<div class="modal fade" id="addScheduleModal" tabindex="-1" role="dialog" aria-labelledby="addScheduleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <form action="<?= base_url('schedule-events/schedule/add') ?>" method="post">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="addScheduleModalLabel">Add Schedule</h5>
          <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
      </div>

      <div class="modal-body">
          <div class="form-group">
            <label for="tag_id">Animal Tag *</label>
            <select name="tag_id" id="tag_id" class="form-control" required>
              <option value="">-- Select Tag --</option>
              <?php foreach ($animals as $animal): ?>
                <option value="<?= $animal['id'] ?>"><?= esc($animal['tag_id']) ?></option>
            <?php endforeach; ?>
        </select>
    </div>

    <div class="form-group">
        <label for="date">Date *</label>
        <input type="date" class="form-control" name="date" id="date" required>
    </div>

    <div class="form-group">
        <label for="time">Time *</label>
        <input type="time" class="form-control" name="time" id="time" required>
    </div>

    <div class="form-group">
        <label for="event_id">Event *</label>
        <select name="event_id" id="event_id" class="form-control" required>
          <option value="">-- Select Event --</option>
          <?php foreach ($events as $event): ?>
            <option value="<?= $event['id'] ?>"><?= esc($event['name']) ?></option>
        <?php endforeach; ?>
    </select>
</div>

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
        $('#scheduleTable').DataTable();
    });
</script>

</body>

</html>