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
        <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

            <!-- Sidebar - Brand -->
            <a class="sidebar-brand d-flex align-items-center justify-content-center" href="index.html">
                <div class="sidebar-brand-icon rotate-n-15">
                    <i class="fas fa-laugh-wink"></i>
                </div>
                <div class="sidebar-brand-text mx-3">Zam Zam Developers</div>
            </a>

            <!-- Divider -->
            <hr class="sidebar-divider my-0">

            <!-- Nav Item - Dashboard -->
            <li class="nav-item">
                <a class="nav-link" href="<?= base_url('login/home') ?>">
                    <i class="fas fa-fw fa-tachometer-alt"></i>
                    <span>Dashboard</span>
                </a>
            </li>

            <!-- Divider -->
            <hr class="sidebar-divider">

            <!-- Heading -->
            <div class="sidebar-heading">
                Admin Panel
            </div>

            <!-- Nav Item - Manage Collapse Menu -->
            <?php if (hasPermission('CanViewManage')): ?>
                <li class="nav-item">
                    <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseTwo"
                    aria-expanded="true" aria-controls="collapseTwo">
                    <i class="fas fa-fw fa-cog"></i>
                    <span>Manage</span>
                </a>
                <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <h6 class="collapse-header">Manage Group:</h6>
                        <a class="collapse-item" href="<?= base_url('manage/employees') ?>">Employees</a>
                        <a class="collapse-item" href="<?= base_url('manage/permissions') ?>">Permissions</a>
                        <a class="collapse-item" href="<?= base_url('manage/permission_groups') ?>">Permission Groups</a>
                    </div>
                </div>
            </li>
        <?php endif; ?>


<!-- Divider -->
<hr class="sidebar-divider d-none d-md-block">

<!-- Heading -->
<div class="sidebar-heading">
    Breeding & Health Management System
</div>

<!-- Nav Item - Animals Collapse Menu -->
<?php if (hasPermission('CanViewAnimals')): ?>
    <li class="nav-item active">
        <a class="nav-link" href="#" data-toggle="collapse" data-target="#collapseAnimals"
        aria-expanded="true" aria-controls="collapseAnimals">
        <i class="fas fa-fw fa-paw"></i>
        <span>Animals</span>
    </a>
    <div id="collapseAnimals" class="collapse show" aria-labelledby="headingAnimals" data-parent="#accordionSidebar">
        <div class="bg-white py-2 collapse-inner rounded">
            <h6 class="collapse-header">Animal Management:</h6>
            <a class="collapse-item active" href="<?= base_url('animals/animalsList') ?>">Manage Animals</a>
        </div>
    </div>
</li>
<?php endif; ?>

<!-- Nav Item - Pen/Semen/Technician Collapse Menu -->
<?php if (hasPermission('CanViewPen')||hasPermission('CanViewSemen')||hasPermission('CanViewTechnician')): ?>
<li class="nav-item">
    <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapsePen"
    aria-expanded="true" aria-controls="collapsePen">
    <i class="fas fa-fw fa-warehouse"></i>
    <span>Pen/Semen/Tech</span>
</a>
<div id="collapsePen" class="collapse" aria-labelledby="headingPen" data-parent="#accordionSidebar">
    <div class="bg-white py-2 collapse-inner rounded">
        <h6 class="collapse-header">Management:</h6>
        <?php if (hasPermission('CanViewPen')): ?>
            <a class="collapse-item" href="<?= base_url('pen-semen-tech/pen') ?>">Pen</a>
        <?php endif; ?>
        <?php if (hasPermission('CanViewSemen')): ?>
            <a class="collapse-item" href="<?= base_url('pen-semen-tech/semen') ?>">Semen</a>
        <?php endif; ?>
        <?php if (hasPermission('CanViewTechnician')): ?>
            <a class="collapse-item" href="<?= base_url('pen-semen-tech/technician') ?>">Technician</a>
        <?php endif; ?>
    </div>
