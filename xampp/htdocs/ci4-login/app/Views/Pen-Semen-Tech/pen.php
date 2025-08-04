<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Pen_List UI Page">
    <meta name="author" content="">

    <title>SB Admin 2 - Pen_List</title>

    <!-- Custom fonts for this template-->
    <link href="<?= base_url('assets/sb-admin-2/vendor/fontawesome-free/css/all.min.css') ?>" rel="stylesheet" type="text/css">
    <link
    href="https://fonts.googleapis.com/css?family=Nunito:200,300,400,600,700,800,900"
    rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="<?= base_url('assets/sb-admin-2/css/sb-admin-2.min.css') ?>" rel="stylesheet">

</head>

<?php foreach ($pens as $pen): ?>
    <?php $animals = $penAnimals[$pen['id']] ?? []; ?>
    <?php if (!empty($animals)): ?>
        <div class="modal fade" id="animalModal<?= $pen['id'] ?>" tabindex="-1" role="dialog" aria-labelledby="animalModalLabel<?= $pen['id'] ?>" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Animals in <?= esc($pen['name']) ?></h5>
                        <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                    </div>
                    <div class="card shadow mb-4">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Name</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($animals as $animal): ?>
                                            <tr>
                                                <td><?= $animal['id'] ?></td>
                                                <td><?= esc($animal['name']) ?></td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>
<?php endforeach; ?>

<?php foreach ($pens as $pen): ?>
<!-- Edit Pen Modal -->
<div class="modal fade" id="editPenModal<?= $pen['id'] ?>" tabindex="-1" role="dialog" aria-labelledby="editPenModalLabel<?= $pen['id'] ?>" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <form action="<?= base_url('pen-semen-tech/pen/edit/' . $pen['id']) ?>" method="post">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="editPenModalLabel<?= $pen['id'] ?>">Edit Pen</h5>
          <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
      </div>

      <div class="modal-body">
          <div class="form-group">
            <label for="name<?= $pen['id'] ?>">Pen Name</label>
            <input type="text" class="form-control" name="name" value="<?= esc($pen['name']) ?>" required>
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


<?php foreach ($pens as $pen): ?>
<!-- Delete Pen Modal -->
<div class="modal fade" id="deletePenModal<?= $pen['id'] ?>" tabindex="-1" role="dialog" aria-labelledby="deletePenModalLabel<?= $pen['id'] ?>" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <form action="<?= base_url('pen-semen-tech/pen/delete/' . $pen['id']) ?>" method="post">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="deletePenModalLabel<?= $pen['id'] ?>">Delete Pen</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>

    <div class="modal-body">
      Are you sure you want to delete the pen <strong>"<?= esc($pen['name']) ?>"</strong>?
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
        <h1 class="h3 mb-0 text-gray-800">Pen List</h1>
    </div>

    <!-- Add Pen Button -->
    <?php if (hasPermission('CanAddPen')): ?>
        <div class="mb-3 text-right">
            <a href="#" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#addPenModal">+ Add Pen</a>
        </div>
    <?php endif; ?>

    <!-- Pen Table -->
    <div class="card shadow mb-4">
        <div class="card-body">
            <?php if (session()->getFlashdata('success')): ?>
            <div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
        <?php endif; ?>

        <div class="table-responsive">
            <table class="table table-bordered" id="penTable">
                <thead class="thead-dark">
                    <tr>
                        <th>ID</th>
                        <th>Pen Name</th>
                        <th>Animals</th>
                        <?php if (hasPermission('CanUpdatePen') || hasPermission('CanDeletePen')): ?>
                        <th>Actions</th>
                    <?php endif; ?>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($pens as $pen): ?>
                    <tr>
                        <td><?= $pen['id'] ?></td>
                        <td><?= esc($pen['name']) ?></td>
                        <td>
                            <?php 
                            $animals = $penAnimals[$pen['id']] ?? [];
                            $count = count($animals);
                            ?>
                            <?= $count ?>
                            <?php if ($count > 0): ?>
                                <button class="btn btn-sm btn-info ml-2" data-toggle="modal" data-target="#animalModal<?= $pen['id'] ?>">View More</button>
                            <?php endif; ?>
                        </td>
                        <?php if (hasPermission('CanUpdatePen') || hasPermission('CanDeletePen')): ?>
                        <td>
                            <?php if (hasPermission('CanUpdatePen')): ?>
                                <a href="#" class="btn btn-sm btn-info" data-toggle="modal" data-target="#editPenModal<?= $pen['id'] ?>">Edit</a>
                            <?php endif; ?>
                            <?php if (hasPermission('CanDeletePen')): ?>
                                <a href="#" class="btn btn-sm btn-danger" data-toggle="modal" data-target="#deletePenModal<?= $pen['id'] ?>">Delete</a>
                            <?php endif; ?>
                        </td>
                    <?php endif; ?>
                </tr>
            <?php endforeach; ?>

            <?php if (empty($pens)): ?>
                <tr><td colspan="8" class="text-center">No pen records found.</td></tr>
            <?php endif ?>
        </tbody>
    </table>
</div>
</div>
</div>
</div>

<!-- Add Pen Modal -->
<div class="modal fade" id="addPenModal" tabindex="-1" role="dialog" aria-labelledby="addPenModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <form action="<?= base_url('pen-semen-tech/pen/add') ?>" method="post">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="addPenModalLabel">Add Pen</h5>
          <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
      </div>

      <div class="modal-body">
          <div class="form-group">
            <label for="penName">Pen Name</label>
            <input type="text" class="form-control" name="name" id="penName" required>
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
        $('#penTable').DataTable();
    });
</script>

</body>

</html>