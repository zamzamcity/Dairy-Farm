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


<?php foreach ($account_heads as $account_head): ?>
<!-- Edit Account Head Modal -->
<div class="modal fade" id="editAccountHeadModal<?= $account_head['id'] ?>" tabindex="-1" role="dialog" aria-labelledby="editAccountHeadModalLabel<?= $account_head['id'] ?>" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <form action="<?= base_url('chart-of-accounts/accountHeads/edit/' . $account_head['id']) ?>" method="post">
      <?= csrf_field() ?>
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="editAccountHeadModalLabel<?= $account_head['id'] ?>">Edit Account Head</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">

          <div class="form-group">
            <label for="account_code<?= $account_head['id'] ?>">Account Code</label>
            <input type="text" class="form-control" id="account_code<?= $account_head['id'] ?>" name="account_code" value="<?= esc($account_head['account_code']) ?>" required>
          </div>

          <div class="form-group">
            <label for="name<?= $account_head['id'] ?>">Account Name</label>
            <input type="text" class="form-control" id="name<?= $account_head['id'] ?>" name="name" value="<?= esc($account_head['name']) ?>" required>
          </div>

          <div class="form-group">
            <label for="type<?= $account_head['id'] ?>">Account Type</label>
            <select class="form-control" id="type<?= $account_head['id'] ?>" name="type" required>
              <?php 
                $types = ['Cash-in-Hand', 'Bank Accounts', 'Current Liabilities/Loans', 'Capital Account', 'Cost of Goods Sales', 'Expense', 'Direct Income', 'Stock Assets', 'Fixed Assets', 'Current Assets', 'Creditors/Vendors', 'Customer', 'Employee', 'Milk Incentive'];
                foreach ($types as $type):
              ?>
              <option value="<?= $type ?>" <?= ($account_head['type'] == $type) ? 'selected' : '' ?>><?= $type ?></option>
              <?php endforeach; ?>
            </select>
          </div>

          <div class="form-group">
            <label for="opening_balance<?= $account_head['id'] ?>">Opening Balance</label>
            <input type="number" step="0.01" class="form-control" id="opening_balance<?= $account_head['id'] ?>" name="opening_balance" value="<?= esc($account_head['opening_balance']) ?>" required>
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

<!-- Delete Account Head Modal -->
<div class="modal fade" id="deleteAccountHeadModal<?= $account_head['id'] ?>" tabindex="-1" role="dialog" aria-labelledby="deleteAccountHeadModalLabel<?= $account_head['id'] ?>" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <form action="<?= base_url('chart-of-accounts/accountHeads/delete/' . $account_head['id']) ?>" method="post">
      <?= csrf_field() ?>
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="deleteAccountHeadModalLabel<?= $account_head['id'] ?>">Delete Account Head</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          Are you sure you want to delete account head <strong><?= esc($account_head['name']) ?></strong>?
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
        <h1 class="h3 mb-0 text-gray-800">Chart of Accounts</h1>
    </div>

    <?php if (hasPermission('CanAddAccountHead')): ?>
    <div class="mb-3 text-right">
        <a href="#" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#addAccountHeadModal">+ Add Account Head</a>
    </div>
    <?php endif; ?>

    <div class="card shadow mb-4">
        <div class="card-body">
            <?php if (session()->getFlashdata('success')): ?>
            <div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
            <?php endif; ?>

            <div class="table-responsive">
                <table class="table table-bordered" id="accountHeadsTable">
                    <thead class="thead-dark">
                        <tr>
                            <th>ID</th>
                            <th>Code</th>
                            <th>Name</th>
                            <th>Type</th>
                            <th>Balance</th>
                            <?php if (hasPermission('CanUpdateAccountHead') || hasPermission('CanDeleteAccountHead')): ?>
                            <th>Actions</th>
                            <?php endif; ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($account_heads as $head): ?>
                        <tr>
                            <td><?= esc($head['id']) ?></td>
                            <td><?= esc($head['account_code']) ?></td>
                            <td><?= esc($head['name']) ?></td>
                            <td><?= esc($head['type']) ?></td>
                            <td><?= esc($head['opening_balance']) ?></td>
                            <?php if (hasPermission('CanUpdateAccountHead') || hasPermission('CanDeleteAccountHead')): ?>
                            <td>
                                <?php if (hasPermission('CanUpdateAccountHead')): ?>
                                <a href="#" class="btn btn-sm btn-info" data-toggle="modal" data-target="#editAccountHeadModal<?= $head['id'] ?>">Edit</a>
                                <?php endif; ?>
                                <?php if (hasPermission('CanDeleteAccountHead')): ?>
                                <a href="#" class="btn btn-sm btn-danger" data-toggle="modal" data-target="#deleteAccountHeadModal<?= $head['id'] ?>">Delete</a>
                                <?php endif; ?>
                            </td>
                            <?php endif; ?>
                        </tr>
                        <?php endforeach; ?>

                        <?php if (empty($account_heads)): ?>
                        <tr>
                            <td colspan="6" class="text-center">No account heads found.</td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Add Account Head Modal -->
<div class="modal fade" id="addAccountHeadModal" tabindex="-1" role="dialog" aria-labelledby="addAccountHeadModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <form action="<?= base_url('chart-of-accounts/accountHeads/add') ?>" method="post">
      <?= csrf_field() ?>
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="addAccountHeadModalLabel">Add Account Head</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">

          <div class="form-group">
            <label for="account_code">Account Code</label>
            <input type="text" class="form-control" id="account_code" name="account_code" required>
          </div>

          <div class="form-group">
            <label for="name">Account Name</label>
            <input type="text" class="form-control" id="name" name="name" required>
          </div>

          <div class="form-group">
            <label for="type">Account Type</label>
            <select class="form-control" id="type" name="type" required>
              <option value="">-- Select Type --</option>
              <option value="Cash-in-Hand">Cash-in-Hand</option>
              <option value="Bank Accounts">Bank Accounts</option>
              <option value="Current Liabilities/Loans">Current Liabilities/Loans</option>
              <option value="Capital Account">Capital Account</option>
              <option value="Cost of Goods Sales">Cost of Goods Sales</option>
              <option value="Expense">Expense</option>
              <option value="Direct Income">Direct Income</option>
              <option value="Stock Assets">Stock Assets</option>
              <option value="Fixed Assets">Fixed Assets</option>
              <option value="Current Assets">Current Assets</option>
              <option value="Creditors/Vendors">Creditors/Vendors</option>
              <option value="Customer">Customer</option>
              <option value="Employee">Employee</option>
              <option value="Milk Incentive">Milk Incentive</option>
            </select>
          </div>

          <div class="form-group">
            <label for="opening_balance">Opening Balance</label>
            <input type="number" step="0.01" class="form-control" id="opening_balance" name="opening_balance" required>
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
        $('#accountHeadsTable').DataTable();
    });
</script>

</body>

</html>