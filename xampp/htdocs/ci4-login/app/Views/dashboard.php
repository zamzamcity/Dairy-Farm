<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Zam Zam Admin Dashboard">
    <meta name="author" content="">

    <title>Zam Zam Dashboard</title>

    <!-- Custom fonts for this template-->
    <link href="<?= base_url('assets/sb-admin-2/vendor/fontawesome-free/css/all.min.css') ?>" rel="stylesheet">
    <link
    href="https://fonts.googleapis.com/css?family=Nunito:300,400,600,700,800,900"
    rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="<?= base_url('assets/sb-admin-2/css/sb-admin-2.min.css') ?>" rel="stylesheet">

</head>

<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">

        <!-- Sidebar -->
        <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

            <!-- Sidebar - Brand -->
            <a class="sidebar-brand d-flex align-items-center justify-content-center" href="<?= base_url('login/home') ?>">
                <div class="sidebar-brand-icon">
                    <img class="rounded-circle width="32" height="32" " src="<?= base_url('assets/sb-admin-2/img/cow.png') ?>" alt="...">
                </div>
                <div class="sidebar-brand-text mx-3">DairyCare<sup></sup></div>
            </a>

            <!-- Divider -->
            <hr class="sidebar-divider my-0">

            <!-- Nav Item - Dashboard -->
            <li class="nav-item active">
                <a class="nav-link" href="<?= base_url('login/home') ?>">
                    <i class="fas fa-fw fa-tachometer-alt"></i>
                    <span>Dashboard</span></a>
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
<?php if (hasPermission('CanViewSchedule')||hasPermission('CanViewVaccinationSchedule')||hasPermission('CanViewDewormingSchedule')): ?>
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
    <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseStock"
        aria-expanded="true" aria-controls="collapseStock">
        <i class="fas fa-fw fa-list"></i>
        <span>Stock</span>
    </a>
    <div id="collapseStock" class="collapse" aria-labelledby="headingStock" data-parent="#accordionSidebar">
        <div class="bg-white py-2 collapse-inner rounded">
            <h6 class="collapse-header">Stock Management:</h6>
            <a class="collapse-item" href="<?= base_url('stock/stockList') ?>">View Stock List</a>
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

<!-- Nav Item - Stock Ledger -->
<?php if (hasPermission('CanViewStockLedger')): ?>
    <li class="nav-item">
        <a class="nav-link" href="<?= base_url('stockLedger') ?>">
            <i class="fas fa-fw fa-clipboard-list"></i>
            <span>Stock Ledger</span></a>
        </li>
    <?php endif; ?>

    <?php if (hasPermission('CanViewChartOfAccounts')): ?>
<!-- Divider -->
<hr class="sidebar-divider d-none d-md-block">
<!-- Heading -->
<div class="sidebar-heading">
    Account
</div>
<?php endif; ?>
<!-- Nav Item - Chart of Accounts Collapse Menu -->
<?php if (hasPermission('CanViewChartOfAccounts')): ?>
    <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseChartOfAccounts"
        aria-expanded="true" aria-controls="collapseChartOfAccounts">
        <i class="fas fa-fw fa-file-alt"></i>
        <span>Chart of Accounts</span>
    </a>
    <div id="collapseChartOfAccounts" class="collapse" aria-labelledby="headingChartOfAccounts" data-parent="#accordionSidebar">
        <div class="bg-white py-2 collapse-inner rounded">
            <h6 class="collapse-header">Manage Accounts:</h6>
            <a class="collapse-item" href="<?= base_url('chart-of-accounts/accountHeads') ?>">Account Heads</a>
        </div>
    </div>
</li>
<?php endif; ?>

<!-- Nav Item - Vouchers Collapse Menu -->
<?php if (hasPermission('CanViewVouchers')): ?>
    <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseVouchers"
        aria-expanded="true" aria-controls="collapseVouchers">
        <i class="fas fa-fw fa-credit-card"></i>
        <span>Vouchers</span>
    </a>
    <div id="collapseVouchers" class="collapse" aria-labelledby="headingVouchers" data-parent="#accordionSidebar">
        <div class="bg-white py-2 collapse-inner rounded">
            <h6 class="collapse-header">Manage Vouchers:</h6>
            <a class="collapse-item" href="<?= base_url('vouchers/paymentVoucher') ?>">Payment Voucher</a>
            <a class="collapse-item" href="<?= base_url('vouchers/receiptVoucher') ?>">Receipt Voucher</a>
            <a class="collapse-item" href="<?= base_url('vouchers/journalVoucher') ?>">Journal Voucher</a>
        </div>
    </div>
</li>
<?php endif; ?>

