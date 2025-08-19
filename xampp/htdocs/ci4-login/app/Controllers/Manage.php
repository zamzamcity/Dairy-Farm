<?php

namespace App\Controllers;

use App\Models\PermissionModel;
use App\Models\PermissionGroupModel;
use App\Models\PermissionGroupPermissionModel;
use App\Models\UserModel;
use \App\Models\AccountHeadModel;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class Manage extends BaseController
{
    public function employees()
    {
        $model = new UserModel();
        $groupModel = new PermissionGroupModel();

        $data['employees'] = $model->findAll();
        $data['permissionGroups'] = $groupModel->findAll();

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
        'password'             => $this->request->getPost('password'),
        'role'                 => $this->request->getPost('role'),
        'designation'          => $this->request->getPost('designation'),
        'salary_type'          => $this->request->getPost('salary_type'),
        'salary_amount'        => $this->request->getPost('salary_amount'),
        'joining_date'         => $this->request->getPost('joining_date'),
        'is_active'            => $this->request->getPost('is_active') ?? 1,
        'permission_group_id'  => $this->request->getPost('permission_group_id'),
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

    $accountCode = 'EMP-' . str_pad($newNumber, 3, '0', STR_PAD_LEFT);

    $accountHeadModel->insert([
        'account_code'     => $accountCode,
        'name'             => 'Employee - ' . $data['firstname'] . ' ' . $data['lastname'],
        'type'             => 'Employee',
        'opening_balance'  => 0,
        'description'      => 'Auto created on employee creation',
        'linked_user_id'   => $employeeId,
        'created_at'       => date('Y-m-d H:i:s'),
        'updated_at'       => date('Y-m-d H:i:s'),
    ]);

    return redirect()->back()->with('success', 'Employee and Account created successfully.');
}


public function editEmployee($id)
{
    $model = new UserModel();

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
        'permission_group_id' => $this->request->getPost('permission_group_id'),
        'updated_at' => date('Y-m-d H:i:s'),
    ];

    $model->update($id, $data);

    return redirect()->to('/manage/employees')->with('success', 'Employee updated successfully.');
}

public function deleteEmployee($id)
{
    $model = new UserModel();
    $model->delete($id);
    return redirect()->to('/manage/employees')->with('success', 'Employee deleted successfully.');
}

public function downloadEmployees()
    {
        $model = new UserModel();
        $employees = $model->findAll();

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
              ->setCellValue('J1', 'Active');

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
                  ->setCellValue('J'.$row, $emp['is_active'] ? 'Yes' : 'No');
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
    $model = new PermissionModel();
    $data['permissions'] = $model->findAll();

    return view('manage/permissions', $data);
}

public function addPermission()
{
    $model = new PermissionModel();

    $data = [
        'name' => $this->request->getPost('name'),
        'slug' => $this->request->getPost('slug'),
        'created_at' => date('Y-m-d H:i:s'),
        'updated_at' => date('Y-m-d H:i:s'),
    ];

    if ($model->insert($data)) {
        return redirect()->to('/manage/permissions')->with('success', 'Permission added successfully.');
    } else {
        return redirect()->back()->with('error', 'Failed to add permission. Slug might already exist.');
    }
}
public function updatePermission($id)
{
    $model = new PermissionModel();

    $data = [
        'name' => $this->request->getPost('name'),
        'slug' => $this->request->getPost('slug'),
        'updated_at' => date('Y-m-d H:i:s'),
    ];

    $model->update($id, $data);

    return redirect()->to('/manage/permissions')->with('success', 'Permission updated successfully.');
}
public function deletePermission($id)
{
    $model = new PermissionModel();
    if ($model->delete($id)) {
        return redirect()->to('/manage/permissions')->with('success', 'Permission deleted successfully.');
    } else {
        return redirect()->to('/manage/permissions')->with('error', 'Failed to delete permission.');
    }
}

