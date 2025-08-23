<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\MedicineConsumptionModel;
use App\Models\StockRegistrationModel;
use App\Models\TenantsModel;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class MedicineConsumptionController extends BaseController
{
    public function medicineConsumptionList()
    {
        $model       = new MedicineConsumptionModel();
        $stockModel  = new StockRegistrationModel();
        $tenantModel = new TenantsModel();

        $selectedDate = $this->request->getGet('date') ?? date('Y-m-d');

        if (isSuperAdmin()) {
            $data['tenants'] = $tenantModel->findAll();

            $selectedTenantId = $this->request->getGet('tenant_id');

            if ($selectedTenantId) {
                $data['medicine_consumptions'] = $model
                ->select('medicine_consumption.*, tenants.name as tenant_name, stock_registration.product_name')
                ->join('stock_registration', 'stock_registration.id = medicine_consumption.product_id', 'left')
                ->join('tenants', 'tenants.id = medicine_consumption.tenant_id', 'left')
                ->where('medicine_consumption.tenant_id', $selectedTenantId)
                ->where('medicine_consumption.date', $selectedDate)
                ->orderBy('medicine_consumption.date', 'DESC')
                ->findAll();

                $data['medicine_products'] = $stockModel
                ->select('stock_registration.*')
                ->join('stock_heads', 'stock_heads.id = stock_registration.head_id')
                ->where('stock_heads.name', 'Medication')
                ->where('stock_registration.tenant_id', $selectedTenantId)
                ->findAll();
            } else {
                $data['medicine_consumptions'] = $model
                ->select('medicine_consumption.*, tenants.name as tenant_name, stock_registration.product_name')
                ->join('stock_registration', 'stock_registration.id = medicine_consumption.product_id', 'left')
                ->join('tenants', 'tenants.id = medicine_consumption.tenant_id', 'left')
                ->where('medicine_consumption.date', $selectedDate)
                ->orderBy('medicine_consumption.date', 'DESC')
                ->findAll();

                $data['medicine_products'] = $stockModel
                ->select('stock_registration.*')
                ->join('stock_heads', 'stock_heads.id = stock_registration.head_id')
                ->where('stock_heads.name', 'Medication')
                ->findAll();
            }

            $data['selectedTenantId'] = $selectedTenantId;
        } else {
            $tid = currentTenantId();

            $data['medicine_consumptions'] = $model
            ->select('medicine_consumption.*, tenants.name as tenant_name, stock_registration.product_name')
            ->join('stock_registration', 'stock_registration.id = medicine_consumption.product_id', 'left')
            ->join('tenants', 'tenants.id = medicine_consumption.tenant_id', 'left')
            ->where('medicine_consumption.tenant_id', $tid)
            ->where('medicine_consumption.date', $selectedDate)
            ->orderBy('medicine_consumption.date', 'DESC')
            ->findAll();

            $data['medicine_products'] = $stockModel
            ->select('stock_registration.*')
            ->join('stock_heads', 'stock_heads.id = stock_registration.head_id')
            ->where('stock_heads.name', 'Medication')
            ->where('stock_registration.tenant_id', $tid)
            ->findAll();
        }

        $totalQuantity = 0;
        foreach ($data['medicine_consumptions'] as $record) {
            $totalQuantity += floatval($record['quantity']);
        }

        $data['total_quantity'] = $totalQuantity;
        $data['selected_date']  = $selectedDate;

        return view('medicine-consumption/medicineConsumption', $data);
    }

    public function addMedicineConsumption()
    {
        $model = new MedicineConsumptionModel();

        $data = [
            'product_id' => $this->request->getPost('product_id'),
            'quantity'   => $this->request->getPost('quantity'),
            'date'       => $this->request->getPost('date'),
            'tenant_id'  => isSuperAdmin()
            ? ($this->request->getPost('tenant_id') !== '' ? $this->request->getPost('tenant_id') : null)
            : currentTenantId(),
            'created_by' => session()->get('user_id'),
            'updated_by' => session()->get('user_id'),
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ];

        if ($model->insert($data)) {
            return redirect()->to('/medicine-consumption/medicineConsumption')->with('success', 'Medicine consumption added successfully.');
        } else {
            return redirect()->back()->with('error', 'Failed to add medicine consumption.');
        }
    }

    public function editMedicineConsumption($id)
    {
        $model = new MedicineConsumptionModel();

        if (!isSuperAdmin()) {
            $exists = $model->where('id', $id)
            ->where('tenant_id', currentTenantId())
            ->first();
            if (!$exists) {
                return redirect()->back()->with('error', 'Unauthorized.');
            }
        }

        $data = [
            'product_id' => $this->request->getPost('product_id'),
            'quantity'   => $this->request->getPost('quantity'),
            'date'       => $this->request->getPost('date'),
            'updated_by' => session()->get('user_id'),
            'updated_at' => date('Y-m-d H:i:s'),
        ];

        if (isSuperAdmin() && $this->request->getPost('tenant_id')) {
            $data['tenant_id'] = (int) $this->request->getPost('tenant_id');
        }

        $model->update($id, $data);

        return redirect()->to('/medicine-consumption/medicineConsumption')->with('success', 'Medicine consumption updated successfully.');
    }

    public function deleteMedicineConsumption($id)
    {
        $model = new MedicineConsumptionModel();

        if (!isSuperAdmin()) {
            $exists = $model->where('id', $id)
            ->where('tenant_id', currentTenantId())
            ->first();
            if (!$exists) {
                return redirect()->back()->with('error', 'Unauthorized.');
            }
        }

        if ($model->delete($id)) {
            return redirect()->to('/medicine-consumption/medicineConsumption')->with('success', 'Medicine consumption deleted.');
        } else {
            return redirect()->to('/medicine-consumption/medicineConsumption')->with('error', 'Failed to delete medicine consumption.');
        }
    }

    public function downloadExcel()
    {
        $model = new MedicineConsumptionModel();
        $tenantId = $this->request->getGet('tenant_id');
        $selectedDate = $this->request->getGet('date') ?? date('Y-m-d');

        if (isSuperAdmin()) {
            if (!empty($tenantId)) {
                $consumptions = $model
                ->select('medicine_consumption.*, stock_registration.product_name')
                ->join('stock_registration', 'stock_registration.id = medicine_consumption.product_id')
                ->where('medicine_consumption.tenant_id', $tenantId)
                ->where('medicine_consumption.date', $selectedDate)
                ->findAll();
            } else {
                $consumptions = $model
                ->select('medicine_consumption.*, stock_registration.product_name')
                ->join('stock_registration', 'stock_registration.id = medicine_consumption.product_id')
                ->where('medicine_consumption.date', $selectedDate)
                ->findAll();
            }
        } else {
            $consumptions = $model
            ->select('medicine_consumption.*, stock_registration.product_name')
            ->join('stock_registration', 'stock_registration.id = medicine_consumption.product_id')
            ->where('medicine_consumption.tenant_id', currentTenantId())
            ->where('medicine_consumption.date', $selectedDate)
            ->findAll();
        }

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

    // Headers
        $sheet->setCellValue('A1', 'ID')
        ->setCellValue('B1', 'Tenant ID')
        ->setCellValue('C1', 'Date')
        ->setCellValue('D1', 'Product')
        ->setCellValue('E1', 'Quantity');

    // Data
        $row = 2;
        foreach ($consumptions as $consumption) {
            $sheet->setCellValue('A' . $row, $consumption['id'])
            ->setCellValue('B' . $row, $consumption['tenant_id'])
            ->setCellValue('C' . $row, $consumption['date'])
            ->setCellValue('D' . $row, $consumption['product_name'])
            ->setCellValue('E' . $row, $consumption['quantity']);
            $row++;
        }

        $writer = new Xlsx($spreadsheet);
        $filename = 'Medicine_Consumption_' . $selectedDate . '.xlsx';

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header("Content-Disposition: attachment; filename=\"$filename\"");
        $writer->save('php://output');
        exit;
    }
}