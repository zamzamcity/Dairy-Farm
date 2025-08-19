<?php
$uri = service('uri')->getSegments();
$segment1 = $uri[0] ?? '';
$segment2 = $uri[1] ?? '';
?>

<!-- Sidebar -->
<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

    <!-- Sidebar - Brand -->
    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="<?= base_url('login/home') ?>">
        <div class="sidebar-brand-icon">
            <img class="rounded-circle" width="40" height="40" src="<?= base_url('assets/sb-admin-2/img/cow.png') ?>" alt="...">
        </div>
        <div class="sidebar-brand-text">Zam Zam DairyCare</div>
    </a>

    <hr class="sidebar-divider my-0">

    <!-- Dashboard -->
    <li class="nav-item <?= $segment1 == 'login' && $segment2 == 'home' ? 'active' : '' ?>">
        <a class="nav-link" href="<?= base_url('login/home') ?>">
            <i class="fas fa-fw fa-tachometer-alt"></i>
            <span>Dashboard</span>
        </a>
    </li>

    <?php if (hasPermission('CanViewManage')): ?>
        <hr class="sidebar-divider">
        <div class="sidebar-heading">Admin Panel</div>

        <?php
        $manageActive = in_array($segment2, ['employees', 'permissions', 'permission_groups']);
        ?>
        <li class="nav-item <?= $manageActive ? 'active' : '' ?>">
            <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseManage"
            aria-expanded="<?= $manageActive ? 'true' : 'false' ?>" aria-controls="collapseManage">
            <i class="fas fa-fw fa-user"></i>
            <span>Manage Users</span>
        </a>
        <div id="collapseManage" class="collapse <?= $manageActive ? 'show' : '' ?>" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <h6 class="collapse-header">Manage Group:</h6>
                <a class="collapse-item <?= $segment2 == 'employees' ? 'active' : '' ?>" href="<?= base_url('manage/employees') ?>">Employees</a>
                <a class="collapse-item <?= $segment2 == 'permissions' ? 'active' : '' ?>" href="<?= base_url('manage/permissions') ?>">Permissions</a>
                <a class="collapse-item <?= $segment2 == 'permission_groups' ? 'active' : '' ?>" href="<?= base_url('manage/permission_groups') ?>">Permission Groups</a>
            </div>
        </div>
    </li>
<?php endif; ?>

<!-- Breeding & Health -->
<?php if (hasPermission('CanViewAnimals') || hasPermission('CanViewPen') || hasPermission('CanViewSemen') || hasPermission('CanViewTechnician') || hasPermission('CanViewSchedule') || hasPermission('CanViewVaccinationSchedule') || hasPermission('CanViewDewormingSchedule')): ?>
<hr class="sidebar-divider d-none d-md-block">
<div class="sidebar-heading">Breeding & Health Management System</div>
<?php endif; ?>

<?php if (hasPermission('CanViewAnimals')): ?>
    <?php $animalsActive = $segment2 == 'animalsList'; ?>
    <li class="nav-item <?= $animalsActive ? 'active' : '' ?>">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseAnimals"
        aria-expanded="<?= $animalsActive ? 'true' : 'false' ?>">
        <i class="fas fa-fw fa-paw"></i>
        <span>Animals</span>
    </a>
    <div id="collapseAnimals" class="collapse <?= $animalsActive ? 'show' : '' ?>" data-parent="#accordionSidebar">
        <div class="bg-white py-2 collapse-inner rounded">
            <h6 class="collapse-header">Animal Management:</h6>
            <a class="collapse-item <?= $animalsActive ? 'active' : '' ?>" href="<?= base_url('animals/animalsList') ?>">Manage Animals</a>
        </div>
    </div>
</li>
<?php endif; ?>

<?php if (hasPermission('CanViewPen') || hasPermission('CanViewSemen') || hasPermission('CanViewTechnician')): ?>
<?php $penActive = $segment1 == 'pen-semen-tech'; ?>
<li class="nav-item <?= $penActive ? 'active' : '' ?>">
    <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapsePen"
    aria-expanded="<?= $penActive ? 'true' : 'false' ?>">
    <i class="fas fa-fw fa-warehouse"></i>
    <span>Pen/Semen/Tech</span>
