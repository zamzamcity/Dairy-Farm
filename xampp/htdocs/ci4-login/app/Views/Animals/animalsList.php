<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Animals_List UI Page">
    <meta name="author" content="">

    <title>SB Admin 2 - Animals_List</title>

    <!-- Custom fonts for this template-->
    <link href="<?= base_url('assets/sb-admin-2/vendor/fontawesome-free/css/all.min.css') ?>" rel="stylesheet" type="text/css">
    <link
    href="https://fonts.googleapis.com/css?family=Nunito:200,300,400,600,700,800,900"
    rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="<?= base_url('assets/sb-admin-2/css/sb-admin-2.min.css') ?>" rel="stylesheet">

</head>

<!-- Edit Animal Modal -->
<?php foreach ($animals as $animal): ?>
    <div class="modal fade" id="editAnimalModal<?= $animal['id'] ?>" tabindex="-1" role="dialog" aria-labelledby="editAnimalModalLabel<?= $animal['id'] ?>" aria-hidden="true">
      <div class="modal-dialog modal-lg" role="document">
        <form action="<?= base_url('animals/animalsList/edit/' . $animal['id']) ?>" method="post" enctype="multipart/form-data">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title">Edit Animal</h5>
              <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
          </div>
          <div class="modal-body row">

              <div class="form-group col-md-6">
                <label>Pen</label>
                <select name="pen_id" class="form-control" required>
                  <?php foreach ($pens as $pen): ?>
                    <option value="<?= $pen['id'] ?>" <?= $animal['pen_id'] == $pen['id'] ? 'selected' : '' ?>>
                      <?= esc($pen['name']) ?>
                  </option>
              <?php endforeach; ?>
          </select>
      </div>

      <div class="form-group col-md-6">
        <label>Tag ID</label>
        <input type="text" name="tag_id" class="form-control" value="<?= esc($animal['tag_id']) ?>" required>
    </div>

    <div class="form-group col-md-6">
        <label>Electronic ID</label>
        <input type="text" name="electronic_id" class="form-control" value="<?= esc($animal['electronic_id']) ?>">
    </div>

    <div class="form-group col-md-6">
        <label>Name</label>
        <input type="text" name="name" class="form-control" value="<?= esc($animal['name']) ?>">
    </div>

    <div class="form-group col-md-6">
        <label>Animal Type</label>
        <select name="animal_type_id" id="editAnimalType<?= $animal['id'] ?>" class="form-control animal-type-select" data-animal-id="<?= $animal['id'] ?>" required>
            <option value="">Select Type</option>
            <?php foreach ($animalTypes as $type): ?>
                <option value="<?= $type['id'] ?>" <?= $animal['animal_type_id'] == $type['id'] ? 'selected' : '' ?>>
                    <?= esc($type['name']) ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>

    <div class="form-group col-md-6">
        <label>Breed</label>
        <select name="breed_id" id="editBreed<?= $animal['id'] ?>" class="form-control breed-select" required>
            <option value="">Select Breed</option>
            <?php foreach ($breeds as $breed): ?>
                <?php if ($breed['animal_type_id'] == $animal['animal_type_id']): ?>
                    <option value="<?= $breed['id'] ?>" <?= $animal['breed_id'] == $breed['id'] ? 'selected' : '' ?>>
                        <?= esc($breed['name']) ?>
                    </option>
                <?php endif; ?>
            <?php endforeach; ?>
        </select>
    </div>


    <div class="form-group col-md-6">
        <label>Company</label>
        <select name="company_id" class="form-control">
          <?php foreach ($companies as $company): ?>
            <option value="<?= $company['id'] ?>" <?= $animal['company_id'] == $company['id'] ? 'selected' : '' ?>>
              <?= esc($company['name']) ?>
          </option>
      <?php endforeach; ?>
  </select>
</div>

<div class="form-group col-md-6">
    <label>Country</label>
    <select name="country_id" class="form-control">
      <?php foreach ($countries as $country): ?>
        <option value="<?= $country['id'] ?>" <?= $animal['country_id'] == $country['id'] ? 'selected' : '' ?>>
          <?= esc($country['name']) ?>
      </option>
  <?php endforeach; ?>
