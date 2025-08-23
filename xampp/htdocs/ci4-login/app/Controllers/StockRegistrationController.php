<?php

namespace App\Controllers;

use App\Models\StockRegistrationModel;
use App\Models\StockHeadModel;
use App\Models\StockUnitModel;
use App\Models\TenantsModel;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class StockRegistrationController extends BaseController
{
    public function stockList()
    {
        $model       = new StockRegistrationModel();
        $tenantModel = new TenantsModel();
        $headModel   = new StockHeadModel();
        $unitModel   = new StockUnitModel();

        if (isSuperAdmin()) {
            $data['tenants'] = $tenantModel->findAll();
            $selectedTenantId = $this->request->getGet('tenant_id');

            if ($selectedTenantId) {
                $data['stock_registration'] = $model
                ->select('stock_registration.*, tenants.name as tenant_name, stock_heads.name AS head_name, stock_units.name AS unit_name')
                ->join('tenants', 'tenants.id = stock_registration.tenant_id', 'left')
                ->join('stock_heads', 'stock_heads.id = stock_registration.head_id', 'left')
                ->join('stock_units', 'stock_units.id = stock_registration.unit_id', 'left')
                ->where('stock_registration.tenant_id', $selectedTenantId)
                ->orderBy('stock_registration.created_at', 'DESC')
                ->findAll();

                $data['stock_heads'] = $headModel->where('tenant_id', $selectedTenantId)->findAll();
                $data['stock_units'] = $unitModel->where('tenant_id', $selectedTenantId)->findAll();
            } else {
                $data['stock_registration'] = $model
                ->select('stock_registration.*, tenants.name as tenant_name, stock_heads.name AS head_name, stock_units.name AS unit_name')
                ->join('tenants', 'tenants.id = stock_registration.tenant_id', 'left')
                ->join('stock_heads', 'stock_heads.id = stock_registration.head_id', 'left')
                ->join('stock_units', 'stock_units.id = stock_registration.unit_id', 'left')
                ->orderBy('stock_registration.created_at', 'DESC')
                ->findAll();

                $data['stock_heads'] = $headModel->findAll();
                $data['stock_units'] = $unitModel->findAll();
            }

            $data['selectedTenantId'] = $selectedTenantId;
        } else {
            $tid = currentTenantId();

            $data['stock_registration'] = $model
            ->select('stock_registration.*, tenants.name as tenant_name, stock_heads.name AS head_name, stock_units.name AS unit_name')
            ->join('tenants', 'tenants.id = stock_registration.tenant_id', 'left')
            ->join('stock_heads', 'stock_heads.id = stock_registration.head_id', 'left')
            ->join('stock_units', 'stock_units.id = stock_registration.unit_id', 'left')
            ->where('stock_registration.tenant_id', $tid)
            ->orderBy('stock_registration.created_at', 'DESC')
            ->findAll();

            $data['stock_heads'] = $headModel->where('tenant_id', $tid)->findAll();
            $data['stock_units'] = $unitModel->where('tenant_id', $tid)->findAll();
        }

        return view('stock/stockList', $data);
    }

    public function addStock()
    {
        $model = new StockRegistrationModel();

        $isStockItem = (int) $this->request->getPost('is_stock_item');

        $data = [
            'product_name'               => $this->request->getPost('product_name'),
            'head_id'                    => $this->request->getPost('head_id'),
            'unit_id'                    => $this->request->getPost('unit_id'),
            'is_stock_item'              => $isStockItem,
            'opening_stock_qty'          => $isStockItem ? $this->request->getPost('opening_stock_qty') : null,
            'opening_stock_rate_per_unit'=> $isStockItem ? $this->request->getPost('opening_stock_rate_per_unit') : null,
            'rate_per_unit'              => $this->request->getPost('rate_per_unit'),
            'tenant_id'                  => isSuperAdmin()
            ? ($this->request->getPost('tenant_id') !== '' ? $this->request->getPost('tenant_id') : null)
            : currentTenantId(),
            'created_by'                 => session()->get('user_id'),
            'updated_by'                 => session()->get('user_id'),
            'created_at'                 => date('Y-m-d H:i:s'),
            'updated_at'                 => date('Y-m-d H:i:s'),
        ];

        if ($model->insert($data)) {
            return redirect()->to('/stock/stockList')->with('success', 'Stock registered successfully.');
        } else {
            return redirect()->back()->with('error', 'Failed to register stock.');
        }
    }

    public function editStock($id)
    {
        $model = new StockRegistrationModel();

    // Ensure tenant restriction
        if (!isSuperAdmin()) {
            $exists = $model->where('id', $id)
            ->where('tenant_id', currentTenantId())
            ->first();
            if (!$exists) {
                return redirect()->back()->with('error', 'Unauthorized.');
            }
        }

        $isStockItem = (int) $this->request->getPost('is_stock_item');

        $data = [
            'product_name'               => $this->request->getPost('product_name'),
            'head_id'                    => $this->request->getPost('head_id'),
            'unit_id'                    => $this->request->getPost('unit_id'),
            'is_stock_item'              => $isStockItem,
            'opening_stock_qty'          => $isStockItem ? $this->request->getPost('opening_stock_qty') : null,
            'opening_stock_rate_per_unit'=> $isStockItem ? $this->request->getPost('opening_stock_rate_per_unit') : null,
            'rate_per_unit'              => $this->request->getPost('rate_per_unit'),
            'updated_by'                 => session()->get('user_id'),
            'updated_at'                 => date('Y-m-d H:i:s'),
        ];

        if (isSuperAdmin() && $this->request->getPost('tenant_id')) {
            $data['tenant_id'] = (int) $this->request->getPost('tenant_id');
        }

        $model->update($id, $data);

        return redirect()->to('/stock/stockList')->with('success', 'Stock updated successfully.');
    }

    public function deleteStock($id)
    {
        $model = new StockRegistrationModel();

        if (!isSuperAdmin()) {
            $exists = $model->where('id', $id)
            ->where('tenant_id', currentTenantId())
            ->first();
            if (!$exists) {
                return redirect()->back()->with('error', 'Unauthorized.');
            }
        }

        if ($model->delete($id)) {
            return redirect()->to('/stock/stockList')->with('success', 'Stock deleted successfully.');
        } else {
            return redirect()->to('/stock/stockList')->with('error', 'Failed to delete stock.');
        }
    }

    public function addHead()
    {
        $headModel = new StockHeadModel();

        $data = [
            'name'       => $this->request->getPost('head_name'),
            'tenant_id'  => isSuperAdmin()
            ? ($this->request->getPost('tenant_id') !== '' ? $this->request->getPost('tenant_id') : null)
            : currentTenantId(),
            'created_by' => session()->get('user_id'),
            'updated_by' => session()->get('user_id'),
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ];

        if ($headModel->insert($data)) {
            return redirect()->to('/stock/stockList')->with('success', 'New stock head added successfully.');
        } else {
            return redirect()->back()->with('error', 'Failed to add stock head.');
        }
    }

    public function addUnit()
    {
        $unitModel = new StockUnitModel();

        $data = [
            'name'       => $this->request->getPost('unit_name'),
            'tenant_id'  => isSuperAdmin()
            ? ($this->request->getPost('tenant_id') !== '' ? $this->request->getPost('tenant_id') : null)
            : currentTenantId(),
            'created_by' => session()->get('user_id'),
            'updated_by' => session()->get('user_id'),
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ];

        if ($unitModel->insert($data)) {
            return redirect()->to('/stock/stockList')->with('success', 'New stock unit added successfully.');
        } else {
            return redirect()->back()->with('error', 'Failed to add stock unit.');
        }
    }

    public function downloadExcel()
    {
        $model = new StockRegistrationModel();
        $tenantId = $this->request->getGet('tenant_id');

        if (isSuperAdmin()) {
            if (!empty($tenantId)) {
                $stocks = $model
                ->select('stock_registration.*, stock_heads.name AS head_name, stock_units.name AS unit_name')
                ->join('stock_heads', 'stock_heads.id = stock_registration.head_id')
                ->join('stock_units', 'stock_units.id = stock_registration.unit_id')
                ->where('stock_registration.tenant_id', $tenantId)
                ->orderBy('stock_registration.created_at', 'DESC')
                ->findAll();
            } else {
                $stocks = $model
                ->select('stock_registration.*, stock_heads.name AS head_name, stock_units.name AS unit_name')
                ->join('stock_heads', 'stock_heads.id = stock_registration.head_id')
                ->join('stock_units', 'stock_units.id = stock_registration.unit_id')
                ->orderBy('stock_registration.created_at', 'DESC')
                ->findAll();
            }
        } else {
            $stocks = $model
            ->select('stock_registration.*, stock_heads.name AS head_name, stock_units.name AS unit_name')
            ->join('stock_heads', 'stock_heads.id = stock_registration.head_id')
            ->join('stock_units', 'stock_units.id = stock_registration.unit_id')
            ->where('stock_registration.tenant_id', currentTenantId())
            ->orderBy('stock_registration.created_at', 'DESC')
            ->findAll();
        }

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

    // Headers
        $sheet->setCellValue('A1', 'ID')
        ->setCellValue('B1', 'Tenant ID')
        ->setCellValue('C1', 'Product Name')
        ->setCellValue('D1', 'Head')
        ->setCellValue('E1', 'Unit')
        ->setCellValue('F1', 'Stock Item')
        ->setCellValue('G1', 'Opening Qty')
        ->setCellValue('H1', 'Opening Rate')
        ->setCellValue('I1', 'Rate/Unit');

    // Data
        $row = 2;
        foreach ($stocks as $stock) {
            $sheet->setCellValue('A' . $row, $stock['id'])
            ->setCellValue('B' . $row, $stock['tenant_id'])
            ->setCellValue('C' . $row, $stock['product_name'])
            ->setCellValue('D' . $row, $stock['head_name'])
            ->setCellValue('E' . $row, $stock['unit_name'])
            ->setCellValue('F' . $row, $stock['is_stock_item'] ? 'Yes' : 'No')
            ->setCellValue('G' . $row, $stock['opening_stock_qty'])
            ->setCellValue('H' . $row, $stock['opening_stock_rate_per_unit'])
            ->setCellValue('I' . $row, $stock['rate_per_unit']);
            $row++;
        }

        $writer = new Xlsx($spreadsheet);
        $filename = 'Stock_List_' . date('Y-m-d') . '.xlsx';

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header("Content-Disposition: attachment; filename=\"$filename\"");
        $writer->save('php://output');
        exit;
    }
}