</div>
</li>
<?php endif; ?>

<!-- Nav Item - Schedule Events Collapse Menu -->
<?php if (hasPermission('CanViewSchedule')||hasPermission('CanViewVaccinationSchedule')||hasPermission('CanViewDewormingSchedule')||hasPermission('CanViewCalvesSchedule')||hasPermission('CanViewAfterCalvingProtocols')): ?>
<li class="nav-item">
    <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseEvents"
    aria-expanded="true" aria-controls="collapseEvents">
    <i class="fas fa-fw fa-bell"></i>
    <span>Schedule Events</span>
</a>
<div id="collapseEvents" class="collapse" aria-labelledby="headingEvents" data-parent="#accordionSidebar">
    <div class="bg-white py-2 collapse-inner rounded">
        <h6 class="collapse-header">Schedule Management:</h6>
        <?php if (hasPermission('CanViewSchedule')): ?>
            <a class="collapse-item" href="<?= base_url('schedule-events/schedule') ?>">View All Schedules</a>
        <?php endif; ?>
        <?php if (hasPermission('CanViewVaccinationSchedule')): ?>
            <a class="collapse-item" href="<?= base_url('schedule-events/vaccinationSchedule') ?>">Vaccination Schedule</a>
        <?php endif; ?>
        <?php if (hasPermission('CanViewDewormingSchedule')): ?>
            <a class="collapse-item" href="<?= base_url('schedule-events/dewormingSchedule') ?>">Deworming Schedule</a>
        <?php endif; ?>
    </div>
</div>
</li>
<?php endif; ?>

<?php if (hasPermission('CanViewAnimalMilking')||(hasPermission('CanViewDailyMilking'))): ?>
    <!-- Divider -->
    <hr class="sidebar-divider d-none d-md-block">
<!-- Heading -->
<div class="sidebar-heading">
    Milking & Production Management
</div>
<?php endif; ?>

<!-- Nav Item - Animal Milking Collapse Menu -->
<?php if (hasPermission('CanViewAnimalMilking')): ?>
    <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseAnimalMilking"
        aria-expanded="true" aria-controls="collapseAnimalMilking">
        <i class="fas fa-fw fa-glass-whiskey"></i>
        <span>Animal Milking</span>
    </a>
    <div id="collapseAnimalMilking" class="collapse" aria-labelledby="headingAnimalMilking" data-parent="#accordionSidebar">
        <div class="bg-white py-2 collapse-inner rounded">
            <h6 class="collapse-header">Animal Milk Management:</h6>
            <a class="collapse-item" href="<?= base_url('animal-milking/animalMilk') ?>">Manage Animal Milk</a>
        </div>
    </div>
</li>
<?php endif; ?>

<!-- Nav Item - Daily Milking -->
<?php if (hasPermission('CanViewDailyMilking')): ?>
    <li class="nav-item">
        <a class="nav-link" href="<?= base_url('dailyMilk') ?>">
            <i class="fas fa-fw fa-tachometer-alt"></i>
            <span>Daily Milking</span></a>
        </li>
    <?php endif; ?>

<!-- Divider -->
<hr class="sidebar-divider d-none d-md-block">

<!-- Sidebar Toggler (Sidebar) -->
<div class="text-center d-none d-md-inline">
    <button class="rounded-circle border-0" id="sidebarToggle"></button>
</div>

</ul>
<!-- End of Sidebar -->

