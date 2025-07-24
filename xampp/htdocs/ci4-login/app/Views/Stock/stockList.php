<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Stock_List UI Page">
    <meta name="author" content="">

    <title>SB Admin 2 - Stock_List</title>

    <!-- Custom fonts for this template-->
    <link href="<?= base_url('assets/sb-admin-2/vendor/fontawesome-free/css/all.min.css') ?>" rel="stylesheet" type="text/css">
    <link
    href="https://fonts.googleapis.com/css?family=Nunito:200,300,400,600,700,800,900"
    rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="<?= base_url('assets/sb-admin-2/css/sb-admin-2.min.css') ?>" rel="stylesheet">

</head>


<?php foreach ($stock_registration as $stock): ?>
<!-- Edit Stock Registration Modal -->
<div class="modal fade" id="editStockRegistrationModal<?= $stock['id'] ?>" tabindex="-1" role="dialog" aria-labelledby="editStockRegistrationModalLabel<?= $stock['id'] ?>" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form action="<?= base_url('stock/stockList/edit/' . $stock['id']) ?>" method="post">
            <?= csrf_field() ?>
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Stock Registration</h5>
                    <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                </div>

                <div class="modal-body">
                    <!-- Product Name -->
                    <div class="form-group">
                        <label for="product_name">Product Name:</label>
                        <input type="text" name="product_name" class="form-control" value="<?= esc($stock['product_name']) ?>" required>
                    </div>

                    <!-- Head Dropdown with Add Button -->
                    <div class="form-group">
                        <label for="head_id">Choose Head:</label>
                        <div class="input-group">
                            <select name="head_id" class="form-control" required>
                                <option value="">-- Select Head --</option>
                                <?php foreach ($stock_heads as $head): ?>
                                <option value="<?= $head['id'] ?>" <?= $head['id'] == $stock['head_id'] ? 'selected' : '' ?>>
                                    <?= esc($head['name']) ?>
                                </option>
                                <?php endforeach; ?>
                            </select>
                            <div class="input-group-append">
                                <button type="button" class="btn btn-outline-secondary" data-toggle="modal" data-target="#addHeadModal">+</button>
                            </div>
                        </div>
                    </div>

                    <!-- Unit Dropdown with Add Button -->
                    <div class="form-group">
                        <label for="unit_id">Choose Unit:</label>
                        <div class="input-group">
                            <select name="unit_id" class="form-control" required>
                                <option value="">-- Select Unit --</option>
                                <?php foreach ($stock_units as $unit): ?>
                                <option value="<?= $unit['id'] ?>" <?= $unit['id'] == $stock['unit_id'] ? 'selected' : '' ?>>
                                    <?= esc($unit['name']) ?>
                                </option>
                                <?php endforeach; ?>
                            </select>
                            <div class="input-group-append">
                                <button type="button" class="btn btn-outline-secondary" data-toggle="modal" data-target="#addUnitModal">+</button>
                            </div>
                        </div>
                    </div>

                    <!-- Is Stock Item -->
                    <div class="form-group">
                        <label>Is Stock Item?</label><br>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="is_stock_item" id="stockYes<?= $stock['id'] ?>" value="1"
                            <?= (isset($stock['is_stock_item']) && $stock['is_stock_item'] == 1) ? 'checked' : '' ?>
                            onclick="toggleStockFields<?= $stock['id'] ?>(true)">
                            <label class="form-check-label" for="stockYes<?= $stock['id'] ?>">Yes</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="is_stock_item" id="stockNo<?= $stock['id'] ?>" value="0"
                            <?= (isset($stock['is_stock_item']) && $stock['is_stock_item'] == 0) ? 'checked' : '' ?>
                            onclick="toggleStockFields<?= $stock['id'] ?>(false)">
                            <label class="form-check-label" for="stockNo<?= $stock['id'] ?>">No</label>
                        </div>
                    </div>

                    <!-- Conditional Stock Fields -->
                    <div id="stockFields<?= $stock['id'] ?>" style="display: <?= $stock['is_stock_item'] ? 'block' : 'none' ?>;">
                        <div class="form-group">
                            <label for="opening_stock_qty">Opening Stock Quantity:</label>
                            <input type="number" step="0.01" name="opening_stock_qty" class="form-control" value="<?= esc($stock['opening_stock_qty']) ?>">
                        </div>

                        <div class="form-group">
                            <label for="opening_stock_rate_per_unit">Opening Stock Rate/Unit:</label>
                            <input type="number" step="0.01" name="opening_stock_rate_per_unit" class="form-control" value="<?= esc($stock['opening_stock_rate_per_unit']) ?>">
                        </div>
                    </div>

                    <!-- Rate Per Unit -->
                    <div class="form-group">
                        <label for="rate_per_unit">Rate w.r.t 1 Unit:</label>
                        <input type="number" step="0.01" name="rate_per_unit" class="form-control" value="<?= esc($stock['rate_per_unit']) ?>" required>
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

