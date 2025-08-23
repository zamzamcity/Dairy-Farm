<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\FeedConsumptionModel;
use App\Models\StockRegistrationModel;
use App\Models\TenantsModel;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class FeedingConsumptionController extends BaseController
{
    public function feedingConsumptionList()
    {
        $model       = new FeedConsumptionModel();
        $stockModel  = new StockRegistrationModel();
        $tenantModel = new TenantsModel();

        $selectedDate = $this->request->getGet('date') ?? date('Y-m-d');

        if (isSuperAdmin()) {
            $data['tenants'] = $tenantModel->findAll();

            $selectedTenantId = $this->request->getGet('tenant_id');

            if ($selectedTenantId) {
                $data['feeding_consumptions'] = $model
                ->select('feed_consumption.*, tenants.name as tenant_name, stock_registration.product_name')
                ->join('stock_registration', 'stock_registration.id = feed_consumption.product_id', 'left')
                ->join('tenants', 'tenants.id = feed_consumption.tenant_id', 'left')
                ->where('feed_consumption.tenant_id', $selectedTenantId)
                ->where('feed_consumption.date', $selectedDate)
                ->orderBy('feed_consumption.date', 'DESC')
                ->findAll();

                $data['feeding_products'] = $stockModel
                ->select('stock_registration.*')
                ->join('stock_heads', 'stock_heads.id = stock_registration.head_id')
                ->where('stock_heads.name', 'Feeding')
                ->where('stock_registration.tenant_id', $selectedTenantId)
                ->findAll();
            } else {
                $data['feeding_consumptions'] = $model
                ->select('feed_consumption.*, tenants.name as tenant_name, stock_registration.product_name')
                ->join('stock_registration', 'stock_registration.id = feed_consumption.product_id', 'left')
                ->join('tenants', 'tenants.id = feed_consumption.tenant_id', 'left')
                ->where('feed_consumption.date', $selectedDate)
                ->orderBy('feed_consumption.date', 'DESC')
                ->findAll();

                $data['feeding_products'] = $stockModel
                ->select('stock_registration.*')
                ->join('stock_heads', 'stock_heads.id = stock_registration.head_id')
                ->where('stock_heads.name', 'Feeding')
                ->findAll();
            }

            $data['selectedTenantId'] = $selectedTenantId;
        } else {
            $tid = currentTenantId();

            $data['feeding_consumptions'] = $model
            ->select('feed_consumption.*, tenants.name as tenant_name, stock_registration.product_name')
            ->join('stock_registration', 'stock_registration.id = feed_consumption.product_id', 'left')
            ->join('tenants', 'tenants.id = feed_consumption.tenant_id', 'left')
            ->where('feed_consumption.tenant_id', $tid)
            ->where('feed_consumption.date', $selectedDate)
            ->orderBy('feed_consumption.date', 'DESC')
            ->findAll();

            $data['feeding_products'] = $stockModel
            ->select('stock_registration.*')
            ->join('stock_heads', 'stock_heads.id = stock_registration.head_id')
            ->where('stock_heads.name', 'Feeding')
            ->where('stock_registration.tenant_id', $tid)
            ->findAll();
        }

        $totalQuantity = 0;
        foreach ($data['feeding_consumptions'] as $record) {
            $totalQuantity += floatval($record['quantity']);
        }

        $data['total_quantity'] = $totalQuantity;
        $data['selected_date']  = $selectedDate;

        return view('feeding-consumption/feedingConsumption', $data);
    }

    public function addFeedingConsumption()
    {
        $model = new FeedConsumptionModel();

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
            return redirect()->to('/feeding-consumption/feedingConsumption')->with('success', 'Feeding consumption added successfully.');
        } else {
            return redirect()->back()->with('error', 'Failed to add feeding consumption.');
        }
    }

    public function editFeedingConsumption($id)
    {
        $model = new FeedConsumptionModel();

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

        return redirect()->to('/feeding-consumption/feedingConsumption')->with('success', 'Feeding consumption updated successfully.');
    }

    public function deleteFeedingConsumption($id)
    {
        $model = new FeedConsumptionModel();

        if (!isSuperAdmin()) {
            $exists = $model->where('id', $id)
            ->where('tenant_id', currentTenantId())
            ->first();
            if (!$exists) {
                return redirect()->back()->with('error', 'Unauthorized.');
            }
        }

        if ($model->delete($id)) {
            return redirect()->to('/feeding-consumption/feedingConsumption')->with('success', 'Feeding consumption deleted.');
        } else {
            return redirect()->to('/feeding-consumption/feedingConsumption')->with('error', 'Failed to delete feeding consumption.');
        }
    }

    public function downloadExcel()
    {
        $model = new FeedConsumptionModel();
        $tenantId = $this->request->getGet('tenant_id');
        $selectedDate = $this->request->getGet('date') ?? date('Y-m-d');

        if (isSuperAdmin()) {
            if (!empty($tenantId)) {
                $consumptions = $model
                ->select('feed_consumption.*, stock_registration.product_name')
                ->join('stock_registration', 'stock_registration.id = feed_consumption.product_id')
                ->where('feed_consumption.tenant_id', $tenantId)
                ->where('feed_consumption.date', $selectedDate)
                ->findAll();
            } else {
                $consumptions = $model
                ->select('feed_consumption.*, stock_registration.product_name')
                ->join('stock_registration', 'stock_registration.id = feed_consumption.product_id')
                ->where('feed_consumption.date', $selectedDate)
                ->findAll();
            }
        } else {
            $consumptions = $model
            ->select('feed_consumption.*, stock_registration.product_name')
            ->join('stock_registration', 'stock_registration.id = feed_consumption.product_id')
            ->where('feed_consumption.tenant_id', currentTenantId())
            ->where('feed_consumption.date', $selectedDate)
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
        $filename = 'Feeding_Consumption_' . $selectedDate . '.xlsx';

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header("Content-Disposition: attachment; filename=\"$filename\"");
        $writer->save('php://output');
        exit;
    }
}