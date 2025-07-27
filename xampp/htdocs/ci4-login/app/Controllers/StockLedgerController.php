<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\StockRegistrationModel;
use App\Models\StockHeadModel;
use App\Models\FeedConsumptionModel;
use App\Models\MedicineConsumptionModel;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class StockLedgerController extends BaseController
{
    public function stockLedger()
    {
        $fromDate = $this->request->getGet('from_date') ?? date('Y-m-d');
        $toDate = $this->request->getGet('to_date') ?? date('Y-m-d');
        $selectedHead = $this->request->getGet('head_id');

        $stockModel = new StockRegistrationModel();
        $headModel = new StockHeadModel();
        $feedingModel = new FeedConsumptionModel();
        $medicineModel = new MedicineConsumptionModel();

        $heads = $headModel->findAll();

        if (!$selectedHead && !empty($heads)) {
            $selectedHead = $heads[0]['id'];
        }

        $stocks = $stockModel->select('stock_registration.*, stock_heads.name AS head_name, stock_units.name AS unit_name')
        ->join('stock_heads', 'stock_heads.id = stock_registration.head_id')
        ->join('stock_units', 'stock_units.id = stock_registration.unit_id')
        ->where('stock_registration.is_stock_item', 1)
        ->where('stock_registration.head_id', $selectedHead)
        ->findAll();

        $ledgerData = [];

        foreach ($stocks as $stock) {
            $consumedQty = 0;
            $consumedRecords = [];

            if ($stock['head_name'] == 'Feeding') {
                $consumedRecords = $feedingModel
                ->select('date, quantity')
                ->where('product_id', $stock['id'])
                ->where('date >=', $fromDate)
                ->where('date <=', $toDate)
                ->findAll();

            } elseif ($stock['head_name'] == 'Medication') {
                $consumedRecords = $medicineModel
                ->select('date, quantity')
                ->where('product_id', $stock['id'])
                ->where('date >=', $fromDate)
                ->where('date <=', $toDate)
                ->findAll();
            }

            $consumedQty = array_sum(array_column($consumedRecords, 'quantity'));

            $ledgerData[] = [
                'id' => $stock['id'],
                'product_name' => $stock['product_name'],
                'head_name' => $stock['head_name'],
                'unit_name' => $stock['unit_name'],
                'opening_qty' => $stock['opening_stock_qty'],
                'rate_per_unit' => $stock['rate_per_unit'],
                'consumed_qty' => $consumedQty,
                'remaining_qty' => $stock['opening_stock_qty'] - $consumedQty,
            'consumed_records' => $consumedRecords // pass to view
        ];
    }

    return view('stockLedger', [
        'ledgerData' => $ledgerData,
        'heads' => $heads,
        'fromDate' => $fromDate,
        'toDate' => $toDate,
        'selectedHead' => $selectedHead
    ]);
}

public function exportStockLedger()
{
    $fromDate = $this->request->getGet('from_date') ?? date('Y-m-d');
    $toDate = $this->request->getGet('to_date') ?? date('Y-m-d');
    $selectedHead = $this->request->getGet('head_id');

    $stockModel = new StockRegistrationModel();
    $headModel = new StockHeadModel();
    $feedingModel = new FeedConsumptionModel();
    $medicineModel = new MedicineConsumptionModel();

    $heads = $headModel->findAll();

    if (!$selectedHead && !empty($heads)) {
        $selectedHead = $heads[0]['id'];
    }

    $stocks = $stockModel->select('stock_registration.*, stock_heads.name AS head_name, stock_units.name AS unit_name')
    ->join('stock_heads', 'stock_heads.id = stock_registration.head_id')
    ->join('stock_units', 'stock_units.id = stock_registration.unit_id')
    ->where('stock_registration.is_stock_item', 1)
    ->where('stock_registration.head_id', $selectedHead)
    ->findAll();

    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();

    $sheet->setCellValue('A1', 'ID');
    $sheet->setCellValue('B1', 'Product');
    $sheet->setCellValue('C1', 'Head');
    $sheet->setCellValue('D1', 'Unit');
    $sheet->setCellValue('E1', 'Opening Qty');
    $sheet->setCellValue('F1', 'Rate/Unit');
    $sheet->setCellValue('G1', 'Date');
    $sheet->setCellValue('H1', 'Consumed Qty');
    $sheet->setCellValue('I1', 'Remaining Qty');

    $rowIndex = 2;

    foreach ($stocks as $stock) {
        $consumedQty = 0;
        $consumedDates = [];

        if ($stock['head_name'] == 'Feeding') {
            $feedingData = $feedingModel
            ->select('date, SUM(quantity) as qty')
            ->where('product_id', $stock['id'])
            ->where('date >=', $fromDate)
            ->where('date <=', $toDate)
            ->groupBy('date')
            ->orderBy('date')
            ->findAll();

            foreach ($feedingData as $entry) {
                $consumedQty += $entry['qty'];
                $consumedDates[] = $entry['date'];
            }

        } elseif ($stock['head_name'] == 'Medication') {
            $medicineData = $medicineModel
            ->select('date, SUM(quantity) as qty')
            ->where('product_id', $stock['id'])
            ->where('date >=', $fromDate)
            ->where('date <=', $toDate)
            ->groupBy('date')
            ->orderBy('date')
            ->findAll();

            foreach ($medicineData as $entry) {
                $consumedQty += $entry['qty'];
                $consumedDates[] = $entry['date'];
            }
        }

        $remainingQty = $stock['opening_stock_qty'] - $consumedQty;
        $consumedDateStr = implode(', ', $consumedDates);

        $sheet->setCellValue("A{$rowIndex}", $stock['id']);
        $sheet->setCellValue("B{$rowIndex}", $stock['product_name']);
        $sheet->setCellValue("C{$rowIndex}", $stock['head_name']);
        $sheet->setCellValue("D{$rowIndex}", $stock['unit_name']);
        $sheet->setCellValue("E{$rowIndex}", $stock['opening_stock_qty']);
        $sheet->setCellValue("F{$rowIndex}", $stock['rate_per_unit']);
        $sheet->setCellValue("G{$rowIndex}", $consumedDateStr);
        $sheet->setCellValue("H{$rowIndex}", $consumedQty);
        $sheet->setCellValue("I{$rowIndex}", $remainingQty);
        

        $rowIndex++;
    }

    $filename = "stock_ledger_{$fromDate}_to_{$toDate}.xlsx";
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header("Content-Disposition: attachment; filename=\"{$filename}\"");
    header('Cache-Control: max-age=0');

    $writer = new Xlsx($spreadsheet);
    $writer->save('php://output');
    exit;
}


}