<!-- Nav Item - Ledger Collapse Menu -->
<?php if (hasPermission('CanViewLedger')): ?>
    <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseLedger"
        aria-expanded="true" aria-controls="collapseLedger">
        <i class="fas fa-fw fa-clipboard-list"></i>
        <span>Ledger</span>
    </a>
    <div id="collapseLedger" class="collapse" aria-labelledby="headingLedger" data-parent="#accordionSidebar">
        <div class="bg-white py-2 collapse-inner rounded">
            <h6 class="collapse-header">Manage Ledger:</h6>
            <a class="collapse-item" href="<?= base_url('ledger/accountLedger') ?>">Account Ledger</a>
        </div>
    </div>
</li>
<?php endif; ?>

<?php if (hasPermission('CanViewPayroll')): ?>
<!-- Divider -->
<hr class="sidebar-divider d-none d-md-block">
<!-- Heading -->
<div class="sidebar-heading">
    Employee Payroll System
</div>
<?php endif; ?>

<!-- Nav Item - Payroll Collapse Menu -->
<?php if (hasPermission('CanViewPayroll')): ?>
    <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapsePayroll"
        aria-expanded="true" aria-controls="collapsePayroll">
        <i class="fas fa-fw fa-money-check-alt"></i>
        <span>Payroll</span>
    </a>
    <div id="collapsePayroll" class="collapse" aria-labelledby="headingPayroll" data-parent="#accordionSidebar">
        <div class="bg-white py-2 collapse-inner rounded">
            <h6 class="collapse-header">Manage Payroll:</h6>
            <a class="collapse-item" href="<?= base_url('payroll/salaryPayments') ?>">Salary Payments</a>
            <a class="collapse-item" href="<?= base_url('payroll/salaryLedger') ?>">Salary Ledger</a>
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

    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Dashboard</h1>
        <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i
            class="fas fa-download fa-sm text-white-50"></i> Generate Report</a>
        </div>

        <!-- Content Row -->
        <div class="row">

            <!-- Earnings (Monthly) Card Example -->
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-primary shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                    Total Employees
                                </div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $totalEmployees ?></div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-users fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Earnings (Monthly) Card Example -->
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-success shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                    Total Animals
                                </div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $totalAnimals ?></div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-paw fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Earnings (Monthly) Card Example -->
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-info shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="row no-gutters align-items-center">
                                    <div class="col mr-2">
                                        <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                            Total Milk (Liters)
                                        </div>
                                        <div class="h5 mb-0 font-weight-bold text-gray-800"><?= number_format($totalMilk, 2) ?> L</div>
                                    </div>
                                    <div class="col-auto">
                                        <i class="fas fa-glass-whiskey fa-2x text-gray-300"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Pending Requests Card Example -->
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-warning shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                    Total Products
                                </div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800"><?= number_format($totalProducts) ?></div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-box fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Content Row -->

        <div class="row">

            <!-- Area Chart -->
            <div class="col-xl-8 col-lg-7">
                <div class="card shadow mb-4">
                    <!-- Card Header - Dropdown -->
                    <div
                    class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Earnings Overview</h6>
                    <div class="dropdown no-arrow">
                        <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink"
                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in"
                    aria-labelledby="dropdownMenuLink">
                    <div class="dropdown-header">Dropdown Header:</div>
                    <a class="dropdown-item" href="#">Action</a>
                    <a class="dropdown-item" href="#">Another action</a>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item" href="#">Something else here</a>
                </div>
            </div>
        </div>
        <!-- Card Body -->
        <div class="card-body">
            <div class="chart-area">
                <canvas id="myAreaChart"></canvas>
            </div>
        </div>
    </div>
</div>

<!-- Pie Chart -->
<div class="col-xl-4 col-lg-5">
    <div class="card shadow mb-4">
        <!-- Card Header - Dropdown -->
        <div
        class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
        <h6 class="m-0 font-weight-bold text-primary">Revenue Sources</h6>
        <div class="dropdown no-arrow">
            <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink"
            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
        </a>
        <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in"
        aria-labelledby="dropdownMenuLink">
        <div class="dropdown-header">Dropdown Header:</div>
        <a class="dropdown-item" href="#">Action</a>
        <a class="dropdown-item" href="#">Another action</a>
        <div class="dropdown-divider"></div>
        <a class="dropdown-item" href="#">Something else here</a>
    </div>
</div>
</div>
<!-- Card Body -->
<div class="card-body">
    <div class="chart-pie pt-4 pb-2">
        <canvas id="myPieChart"></canvas>
    </div>
    <div class="mt-4 text-center small">
        <span class="mr-2">
            <i class="fas fa-circle text-primary"></i> Direct
        </span>
        <span class="mr-2">
            <i class="fas fa-circle text-success"></i> Social
        </span>
        <span class="mr-2">
            <i class="fas fa-circle text-info"></i> Referral
        </span>
    </div>
