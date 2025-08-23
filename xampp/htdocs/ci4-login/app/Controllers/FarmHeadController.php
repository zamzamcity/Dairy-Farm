<?php

namespace App\Controllers;

use App\Models\FarmHeadModel;
use App\Models\TenantsModel;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class FarmHeadController extends BaseController
{
    public function farmHeadList()
    {
        $model       = new FarmHeadModel();
        $tenantModel = new TenantsModel();

        if (isSuperAdmin()) {
            $data['tenants'] = $tenantModel->findAll();

            $selectedTenantId = $this->request->getGet('tenant_id'); 

            if ($selectedTenantId) {
                $data['farm_head'] = $model
                ->select('farm_head.*, tenants.name as tenant_name')
                ->join('tenants', 'tenants.id = farm_head.tenant_id', 'left')
                ->where('farm_head.tenant_id', $selectedTenantId)
                ->orderBy('farm_head.created_at', 'DESC')
                ->findAll();
            } else {
                $data['farm_head'] = $model
                ->select('farm_head.*, tenants.name as tenant_name')
                ->join('tenants', 'tenants.id = farm_head.tenant_id', 'left')
                ->orderBy('farm_head.created_at', 'DESC')
                ->findAll();
            }

            $data['selectedTenantId'] = $selectedTenantId;
        } else {
            $tid = currentTenantId();

            $data['farm_head'] = $model
            ->select('farm_head.*, tenants.name as tenant_name')
            ->join('tenants', 'tenants.id = farm_head.tenant_id', 'left')
            ->where('farm_head.tenant_id', $tid)
            ->orderBy('farm_head.created_at', 'DESC')
            ->findAll();
        }

        return view('milk-consumption/farmHead', $data);
    }

    public function addFarmHead()
    {
        $model = new FarmHeadModel();

        $data = [
            'head_name'   => $this->request->getPost('head_name'),
            'tenant_id'   => isSuperAdmin()
            ? ($this->request->getPost('tenant_id') !== '' ? $this->request->getPost('tenant_id') : null)
            : currentTenantId(),
            'created_by'  => session()->get('user_id'),
            'updated_by'  => session()->get('user_id'),
            'created_at'  => date('Y-m-d H:i:s'),
            'updated_at'  => date('Y-m-d H:i:s'),
        ];

        if ($model->insert($data)) {
            return redirect()->to('/milk-consumption/farmHead')->with('success', 'Farm head added successfully.');
        } else {
            return redirect()->back()->with('error', 'Failed to add farm head.');
        }
    }

    public function editFarmHead($id)
    {
        $model = new FarmHeadModel();

        if (!isSuperAdmin()) {
            $exists = $model->where('id', $id)
            ->where('tenant_id', currentTenantId())
            ->first();
            if (!$exists) {
                return redirect()->back()->with('error', 'Unauthorized.');
            }
        }

        $data = [
            'head_name'   => $this->request->getPost('head_name'),
            'updated_by'  => session()->get('user_id'),
            'updated_at'  => date('Y-m-d H:i:s'),
        ];

        if (isSuperAdmin() && $this->request->getPost('tenant_id')) {
            $data['tenant_id'] = (int) $this->request->getPost('tenant_id');
        }

        $model->update($id, $data);

        return redirect()->to('/milk-consumption/farmHead')->with('success', 'Farm head updated successfully.');
    }

    public function deleteFarmHead($id)
    {
        $model = new FarmHeadModel();

        if (!isSuperAdmin()) {
            $exists = $model->where('id', $id)
            ->where('tenant_id', currentTenantId())
            ->first();
            if (!$exists) {
                return redirect()->back()->with('error', 'Unauthorized.');
            }
        }

        if ($model->delete($id)) {
            return redirect()->to('/milk-consumption/farmHead')->with('success', 'Farm head deleted successfully.');
        } else {
            return redirect()->to('/milk-consumption/farmHead')->with('error', 'Failed to delete farm head.');
        }
    }

    public function exportFarmHead()
    {
        $model = new FarmHeadModel();

        $tenantId = $this->request->getGet('tenant_id');

        if (isSuperAdmin()) {
            if (!empty($tenantId)) {
                $farmHeads = $model->where('tenant_id', $tenantId)->orderBy('id', 'ASC')->findAll();
            } else {
                $farmHeads = $model->orderBy('id', 'ASC')->findAll();
            }
        } else {
            $farmHeads = $model->where('tenant_id', currentTenantId())->orderBy('id', 'ASC')->findAll();
        }

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

    // Headers
        $sheet->setCellValue('A1', 'ID')
        ->setCellValue('B1', 'Head Name')
        ->setCellValue('C1', 'Tenant ID');

        $row = 2;
        foreach ($farmHeads as $head) {
            $sheet->setCellValue('A' . $row, $head['id'])
            ->setCellValue('B' . $row, $head['head_name'])
            ->setCellValue('C' . $row, $head['tenant_id']);
            $row++;
        }

        $writer = new Xlsx($spreadsheet);
        $filename = 'farm_head_list_' . date('Y-m-d_H-i-s') . '.xlsx';

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header("Content-Disposition: attachment; filename=\"$filename\"");
        $writer->save('php://output');
        exit;
    }
}