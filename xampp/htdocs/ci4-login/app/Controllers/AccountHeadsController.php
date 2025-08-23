<?php

namespace App\Controllers;
use App\Models\AccountHeadModel;
use CodeIgniter\Controller;
use App\Models\TenantsModel;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class AccountHeadsController extends BaseController
{
    public function accountHeadsList()
    {
        $accountHeadModel = new AccountHeadModel();
        $tenantModel      = new TenantsModel();

        if (isSuperAdmin()) {
            $data['tenants'] = $tenantModel->findAll();
            $selectedTenantId = $this->request->getGet('tenant_id');

            if ($selectedTenantId) {
                $data['account_heads'] = $accountHeadModel
                ->select('account_heads.*, tenants.name as tenant_name')
                ->join('tenants', 'tenants.id = account_heads.tenant_id', 'left')
                ->where('account_heads.tenant_id', $selectedTenantId)
                ->orderBy('account_heads.name')
                ->findAll();
            } else {
                $data['account_heads'] = $accountHeadModel
                ->select('account_heads.*, tenants.name as tenant_name')
                ->join('tenants', 'tenants.id = account_heads.tenant_id', 'left')
                ->orderBy('account_heads.name')
                ->findAll();
            }

            $data['selectedTenantId'] = $selectedTenantId;
        } else {
            $tid = currentTenantId();

            $data['account_heads'] = $accountHeadModel
            ->select('account_heads.*, tenants.name as tenant_name')
            ->join('tenants', 'tenants.id = account_heads.tenant_id', 'left')
            ->where('account_heads.tenant_id', $tid)
            ->orderBy('account_heads.name')
            ->findAll();
        }

        return view('chart-of-accounts/accountHeads', $data);
    }

    public function addAccountHeads()
    {
        $model = new AccountHeadModel();

        $data = [
            'account_code'     => $this->request->getPost('account_code'),
            'name'             => $this->request->getPost('name'),
            'type'             => $this->request->getPost('type'),
            'opening_balance'  => $this->request->getPost('opening_balance'),
            'description'      => $this->request->getPost('description'),
            'tenant_id'        => isSuperAdmin()
            ? ($this->request->getPost('tenant_id') !== '' ? $this->request->getPost('tenant_id') : null)
            : currentTenantId(),
            'created_by'       => session()->get('user_id'),
            'updated_by'       => session()->get('user_id'),
            'created_at'       => date('Y-m-d H:i:s'),
            'updated_at'       => date('Y-m-d H:i:s'),
        ];

        if ($model->insert($data)) {
            return redirect()->back()->with('success', 'Account Head added successfully.');
        } else {
            return redirect()->back()->with('error', 'Failed to add Account Head.');
        }
    }

    public function editAccountHeads($id)
    {
        $model = new AccountHeadModel();

        if (!isSuperAdmin()) {
            $exists = $model->where('id', $id)
            ->where('tenant_id', currentTenantId())
            ->first();
            if (!$exists) {
                return redirect()->back()->with('error', 'Unauthorized.');
            }
        }

        $data = [
            'account_code'    => $this->request->getPost('account_code'),
            'name'            => $this->request->getPost('name'),
            'type'            => $this->request->getPost('type'),
            'opening_balance' => $this->request->getPost('opening_balance'),
            'description'     => $this->request->getPost('description'),
            'updated_by'      => session()->get('user_id'),
            'updated_at'      => date('Y-m-d H:i:s'),
        ];

        if (isSuperAdmin() && $this->request->getPost('tenant_id')) {
            $data['tenant_id'] = (int) $this->request->getPost('tenant_id');
        }

        $model->update($id, $data);

        return redirect()->back()->with('success', 'Account Head updated successfully.');
    }

    public function deleteAccountHeads($id)
    {
        $model = new AccountHeadModel();

        if (!isSuperAdmin()) {
            $exists = $model->where('id', $id)
            ->where('tenant_id', currentTenantId())
            ->first();
            if (!$exists) {
                return redirect()->back()->with('error', 'Unauthorized.');
            }
        }

        if ($model->delete($id)) {
            return redirect()->back()->with('success', 'Account Head deleted successfully.');
        } else {
            return redirect()->back()->with('error', 'Failed to delete Account Head.');
        }
    }

    public function exportAccountHeads()
    {
        $model = new AccountHeadModel();
        $tenantId = $this->request->getGet('tenant_id');

        if (isSuperAdmin()) {
            if (!empty($tenantId)) {
                $accountHeads = $model->where('tenant_id', $tenantId)->orderBy('id', 'DESC')->findAll();
            } else {
                $accountHeads = $model->orderBy('id', 'DESC')->findAll();
            }
        } else {
            $accountHeads = $model->where('tenant_id', currentTenantId())->orderBy('id', 'DESC')->findAll();
        }

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $sheet->setCellValue('A1', 'ID')
        ->setCellValue('B1', 'Account Code')
        ->setCellValue('C1', 'Name')
        ->setCellValue('D1', 'Type')
        ->setCellValue('E1', 'Opening Balance')
        ->setCellValue('F1', 'Description')
        ->setCellValue('G1', 'Tenant ID');

        $row = 2;
        foreach ($accountHeads as $head) {
            $sheet->setCellValue('A' . $row, $head['id'])
            ->setCellValue('B' . $row, $head['account_code'])
            ->setCellValue('C' . $row, $head['name'])
            ->setCellValue('D' . $row, $head['type'])
            ->setCellValue('E' . $row, $head['opening_balance'])
            ->setCellValue('F' . $row, $head['description'])
            ->setCellValue('G' . $row, $head['tenant_id']);
            $row++;
        }

        $writer = new Xlsx($spreadsheet);
        $filename = 'Account_Heads_' . date('Y-m-d') . '.xlsx';

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header("Content-Disposition: attachment; filename=\"$filename\"");
        $writer->save('php://output');
        exit;
    }
}