</select>
</div>

<?php $sex = isset($animal['sex']) ? trim($animal['sex']) : ''; ?>
<div class="form-group col-md-6">
    <label>Sex</label><br>
    <label class="mr-2">
        <input type="radio" name="sex" value="male" <?= $sex === 'male' ? 'checked' : '' ?>> Male
    </label>
    <label>
        <input type="radio" name="sex" value="female" <?= $sex === 'female' ? 'checked' : '' ?>> Female
    </label>
</div>

<div class="form-group col-md-6">
    <label>Status</label>
    <select name="status" class="form-control">
      <?php
      $statuses = ['Non-Pregnant Heifer', 'Pregnant Heifer', 'Cow', 'Pregnant Cow'];
      foreach ($statuses as $status):
          ?>
          <option value="<?= $status ?>" <?= $animal['status'] === $status ? 'selected' : '' ?>><?= $status ?></option>
      <?php endforeach; ?>
  </select>
</div>

<div class="form-group col-md-6">
    <label>Insertion Date</label>
    <input type="date" name="insertion_date" class="form-control" value="<?= esc($animal['insertion_date']) ?>">
</div>

<div class="form-group col-md-6">
    <label>Birth Date</label>
    <input type="date" name="birth_date" class="form-control" value="<?= esc($animal['birth_date']) ?>">
</div>

<div class="form-group col-md-6">
    <label>Price</label>
    <input type="number" name="price" class="form-control" step="0.01" value="<?= esc($animal['price']) ?>">
</div>

<div class="form-group col-md-6">
    <label>Pedigree Info</label><br>
    <label class="mr-2"><input type="radio" name="pedigree_info" value="1" <?= $animal['pedigree_info'] ? 'checked' : '' ?>> Yes</label>
    <label><input type="radio" name="pedigree_info" value="0" <?= !$animal['pedigree_info'] ? 'checked' : '' ?>> No</label>
</div>

<div class="form-group col-md-12">
    <label>Picture (optional)</label>
    <input type="file" name="picture" class="form-control-file">
    <?php if (!empty($animal['picture'])): ?>
      <div class="mt-2">
        <img src="<?= base_url('uploads/animals/' . $animal['picture']) ?>" width="80">
        <div class="form-check mt-2">
          <input type="checkbox" name="remove_picture" value="1" class="form-check-input" id="removePic<?= $animal['id'] ?>">
          <label class="form-check-label" for="removePic<?= $animal['id'] ?>">Remove Picture</label>
      </div>
  </div>
<?php endif; ?>
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