</a>
<div id="collapsePen" class="collapse <?= $penActive ? 'show' : '' ?>" data-parent="#accordionSidebar">
    <div class="bg-white py-2 collapse-inner rounded">
        <h6 class="collapse-header">Management:</h6>
        <?php if (hasPermission('CanViewPen')): ?>
            <a class="collapse-item <?= $segment2 == 'pen' ? 'active' : '' ?>" href="<?= base_url('pen-semen-tech/pen') ?>">Pen</a>
        <?php endif; ?>
        <?php if (hasPermission('CanViewSemen')): ?>
            <a class="collapse-item <?= $segment2 == 'semen' ? 'active' : '' ?>" href="<?= base_url('pen-semen-tech/semen') ?>">Semen</a>
        <?php endif; ?>
        <?php if (hasPermission('CanViewTechnician')): ?>
            <a class="collapse-item <?= $segment2 == 'technician' ? 'active' : '' ?>" href="<?= base_url('pen-semen-tech/technician') ?>">Technician</a>
        <?php endif; ?>
    </div>
</div>
</li>
<?php endif; ?>

<?php if (hasPermission('CanViewSchedule') || hasPermission('CanViewVaccinationSchedule') || hasPermission('CanViewDewormingSchedule')): ?>
<?php $scheduleActive = $segment1 == 'schedule-events'; ?>
<li class="nav-item <?= $scheduleActive ? 'active' : '' ?>">
    <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseEvents"
    aria-expanded="<?= $scheduleActive ? 'true' : 'false' ?>">
    <i class="fas fa-fw fa-bell"></i>
    <span>Schedule Events</span>
</a>
<div id="collapseEvents" class="collapse <?= $scheduleActive ? 'show' : '' ?>" data-parent="#accordionSidebar">
    <div class="bg-white py-2 collapse-inner rounded">
        <h6 class="collapse-header">Schedule Management:</h6>
        <?php if (hasPermission('CanViewSchedule')): ?>
            <a class="collapse-item <?= $segment2 == 'schedule' ? 'active' : '' ?>" href="<?= base_url('schedule-events/schedule') ?>">View All Schedules</a>
        <?php endif; ?>
        <?php if (hasPermission('CanViewVaccinationSchedule')): ?>
            <a class="collapse-item <?= $segment2 == 'vaccinationSchedule' ? 'active' : '' ?>" href="<?= base_url('schedule-events/vaccinationSchedule') ?>">Vaccination Schedule</a>
        <?php endif; ?>
        <?php if (hasPermission('CanViewDewormingSchedule')): ?>
            <a class="collapse-item <?= $segment2 == 'dewormingSchedule' ? 'active' : '' ?>" href="<?= base_url('schedule-events/dewormingSchedule') ?>">Deworming Schedule</a>
        <?php endif; ?>
    </div>
</div>
</li>
<?php endif; ?>

<!-- Milking & Production -->
<?php if (hasPermission('CanViewAnimalMilking') || hasPermission('CanViewDailyMilking') || hasPermission('CanViewMilkConsumption') || hasPermission('CanViewMilkInOut')): ?>
<hr class="sidebar-divider d-none d-md-block">
<div class="sidebar-heading">Milking & Production Management</div>
<?php endif; ?>

<?php if (hasPermission('CanViewAnimalMilking')): ?>
    <li class="nav-item <?= $segment1 == 'animal-milking' ? 'active' : '' ?>">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseAnimalMilking"
        aria-expanded="<?= $segment1 == 'animal-milking' ? 'true' : 'false' ?>">
        <i class="fas fa-fw fa-glass-whiskey"></i>
        <span>Animal Milking</span>
    </a>
    <div id="collapseAnimalMilking" class="collapse <?= $segment1 == 'animal-milking' ? 'show' : '' ?>" data-parent="#accordionSidebar">
        <div class="bg-white py-2 collapse-inner rounded">
            <a class="collapse-item <?= $segment2 == 'animalMilk' ? 'active' : '' ?>" href="<?= base_url('animal-milking/animalMilk') ?>">Manage Animal Milk</a>
        </div>
    </div>
</li>
<?php endif; ?>

<?php if (hasPermission('CanViewDailyMilking')): ?>
    <li class="nav-item <?= $segment1 == 'dailyMilk' ? 'active' : '' ?>">
        <a class="nav-link" href="<?= base_url('dailyMilk') ?>">
            <i class="fas fa-fw fa-tachometer-alt"></i>
            <span>Daily Milking</span>
        </a>
    </li>
<?php endif; ?>