public function downloadPermissions()
    {
        $model = new PermissionModel();
        $permissions = $model->findAll();

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Header
        $sheet->setCellValue('A1', 'ID')
              ->setCellValue('B1', 'Name')
              ->setCellValue('C1', 'Slug');

        // Data
        $row = 2;
        foreach ($permissions as $perm) {
            $sheet->setCellValue('A'.$row, $perm['id'])
                  ->setCellValue('B'.$row, $perm['name'])
                  ->setCellValue('C'.$row, $perm['slug']);
            $row++;
        }

        $writer = new Xlsx($spreadsheet);
        $filename = 'permissions.xlsx';

        header('Content-Type: application/vnd.ms-excel');
        header("Content-Disposition: attachment; filename=\"$filename\"");
        $writer->save('php://output');
        exit;
    }

public function permissionGroups()
{
    $groupModel = new PermissionGroupModel();
    $permModel = new PermissionModel();
    $linkModel = new PermissionGroupPermissionModel();

    $groups = $groupModel->findAll();

    foreach ($groups as &$group) {
        $assigned = $linkModel->where('permission_group_id', $group['id'])->findAll();
        $group['assigned_permissions'] = array_column($assigned, 'permission_id');
    }

    $data['groups'] = $groups;
    $data['permissions'] = $permModel->findAll();

    return view('manage/permission_groups', $data);
}
public function addPermissionGroup()
{
    $groupModel = new PermissionGroupModel();
    $groupPermModel = new PermissionGroupPermissionModel();

    $groupData = [
        'name' => $this->request->getPost('name'),
        'created_at' => date('Y-m-d H:i:s'),
        'updated_at' => date('Y-m-d H:i:s'),
    ];

    if ($groupModel->insert($groupData)) {
        $groupId = $groupModel->getInsertID();
        $selectedPermissions = $this->request->getPost('permissions') ?? [];

        foreach ($selectedPermissions as $permId) {
            $groupPermModel->insert([
                'permission_group_id' => $groupId,
                'permission_id' => $permId
            ]);
        }

        return redirect()->to('/manage/permission_groups')->with('success', 'Permission group added successfully.');
    }

    return redirect()->back()->with('error', 'Failed to add group.');
}
public function editPermissionGroup($id)
{
    $groupModel = new PermissionGroupModel();
    $linkModel = new PermissionGroupPermissionModel();

        // Update name
    $groupModel->update($id, [
        'name' => $this->request->getPost('name'),
        'updated_at' => date('Y-m-d H:i:s'),
    ]);

        // Update assigned permissions
    $linkModel->where('permission_group_id', $id)->delete();

    $permissions = $this->request->getPost('permissions') ?? [];

    foreach ($permissions as $permId) {
        $linkModel->insert([
            'permission_group_id' => $id,
            'permission_id' => $permId,
        ]);
    }

    return redirect()->to('/manage/permission_groups')->with('success', 'Permission group updated.');
}
public function deletePermissionGroup($id)
{
    $groupModel = new \App\Models\PermissionGroupModel();
    $pivotModel = new \App\Models\PermissionGroupPermissionModel();

        // First delete from pivot table
    $pivotModel->where('permission_group_id', $id)->delete();

        // Then delete the permission group itself
    $groupModel->delete($id);

    return redirect()->to('/manage/permission_groups')->with('success', 'Permission group deleted successfully.');
}

public function downloadPermissionGroups()
    {
        $groupModel = new PermissionGroupModel();
        $permModel = new PermissionModel();
        $linkModel = new PermissionGroupPermissionModel();

        $groups = $groupModel->findAll();
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
              ->setCellValue('C1', 'Permissions');

        // Data
        $row = 2;
        foreach ($groups as $grp) {
            $sheet->setCellValue('A'.$row, $grp['id'])
                  ->setCellValue('B'.$row, $grp['name'])
                  ->setCellValue('C'.$row, $grp['permissions']);
            $row++;
        }

        $writer = new Xlsx($spreadsheet);
        $filename = 'permission_groups.xlsx';

        header('Content-Type: application/vnd.ms-excel');
        header("Content-Disposition: attachment; filename=\"$filename\"");
        $writer->save('php://output');
        exit;
    }

}
