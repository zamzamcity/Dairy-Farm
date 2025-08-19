<!DOCTYPE html>
<html lang="en">

<?= $this->include('components/head') ?>

<?php foreach ($daily_milking as $entry): ?>
<!-- Edit Daily Milking Modal -->
<div class="modal fade" id="editDailyMilkingModal<?= $entry['id'] ?>" tabindex="-1" role="dialog" aria-labelledby="editDailyMilkingModalLabel<?= $entry['id'] ?>" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <form action="<?= base_url('dailyMilk/edit/' . $entry['id']) ?>" method="post">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="editDailyMilkingModalLabel<?= $entry['id'] ?>">Edit Daily Milking Record</h5>
          <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
      </div>

      <div class="modal-body">
          <div class="form-group">
            <label for="date<?= $entry['id'] ?>">Date *</label>
            <input type="date" class="form-control" name="date" value="<?= esc($entry['date']) ?>" required>
        </div>

        <div class="form-group">
            <label for="milk_product<?= $entry['id'] ?>">Milk Product *</label>
            <select name="milk_product" class="form-control" required>
              <option value="">-- Select Milk Product --</option>
              <option value="Milk" <?= $entry['milk_product'] == 'Milk' ? 'selected' : '' ?>>Milk</option>
              <!-- Add more options as needed -->
          </select>
      </div>

      <div class="form-group">
        <label for="milk_1<?= $entry['id'] ?>">Milk 1 (Litres) *</label>
        <input type="number" step="0.01" class="form-control" name="milk_1" value="<?= esc($entry['milk_1']) ?>" required>
    </div>

    <div class="form-group">
        <label for="milk_2<?= $entry['id'] ?>">Milk 2 (Litres)</label>
        <input type="number" step="0.01" class="form-control" name="milk_2" value="<?= esc($entry['milk_2']) ?>">
    </div>

    <div class="form-group">
        <label for="milk_3<?= $entry['id'] ?>">Milk 3 (Litres)</label>
        <input type="number" step="0.01" class="form-control" name="milk_3" value="<?= esc($entry['milk_3']) ?>">
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


<?php foreach ($daily_milking as $entry): ?>
<!-- Delete Daily Milking Modal -->
<div class="modal fade" id="deleteDailyMilkingModal<?= $entry['id'] ?>" tabindex="-1" role="dialog" aria-labelledby="deleteDailyMilkingModalLabel<?= $entry['id'] ?>" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <form action="<?= base_url('dailyMilk/delete/' . $entry['id']) ?>" method="post">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="deleteDailyMilkingModalLabel<?= $entry['id'] ?>">Delete Daily Milking Record</h5>
          <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
      </div>

      <div class="modal-body">
          Are you sure you want to delete the daily milking record for 
          <strong><?= esc($entry['milk_product']) ?></strong> dated 
          <strong><?= esc($entry['date']) ?></strong>?
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
        <h1 class="h3 mb-0 text-gray-800">Daily Milk List</h1>
    </div>

    <?php
    $grandTotal = 0;
    foreach ($daily_milking as $record) {
        $grandTotal += floatval($record['total_milk']);
    }
    ?>

    <form method="get" action="<?= base_url('dailyMilk') ?>" class="form-inline mb-3">
        <label for="start_date" class="mr-2">Start Date:</label>
        <input type="date" name="start_date" class="form-control mr-3" value="<?= esc($start_date ?? '') ?>">

        <label for="end_date" class="mr-2">End Date:</label>
        <input type="date" name="end_date" class="form-control mr-3" value="<?= esc($end_date ?? '') ?>">

        <button type="submit" class="btn btn-primary">Filter</button>
        <a href="<?= base_url('dailyMilk') ?>" class="btn btn-secondary ml-2">Reset</a>
    </form>

    <div class="mb-3 text-right">
        <a href="<?= base_url('dailyMilk/export') . '?' . $_SERVER['QUERY_STRING'] ?>" class="btn btn-success mb-3">
            <i class="fas fa-file-excel"></i> Download Excel
        </a>
    </div>

    <!-- Add Daily Milking Button -->
    <?php if (hasPermission('CanAddDailyMilking')): ?>
        <div class="mb-3 text-right">
            <a href="#" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#addDailyMilkingModal">+ Add Daily Milk</a>
        </div>
    <?php endif; ?>