<!-- Delete Animal Modal -->
<?php foreach ($animals as $animal): ?>
    <div class="modal fade" id="deleteAnimalModal<?= $animal['id'] ?>" tabindex="-1" role="dialog" aria-labelledby="deleteAnimalModalLabel<?= $animal['id'] ?>" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <form action="<?= base_url('animals/animalsList/delete/' . $animal['id']) ?>" method="post">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title">Confirm Delete</h5>
              <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
          </div>
          <div class="modal-body">
              Are you sure you want to delete <strong><?= esc($animal['name']) ?></strong>?
          </div>
          <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
              <button type="submit" class="btn btn-danger">Yes, Delete</button>
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
        <h1 class="h3 mb-0 text-gray-800">Animals List</h1>
    </div>

    <!-- Add Animal Button -->
    <?php if (hasPermission('CanAddAnimal')): ?>
        <div class="mb-3 text-right">
            <a href="#" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#addAnimalModal">+ Add Animal</a>
        </div>
    <?php endif; ?>

    <!-- Animal Table -->
    <div class="card shadow mb-4">
        <div class="card-body">
            <?php if (session()->getFlashdata('success')): ?>
            <div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
        <?php endif; ?>

        <div class="table-responsive">
            <table class="table table-bordered" id="animalTable">
                <thead class="thead-dark">
                    <tr>
                        <th>ID</th>
                        <th>Picture</th>
                        <th>Name</th>
                        <th>Tag ID</th>
                        <th>Sex</th>
                        <th>Animal Type</th>
                        <th>Breed</th>
                        <th>Birth Date</th>
                        <?php if (hasPermission('CanUpdateAnimal') || hasPermission('CanDeleteAnimal')): ?>
                        <th>Actions</th>
                    <?php endif; ?>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($animals as $animal): ?>
                    <tr>
                        <td><?= $animal['id'] ?></td>
                        <td>
                            <?php if (!empty($animal['picture'])): ?>
                                <img src="<?= base_url('uploads/animals/' . $animal['picture']) ?>" alt="Animal" width="50" height="50" style="object-fit:cover;">
                            <?php else: ?>
                                <i class="fas fa-image text-muted" style="font-size: 24px;"></i>
                            <?php endif; ?>
                        </td>
                        <td><?= esc($animal['name']) ?></td>
                        <td><?= esc($animal['tag_id']) ?></td>
                        <td><?= ucfirst($animal['sex']) ?></td>
                        <td><?= esc($animal['animal_type']) ?></td>
                        <td><?= esc($animal['breed']) ?></td>
                        <td><?= esc($animal['birth_date']) ?></td>
                        <?php if (hasPermission('CanUpdateAnimal') || hasPermission('CanDeleteAnimal')): ?>
                        <td>
                            <?php if (hasPermission('CanUpdateAnimal')): ?>
                                <a href="#" class="btn btn-sm btn-info" data-toggle="modal" data-target="#editAnimalModal<?= $animal['id'] ?>">Edit</a>
                            <?php endif; ?>
                            <?php if (hasPermission('CanDeleteAnimal')): ?>
                                <a href="#" class="btn btn-sm btn-danger" data-toggle="modal" data-target="#deleteAnimalModal<?= $animal['id'] ?>">Delete</a>
                            <?php endif; ?>
                        </td>
                    <?php endif; ?>
                </tr>
            <?php endforeach; ?>

            <?php if (empty($animals)): ?>
                <tr><td colspan="8" class="text-center">No animals found.</td></tr>
            <?php endif ?>
        </tbody>
    </table>
</div>
</div>
</div>
</div>

<!-- Add Animal Modal -->
<div class="modal fade" id="addAnimalModal" tabindex="-1" role="dialog" aria-labelledby="addAnimalModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <form action="<?= base_url('animals/animalsList/add') ?>" method="post" enctype="multipart/form-data">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Add Animal</h5>
          <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
      </div>

      <div class="modal-body row">
          <!-- Pen -->
          <div class="form-group col-md-6">
            <label>Pen</label>
            <select name="pen_id" class="form-control" required>
              <option value="">Select Pen</option>
              <?php foreach ($pens as $pen): ?>
                <option value="<?= $pen['id'] ?>"><?= esc($pen['name']) ?></option>
            <?php endforeach; ?>
        </select>
    </div>

    <!-- Tag ID -->
    <div class="form-group col-md-6">
        <label>Tag ID</label>
        <input type="text" name="tag_id" class="form-control" required>
    </div>

    <!-- Electronic ID -->
    <div class="form-group col-md-6">
        <label>Electronic ID</label>
        <input type="text" name="electronic_id" class="form-control">
    </div>

    <!-- Name -->
    <div class="form-group col-md-6">
        <label>Name</label>
        <input type="text" name="name" class="form-control">
    </div>

<!-- Animal Type -->
<div class="form-group col-md-6">
    <label>Animal Type</label>
    <select name="animal_type_id" id="animalTypeSelect" class="form-control" required>
      <option value="">Select Type</option>
      <?php foreach ($animalTypes as $type): ?>
        <option value="<?= $type['id'] ?>"><?= esc($type['name']) ?></option>
    <?php endforeach; ?>
</select>
</div>

<!-- Breed -->
<div class="form-group col-md-6">
    <label>Breed</label>
    <select name="breed_id" id="breedSelect" class="form-control">
      <option value="">Select Breed</option>
      <!-- Options will be loaded via AJAX -->
  </select>
