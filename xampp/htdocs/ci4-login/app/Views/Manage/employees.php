<!DOCTYPE html>
<html lang="en">

<?= $this->include('components/head') ?>

<!-- Edit Modal -->
<?php foreach ($employees as $employee): ?>
    <div class="modal fade" id="editEmployeeModal<?= $employee['id'] ?>" tabindex="-1" role="dialog" aria-labelledby="editEmployeeModalLabel<?= $employee['id'] ?>" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <form action="<?= base_url('manage/employees/edit/' . $employee['id']) ?>" method="post">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editEmployeeModalLabel<?= $employee['id'] ?>">Edit Employee</h5>
                        <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                    </div>

                    <div class="modal-body">
                        <div class="form-group">
                            <label for="firstname<?= $employee['id'] ?>">First Name *</label>
                            <input type="text" class="form-control" id="firstname<?= $employee['id'] ?>" name="firstname" value="<?= esc($employee['firstname']) ?>" required>
                        </div>

                        <div class="form-group">
                            <label for="lastname<?= $employee['id'] ?>">Last Name</label>
                            <input type="text" class="form-control" id="lastname<?= $employee['id'] ?>" name="lastname" value="<?= esc($employee['lastname']) ?>">
                        </div>

                        <div class="form-group">
                            <label for="email<?= $employee['id'] ?>">Email *</label>
                            <input type="email" class="form-control" id="email<?= $employee['id'] ?>" name="email" value="<?= esc($employee['email']) ?>" required>
                        </div>

                        <div class="form-group">
                            <label for="role<?= $employee['id'] ?>">Role *</label>
                            <select name="role" id="role<?= $employee['id'] ?>" class="form-control roleSelect" required>
                                <option value="user" <?= $employee['role'] === 'user' ? 'selected' : '' ?>>User</option>
                                <option value="admin" <?= $employee['role'] === 'admin' ? 'selected' : '' ?>>Admin</option>
                                <?php if (hasPermission('CanViewTenants')): ?>
                                    <option value="superadmin" <?= $employee['role'] === 'superadmin' ? 'selected' : '' ?>>Super Admin</option>
                                <?php endif; ?>
                            </select>
                        </div>

                        <!-- ✅ Tenant Dropdown (Super Admin Only) -->
                        <?php if (isSuperAdmin()): ?>
                            <div class="form-group tenantSelectWrapper" id="tenantSelectWrapper<?= $employee['id'] ?>">
                                <label for="tenant_id<?= $employee['id'] ?>">Tenant *</label>
                                <select name="tenant_id" id="tenant_id<?= $employee['id'] ?>" class="form-control">
                                    <option value="">Select Tenant</option>
                                    <?php foreach ($tenants as $tenant): ?>
                                        <option value="<?= $tenant['id'] ?>" <?= $employee['tenant_id'] == $tenant['id'] ? 'selected' : '' ?>>
                                            <?= esc($tenant['name']) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        <?php endif; ?>

                        <div class="form-group">
                            <label for="designation<?= $employee['id'] ?>">Designation *</label>
                            <input type="text" class="form-control" id="designation<?= $employee['id'] ?>" name="designation" value="<?= esc($employee['designation']) ?>" required>
                        </div>

                        <div class="form-group">
                            <label for="salary_type<?= $employee['id'] ?>">Salary Type *</label>
                            <select name="salary_type" id="salary_type<?= $employee['id'] ?>" class="form-control">
                                <option value="">-- Select Type --</option>
                                <option value="monthly" <?= $employee['salary_type'] === 'monthly' ? 'selected' : '' ?>>Monthly</option>
                                <option value="daily" <?= $employee['salary_type'] === 'daily' ? 'selected' : '' ?>>Daily</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="salary_amount<?= $employee['id'] ?>">Salary Amount *</label>
                            <input type="number" class="form-control" id="salary_amount<?= $employee['id'] ?>" name="salary_amount" value="<?= esc($employee['salary_amount']) ?>" step="0.01">
                        </div>

                        <div class="form-group">
                            <label for="joining_date<?= $employee['id'] ?>">Joining Date *</label>
                            <input type="date" class="form-control" id="joining_date<?= $employee['id'] ?>" name="joining_date" value="<?= esc($employee['joining_date']) ?>">
                        </div>

                        <div class="form-group">
                            <label for="permission_group_id<?= $employee['id'] ?>">Permission Group</label>
                            <select name="permission_group_id" id="permission_group_id<?= $employee['id'] ?>" class="form-control">
                                <option value="">-- Select Group --</option>
                                <?php foreach ($permissionGroups as $group): ?>
                                    <option value="<?= $group['id'] ?>" <?= $employee['permission_group_id'] == $group['id'] ? 'selected' : '' ?>>
                                        <?= esc($group['name']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="status<?= $employee['id'] ?>">Status *</label>
                            <select name="is_active" id="status<?= $employee['id'] ?>" class="form-control" required>
                                <option value="1" <?= $employee['is_active'] == 1 ? 'selected' : '' ?>>Active</option>
                                <option value="0" <?= $employee['is_active'] == 0 ? 'selected' : '' ?>>Inactive</option>
                            </select>
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

<!-- ✅ JS for hiding tenant dropdown when role=superadmin -->
<script>
    document.addEventListener("DOMContentLoaded", function() {
        document.querySelectorAll(".roleSelect").forEach(function(select) {
            select.addEventListener("change", function() {
                const employeeId = this.id.replace("role", "");
                const tenantWrapper = document.getElementById("tenantSelectWrapper" + employeeId);
                if (tenantWrapper) {
                    if (this.value === "superadmin") {
                        tenantWrapper.style.display = "none";
                    } else {
                        tenantWrapper.style.display = "block";
                    }
                }
            });

        // Run on page load to set correct visibility
            select.dispatchEvent(new Event("change"));
        });
    });
</script>

<!-- Delete Modal -->
<?php foreach ($employees as $employee): ?>
    <div class="modal fade" id="deleteEmployeeModal<?= $employee['id'] ?>" tabindex="-1" role="dialog" aria-labelledby="deleteEmployeeModalLabel<?= $employee['id'] ?>" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <form action="<?= base_url('manage/employees/delete/' . $employee['id']) ?>" method="post">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title">Confirm Delete</h5>
              <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
          </div>
          <div class="modal-body">
              Are you sure you want to delete <strong><?= esc($employee['firstname'] . ' ' . $employee['lastname']) ?></strong>?
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

    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Employees</h1>
    </div>

    <?php if (isSuperAdmin()): ?>
        <form method="get" class="form-inline mb-4">
            <label class="mr-2">Tenant:</label>
            <select name="tenant_id" class="form-control mr-2">
                <option value="">-- All Tenants --</option>
                <?php foreach ($tenants as $tenant): ?>
                    <option value="<?= esc($tenant['id']) ?>" 
                        <?= ($selectedTenantId == $tenant['id']) ? 'selected' : '' ?>>
                        <?= esc($tenant['name']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <button type="submit" class="btn btn-primary">Filter</button>
        </form>
    <?php endif; ?>

    <div class="mb-3 text-right">
        <a href="<?= base_url('manage/employees/export') . (!empty($selectedTenantId) ? '?tenant_id='.$selectedTenantId : '') ?>" class="btn btn-success mb-3">
            <i class="fas fa-file-excel"></i> Download Excel
        </a>
    </div>

    <!-- Add Employee Button -->
    <div class="mb-3 text-right">
        <a href="#" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#addEmployeeModal">+ Add Employee</a>
    </div>

    <!-- Employee Table -->
    <div class="card shadow mb-4">
        <div class="card-body">
            <?php if (session()->getFlashdata('success')): ?>
            <div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
        <?php endif; ?>

        <div class="table-responsive">
            <table class="table table-bordered table-sm" id="employeeTable">
                <thead class="thead-dark">
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Designation</th>
                        <th>Salary Type</th>
                        <th>Salary Amount</th>
                        <th>Role</th>
                        <th>Tenant</th>
                        <th>Joining Date</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($employees)) : ?>
                        <?php foreach ($employees as $emp): ?>
                            <tr>
                                <td><?= $emp['id'] ?></td>
                                <td><?= esc($emp['firstname']) . ' ' . esc($emp['lastname']) ?></td>
                                <td><?= esc($emp['email']) ?></td>
                                <td><?= esc($emp['designation']) ?></td>
                                <td><?= esc(ucfirst($emp['salary_type'])) ?></td>
                                <td><?= number_format($emp['salary_amount']) ?></td>
                                <td><?= esc(ucfirst($emp['role'])) ?></td>
                                <td><?= esc($emp['tenant_name'] ?? 'N/A') ?></td>
                                <td><?= esc($emp['joining_date']) ?></td>
                                <td>
                                    <?php if ($emp['is_active']): ?>
                                        <span class="badge badge-success">Active</span>
                                    <?php else: ?>
                                        <span class="badge badge-danger">Inactive</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <a href="#" class="btn btn-sm btn-info" data-toggle="modal" data-target="#editEmployeeModal<?= $emp['id'] ?>">Edit</a>
                                    <a href="#" class="btn btn-sm btn-danger" data-toggle="modal" data-target="#deleteEmployeeModal<?= $emp['id'] ?>">Delete</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
</div>


<!-- /.container-fluid -->

<!-- Add Employee Modal -->
<div class="modal fade" id="addEmployeeModal" tabindex="-1" role="dialog" aria-labelledby="addEmployeeModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <form action="<?= base_url('manage/employees/add') ?>" method="post">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Add Employee</h5>
          <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
      </div>

      <div class="modal-body">
          <div class="form-group">
            <label>First Name *</label>
            <input type="text" name="firstname" class="form-control" required>
        </div>

        <div class="form-group">
            <label>Last Name</label>
            <input type="text" name="lastname" class="form-control">
        </div>

        <div class="form-group">
            <label>Email *</label>
            <input type="email" name="email" class="form-control" required>
        </div>

        <div class="form-group">
            <label>Password *</label>
            <input type="password" name="password" class="form-control" required>
        </div>

        <div class="form-group">
            <label>Role *</label>
            <select name="role" id="roleSelect" class="form-control" required>
              <option value="user">User</option>
              <option value="admin">Admin</option>
              <?php if (hasPermission('CanViewTenants')): ?>
                <option value="superadmin">Super Admin</option>
            <?php endif; ?>
        </select>
    </div>

    <!-- Tenant Dropdown: only visible for super admins -->
    <?php if (isSuperAdmin()): ?>
      <div class="form-group" id="tenantSelectWrapper">
        <label>Tenant *</label>
        <select name="tenant_id" class="form-control">
          <option value="">Select Tenant</option>
          <?php foreach ($tenants as $tenant): ?>
            <option value="<?= $tenant['id'] ?>"><?= esc($tenant['name']) ?></option>
        <?php endforeach; ?>
    </select>
</div>
<?php endif; ?>

<div class="form-group">
    <label>Designation *</label>
    <input type="text" name="designation" class="form-control" required>
</div>

<div class="form-group">
    <label>Salary Type *</label>
    <select name="salary_type" class="form-control" required>
      <option value="monthly">Monthly</option>
      <option value="daily">Daily</option>
  </select>
</div>

<div class="form-group">
    <label>Salary Amount *</label>
    <input type="number" name="salary_amount" class="form-control" required>
</div>

<div class="form-group">
    <label>Joining Date *</label>
    <input type="date" name="joining_date" class="form-control" required>
</div>

<div class="form-group">
    <label>Permission Group</label>
    <select name="permission_group_id" class="form-control">
      <option value="">Select Group</option>
      <?php foreach ($permissionGroups as $group): ?>
        <option value="<?= $group['id'] ?>"><?= esc($group['name']) ?></option>
    <?php endforeach; ?>
</select>
</div>

<div class="form-group">
    <label>Status *</label>
    <select name="is_active" class="form-control" required>
      <option value="1">Active</option>
      <option value="0">Inactive</option>
  </select>
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

<!-- JS: Hide Tenant Dropdown if role = Super Admin -->
<script>
  document.addEventListener("DOMContentLoaded", function() {
    const roleSelect = document.getElementById("roleSelect");
    const tenantWrapper = document.getElementById("tenantSelectWrapper");

    if (roleSelect && tenantWrapper) {
      function toggleTenantDropdown() {
        if (roleSelect.value === "superadmin") {
          tenantWrapper.style.display = "none";
      } else {
          tenantWrapper.style.display = "block";
      }
  }
      toggleTenantDropdown(); // run on load
      roleSelect.addEventListener("change", toggleTenantDropdown);
  }
});
</script>

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
        $('#employeeTable').DataTable();
    });
</script>

</body>

</html>