<!-- Daily Milking Table -->
<div class="card shadow mb-4">
    <div class="card-body">
        <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
    <?php endif; ?>

    <div class="table-responsive">

        <table class="table table-bordered">
            <thead class="thead-dark">
                <tr>
                    <th>ID</th>
                    <th>Date</th>
                    <th>Milk Product</th>
                    <th>Milk 1 (L)</th>
                    <th>Milk 2 (L)</th>
                    <th>Milk 3 (L)</th>
                    <th>Total Milk (L)</th>
                    <?php if (hasPermission('CanUpdateDailyMilking') || hasPermission('CanDeleteDailyMilking')): ?>
                    <th>Actions</th>
                <?php endif; ?>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($daily_milking as $record): ?>
                <tr>
                    <td><?= esc($record['id']) ?></td>
                    <td><?= esc($record['date']) ?></td>
                    <td><?= esc($record['milk_product']) ?></td>
                    <td><?= esc($record['milk_1']) ?></td>
                    <td><?= esc($record['milk_2']) ?></td>
                    <td><?= esc($record['milk_3']) ?></td>
                    <td><?= esc($record['total_milk']) ?></td>
                    <?php if (hasPermission('CanUpdateDailyMilking') || hasPermission('CanDeleteDailyMilking')): ?>
                    <td>
                        <?php if (hasPermission('CanUpdateDailyMilking')): ?>
                            <a href="#" class="btn btn-sm btn-info" data-toggle="modal" data-target="#editDailyMilkingModal<?= $record['id'] ?>">Edit</a>
                        <?php endif; ?>
                        <?php if (hasPermission('CanDeleteDailyMilking')): ?>
                            <a href="#" class="btn btn-sm btn-danger" data-toggle="modal" data-target="#deleteDailyMilkingModal<?= $record['id'] ?>">Delete</a>
                        <?php endif; ?>
                    </td>
                <?php endif; ?>
            </tr>
        <?php endforeach; ?>

        <?php if (empty($daily_milking)): ?>
            <tr>
                <td colspan="8" class="text-center">No daily milking records found.</td>
            </tr>
        <?php else: ?>
            <!-- Total Row -->
            <tr style="font-weight: bold; background-color: #f1f1f1;">
                <td colspan="6" class="text-center">Grand Total (Litres):</td>
                <td><?= number_format($grandTotal, 2) ?></td>
                <?php if (hasPermission('CanUpdateDailyMilking') || hasPermission('CanDeleteDailyMilking')): ?>
                <td></td>
            <?php endif; ?>
        </tr>
    <?php endif; ?>
</tbody>
</table>
</div>
</div>
</div>

</div>

<!-- Add Daily Milking Modal -->
<div class="modal fade" id="addDailyMilkingModal" tabindex="-1" role="dialog" aria-labelledby="addDailyMilkingModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <form action="<?= base_url('dailyMilk/add') ?>" method="post">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="addDailyMilkingModalLabel">Add Daily Milking Record</h5>
          <button type="button" class="close" data-dismiss="modal">
            <span>&times;</span>
        </button>
    </div>

    <div class="modal-body">
      <div class="form-group">
        <label for="date">Date *</label>
        <input type="date" class="form-control" name="date" id="date" required>
    </div>

    <div class="form-group">
        <label for="milk_product">Milk Product *</label>
        <select name="milk_product" id="milk_product" class="form-control" required>
          <option value="">-- Select Milk Product --</option>
          <option value="Milk">Milk</option>
          <!-- Add more options if needed -->
      </select>
  </div>

  <div class="form-group">
    <label for="milk_1">Milk 1 (Litres) *</label>
    <input type="number" step="0.01" class="form-control" name="milk_1" id="milk_1" required>
</div>

<div class="form-group">
    <label for="milk_2">Milk 2 (Litres)</label>
    <input type="number" step="0.01" class="form-control" name="milk_2" id="milk_2">
</div>

<div class="form-group">
    <label for="milk_3">Milk 3 (Litres)</label>
    <input type="number" step="0.01" class="form-control" name="milk_3" id="milk_3">
</div>

<p class="text-muted"><small>Total Milk will be calculated automatically.</small></p>
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

</body>

</html>