<!-- JavaScript to toggle stock fields -->
<script>
    function toggleStockFields<?= $stock['id'] ?>(show) {
        const fieldDiv = document.getElementById('stockFields<?= $stock['id'] ?>');
        fieldDiv.style.display = show ? 'block' : 'none';

        // Update hidden input
        const hiddenInput = document.getElementById('is_stock_item_hidden_<?= $stock['id'] ?>');
        hiddenInput.value = show ? '1' : '0';
    }
</script>


<!-- Delete Stock Registration Modal -->
<div class="modal fade" id="deleteStockRegistrationModal<?= $stock['id'] ?>" tabindex="-1" role="dialog" aria-labelledby="deleteStockRegistrationModalLabel<?= $stock['id'] ?>" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form action="<?= base_url('stock/stockList/delete/' . $stock['id']) ?>" method="post">
            <?= csrf_field() ?>
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Delete Stock Registration</h5>
                    <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                </div>

                <div class="modal-body">
                    Are you sure you want to delete "<strong><?= esc($stock['product_name']) ?></strong>"?
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
        <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

            <!-- Sidebar - Brand -->
            <a class="sidebar-brand d-flex align-items-center justify-content-center" href="<?= base_url('login/home') ?>">
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

            <?php if (hasPermission('CanViewManage')): ?>
            <!-- Divider -->
            <hr class="sidebar-divider">

            <!-- Heading -->
            <div class="sidebar-heading">
                Admin Panel
            </div>
            <?php endif; ?>

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

        <?php if (hasPermission('CanViewAnimals')||hasPermission('CanViewPen')||hasPermission('CanViewSemen')||hasPermission('CanViewTechnician')||hasPermission('CanViewSchedule')||hasPermission('CanViewVaccinationSchedule')||hasPermission('CanViewDewormingSchedule')): ?>
<!-- Divider -->
<hr class="sidebar-divider d-none d-md-block">

<!-- Heading -->
<div class="sidebar-heading">
    Breeding & Health Management System
</div>
<?php endif; ?>

<!-- Nav Item - Animals Collapse Menu -->
<?php if (hasPermission('CanViewAnimals')): ?>
<li class="nav-item">
    <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseAnimals"
    aria-expanded="true" aria-controls="collapseAnimals">
    <i class="fas fa-fw fa-paw"></i>
    <span>Animals</span>
</a>
<div id="collapseAnimals" class="collapse" aria-labelledby="headingAnimals" data-parent="#accordionSidebar">
    <div class="bg-white py-2 collapse-inner rounded">
        <h6 class="collapse-header">Animal Management:</h6>
        <a class="collapse-item" href="<?= base_url('animals/animalsList') ?>">Manage Animals</a>
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

<?php if (hasPermission('CanViewAnimalMilking')||(hasPermission('CanViewDailyMilking'))||(hasPermission('CanViewMilkConsumption'))||(hasPermission('CanViewMilkInOut'))): ?>
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

    <!-- Nav Item - Milk Consumption Collapse Menu -->
    <?php if (hasPermission('CanViewMilkConsumption')): ?>
    <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseMilkConsumption"
        aria-expanded="true" aria-controls="collapseMilkConsumption">
        <i class="fas fa-fw fa-filter"></i>
        <span>Milk Consumption</span>
    </a>
    <div id="collapseMilkConsumption" class="collapse" aria-labelledby="headingMilkConsumption" data-parent="#accordionSidebar">
        <div class="bg-white py-2 collapse-inner rounded">
            <h6 class="collapse-header">Manage Milk Consumption:</h6>
            <a class="collapse-item" href="<?= base_url('milk-consumption/milkConsumption') ?>">Milk Consumption</a>
            <a class="collapse-item" href="<?= base_url('milk-consumption/farmHead') ?>">Farm Milk Head</a>
        </div>
    </div>
</li>
<?php endif; ?>

<!-- Nav Item - Milk In/Out -->
<?php if (hasPermission('CanViewMilkInOut')): ?>
<li class="nav-item">
    <a class="nav-link" href="<?= base_url('milkInOut') ?>">
        <i class="fas fa-fw fa-chart-area"></i>
        <span>Milk In/Out</span></a>
    </li>
    <?php endif; ?>

    <?php if (hasPermission('CanViewStockList')): ?>
<!-- Divider -->
<hr class="sidebar-divider d-none d-md-block">
<!-- Heading -->
<div class="sidebar-heading">
    Stock Management
