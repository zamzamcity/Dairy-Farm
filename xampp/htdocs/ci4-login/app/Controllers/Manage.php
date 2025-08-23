<?php

namespace App\Controllers;

use App\Models\PermissionModel;
use App\Models\PermissionGroupModel;
use App\Models\PermissionGroupPermissionModel;
use App\Models\UserModel;
use App\Models\TenantsModel;
use \App\Models\AccountHeadModel;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class Manage extends BaseController
{
    public function employees()
    {
        $userModel   = new UserModel();
        $tenantModel = new TenantsModel();
        $groupModel  = new PermissionGroupModel();

        if (isSuperAdmin()) {
            $data['tenants'] = $tenantModel->findAll();

            $selectedTenantId = $this->request->getGet('tenant_id'); 

            if ($selectedTenantId) {
                $data['employees'] = $userModel
                ->select('users.*, tenants.name as tenant_name')
                ->join('tenants', 'tenants.id = users.tenant_id', 'left')
                ->where('users.tenant_id', $selectedTenantId)
                ->findAll();

                $data['permissionGroups'] = $groupModel->where('tenant_id', $selectedTenantId)->findAll();
            } else {
                $data['employees'] = $userModel
                ->select('users.*, tenants.name as tenant_name')
                ->join('tenants', 'tenants.id = users.tenant_id', 'left')
                ->findAll();

                $data['permissionGroups'] = $groupModel->findAll();
            }

            $data['selectedTenantId'] = $selectedTenantId;
        } else {
            $tid = currentTenantId();

            $data['employees'] = $userModel
            ->select('users.*, tenants.name as tenant_name')
            ->join('tenants', 'tenants.id = users.tenant_id', 'left')
            ->where('users.tenant_id', $tid)
            ->findAll();

            $data['permissionGroups'] = $groupModel->where('tenant_id', $tid)->findAll();
        }

        return view('manage/employees', $data);
    }

    public function addEmployee()
    {
        $userModel = new UserModel();
        $accountHeadModel = new AccountHeadModel();

        $data = [
            'firstname'            => $this->request->getPost('firstname'),
            'lastname'             => $this->request->getPost('lastname'),
            'email'                => $this->request->getPost('email'),
            'password'             => password_hash($this->request->getPost('password'), PASSWORD_BCRYPT),
            'role'                 => $this->request->getPost('role'),
            'designation'          => $this->request->getPost('designation'),
            'salary_type'          => $this->request->getPost('salary_type'),
            'salary_amount'        => $this->request->getPost('salary_amount'),
            'joining_date'         => $this->request->getPost('joining_date'),
            'is_active'            => $this->request->getPost('is_active') ?? 1,
            'permission_group_id' => $this->request->getPost('permission_group_id') !== '' 
            ? $this->request->getPost('permission_group_id') 
            : null,
            'tenant_id'            => isSuperAdmin()
            ? ($this->request->getPost('tenant_id') !== '' ? $this->request->getPost('tenant_id') : null)
            : currentTenantId(),
            'created_by'           => session()->get('user_id'),
            'updated_by'           => session()->get('user_id'),
        ];

        $userModel->insert($data);
        $employeeId = $userModel->getInsertID();

        $lastAccount = $accountHeadModel
        ->where('account_code LIKE', 'EMP-%')
        ->orderBy('id', 'DESC')
        ->first();

        if ($lastAccount && preg_match('/EMP-(\d+)/', $lastAccount['account_code'], $matches)) {
            $lastNumber = (int)$matches[1];
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }
        $tid = $data['tenant_id'];
        $accountCode = 'EMP-' . str_pad($newNumber, 3, '0', STR_PAD_LEFT);

        $accountHeadModel->insert([
            'account_code'     => $accountCode,
            'name'             => 'Employee - ' . $data['firstname'] . ' ' . $data['lastname'],
            'type'             => 'Employee',
            'opening_balance'  => 0,
            'description'      => 'Auto created on employee creation',
            'linked_user_id'   => $employeeId,
            'tenant_id'        => $tid,
            'created_by'       => session()->get('user_id'),
            'updated_by'       => session()->get('user_id'),
            'created_at'       => date('Y-m-d H:i:s'),
            'updated_at'       => date('Y-m-d H:i:s'),
        ]);

        return redirect()->to('/manage/employees')->with('success', 'Employee and Account created successfully.');
    }

    public function editEmployee($id)
    {
        $model = new UserModel();

        if (!isSuperAdmin()) {
            $exists = $model->where('id', $id)
            ->where('tenant_id', currentTenantId())
            ->first();
            if (!$exists) {
                return redirect()->back()->with('error', 'Unauthorized.');
            }
        }

        $data = [
            'firstname' => $this->request->getPost('firstname'),
            'lastname' => $this->request->getPost('lastname'),
            'email' => $this->request->getPost('email'),
            'role' => $this->request->getPost('role'),
            'designation' => $this->request->getPost('designation'),
            'salary_type' => $this->request->getPost('salary_type'),
            'salary_amount' => $this->request->getPost('salary_amount'),
            'joining_date' => $this->request->getPost('joining_date'),
            'is_active' => $this->request->getPost('is_active') ?? 1,
            'permission_group_id' => $this->request->getPost('permission_group_id') !== '' 
            ? $this->request->getPost('permission_group_id') 
            : null,
            'updated_by' => session()->get('user_id'),
            'updated_at' => date('Y-m-d H:i:s'),
        ];

        if (isSuperAdmin() && $this->request->getPost('tenant_id')) {
            $data['tenant_id'] = (int) $this->request->getPost('tenant_id');
        }

        $model->update($id, $data);

        return redirect()->to('/manage/employees')->with('success', 'Employee updated successfully.');
    }

    public function deleteEmployee($id)
    {
        $model = new UserModel();
        $accountHeadModel = new AccountHeadModel();

        if (!isSuperAdmin()) {
            $exists = $model->where('id', $id)->where('tenant_id', currentTenantId())->first();
            if (!$exists) return redirect()->back()->with('error', 'Unauthorized.');
        }
        $accountHeadModel->where('linked_user_id', $id)->delete();
        $model->delete($id);
        return redirect()->to('/manage/employees')->with('success', 'Employee deleted successfully.');
    }

    public function downloadEmployees()
    {
        $model = new UserModel();
        $employees = $model->findAll();

        $tenantId = $this->request->getGet('tenant_id');

        if (isSuperAdmin()) {
            if(!empty($tenantId)){
                $employees = $model->where('tenant_id', $tenantId)->findAll();
            }
            else{
                $employees = $model->findAll();
            }
        } else {
            $employees = $model->where('tenant_id', currentTenantId())->findAll();
        }

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Header
        $sheet->setCellValue('A1', 'ID')
        ->setCellValue('B1', 'First Name')
        ->setCellValue('C1', 'Last Name')
        ->setCellValue('D1', 'Email')
        ->setCellValue('E1', 'Role')
        ->setCellValue('F1', 'Designation')
        ->setCellValue('G1', 'Salary Type')
        ->setCellValue('H1', 'Salary Amount')
        ->setCellValue('I1', 'Joining Date')
        ->setCellValue('J1', 'Active')
        ->setCellValue('K1', 'Tenant ID');

        // Data
        $row = 2;
        foreach ($employees as $emp) {
            $sheet->setCellValue('A'.$row, $emp['id'])
            ->setCellValue('B'.$row, $emp['firstname'])
            ->setCellValue('C'.$row, $emp['lastname'])
            ->setCellValue('D'.$row, $emp['email'])
            ->setCellValue('E'.$row, $emp['role'])
            ->setCellValue('F'.$row, $emp['designation'])
            ->setCellValue('G'.$row, $emp['salary_type'])
            ->setCellValue('H'.$row, $emp['salary_amount'])
            ->setCellValue('I'.$row, $emp['joining_date'])
            ->setCellValue('J'.$row, $emp['is_active'] ? 'Yes' : 'No')
            ->setCellValue('K'.$row, $emp['tenant_id']);
            $row++;
        }

        $writer = new Xlsx($spreadsheet);
        $filename = 'employees.xlsx';

        // Force download
        header('Content-Type: application/vnd.ms-excel');
        header("Content-Disposition: attachment; filename=\"$filename\"");
        $writer->save('php://output');
        exit;
    }

    public function permissions()
    {
        $permissionModel = new PermissionModel();
        $tenantModel     = new TenantsModel();

        if (isSuperAdmin()) {
            $data['tenants'] = $tenantModel->findAll();

            $selectedTenantId = $this->request->getGet('tenant_id'); 

            if ($selectedTenantId) {
                $data['permissions'] = $permissionModel
                ->select('permissions.*, tenants.name as tenant_name')
                ->join('tenants', 'tenants.id = permissions.tenant_id', 'left')
                ->where('permissions.tenant_id', $selectedTenantId)
                ->findAll();
            } else {
                $data['permissions'] = $permissionModel
                ->select('permissions.*, tenants.name as tenant_name')
                ->join('tenants', 'tenants.id = permissions.tenant_id', 'left')
                ->findAll();
            }

            $data['selectedTenantId'] = $selectedTenantId;
        } else {
            $tid = currentTenantId();

            $data['permissions'] = $permissionModel
            ->select('permissions.*, tenants.name as tenant_name')
            ->join('tenants', 'tenants.id = permissions.tenant_id', 'left')
            ->where('permissions.tenant_id', $tid)
            ->findAll();
        }

        return view('manage/permissions', $data);
    }

    public function addPermission()
    {
        $permissionModel = new PermissionModel();

        $data = [
            'name'       => $this->request->getPost('name'),
            'slug'       => $this->request->getPost('slug'),
            'tenant_id'  => isSuperAdmin()
            ? ($this->request->getPost('tenant_id') !== '' ? $this->request->getPost('tenant_id') : null)
            : currentTenantId(),
            'created_by' => session()->get('user_id'),
            'updated_by' => session()->get('user_id'),
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ];

        if ($permissionModel->insert($data)) {
            return redirect()->to('/manage/permissions')->with('success', 'Permission added successfully.');
        } else {
            return redirect()->back()->with('error', 'Failed to add permission. Slug might already exist.');
        }
    }

    public function updatePermission($id)
    {
        $model = new PermissionModel();

        if (!isSuperAdmin()) {
            $exists = $model->where('id', $id)
            ->where('tenant_id', currentTenantId())
            ->first();
            if (!$exists) {
                return redirect()->back()->with('error', 'Unauthorized.');
            }
        }

        $data = [
            'name'       => $this->request->getPost('name'),
            'slug'       => $this->request->getPost('slug'),
            'updated_by' => session()->get('user_id'),
            'updated_at' => date('Y-m-d H:i:s'),
        ];

        if (isSuperAdmin() && $this->request->getPost('tenant_id')) {
            $data['tenant_id'] = (int) $this->request->getPost('tenant_id');
        }

        $model->update($id, $data);

        return redirect()->to('/manage/permissions')->with('success', 'Permission updated successfully.');
    }

    public function deletePermission($id)
    {
        $model = new PermissionModel();

        if (!isSuperAdmin()) {
            $exists = $model->where('id', $id)
            ->where('tenant_id', currentTenantId())
            ->first();
            if (!$exists) {
                return redirect()->back()->with('error', 'Unauthorized.');
            }
        }

        if ($model->delete($id)) {
            return redirect()->to('/manage/permissions')->with('success', 'Permission deleted successfully.');
        } else {
            return redirect()->to('/manage/permissions')->with('error', 'Failed to delete permission.');
        }
    }

    public function downloadPermissions()
    {
        $model = new PermissionModel();

        $tenantId = $this->request->getGet('tenant_id');

        if (isSuperAdmin()) {
            if(!empty($tenantId)){
                $permissions = $model->where('tenant_id', $tenantId)->findAll();
            }
            else{
                $permissions = $model->findAll();
            }
        } else {
            $permissions = $model->where('tenant_id', currentTenantId())->findAll();
        }

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

    // Header
        $sheet->setCellValue('A1', 'ID')
        ->setCellValue('B1', 'Name')
        ->setCellValue('C1', 'Slug')
        ->setCellValue('D1', 'Tenant ID');

    // Data
        $row = 2;
        foreach ($permissions as $perm) {
            $sheet->setCellValue('A'.$row, $perm['id'])
            ->setCellValue('B'.$row, $perm['name'])
            ->setCellValue('C'.$row, $perm['slug'])
            ->setCellValue('D'.$row, $perm['tenant_id']);
            $row++;
        }

        $writer = new Xlsx($spreadsheet);
        $filename = 'permissions.xlsx';

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header("Content-Disposition: attachment; filename=\"$filename\"");
        $writer->save('php://output');
        exit;
    }

    public function permissionGroups()
    {
        $groupModel = new PermissionGroupModel();
        $permModel  = new PermissionModel();
        $linkModel  = new PermissionGroupPermissionModel();
        $tenantModel = new TenantsModel();

        if (isSuperAdmin()) {
            $data['tenants'] = $tenantModel->findAll();

            $selectedTenantId = $this->request->getGet('tenant_id');

            if ($selectedTenantId) {
                $groups = $groupModel
                ->select('permission_groups.*, tenants.name as tenant_name')
                ->join('tenants', 'tenants.id = permission_groups.tenant_id', 'left')
                ->where('permission_groups.tenant_id', $selectedTenantId)
                ->findAll();

                $data['permissions'] = $permModel
                ->where('tenant_id', $selectedTenantId)
                ->findAll();
            } else {
                $groups = $groupModel
                ->select('permission_groups.*, tenants.name as tenant_name')
                ->join('tenants', 'tenants.id = permission_groups.tenant_id', 'left')
                ->findAll();

                $data['permissions'] = $permModel->findAll();
            }

            $data['selectedTenantId'] = $selectedTenantId;
        } else {
            $tid = currentTenantId();

            $groups = $groupModel
            ->select('permission_groups.*, tenants.name as tenant_name')
            ->join('tenants', 'tenants.id = permission_groups.tenant_id', 'left')
            ->where('permission_groups.tenant_id', $tid)
            ->findAll();

            $data['permissions'] = $permModel->where('tenant_id', $tid)->findAll();
        }

        foreach ($groups as &$group) {
            $assigned = $linkModel->where('permission_group_id', $group['id'])->findAll();
            $group['assigned_permissions'] = array_column($assigned, 'permission_id');
        }

        $data['groups'] = $groups;

        return view('manage/permission_groups', $data);
    }
    public function addPermissionGroup()
    {
        $groupModel     = new PermissionGroupModel();
        $groupPermModel = new PermissionGroupPermissionModel();

        $groupData = [
            'name'       => $this->request->getPost('name'),
            'tenant_id'  => isSuperAdmin()
            ? ($this->request->getPost('tenant_id') !== '' ? $this->request->getPost('tenant_id') : null)
            : currentTenantId(),
            'created_by' => session()->get('user_id'),
            'updated_by' => session()->get('user_id'),
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ];

        if ($groupModel->insert($groupData)) {
            $groupId = $groupModel->getInsertID();
            $selectedPermissions = $this->request->getPost('permissions') ?? [];

            foreach ($selectedPermissions as $permId) {
                $groupPermModel->insert([
                    'permission_group_id' => $groupId,
                    'permission_id'       => $permId,
                    'tenant_id'           => $groupData['tenant_id'],
                    'created_by'          => session()->get('user_id'),
                    'updated_by'          => session()->get('user_id'),
                    'created_at'          => date('Y-m-d H:i:s'),
                    'updated_at'          => date('Y-m-d H:i:s'),
                ]);
            }

            return redirect()->to('/manage/permission_groups')->with('success', 'Permission group added successfully.');
        }

        return redirect()->back()->with('error', 'Failed to add group.');
    }

    public function editPermissionGroup($id)
    {
        $groupModel = new PermissionGroupModel();
        $linkModel  = new PermissionGroupPermissionModel();

        if (!isSuperAdmin()) {
            $exists = $groupModel->where('id', $id)
            ->where('tenant_id', currentTenantId())
            ->first();

            if (!$exists) {
                return redirect()->back()->with('error', 'Unauthorized.');
            }
        }

        $data = [
            'name'       => $this->request->getPost('name'),
            'updated_by' => session()->get('user_id'),
            'updated_at' => date('Y-m-d H:i:s'),
        ];

        if (isSuperAdmin() && $this->request->getPost('tenant_id')) {
            $data['tenant_id'] = (int) $this->request->getPost('tenant_id');
        }

        $groupModel->update($id, $data);

        $linkModel->where('permission_group_id', $id)->delete();

        $permissions = $this->request->getPost('permissions') ?? [];
        foreach ($permissions as $permId) {
            $linkModel->insert([
                'permission_group_id' => $id,
                'permission_id'       => $permId,
            ]);
        }

        return redirect()->to('/manage/permission_groups')->with('success', 'Permission group updated successfully.');
    }

    public function deletePermissionGroup($id)
    {
        $groupModel = new \App\Models\PermissionGroupModel();
        $pivotModel = new \App\Models\PermissionGroupPermissionModel();

        if (!isSuperAdmin()) {
            $exists = $model->where('id', $id)
            ->where('tenant_id', currentTenantId())
            ->first();
            if (!$exists) {
                return redirect()->back()->with('error', 'Unauthorized.');
            }
        }

        $pivotModel->where('permission_group_id', $id)->delete();

        $groupModel->delete($id);

        return redirect()->to('/manage/permission_groups')->with('success', 'Permission group deleted successfully.');
    }

    public function downloadPermissionGroups()
    {
        $groupModel = new PermissionGroupModel();
        $permModel = new PermissionModel();
        $linkModel = new PermissionGroupPermissionModel();

        $tenantId = $this->request->getGet('tenant_id');

        if (isSuperAdmin()) {
            if(!empty($tenantId)){
                $groups = $groupModel->where('tenant_id', $tenantId)->findAll();
            }
            else{
                $groups = $groupModel->findAll();
            }
        } else {
            $groups = $groupModel->where('tenant_id', currentTenantId())->findAll();
        }

        foreach ($groups as &$group) {
            $assigned = $linkModel->where('permission_group_id', $group['id'])->findAll();
            $permNames = [];
            foreach ($assigned as $a) {
                $perm = $permModel->find($a['permission_id']);
                if ($perm) $permNames[] = $perm['name'];
            }
            $group['permissions'] = implode(", ", $permNames);
        }

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Header
        $sheet->setCellValue('A1', 'ID')
        ->setCellValue('B1', 'Group Name')
        ->setCellValue('C1', 'Permissions')
        ->setCellValue('D1', 'Tenant ID');

        // Data
        $row = 2;
        foreach ($groups as $grp) {
            $sheet->setCellValue('A'.$row, $grp['id'])
            ->setCellValue('B'.$row, $grp['name'])
            ->setCellValue('C'.$row, $grp['permissions'])
            ->setCellValue('D'.$row, $grp['tenant_id']);
            $row++;
        }

        $writer = new Xlsx($spreadsheet);
        $filename = 'permission_groups.xlsx';

        header('Content-Type: application/vnd.ms-excel');
        header("Content-Disposition: attachment; filename=\"$filename\"");
        $writer->save('php://output');
        exit;
    }
    
    public function tenants()
    {
        $tenantModel = new TenantsModel();
        $data['tenants'] = $tenantModel->findAll();

        return view('manage/tenants', $data);
    }

    public function addTenant()
    {
        $tenantModel = new TenantsModel();

        $data = [
            'name'       => $this->request->getPost('name'),
            'status'     => $this->request->getPost('status') ?? 'active',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ];

        $tenantModel->insert($data);

        return redirect()->back()->with('success', 'Tenant created successfully.');
    }

    public function editTenant($id)
    {
        $tenantModel = new TenantsModel();

        $data = [
            'name'       => $this->request->getPost('name'),
            'status'     => $this->request->getPost('status'),
            'updated_at' => date('Y-m-d H:i:s'),
        ];

        $tenantModel->update($id, $data);

        return redirect()->to('/manage/tenants')->with('success', 'Tenant updated successfully.');
    }

    public function deleteTenant($id)
    {
        $tenantModel = new TenantsModel();
        $tenantModel->delete($id);

        return redirect()->to('/manage/tenants')->with('success', 'Tenant deleted successfully.');
    }

    public function downloadTenants()
    {
        $tenantModel = new TenantsModel();
        $tenants = $tenantModel->findAll();

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Header
        $sheet->setCellValue('A1', 'ID')
        ->setCellValue('B1', 'Name')
        ->setCellValue('C1', 'Status')
        ->setCellValue('D1', 'Created At')
        ->setCellValue('E1', 'Updated At');

        // Data
        $row = 2;
        foreach ($tenants as $tenant) {
            $sheet->setCellValue('A'.$row, $tenant['id'])
            ->setCellValue('B'.$row, $tenant['name'])
            ->setCellValue('C'.$row, ucfirst($tenant['status']))
            ->setCellValue('D'.$row, $tenant['created_at'])
            ->setCellValue('E'.$row, $tenant['updated_at']);
            $row++;
        }

        $writer = new Xlsx($spreadsheet);
        $filename = 'tenants.xlsx';

        // Force download
        header('Content-Type: application/vnd.ms-excel');
        header("Content-Disposition: attachment; filename=\"$filename\"");
        $writer->save('php://output');
        exit;
    }
}