<!-- Content Wrapper -->
<div id="content-wrapper" class="d-flex flex-column">

    <!-- Main Content -->
    <div id="content">

        <!-- Topbar -->
        <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">

            <!-- Sidebar Toggle (Topbar) -->
            <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
                <i class="fa fa-bars"></i>
            </button>

            <!-- Topbar Search -->
            <form
            class="d-none d-sm-inline-block form-inline mr-auto ml-md-3 my-2 my-md-0 mw-100 navbar-search">
            <div class="input-group">
                <input type="text" class="form-control bg-light border-0 small" placeholder="Search for..."
                aria-label="Search" aria-describedby="basic-addon2">
                <div class="input-group-append">
                    <button class="btn btn-primary" type="button">
                        <i class="fas fa-search fa-sm"></i>
                    </button>
                </div>
            </div>
        </form>

        <!-- Topbar Navbar -->
        <ul class="navbar-nav ml-auto">

            <!-- Nav Item - Search Dropdown (Visible Only XS) -->
            <li class="nav-item dropdown no-arrow d-sm-none">
                <a class="nav-link dropdown-toggle" href="#" id="searchDropdown" role="button"
                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <i class="fas fa-search fa-fw"></i>
            </a>
            <!-- Dropdown - Messages -->
            <div class="dropdown-menu dropdown-menu-right p-3 shadow animated--grow-in"
            aria-labelledby="searchDropdown">
            <form class="form-inline mr-auto w-100 navbar-search">
                <div class="input-group">
                    <input type="text" class="form-control bg-light border-0 small"
                    placeholder="Search for..." aria-label="Search"
                    aria-describedby="basic-addon2">
                    <div class="input-group-append">
                        <button class="btn btn-primary" type="button">
                            <i class="fas fa-search fa-sm"></i>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </li>

    <!-- Nav Item - Alerts -->
    <li class="nav-item dropdown no-arrow mx-1">
        <a class="nav-link dropdown-toggle" href="#" id="alertsDropdown" role="button"
        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        <i class="fas fa-bell fa-fw"></i>
        <!-- Counter - Alerts -->
        <span class="badge badge-danger badge-counter">3+</span>
    </a>
    <!-- Dropdown - Alerts -->
    <div class="dropdown-list dropdown-menu dropdown-menu-right shadow animated--grow-in"
    aria-labelledby="alertsDropdown">
    <h6 class="dropdown-header">
        Alerts Center
    </h6>
    <a class="dropdown-item d-flex align-items-center" href="#">
        <div class="mr-3">
            <div class="icon-circle bg-primary">
                <i class="fas fa-file-alt text-white"></i>
            </div>
        </div>
        <div>
            <div class="small text-gray-500">December 12, 2019</div>
            <span class="font-weight-bold">A new monthly report is ready to download!</span>
        </div>
    </a>
    <a class="dropdown-item d-flex align-items-center" href="#">
        <div class="mr-3">
            <div class="icon-circle bg-success">
                <i class="fas fa-donate text-white"></i>
            </div>
        </div>
        <div>
            <div class="small text-gray-500">December 7, 2019</div>
            $290.29 has been deposited into your account!
        </div>
    </a>
    <a class="dropdown-item d-flex align-items-center" href="#">
        <div class="mr-3">
            <div class="icon-circle bg-warning">
                <i class="fas fa-exclamation-triangle text-white"></i>
            </div>
        </div>
        <div>
            <div class="small text-gray-500">December 2, 2019</div>
            Spending Alert: We've noticed unusually high spending for your account.
        </div>
    </a>
    <a class="dropdown-item text-center small text-gray-500" href="#">Show All Alerts</a>
</div>
</li>

<!-- Nav Item - Messages -->
<li class="nav-item dropdown no-arrow mx-1">
    <a class="nav-link dropdown-toggle" href="#" id="messagesDropdown" role="button"
    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
    <i class="fas fa-envelope fa-fw"></i>
    <!-- Counter - Messages -->
    <span class="badge badge-danger badge-counter">7</span>
</a>
<!-- Dropdown - Messages -->
<div class="dropdown-list dropdown-menu dropdown-menu-right shadow animated--grow-in"
aria-labelledby="messagesDropdown">
<h6 class="dropdown-header">
    Message Center
