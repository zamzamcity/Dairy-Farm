<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\StockRegistrationModel;
use App\Models\StockHeadModel;
use App\Models\FeedConsumptionModel;
use App\Models\MedicineConsumptionModel;
use App\Models\TenantsModel;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class StockLedgerController extends BaseController
{
    public function stockLedger()
    {
        $stockModel    = new StockRegistrationModel();
        $headModel     = new StockHeadModel();
        $feedingModel  = new FeedConsumptionModel();
        $medicineModel = new MedicineConsumptionModel();
        $tenantModel   = new TenantsModel();

        $fromDate     = $this->request->getGet('from_date');
        $toDate       = $this->request->getGet('to_date');
        $selectedHead = $this->request->getGet('head_id');

        $heads = $headModel->findAll();
        if (!$selectedHead && !empty($heads)) {
            $selectedHead = $heads[0]['id'];
        }

        if (isSuperAdmin()) {
            $data['tenants'] = $tenantModel->findAll();
            $selectedTenantId = $this->request->getGet('tenant_id');

            if ($selectedTenantId) {
                $stocks = $stockModel
                ->select('stock_registration.*, stock_heads.name AS head_name, stock_units.name AS unit_name, tenants.name as tenant_name')
                ->join('stock_heads', 'stock_heads.id = stock_registration.head_id')
                ->join('stock_units', 'stock_units.id = stock_registration.unit_id')
                ->join('tenants', 'tenants.id = stock_registration.tenant_id', 'left')
                ->where('stock_registration.is_stock_item', 1)
                ->where('stock_registration.head_id', $selectedHead)
                ->where('stock_registration.tenant_id', $selectedTenantId)
                ->findAll();
            } else {
                $stocks = $stockModel
                ->select('stock_registration.*, stock_heads.name AS head_name, stock_units.name AS unit_name, tenants.name as tenant_name')
                ->join('stock_heads', 'stock_heads.id = stock_registration.head_id')
                ->join('stock_units', 'stock_units.id = stock_registration.unit_id')
                ->join('tenants', 'tenants.id = stock_registration.tenant_id', 'left')
                ->where('stock_registration.is_stock_item', 1)
                ->where('stock_registration.head_id', $selectedHead)
                ->findAll();
            }

            $data['selectedTenantId'] = $selectedTenantId;
        } else {
            $tid = currentTenantId();

            $stocks = $stockModel
            ->select('stock_registration.*, stock_heads.name AS head_name, stock_units.name AS unit_name, tenants.name as tenant_name')
            ->join('stock_heads', 'stock_heads.id = stock_registration.head_id')
            ->join('stock_units', 'stock_units.id = stock_registration.unit_id')
            ->join('tenants', 'tenants.id = stock_registration.tenant_id', 'left')
            ->where('stock_registration.is_stock_item', 1)
            ->where('stock_registration.head_id', $selectedHead)
            ->where('stock_registration.tenant_id', $tid)
            ->findAll();
        }

        $ledgerData = [];
        foreach ($stocks as $stock) {
            $consumedRecords = [];

            if ($stock['head_name'] === 'Feeding') {
                $query = $feedingModel
                ->select('date, quantity')
                ->where('product_id', $stock['id']);
            } elseif ($stock['head_name'] === 'Medication') {
                $query = $medicineModel
                ->select('date, quantity')
                ->where('product_id', $stock['id']);
            }

            if (!empty($query)) {
                if ($fromDate && $toDate) {
                    $query->where('date >=', $fromDate)->where('date <=', $toDate);
                } elseif ($fromDate) {
                    $query->where('date >=', $fromDate);
                } elseif ($toDate) {
                    $query->where('date <=', $toDate);
                }

                $consumedRecords = $query->findAll();
            }

            $consumedQty = array_sum(array_column($consumedRecords, 'quantity'));

            $ledgerData[] = [
                'id'               => $stock['id'],
                'product_name'     => $stock['product_name'],
                'head_name'        => $stock['head_name'],
                'unit_name'        => $stock['unit_name'],
                'tenant_name'      => $stock['tenant_name'] ?? null,
                'opening_qty'      => $stock['opening_stock_qty'],
                'rate_per_unit'    => $stock['rate_per_unit'],
                'consumed_qty'     => $consumedQty,
                'remaining_qty'    => $stock['opening_stock_qty'] - $consumedQty,
                'consumed_records' => $consumedRecords,
            ];
        }

        $data['ledgerData']   = $ledgerData;
        $data['heads']        = $heads;
        $data['fromDate']     = $fromDate;
        $data['toDate']       = $toDate;
        $data['selectedHead'] = $selectedHead;

        return view('stockLedger', $data);
    }

    public function exportStockLedger()
    {
        $fromDate     = $this->request->getGet('from_date') ?? date('Y-m-d');
        $toDate       = $this->request->getGet('to_date') ?? date('Y-m-d');
        $selectedHead = $this->request->getGet('head_id');
        $tenantId     = $this->request->getGet('tenant_id');

        $stockModel    = new StockRegistrationModel();
        $headModel     = new StockHeadModel();
        $feedingModel  = new FeedConsumptionModel();
        $medicineModel = new MedicineConsumptionModel();

        $heads = $headModel->findAll();
        if (!$selectedHead && !empty($heads)) {
            $selectedHead = $heads[0]['id'];
        }

    // ---------------- Get Stock Data ----------------
        if (isSuperAdmin()) {
            if (!empty($tenantId)) {
                $stocks = $stockModel
                ->select('stock_registration.*, stock_heads.name AS head_name, stock_units.name AS unit_name, tenants.name as tenant_name')
                ->join('stock_heads', 'stock_heads.id = stock_registration.head_id')
                ->join('stock_units', 'stock_units.id = stock_registration.unit_id')
                ->join('tenants', 'tenants.id = stock_registration.tenant_id', 'left')
                ->where('stock_registration.is_stock_item', 1)
                ->where('stock_registration.head_id', $selectedHead)
                ->where('stock_registration.tenant_id', $tenantId)
                ->findAll();
            } else {
                $stocks = $stockModel
                ->select('stock_registration.*, stock_heads.name AS head_name, stock_units.name AS unit_name, tenants.name as tenant_name')
                ->join('stock_heads', 'stock_heads.id = stock_registration.head_id')
                ->join('stock_units', 'stock_units.id = stock_registration.unit_id')
                ->join('tenants', 'tenants.id = stock_registration.tenant_id', 'left')
                ->where('stock_registration.is_stock_item', 1)
                ->where('stock_registration.head_id', $selectedHead)
                ->findAll();
            }
        } else {
            $tid = currentTenantId();

            $stocks = $stockModel
            ->select('stock_registration.*, stock_heads.name AS head_name, stock_units.name AS unit_name, tenants.name as tenant_name')
            ->join('stock_heads', 'stock_heads.id = stock_registration.head_id')
            ->join('stock_units', 'stock_units.id = stock_registration.unit_id')
            ->join('tenants', 'tenants.id = stock_registration.tenant_id', 'left')
            ->where('stock_registration.is_stock_item', 1)
            ->where('stock_registration.head_id', $selectedHead)
            ->where('stock_registration.tenant_id', $tid)
            ->findAll();
        }

    // ---------------- Excel Setup ----------------
        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $sheet->setCellValue('A1', "Stock Ledger Report ($fromDate to $toDate)");

        $sheet->setCellValue('A3', 'Tenant');
        $sheet->setCellValue('B3', 'Product');
        $sheet->setCellValue('C3', 'Head');
        $sheet->setCellValue('D3', 'Unit');
        $sheet->setCellValue('E3', 'Opening Qty');
        $sheet->setCellValue('F3', 'Rate/Unit');
        $sheet->setCellValue('G3', 'Dates');
        $sheet->setCellValue('H3', 'Consumed Qty');
        $sheet->setCellValue('I3', 'Remaining Qty');

        $row = 4;

    // ---------------- Process Each Stock ----------------
        foreach ($stocks as $stock) {
            $consumedQty   = 0;
            $consumedDates = [];
            $query         = null;

            if ($stock['head_name'] === 'Feeding') {
                $query = $feedingModel
                ->select('date, SUM(quantity) as qty')
                ->where('product_id', $stock['id'])
                ->groupBy('date')
                ->orderBy('date');
            } elseif ($stock['head_name'] === 'Medication') {
                $query = $medicineModel
                ->select('date, SUM(quantity) as qty')
                ->where('product_id', $stock['id'])
                ->groupBy('date')
                ->orderBy('date');
            }

            if (!empty($query)) {
                if ($fromDate && $toDate) {
                    $query->where('date >=', $fromDate)->where('date <=', $toDate);
                } elseif ($fromDate) {
                    $query->where('date >=', $fromDate);
                } elseif ($toDate) {
                    $query->where('date <=', $toDate);
                }

                $consumptionData = $query->findAll();

                foreach ($consumptionData as $entry) {
                    $consumedQty   += $entry['qty'];
                    $consumedDates[] = $entry['date'];
                }
            }

            $remainingQty     = $stock['opening_stock_qty'] - $consumedQty;
            $consumedDateStr  = implode(', ', $consumedDates);

            $sheet->setCellValue("A{$row}", $stock['tenant_name'] ?? '');
            $sheet->setCellValue("B{$row}", $stock['product_name']);
            $sheet->setCellValue("C{$row}", $stock['head_name']);
            $sheet->setCellValue("D{$row}", $stock['unit_name']);
            $sheet->setCellValue("E{$row}", $stock['opening_stock_qty']);
            $sheet->setCellValue("F{$row}", $stock['rate_per_unit']);
            $sheet->setCellValue("G{$row}", $consumedDateStr);
            $sheet->setCellValue("H{$row}", $consumedQty);
            $sheet->setCellValue("I{$row}", $remainingQty);

            $row++;
        }

        foreach (range('A', 'I') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        $fileName = "Stock_Ledger_{$fromDate}_to_{$toDate}.xlsx";

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header("Content-Disposition: attachment; filename=\"$fileName\"");
        header('Cache-Control: max-age=0');

        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        $writer->save('php://output');
        exit;
    }
}