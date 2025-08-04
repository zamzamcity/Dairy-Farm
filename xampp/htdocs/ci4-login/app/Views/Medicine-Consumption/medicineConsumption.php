<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Medicine_Consumption UI Page">
    <meta name="author" content="">

    <title>SB Admin 2 - Medicine_Consumption</title>

    <!-- Custom fonts for this template-->
    <link href="<?= base_url('assets/sb-admin-2/vendor/fontawesome-free/css/all.min.css') ?>" rel="stylesheet" type="text/css">
    <link
    href="https://fonts.googleapis.com/css?family=Nunito:200,300,400,600,700,800,900"
    rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="<?= base_url('assets/sb-admin-2/css/sb-admin-2.min.css') ?>" rel="stylesheet">

</head>


<?php foreach ($medicine_consumptions as $med): ?>
<!-- Edit Medicine Consumption Modal -->
<div class="modal fade" id="editMedicineConsumptionModal<?= $med['id'] ?>" tabindex="-1" role="dialog" aria-labelledby="editMedicineConsumptionModalLabel<?= $med['id'] ?>" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <form action="<?= base_url('medicine-consumption/medicineConsumption/edit/' . $med['id']) ?>" method="post">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Edit Medicine Consumption</h5>
          <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
        </div>
        <div class="modal-body">

          <div class="form-group">
            <label>Date</label>
            <input type="date" name="date" class="form-control" required value="<?= esc($med['date']) ?>">
          </div>

          <div class="form-group">
            <label>Medicine Product</label>
            <select name="product_id" class="form-control" required>
              <option value="">Select Product</option>
              <?php foreach ($medicine_products as $product): ?>
                <option value="<?= $product['id'] ?>" <?= $product['id'] == $med['product_id'] ? 'selected' : '' ?>>
                  <?= esc($product['product_name']) ?>
                </option>
              <?php endforeach; ?>
            </select>
          </div>

          <div class="form-group">
            <label>Quantity</label>
            <input type="number" step="0.01" name="quantity" class="form-control" value="<?= esc($med['quantity']) ?>" required>
          </div>

        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-primary">Update</button>
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
        </div>
      </div>
    </form>
  </div>
</div>
<?php endforeach; ?>

<?php foreach ($medicine_consumptions as $med): ?>
<!-- Delete Medicine Consumption Modal -->
<div class="modal fade" id="deleteMedicineConsumptionModal<?= $med['id'] ?>" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <form action="<?= base_url('medicine-consumption/medicineConsumption/delete/' . $med['id']) ?>" method="post">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Confirm Delete</h5>
          <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
        </div>
        <div class="modal-body">
          Are you sure you want to delete "<strong><?= esc($med['product_name']) ?></strong>"?
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-danger">Delete</button>
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
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
        <h1 class="h3 mb-0 text-gray-800">Medicine Consumption</h1>
    </div>

    <form method="get" class="form-inline mb-3">
        <label for="date" class="mr-2">Date:</label>
        <input type="date" name="date" id="date" class="form-control mr-2" value="<?= esc($selected_date) ?>">
        <button type="submit" class="btn btn-sm btn-primary">Filter</button>
    </form>


    <?php if (hasPermission('CanAddMedicineConsumption')): ?>
    <div class="mb-3 text-right">
        <a href="#" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#addMedicineConsumptionModal">+ Add Medicine Consumption</a>
    </div>
    <?php endif; ?>

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
                            <th>Product</th>
                            <th>Quantity</th>
                            <?php if (hasPermission('CanUpdateMedicineConsumption') || hasPermission('CanDeleteMedicineConsumption')): ?>
                            <th>Actions</th>
                            <?php endif; ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($medicine_consumptions as $consumption): ?>
                        <tr>
                            <td><?= esc($consumption['id']) ?></td>
                            <td><?= esc($consumption['date']) ?></td>
                            <td><?= esc($consumption['product_name']) ?></td>
                            <td><?= esc($consumption['quantity']) ?></td>
                            <?php if (hasPermission('CanUpdateMedicineConsumption') || hasPermission('CanDeleteMedicineConsumption')): ?>
                            <td>
                                <?php if (hasPermission('CanUpdateMedicineConsumption')): ?>
                                <a href="#" class="btn btn-sm btn-info" data-toggle="modal" data-target="#editMedicineConsumptionModal<?= $consumption['id'] ?>">Edit</a>
                                <?php endif; ?>
                                <?php if (hasPermission('CanDeleteMedicineConsumption')): ?>
                                <a href="#" class="btn btn-sm btn-danger" data-toggle="modal" data-target="#deleteMedicineConsumptionModal<?= $consumption['id'] ?>">Delete</a>
                                <?php endif; ?>
                            </td>
                            <?php endif; ?>
                        </tr>
                        <?php endforeach; ?>

                        <?php if (empty($medicine_consumptions)): ?>
                        <tr>
                            <td colspan="5" class="text-center">No medicine consumption records found.</td>
                        </tr>
                        <?php endif; ?>
                        <?php if (!empty($medicine_consumptions)): ?>
                        <tr>
                            <td colspan="3" class="text-center"><strong>Total Quantity</strong></td>
                            <td><strong><?= esc($total_quantity) ?></strong></td>
                            <?php if (hasPermission('CanUpdateMedicineConsumption') || hasPermission('CanDeleteMedicineConsumption')): ?>
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

<!-- Add Medicine Consumption Modal -->
<div class="modal fade" id="addMedicineConsumptionModal" tabindex="-1" role="dialog" aria-labelledby="addMedicineConsumptionModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <form action="<?= base_url('medicine-consumption/medicineConsumption/add') ?>" method="post">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Add Medicine Consumption</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          
          <div class="form-group">
            <label for="date">Date</label>
            <input type="date" name="date" class="form-control" required value="<?= date('Y-m-d') ?>">
          </div>

          <div class="form-group">
            <label for="product_id">Medicine Product</label>
            <select name="product_id" class="form-control" required>
              <option value="">Select Product</option>
              <?php foreach ($medicine_products as $product): ?>
                <option value="<?= esc($product['id']) ?>"><?= esc($product['product_name']) ?></option>
              <?php endforeach; ?>
            </select>
          </div>

          <div class="form-group">
            <label for="quantity">Quantity</label>
            <input type="number" step="0.01" name="quantity" class="form-control" required>
          </div>

        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-primary">Add</button>
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
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