<?php if (hasPermission('CanViewMilkConsumption')): ?>
    <li class="nav-item <?= $segment1 == 'milk-consumption' ? 'active' : '' ?>">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseMilkConsumption"
        aria-expanded="<?= $segment1 == 'milk-consumption' ? 'true' : 'false' ?>">
        <i class="fas fa-fw fa-filter"></i>
        <span>Milk Consumption</span>
    </a>
    <div id="collapseMilkConsumption" class="collapse <?= $segment1 == 'milk-consumption' ? 'show' : '' ?>" data-parent="#accordionSidebar">
        <div class="bg-white py-2 collapse-inner rounded">
            <a class="collapse-item <?= $segment2 == 'milkConsumption' ? 'active' : '' ?>" href="<?= base_url('milk-consumption/milkConsumption') ?>">Milk Consumption</a>
            <a class="collapse-item <?= $segment2 == 'farmHead' ? 'active' : '' ?>" href="<?= base_url('milk-consumption/farmHead') ?>">Farm Milk Head</a>
        </div>
    </div>
</li>
<?php endif; ?>

<?php if (hasPermission('CanViewMilkInOut')): ?>
    <li class="nav-item <?= $segment1 == 'milkInOut' ? 'active' : '' ?>">
        <a class="nav-link" href="<?= base_url('milkInOut') ?>">
            <i class="fas fa-fw fa-chart-area"></i>
            <span>Milk In/Out</span>
        </a>
    </li>
<?php endif; ?>

<?php if (hasPermission('CanViewStockList')): ?>
    <hr class="sidebar-divider d-none d-md-block">
    <div class="sidebar-heading">Stock Management</div>

    <?php $stockActive = ($segment1 == 'stock'); ?>
    <li class="nav-item <?= $stockActive ? 'active' : '' ?>">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseStock"
           aria-expanded="<?= $stockActive ? 'true' : 'false' ?>" aria-controls="collapseStock">
            <i class="fas fa-fw fa-list"></i>
            <span>Stock</span>
        </a>
        <div id="collapseStock" class="collapse <?= $stockActive ? 'show' : '' ?>" aria-labelledby="headingStock" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <h6 class="collapse-header">Stock Management:</h6>
                <a class="collapse-item <?= $segment2 == 'stockList' ? 'active' : '' ?>" href="<?= base_url('stock/stockList') ?>">View Stock List</a>
            </div>
        </div>
    </li>
<?php endif; ?>

<?php if (hasPermission('CanViewFeedingConsumption')): ?>
    <?php $feedingActive = ($segment1 == 'feeding-consumption'); ?>
    <li class="nav-item <?= $feedingActive ? 'active' : '' ?>">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseFeedingConsumption"
           aria-expanded="<?= $feedingActive ? 'true' : 'false' ?>" aria-controls="collapseFeedingConsumption">
            <i class="fas fa-fw fa-utensils"></i>
            <span>Feeding Consumption</span>
        </a>
        <div id="collapseFeedingConsumption" class="collapse <?= $feedingActive ? 'show' : '' ?>" aria-labelledby="headingFeedingConsumption" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <h6 class="collapse-header">Manage Feed Consumption:</h6>
                <a class="collapse-item <?= $segment2 == 'feedingConsumption' ? 'active' : '' ?>" href="<?= base_url('feeding-consumption/feedingConsumption') ?>">Feeding Consumption</a>
            </div>
        </div>
    </li>
<?php endif; ?>

<?php if (hasPermission('CanViewMedicineConsumption')): ?>
    <?php $medActive = ($segment1 == 'medicine-consumption'); ?>
    <li class="nav-item <?= $medActive ? 'active' : '' ?>">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseMedicineConsumption"
           aria-expanded="<?= $medActive ? 'true' : 'false' ?>" aria-controls="collapseMedicineConsumption">
            <i class="fas fa-fw fa-first-aid"></i>
            <span>Medicine Consumption</span>
        </a>
        <div id="collapseMedicineConsumption" class="collapse <?= $medActive ? 'show' : '' ?>" aria-labelledby="headingMedicineConsumption" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <h6 class="collapse-header">Manage Medicine Consump:</h6>
                <a class="collapse-item <?= $segment2 == 'medicineConsumption' ? 'active' : '' ?>" href="<?= base_url('medicine-consumption/medicineConsumption') ?>">Medicine Consumption</a>
            </div>
        </div>
    </li>
<?php endif; ?>

<?php if (hasPermission('CanViewStockLedger')): ?>
    <li class="nav-item <?= $segment1 == 'stockLedger' ? 'active' : '' ?>">
        <a class="nav-link" href="<?= base_url('stockLedger') ?>">
            <i class="fas fa-fw fa-clipboard-list"></i>
            <span>Stock Ledger</span>
        </a>
    </li>
<?php endif; ?>