</div>

<!-- Company -->
<div class="form-group col-md-6">
    <label>Company</label>
    <select name="company_id" class="form-control">
      <option value="">Select Company</option>
      <?php foreach ($companies as $company): ?>
        <option value="<?= $company['id'] ?>"><?= esc($company['name']) ?></option>
    <?php endforeach; ?>
</select>
</div>

<!-- Country -->
<div class="form-group col-md-6">
    <label>Country</label>
    <select name="country_id" class="form-control">
      <option value="">Select Country</option>
      <?php foreach ($countries as $country): ?>
        <option value="<?= $country['id'] ?>"><?= esc($country['name']) ?></option>
    <?php endforeach; ?>
</select>
</div>

<!-- Sex -->
<div class="form-group col-md-6">
    <label>Sex</label><br>
    <label class="mr-2"><input type="radio" name="sex" value="Male" required> Male</label>
    <label><input type="radio" name="sex" value="Female" required> Female</label>
</div>

<!-- Status -->
<div class="form-group col-md-6">
    <label>Status</label>
    <select name="status" class="form-control" required>
      <option value="">Select Status</option>
      <option value="Non-Pregnant Heifer">Non-Pregnant Heifer</option>
      <option value="Pregnant Heifer">Pregnant Heifer</option>
      <option value="Cow">Cow</option>
      <option value="Pregnant Cow">Pregnant Cow</option>
  </select>
</div>

<!-- Insertion Date -->
<div class="form-group col-md-6">
    <label>Insertion Date</label>
    <input type="date" name="insertion_date" class="form-control" required>
</div>

<!-- Birth Date -->
<div class="form-group col-md-6">
    <label>Birth Date</label>
    <input type="date" name="birth_date" class="form-control">
</div>

<!-- Price -->
<div class="form-group col-md-6">
    <label>Price</label>
    <input type="number" name="price" class="form-control" step="0.01">
</div>

<!-- Pedigree Info -->
<div class="form-group col-md-6">
    <label>Pedigree Info</label><br>
    <label class="mr-2"><input type="radio" name="pedigree_info" value="1"> Yes</label>
    <label><input type="radio" name="pedigree_info" value="0" checked> No</label>
</div>

<!-- Picture -->
<div class="form-group col-md-12">
    <label>Picture</label>
    <input type="file" name="picture" class="form-control-file">
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

<script>
  $(document).ready(function () {
    $('.animal-type-select').change(function () {
      const animalId = $(this).data('animal-id');
      const typeId = $(this).val();
      const breedSelect = $('#editBreed' + animalId);

      breedSelect.html('<option value="">Loading...</option>');

      $.ajax({
        url: '<?= base_url('animals/get-breeds') ?>/' + typeId,
        method: 'GET',
        success: function (response) {
          let options = '<option value="">Select Breed</option>';
          response.forEach(function (breed) {
            options += `<option value="${breed.id}">${breed.name}</option>`;
        });
          breedSelect.html(options);
      },
      error: function () {
          breedSelect.html('<option value="">Failed to load</option>');
      }
  });
  });
});
</script>

<script>
    $(document).ready(function() {
        $('#animalTypeSelect').on('change', function() {
            var animalTypeId = $(this).val();

            if (animalTypeId) {
                $.ajax({
                    url: '<?= base_url('animals/get-breeds') ?>/' + animalTypeId,
                    type: 'GET',
                    dataType: 'json',
                    success: function(data) {
                        var $breedSelect = $('#breedSelect');
                        $breedSelect.empty().append('<option value="">Select Breed</option>');

                        $.each(data, function(index, breed) {
                            $breedSelect.append('<option value="' + breed.id + '">' + breed.name + '</option>');
                        });
                    }
                });
            } else {
                $('#breedSelect').html('<option value="">Select Breed</option>');
            }
        });
    });
</script>

<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script>
    $(document).ready(function () {
        $('#animalTable').DataTable();
    });
</script>

</body>

</html>