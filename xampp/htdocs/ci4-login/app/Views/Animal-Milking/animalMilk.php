<!DOCTYPE html>
<html lang="en">

<?= $this->include('components/head') ?>

<?php foreach ($animal_milk as $entry): ?>
<!-- Edit Animal Milk Modal -->
<div class="modal fade" id="editAnimalMilkModal<?= $entry['id'] ?>" tabindex="-1" role="dialog" aria-labelledby="editAnimalMilkModalLabel<?= $entry['id'] ?>" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <form action="<?= base_url('animal-milking/animalMilk/edit/' . $entry['id']) ?>" method="post">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="editAnimalMilkModalLabel<?= $entry['id'] ?>">Edit Animal Milk Record</h5>
          <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
      </div>

      <div class="modal-body">

        <div class="form-group">
            <label for="date<?= $entry['id'] ?>">Date *</label>
            <input type="date" class="form-control" name="date" value="<?= esc($entry['date']) ?>" required>
        </div>

        <div class="form-group">
            <label for="animal_id<?= $entry['id'] ?>">Tag ID (Female Animals Only) *</label>
            <select name="animal_id" class="form-control" required>
              <option value="">-- Select Tag ID --</option>
              <?php foreach ($female_animals as $animal): ?>
                  <option value="<?= $animal['id'] ?>" <?= $animal['id'] == $entry['animal_id'] ? 'selected' : '' ?>>
                      <?= esc($animal['tag_id']) ?>
                  </option>
              <?php endforeach; ?>
          </select>
      </div>

      <div class="form-group">
        <label for="first_calving_date<?= $entry['id'] ?>">First Calving Date</label>
        <input type="date" class="form-control" name="first_calving_date" value="<?= esc($entry['first_calving_date']) ?>">
    </div>

    <div class="form-group">
        <label for="last_calving_date<?= $entry['id'] ?>">Last Calving Date</label>
        <input type="date" class="form-control" name="last_calving_date" value="<?= esc($entry['last_calving_date']) ?>">
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