<?php if (hasPermission('CanViewChartOfAccounts')): ?>
    <hr class="sidebar-divider d-none d-md-block">
    <div class="sidebar-heading">Account</div>

    <?php $coaActive = ($segment1 == 'chart-of-accounts'); ?>
    <li class="nav-item <?= $coaActive ? 'active' : '' ?>">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseChartOfAccounts"
           aria-expanded="<?= $coaActive ? 'true' : 'false' ?>" aria-controls="collapseChartOfAccounts">
            <i class="fas fa-fw fa-file-alt"></i>
            <span>Chart of Accounts</span>
        </a>
        <div id="collapseChartOfAccounts" class="collapse <?= $coaActive ? 'show' : '' ?>" aria-labelledby="headingChartOfAccounts" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <h6 class="collapse-header">Manage Accounts:</h6>
                <a class="collapse-item <?= $segment2 == 'accountHeads' ? 'active' : '' ?>" href="<?= base_url('chart-of-accounts/accountHeads') ?>">Account Heads</a>
            </div>
        </div>
    </li>
<?php endif; ?>

<?php if (hasPermission('CanViewVouchers')): ?>
    <?php $voucherActive = ($segment1 == 'vouchers'); ?>
    <li class="nav-item <?= $voucherActive ? 'active' : '' ?>">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseVouchers"
           aria-expanded="<?= $voucherActive ? 'true' : 'false' ?>" aria-controls="collapseVouchers">
            <i class="fas fa-fw fa-credit-card"></i>
            <span>Vouchers</span>
        </a>
        <div id="collapseVouchers" class="collapse <?= $voucherActive ? 'show' : '' ?>" aria-labelledby="headingVouchers" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <h6 class="collapse-header">Manage Vouchers:</h6>
                <a class="collapse-item <?= $segment2 == 'paymentVoucher' ? 'active' : '' ?>" href="<?= base_url('vouchers/paymentVoucher') ?>">Payment Voucher</a>
                <a class="collapse-item <?= $segment2 == 'receiptVoucher' ? 'active' : '' ?>" href="<?= base_url('vouchers/receiptVoucher') ?>">Receipt Voucher</a>
                <a class="collapse-item <?= $segment2 == 'journalVoucher' ? 'active' : '' ?>" href="<?= base_url('vouchers/journalVoucher') ?>">Journal Voucher</a>
            </div>
        </div>
    </li>
<?php endif; ?>

<?php if (hasPermission('CanViewLedger')): ?>
    <?php $ledgerActive = ($segment1 == 'ledger'); ?>
    <li class="nav-item <?= $ledgerActive ? 'active' : '' ?>">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseLedger"
           aria-expanded="<?= $ledgerActive ? 'true' : 'false' ?>" aria-controls="collapseLedger">
            <i class="fas fa-fw fa-clipboard-list"></i>
            <span>Ledger</span>
        </a>
        <div id="collapseLedger" class="collapse <?= $ledgerActive ? 'show' : '' ?>" aria-labelledby="headingLedger" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <h6 class="collapse-header">Manage Ledger:</h6>
                <a class="collapse-item <?= $segment2 == 'accountLedger' ? 'active' : '' ?>" href="<?= base_url('ledger/accountLedger') ?>">Account Ledger</a>
            </div>
        </div>
    </li>
<?php endif; ?>

<?php if (hasPermission('CanViewPayroll')): ?>
    <hr class="sidebar-divider d-none d-md-block">
    <div class="sidebar-heading">Employee Payroll System</div>

    <?php $payrollActive = ($segment1 == 'payroll'); ?>
    <li class="nav-item <?= $payrollActive ? 'active' : '' ?>">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapsePayroll"
           aria-expanded="<?= $payrollActive ? 'true' : 'false' ?>" aria-controls="collapsePayroll">
            <i class="fas fa-fw fa-money-check-alt"></i>
            <span>Payroll</span>
        </a>
        <div id="collapsePayroll" class="collapse <?= $payrollActive ? 'show' : '' ?>" aria-labelledby="headingPayroll" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <h6 class="collapse-header">Manage Payroll:</h6>
                <a class="collapse-item <?= $segment2 == 'salaryPayments' ? 'active' : '' ?>" href="<?= base_url('payroll/salaryPayments') ?>">Salary Payments</a>
                <a class="collapse-item <?= $segment2 == 'salaryLedger' ? 'active' : '' ?>" href="<?= base_url('payroll/salaryLedger') ?>">Salary Ledger</a>
            </div>
        </div>
    </li>
<?php endif; ?>

<hr class="sidebar-divider d-none d-md-block">

<div class="text-center d-none d-md-inline">
    <button class="rounded-circle border-0" id="sidebarToggle"></button>
</div>
</ul>
<!-- End of Sidebar -->
