<?php

namespace App\Controllers;

use App\Models\PermissionModel;
use App\Models\PermissionGroupModel;
use App\Models\PermissionGroupPermissionModel;
use App\Models\UserModel;

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
        $model = new UserModel();

        $data = [
            'firstname' => $this->request->getPost('firstname'),
            'lastname' => $this->request->getPost('lastname'),
            'email' => $this->request->getPost('email'),
            'password' => $this->request->getPost('password'),
            'role' => $this->request->getPost('role'),
            'permission_group_id' => $this->request->getPost('permission_group_id'),
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ];

        $model->insert($data);
        return redirect()->to('/manage/employees')->with('success', 'Employee added successfully.');
    }
    public function editEmployee($id)
    {
        $model = new UserModel();

        $data = [
            'firstname' => $this->request->getPost('firstname'),
            'lastname' => $this->request->getPost('lastname'),
            'email' => $this->request->getPost('email'),
            'role' => $this->request->getPost('role'),
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

}