<?php foreach ($animal_milk as $entry): ?>
<!-- Delete Animal Milk Modal -->
<div class="modal fade" id="deleteAnimalMilkModal<?= $entry['id'] ?>" tabindex="-1" role="dialog" aria-labelledby="deleteAnimalMilkModalLabel<?= $entry['id'] ?>" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <form action="<?= base_url('animal-milking/animalMilk/delete/' . $entry['id']) ?>" method="post">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="deleteAnimalMilkModalLabel<?= $entry['id'] ?>">Delete Animal Milk Record</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>

    <div class="modal-body">
      Are you sure you want to delete the milk record for 
      <strong><?= esc($entry['tag_id'] ?? 'Unknown Tag') ?></strong> 
      dated <strong><?= esc($entry['date']) ?></strong>?
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
        <h1 class="h3 mb-0 text-gray-800">Animal Milk List</h1>
    </div>

    <form method="get" class="form-inline mb-4">
        <!-- Tenant Filter -->
        <?php if (isSuperAdmin()): ?>
            <label class="mr-2">Tenant:</label>
            <select name="tenant_id" class="form-control mr-3">
                <option value="">-- All Tenants --</option>
                <?php foreach ($tenants as $tenant): ?>
                    <option value="<?= esc($tenant['id']) ?>" 
                        <?= ($selectedTenantId == $tenant['id']) ? 'selected' : '' ?>>
                        <?= esc($tenant['name']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        <?php endif; ?>

        <label class="mr-2">Date:</label>
        <input type="date" name="date" class="form-control mr-3"
        value="<?= esc($_GET['date'] ?? date('Y-m-d')) ?>">

        <button type="submit" class="btn btn-primary">Filter</button>
    </form>    

    <div class="mb-3 text-right">
        <a href="<?= base_url('animal-milking/animalMilk/animalMilkExport')
        . (!empty($_SERVER['QUERY_STRING']) ? '?' . $_SERVER['QUERY_STRING'] : '')
        . (!empty($selectedTenantId) 
            ? (empty($_SERVER['QUERY_STRING']) ? '?' : '&') . 'tenant_id=' . $selectedTenantId 
            : '') ?>" 
            class="btn btn-success mb-3">
            <i class="fas fa-file-excel"></i> Download Excel
        </a>
    </div>

<!-- Add Animal Milk Button -->
<?php if (hasPermission('CanAddAnimalMilk')): ?>
    <div class="mb-3 text-right">
        <a href="#" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#addAnimalMilkModal">+ Add Animal Milk</a>
    </div>
<?php endif; ?>

<!-- Animal Milk Table -->
<div class="card shadow mb-4">
    <div class="card-body">
        <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
    <?php endif; ?>

    <div class="table-responsive">
        <?php
        $previousDate = null;
        $totalMilk1 = 0;
        $totalMilk2 = 0;
        $totalMilk3 = 0;
        ?>

        <table class="table table-bordered" id="animalMilkTable">
          <thead class="thead-dark">
            <tr>
              <th>ID</th>
              <th>Date</th>
              <th>Tag ID</th>
              <th>First Calving Date</th>
              <th>Last Calving Date</th>
              <th>Milk 1 (L)</th>
              <th>Milk 2 (L)</th>
              <th>Milk 3 (L)</th>
              <th>Tenant</th>
              <?php if (hasPermission('CanUpdateAnimalMilk') || hasPermission('CanDeleteAnimalMilk')): ?>
              <th>Actions</th>
          <?php endif; ?>
      </tr>
  </thead>
  <tbody>
    <?php if (!empty($animal_milk)): ?>
        <?php foreach ($animal_milk as $index => $record): ?>
            <?php
            $currentDate = $record['date'];

            if ($previousDate !== null && $currentDate !== $previousDate):
                ?>
                <tr style="font-weight: bold; background-color: #f1f1f1;">
                  <td colspan="5" class="text-center">Total Milk:</td>
                  <td><?= number_format($totalMilk1, 2) ?> L</td>
                  <td><?= number_format($totalMilk2, 2) ?> L</td>
                  <td><?= number_format($totalMilk3, 2) ?> L</td>
                  <td></td>
              </tr>
              <?php
              $totalMilk1 = 0;
              $totalMilk2 = 0;
              $totalMilk3 = 0;
              ?>
          <?php endif; ?>

          <tr>
            <td><?= esc($record['id']) ?></td>
            <td><?= esc($record['date']) ?></td>
            <td><?= esc($record['tag_id']) ?></td>
            <td><?= esc($record['first_calving_date']) ?></td>
            <td><?= esc($record['last_calving_date']) ?></td>
            <td><?= esc($record['milk_1']) ?></td>
            <td><?= esc($record['milk_2']) ?></td>
            <td><?= esc($record['milk_3']) ?></td>
            <td><?= esc($record['tenant_name'] ?? 'N/A') ?></td>
            <?php if (hasPermission('CanUpdateAnimalMilk') || hasPermission('CanDeleteAnimalMilk')): ?>
            <td>
                <?php if (hasPermission('CanUpdateAnimalMilk')): ?>
                    <a href="#" class="btn btn-sm btn-info" data-toggle="modal" data-target="#editAnimalMilkModal<?= $record['id'] ?>">Edit</a>
                <?php endif; ?>
                <?php if (hasPermission('CanDeleteAnimalMilk')): ?>
                    <a href="#" class="btn btn-sm btn-danger" data-toggle="modal" data-target="#deleteAnimalMilkModal<?= $record['id'] ?>">Delete</a>
                <?php endif; ?>
            </td>
        <?php endif; ?>
    </tr>

    <?php
    $totalMilk1 += (float)$record['milk_1'];
    $totalMilk2 += (float)$record['milk_2'];
    $totalMilk3 += (float)$record['milk_3'];

    $previousDate = $currentDate;
    ?>
<?php endforeach; ?>
<tfoot>
    <tr style="font-weight: bold; background-color: #f1f1f1;">
        <td colspan="5" class="text-center">Total Milk:</td>
        <td><?= number_format($totalMilk1, 2) ?> L</td>
        <td><?= number_format($totalMilk2, 2) ?> L</td>
        <td><?= number_format($totalMilk3, 2) ?> L</td>
        <td></td>
        <?php if (hasPermission('CanUpdateAnimalMilk') || hasPermission('CanDeleteAnimalMilk')): ?>
        <td></td>
    <?php endif; ?>
</tr>
</tfoot>
<?php endif; ?>
</tbody>
</table>
</div>
</div>
</div>
</div>

<!-- Add Animal Milk Modal -->
<div class="modal fade" id="addAnimalMilkModal" tabindex="-1" role="dialog" aria-labelledby="addAnimalMilkModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <form action="<?= base_url('animal-milking/animalMilk/add') ?>" method="post">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="addAnimalMilkModalLabel">Add Animal Milk Record</h5>
          <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
      </div>

      <div class="modal-body">
          <div class="form-group">
            <label for="date">Date *</label>
            <input type="date" class="form-control" name="date" id="date" required>
        </div>

        <div class="form-group">
            <label for="animal_id">Tag ID (Female Animals Only) *</label>
            <select name="animal_id" id="animal_id" class="form-control" required>
              <option value="">-- Select Tag ID --</option>
              <?php foreach ($female_animals as $animal): ?>
                  <option value="<?= esc($animal['id']) ?>"><?= esc($animal['tag_id']) ?></option>
              <?php endforeach; ?>
          </select>
      </div>

      <div class="form-group">
        <label for="first_calving_date">First Calving Date</label>
        <input type="date" class="form-control" name="first_calving_date" id="first_calving_date">
    </div>

    <div class="form-group">
        <label for="last_calving_date">Last Calving Date</label>
        <input type="date" class="form-control" name="last_calving_date" id="last_calving_date">
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
        $('#animalMilkTable').DataTable();
    });
</script>

</body>

</html>