</div>
<?php endif; ?>

<!-- Nav Item - Stock Collapse Menu -->
<?php if (hasPermission('CanViewStockList')): ?>
<li class="nav-item active">
    <a class="nav-link" href="#" data-toggle="collapse" data-target="#collapseStock"
    aria-expanded="true" aria-controls="collapseStock">
    <i class="fas fa-fw fa-list"></i>
    <span>Stock</span>
</a>
<div id="collapseStock" class="collapse show" aria-labelledby="headingStock" data-parent="#accordionSidebar">
    <div class="bg-white py-2 collapse-inner rounded">
        <h6 class="collapse-header">Stock Management:</h6>
        <a class="collapse-item active" href="<?= base_url('stock/stockList') ?>">View Stock List</a>
    </div>
</div>
</li>
<?php endif; ?>

<!-- Nav Item - Feeding Consumption Collapse Menu -->
<?php if (hasPermission('CanViewFeedingConsumption')): ?>
<li class="nav-item">
    <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseFeedingConsumption"
    aria-expanded="true" aria-controls="collapseFeedingConsumption">
    <i class="fas fa-fw fa-utensils"></i>
    <span>Feeding Consumption</span>
</a>
<div id="collapseFeedingConsumption" class="collapse" aria-labelledby="headingFeedingConsumption" data-parent="#accordionSidebar">
    <div class="bg-white py-2 collapse-inner rounded">
        <h6 class="collapse-header">Manage Feed Consumption:</h6>
        <a class="collapse-item" href="<?= base_url('feeding-consumption/feedingConsumption') ?>">Feeding Consumption</a>
    </div>
</div>
</li>
<?php endif; ?>

<!-- Nav Item - Medicine Consumption Collapse Menu -->
<?php if (hasPermission('CanViewMedicineConsumption')): ?>
<li class="nav-item">
    <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseMedicineConsumption"
    aria-expanded="true" aria-controls="collapseMedicineConsumption">
    <i class="fas fa-fw fa-first-aid"></i>
    <span>Medicine Consumption</span>
</a>
<div id="collapseMedicineConsumption" class="collapse" aria-labelledby="headingMedicineConsumption" data-parent="#accordionSidebar">
    <div class="bg-white py-2 collapse-inner rounded">
        <h6 class="collapse-header">Manage Medicine Consump:</h6>
        <a class="collapse-item" href="<?= base_url('medicine-consumption/medicineConsumption') ?>">Medicine Consumption</a>
    </div>
</div>
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
        <h1 class="h3 mb-0 text-gray-800">Stock Registration</h1>
    </div>

    <!-- Stock Registration Button -->
    <?php if (hasPermission('CanAddStockRegistration')): ?>
    <div class="mb-3 text-right">
        <a href="#" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#addStockRegistrationModal">+ Stock Registration</a>
    </div>
    <?php endif; ?>

    <!-- Stock Registration Table -->
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
                            <th>Product Name</th>
                            <th>Head</th>
                            <th>Unit</th>
                            <th>Stock Item</th>
                            <th>Opening Qty</th>
                            <th>Opening Rate</th>
                            <th>Rate/Unit</th>
                            <?php if (hasPermission('CanUpdateStockRegistration') || hasPermission('CanDeleteStockRegistration')): ?>
                            <th>Actions</th>
                            <?php endif; ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($stock_registration as $stock): ?>
                        <tr>
                            <td><?= esc($stock['id']) ?></td>
                            <td><?= esc($stock['product_name']) ?></td>
                            <td><?= esc($stock['head_name']) ?></td>
                            <td><?= esc($stock['unit_name']) ?></td>
                            <td><?= esc($stock['is_stock_item'] ? 'Yes' : 'No') ?></td>
                            <td><?= esc($stock['opening_stock_qty']) ?></td>
                            <td><?= esc($stock['opening_stock_rate_per_unit']) ?></td>
                            <td><?= esc($stock['rate_per_unit']) ?></td>
                            <?php if (hasPermission('CanUpdateStockRegistration') || hasPermission('CanDeleteStockRegistration')): ?>
                            <td>
                                <?php if (hasPermission('CanUpdateStockRegistration')): ?>
                                <a href="#" class="btn btn-sm btn-info" data-toggle="modal" data-target="#editStockRegistrationModal<?= $stock['id'] ?>">Edit</a>
                                <?php endif; ?>
                                <?php if (hasPermission('CanDeleteStockRegistration')): ?>
                                <a href="#" class="btn btn-sm btn-danger" data-toggle="modal" data-target="#deleteStockRegistrationModal<?= $stock['id'] ?>">Delete</a>
                                <?php endif; ?>
                            </td>
                            <?php endif; ?>
                        </tr>
                        <?php endforeach; ?>

                        <?php if (empty($stock_registration)): ?>
                        <tr>
                            <td colspan="9" class="text-center">No stock records found.</td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Add Stock Registration Modal -->
