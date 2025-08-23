<?php

namespace App\Controllers;

use App\Models\MilkConsumptionModel;
use App\Models\FarmHeadModel;
use App\Models\TenantsModel;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class MilkConsumptionController extends BaseController
{
    public function milkConsumptionList()
    {
        $model         = new MilkConsumptionModel();
        $farmHeadModel = new FarmHeadModel();
        $tenantModel   = new TenantsModel();

        $selectedDate = $this->request->getGet('date') ?? date('Y-m-d');

        if (isSuperAdmin()) {
            $data['tenants'] = $tenantModel->findAll();

            $selectedTenantId = $this->request->getGet('tenant_id'); 

            if ($selectedTenantId) {
                $data['milk_consumption'] = $model
                ->select('milk_consumption.*, farm_head.head_name, tenants.name as tenant_name')
                ->join('farm_head', 'farm_head.id = milk_consumption.farm_head_id', 'left')
                ->join('tenants', 'tenants.id = milk_consumption.tenant_id', 'left')
                ->where('milk_consumption.tenant_id', $selectedTenantId)
                ->where('milk_consumption.date', $selectedDate)
                ->orderBy('milk_consumption.date', 'DESC')
                ->findAll();

                $data['farm_heads'] = $farmHeadModel->where('tenant_id', $selectedTenantId)->findAll();
            } else {
                $data['milk_consumption'] = $model
                ->select('milk_consumption.*, farm_head.head_name, tenants.name as tenant_name')
                ->join('farm_head', 'farm_head.id = milk_consumption.farm_head_id', 'left')
                ->join('tenants', 'tenants.id = milk_consumption.tenant_id', 'left')
                ->where('milk_consumption.date', $selectedDate)
                ->orderBy('milk_consumption.date', 'DESC')
                ->findAll();

                $data['farm_heads'] = $farmHeadModel->findAll();
            }

            $data['selectedTenantId'] = $selectedTenantId;
        } else {
            $tid = currentTenantId();

            $data['milk_consumption'] = $model
            ->select('milk_consumption.*, farm_head.head_name, tenants.name as tenant_name')
            ->join('farm_head', 'farm_head.id = milk_consumption.farm_head_id', 'left')
            ->join('tenants', 'tenants.id = milk_consumption.tenant_id', 'left')
            ->where('milk_consumption.tenant_id', $tid)
            ->where('milk_consumption.date', $selectedDate)
            ->orderBy('milk_consumption.date', 'DESC')
            ->findAll();

            $data['farm_heads'] = $farmHeadModel->where('tenant_id', $tid)->findAll();
        }

        $totalMilk = 0;
        foreach ($data['milk_consumption'] as $record) {
            $totalMilk += floatval($record['milk_litres']);
        }

        $data['total_milk']    = $totalMilk;
        $data['selected_date'] = $selectedDate;

        return view('milk-consumption/milkConsumption', $data);
    }

    public function addMilkConsumption()
    {
        $model = new MilkConsumptionModel();

        $data = [
            'date'         => $this->request->getPost('date'),
            'farm_head_id' => $this->request->getPost('farm_head_id'),
            'milk_litres'  => $this->request->getPost('milk_litres'),
            'tenant_id'    => isSuperAdmin()
            ? ($this->request->getPost('tenant_id') !== '' ? $this->request->getPost('tenant_id') : null)
            : currentTenantId(),
            'created_by'   => session()->get('user_id'),
            'updated_by'   => session()->get('user_id'),
            'created_at'   => date('Y-m-d H:i:s'),
            'updated_at'   => date('Y-m-d H:i:s'),
        ];

        if ($model->insert($data)) {
            return redirect()->to('/milk-consumption/milkConsumption')->with('success', 'Milk consumption added successfully.');
        } else {
            return redirect()->back()->with('error', 'Failed to add milk consumption.');
        }
    }

    public function editMilkConsumption($id)
    {
        $model = new MilkConsumptionModel();

        if (!isSuperAdmin()) {
            $exists = $model->where('id', $id)
            ->where('tenant_id', currentTenantId())
            ->first();
            if (!$exists) {
                return redirect()->back()->with('error', 'Unauthorized.');
            }
        }

        $data = [
            'date'         => $this->request->getPost('date'),
            'farm_head_id' => $this->request->getPost('farm_head_id'),
            'milk_litres'  => $this->request->getPost('milk_litres'),
            'updated_by'   => session()->get('user_id'),
            'updated_at'   => date('Y-m-d H:i:s'),
        ];

        if (isSuperAdmin() && $this->request->getPost('tenant_id')) {
            $data['tenant_id'] = (int) $this->request->getPost('tenant_id');
        }

        $model->update($id, $data);

        return redirect()->to('/milk-consumption/milkConsumption')->with('success', 'Milk consumption updated successfully.');
    }

    public function deleteMilkConsumption($id)
    {
        $model = new MilkConsumptionModel();

        if (!isSuperAdmin()) {
            $exists = $model->where('id', $id)
            ->where('tenant_id', currentTenantId())
            ->first();
            if (!$exists) {
                return redirect()->back()->with('error', 'Unauthorized.');
            }
        }

        if ($model->delete($id)) {
            return redirect()->to('/milk-consumption/milkConsumption')->with('success', 'Milk consumption deleted successfully.');
        } else {
            return redirect()->to('/milk-consumption/milkConsumption')->with('error', 'Failed to delete milk consumption.');
        }
    }

    public function exportMilkConsumption()
    {
        $model = new MilkConsumptionModel();

        $tenantId     = $this->request->getGet('tenant_id');
        $selectedDate = $this->request->getGet('date') ?? date('Y-m-d');

        if (isSuperAdmin()) {
            if (!empty($tenantId)) {
                $query = $model->where('milk_consumption.tenant_id', $tenantId);
            } else {
                $query = $model;
            }
        } else {
            $query = $model->where('milk_consumption.tenant_id', currentTenantId());
        }

        $records = $query
        ->select('milk_consumption.*, farm_head.head_name')
        ->join('farm_head', 'farm_head.id = milk_consumption.farm_head_id')
        ->where('milk_consumption.date', $selectedDate)
        ->orderBy('milk_consumption.date', 'DESC')
        ->findAll();

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

    // Headers
        $sheet->setCellValue('A1', 'ID')
        ->setCellValue('B1', 'Date')
        ->setCellValue('C1', 'Head Name')
        ->setCellValue('D1', 'Milk (Litres)')
        ->setCellValue('E1', 'Tenant ID');

        $row = 2;
        $totalMilk = 0;

        foreach ($records as $rec) {
            $sheet->setCellValue('A' . $row, $rec['id'])
            ->setCellValue('B' . $row, $rec['date'])
            ->setCellValue('C' . $row, $rec['head_name'])
            ->setCellValue('D' . $row, $rec['milk_litres'])
            ->setCellValue('E' . $row, $rec['tenant_id']);

            $totalMilk += floatval($rec['milk_litres']);
            $row++;
        }

    // Add Grand Total
        if (!empty($records)) {
            $sheet->setCellValue('C' . $row, 'Grand Total');
            $sheet->setCellValue('D' . $row, $totalMilk);
            $sheet->getStyle("C{$row}:D{$row}")->getFont()->setBold(true);
        }

        $writer = new Xlsx($spreadsheet);
        $filename = 'milk_consumption_' . $selectedDate . '_' . date('H-i-s') . '.xlsx';

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header("Content-Disposition: attachment; filename=\"$filename\"");
        $writer->save('php://output');
        exit;
    }
}