</h6>
<a class="dropdown-item d-flex align-items-center" href="#">
    <div class="dropdown-list-image mr-3">
        <img class="rounded-circle" src="<?= base_url('assets/sb-admin-2/img/undraw_profile_1.svg') ?>" alt="...">
        <div class="status-indicator bg-success"></div>
    </div>
    <div class="font-weight-bold">
        <div class="text-truncate">Hi there! I am wondering if you can help me with a
        problem I've been having.</div>
        <div class="small text-gray-500">Emily Fowler · 58m</div>
    </div>
</a>
<a class="dropdown-item d-flex align-items-center" href="#">
    <div class="dropdown-list-image mr-3">
        <img class="rounded-circle" src="<?= base_url('assets/sb-admin-2/img/undraw_profile_2.svg') ?>" alt="...">
        <div class="status-indicator"></div>
    </div>
    <div>
        <div class="text-truncate">I have the photos that you ordered last month, how
        would you like them sent to you?</div>
        <div class="small text-gray-500">Jae Chun · 1d</div>
    </div>
</a>
<a class="dropdown-item d-flex align-items-center" href="#">
    <div class="dropdown-list-image mr-3">
        <img class="rounded-circle" src="<?= base_url('assets/sb-admin-2/img/undraw_profile_3.svg') ?>" alt="...">
        <div class="status-indicator bg-warning"></div>
    </div>
    <div>
        <div class="text-truncate">Last month's report looks great, I am very happy with
        the progress so far, keep up the good work!</div>
        <div class="small text-gray-500">Morgan Alvarez · 2d</div>
    </div>
</a>
<a class="dropdown-item d-flex align-items-center" href="#">
    <div class="dropdown-list-image mr-3">
        <img class="rounded-circle" src="<?= base_url('assets/sb-admin-2/img/undraw_profile.svg') ?>"
        alt="...">
        <div class="status-indicator bg-success"></div>
    </div>
    <div>
        <div class="text-truncate">Am I a good boy? The reason I ask is because someone
        told me that people say this to all dogs, even if they aren't good...</div>
        <div class="small text-gray-500">Chicken the Dog · 2w</div>
    </div>
</a>
<a class="dropdown-item text-center small text-gray-500" href="#">Read More Messages</a>
</div>
</li>

<div class="topbar-divider d-none d-sm-block"></div>

<!-- Nav Item - User Information -->
<li class="nav-item dropdown no-arrow">
    <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button"
    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
    <span class="mr-2 d-none d-lg-inline text-gray-600 small">
        <?= session()->get('firstname') && session()->get('lastname')
        ? session()->get('firstname') . ' ' . session()->get('lastname')
        : 'Guest' ?>
    </span>
    <img class="img-profile rounded-circle" src="<?= base_url('assets/sb-admin-2/img/undraw_profile.svg') ?>" alt="...">
</a>
<!-- Dropdown - User Information -->
<div class="dropdown-menu dropdown-menu-right shadow animated--grow-in"
aria-labelledby="userDropdown">
<a class="dropdown-item" href="#">
    <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>
    Profile
</a>
<a class="dropdown-item" href="#">
    <i class="fas fa-cogs fa-sm fa-fw mr-2 text-gray-400"></i>
    Settings
</a>
<a class="dropdown-item" href="#">
    <i class="fas fa-list fa-sm fa-fw mr-2 text-gray-400"></i>
    Activity Log
</a>
<div class="dropdown-divider"></div>
<a class="dropdown-item" href="#" data-toggle="modal" data-target="#logoutModal">
    <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
    Logout
</a>
</div>
</li>

</ul>

</nav>
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
            <table class="table table-bordered">
                <thead>
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
<footer class="sticky-footer bg-white">
    <div class="container my-auto">
        <div class="copyright text-center my-auto">
            <span>Copyright &copy; Your Website 2020</span>
        </div>
    </div>
</footer>
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

</body>

</html>