<div class="modal fade" id="addStockRegistrationModal" tabindex="-1" role="dialog" aria-labelledby="addStockRegistrationModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form action="<?= base_url('stock/stockList/add') ?>" method="post">
            <?= csrf_field() ?>
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Stock Registration</h5>
                    <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                </div>

                <div class="modal-body">
                    <!-- Product Name -->
                    <div class="form-group">
                        <label for="product_name">Product Name:</label>
                        <input type="text" name="product_name" id="product_name" class="form-control" placeholder="Enter product name" required>
                    </div>

                    <!-- Head Dropdown with Add Button -->
                    <div class="form-group">
                        <label for="head_id">Choose Head:</label>
                        <div class="input-group">
                            <select name="head_id" id="head_id" class="form-control" required>
                                <option value="">-- Select Head --</option>
                                <?php foreach ($stock_heads as $head): ?>
                                <option value="<?= esc($head['id']) ?>"><?= esc($head['name']) ?></option>
                                <?php endforeach; ?>
                            </select>
                            <div class="input-group-append">
                                <button type="button" class="btn btn-outline-secondary" data-toggle="modal" data-target="#addHeadModal">+</button>
                            </div>
                        </div>
                    </div>

                    <!-- Unit Dropdown with Add Button -->
                    <div class="form-group">
                        <label for="unit_id">Choose Unit:</label>
                        <div class="input-group">
                            <select name="unit_id" id="unit_id" class="form-control" required>
                                <option value="">-- Select Unit --</option>
                                <?php foreach ($stock_units as $unit): ?>
                                <option value="<?= esc($unit['id']) ?>"><?= esc($unit['name']) ?></option>
                                <?php endforeach; ?>
                            </select>
                            <div class="input-group-append">
                                <button type="button" class="btn btn-outline-secondary" data-toggle="modal" data-target="#addUnitModal">+</button>
                            </div>
                        </div>
                    </div>

                    <!-- Stock Item: Yes/No -->
                    <div class="form-group">
                        <label>Is Stock Item?</label><br>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="is_stock_item" id="stockYes" value="1" checked onclick="toggleStockFields(true)">
                            <label class="form-check-label" for="stockYes">Yes</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="is_stock_item" id="stockNo" value="0" onclick="toggleStockFields(false)">
                            <label class="form-check-label" for="stockNo">No</label>
                        </div>
                    </div>

                    <!-- Conditional Stock Fields -->
                    <div id="stockFields">
                        <div class="form-group">
                            <label for="opening_stock_qty">Opening Stock Quantity:</label>
                            <input type="number" step="0.01" name="opening_stock_qty" id="opening_stock_qty" class="form-control">
                        </div>

                        <div class="form-group">
                            <label for="opening_stock_rate_per_unit">Opening Stock Rate/Unit:</label>
                            <input type="number" step="0.01" name="opening_stock_rate_per_unit" id="opening_stock_rate_per_unit" class="form-control">
                        </div>
                    </div>

                    <!-- Rate Per Unit -->
                    <div class="form-group">
                        <label for="rate_per_unit">Rate w.r.t 1 Unit:</label>
                        <input type="number" step="0.01" name="rate_per_unit" id="rate_per_unit" class="form-control" required>
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

<!-- Add Head Modal -->
<div class="modal fade" id="addHeadModal" tabindex="-1" role="dialog" aria-labelledby="addHeadModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form action="<?= base_url('stock/stockList/add-head') ?>" method="post">
            <?= csrf_field() ?>
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add New Head</h5>
                    <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="head_name">Head Name:</label>
                        <input type="text" name="head_name" id="head_name" class="form-control" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Add</button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Add Unit Modal -->
<div class="modal fade" id="addUnitModal" tabindex="-1" role="dialog" aria-labelledby="addUnitModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form action="<?= base_url('stock/stockList/add-unit') ?>" method="post">
            <?= csrf_field() ?>
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add New Unit</h5>
                    <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="unit_name">Unit Name:</label>
                        <input type="text" name="unit_name" id="unit_name" class="form-control" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Add</button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- JavaScript to toggle stock fields -->
<script>
    function toggleStockFields(show) {
        document.getElementById('stockFields').style.display = show ? 'block' : 'none';
    }
    
    document.addEventListener('DOMContentLoaded', function() {
        toggleStockFields(document.getElementById('stockYes').checked);
    });
</script>

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

</body>

</html>