</div>
</div>
</div>
</div>

<!-- Content Row -->
<div class="row">

    <!-- Content Column -->
    <div class="col-lg-6 mb-4">

        <!-- Project Card Example -->
<!--         <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Projects</h6>
            </div>
            <div class="card-body">
                <h4 class="small font-weight-bold">Server Migration <span
                    class="float-right">20%</span></h4>
                    <div class="progress mb-4">
                        <div class="progress-bar bg-danger" role="progressbar" style="width: 20%"
                        aria-valuenow="20" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                    <h4 class="small font-weight-bold">Sales Tracking <span
                        class="float-right">40%</span></h4>
                        <div class="progress mb-4">
                            <div class="progress-bar bg-warning" role="progressbar" style="width: 40%"
                            aria-valuenow="40" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                        <h4 class="small font-weight-bold">Customer Database <span
                            class="float-right">60%</span></h4>
                            <div class="progress mb-4">
                                <div class="progress-bar" role="progressbar" style="width: 60%"
                                aria-valuenow="60" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                            <h4 class="small font-weight-bold">Payout Details <span
                                class="float-right">80%</span></h4>
                                <div class="progress mb-4">
                                    <div class="progress-bar bg-info" role="progressbar" style="width: 80%"
                                    aria-valuenow="80" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                                <h4 class="small font-weight-bold">Account Setup <span
                                    class="float-right">Complete!</span></h4>
                                    <div class="progress">
                                        <div class="progress-bar bg-success" role="progressbar" style="width: 100%"
                                        aria-valuenow="100" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                </div>
                            </div> -->
                        </div>

                        <div class="col-lg-6 mb-4">

                            <!-- Illustrations -->
                            <!-- <div class="card shadow mb-4">
                                <div class="card-header py-3">
                                    <h6 class="m-0 font-weight-bold text-primary">Illustrations</h6>
                                </div>
                                <div class="card-body">
                                    <div class="text-center">

                                        <img class="img-fluid px-3 px-sm-4 mt-3 mb-4" style="width: 25rem;"
                                        src="<?= base_url('assets/sb-admin-2/img/undraw_posting_photo.svg') ?>" alt="...">
                                    </div>
                                    <p>Add some quality, svg illustrations to your project courtesy of <a
                                        target="_blank" rel="nofollow" href="https://undraw.co/">unDraw</a>, a
                                        constantly updated collection of beautiful svg images that you can use
                                    completely free and without attribution!</p>
                                    <a target="_blank" rel="nofollow" href="https://undraw.co/">Browse Illustrations on
                                    unDraw &rarr;</a>
                                </div>
                            </div> -->

                            <!-- Approach -->
                            <!-- <div class="card shadow mb-4">
                                <div class="card-header py-3">
                                    <h6 class="m-0 font-weight-bold text-primary">Development Approach</h6>
                                </div>
                                <div class="card-body">
                                    <p>SB Admin 2 makes extensive use of Bootstrap 4 utility classes in order to reduce
                                        CSS bloat and poor page performance. Custom CSS classes are used to create
                                    custom components and custom utility classes.</p>
                                    <p class="mb-0">Before working with this theme, you should become familiar with the
                                    Bootstrap framework, especially the utility classes.</p>
                                </div>
                            </div> -->

                        </div>
                    </div>

                </div>
                <!-- /.container-fluid -->

            </div>
            <!-- End of Main Content -->

            <!-- Footer -->
            <footer class="sticky-footer bg-white">
                <div class="container my-auto">
                    <div class="copyright text-center my-auto">
                        <span>&copy; 2025 Zam Zam Developers | All rights reserved.</span>
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
                <h5 class="modal-title">Ready to Leave?</h5>
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


<!-- Bootstrap core JavaScript -->
<script src="<?= base_url('assets/sb-admin-2/vendor/jquery/jquery.min.js') ?>"></script>
<script src="<?= base_url('assets/sb-admin-2/vendor/bootstrap/js/bootstrap.bundle.min.js') ?>"></script>

<!-- Core plugin JavaScript -->
<script src="<?= base_url('assets/sb-admin-2/vendor/jquery-easing/jquery.easing.min.js') ?>"></script>

<!-- Custom scripts for all pages -->
<script src="<?= base_url('assets/sb-admin-2/js/sb-admin-2.min.js') ?>"></script>

<!-- Page level plugins -->
<script src="<?= base_url('assets/sb-admin-2/vendor/chart.js/Chart.min.js') ?>"></script>

<!-- Page level custom scripts -->
<script src="<?= base_url('assets/sb-admin-2/js/demo/chart-area-demo.js') ?>"></script>
<script src="<?= base_url('assets/sb-admin-2/js/demo/chart-pie-demo.js') ?>"></script>

</body>

</html>