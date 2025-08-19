<?php

namespace App\Controllers;

use App\Models\StockRegistrationModel;
use App\Models\StockHeadModel;
use App\Models\StockUnitModel;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class StockRegistrationController extends BaseController
{
    public function stockList()
    {
        $model = new StockRegistrationModel();

        $data['stock_registration'] = $model
        ->select('stock_registration.*, stock_heads.name AS head_name, stock_units.name AS unit_name')
        ->join('stock_heads', 'stock_heads.id = stock_registration.head_id')
        ->join('stock_units', 'stock_units.id = stock_registration.unit_id')
        ->orderBy('stock_registration.created_at', 'DESC')
        ->findAll();

        $headModel = new StockHeadModel();
        $unitModel = new StockUnitModel();

        $data['stock_heads'] = $headModel->findAll();
        $data['stock_units'] = $unitModel->findAll();

        return view('stock/stockList', $data);
    }

    public function addStock()
    {
        $model = new StockRegistrationModel();

        $isStockItem = (int) $this->request->getPost('is_stock_item');

        $data = [
            'product_name'             => $this->request->getPost('product_name'),
            'head_id'                  => $this->request->getPost('head_id'),
            'unit_id'                  => $this->request->getPost('unit_id'),
            'is_stock_item'            => $isStockItem,
            'opening_stock_qty'       => $isStockItem ? $this->request->getPost('opening_stock_qty') : null,
            'opening_stock_rate_per_unit' => $isStockItem ? $this->request->getPost('opening_stock_rate_per_unit') : null,
            'rate_per_unit'           => $this->request->getPost('rate_per_unit'),
        ];

        $model->insert($data);

        return redirect()->to('/stock/stockList')->with('success', 'Stock registered successfully.');
    }

    public function editStock($id)
    {
        $model = new StockRegistrationModel();

        $isStockItem = (int) $this->request->getPost('is_stock_item');

        $data = [
            'product_name'             => $this->request->getPost('product_name'),
            'head_id'                  => $this->request->getPost('head_id'),
            'unit_id'                  => $this->request->getPost('unit_id'),
            'is_stock_item'            => $isStockItem,
            'opening_stock_qty'       => $isStockItem ? $this->request->getPost('opening_stock_qty') : null,
            'opening_stock_rate_per_unit' => $isStockItem ? $this->request->getPost('opening_stock_rate_per_unit') : null,
            'rate_per_unit'           => $this->request->getPost('rate_per_unit'),
        ];

        $model->update($id, $data);

        return redirect()->to('/stock/stockList')->with('success', 'Stock updated successfully.');
    }

    public function deleteStock($id)
    {
        $model = new StockRegistrationModel();
        $model->delete($id);

        return redirect()->to('/stock/stockList')->with('success', 'Stock deleted successfully.');
    }

    public function addHead()
    {
        $headModel = new StockHeadModel();

        $data = [
            'name' => $this->request->getPost('head_name'),
        ];

        $headModel->insert($data);

        return redirect()->back()->with('success', 'New stock head added successfully.');
    }

    public function addUnit()
    {
        $unitModel = new StockUnitModel();

        $data = [
            'name' => $this->request->getPost('unit_name'),
        ];

        $unitModel->insert($data);

        return redirect()->back()->with('success', 'New stock unit added successfully.');
    }

    public function downloadExcel()
    {
        $model = new StockRegistrationModel();

        $stocks = $model
        ->select('stock_registration.*, stock_heads.name AS head_name, stock_units.name AS unit_name')
        ->join('stock_heads', 'stock_heads.id = stock_registration.head_id')
        ->join('stock_units', 'stock_units.id = stock_registration.unit_id')
        ->orderBy('stock_registration.created_at', 'DESC')
        ->findAll();

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

    // Headers
        $sheet->setCellValue('A1', 'ID');
        $sheet->setCellValue('B1', 'Product Name');
        $sheet->setCellValue('C1', 'Head');
        $sheet->setCellValue('D1', 'Unit');
        $sheet->setCellValue('E1', 'Stock Item');
        $sheet->setCellValue('F1', 'Opening Qty');
        $sheet->setCellValue('G1', 'Opening Rate');
        $sheet->setCellValue('H1', 'Rate/Unit');

    // Data
        $row = 2;
        foreach ($stocks as $stock) {
            $sheet->setCellValue('A' . $row, $stock['id']);
            $sheet->setCellValue('B' . $row, $stock['product_name']);
            $sheet->setCellValue('C' . $row, $stock['head_name']);
            $sheet->setCellValue('D' . $row, $stock['unit_name']);
            $sheet->setCellValue('E' . $row, $stock['is_stock_item'] ? 'Yes' : 'No');
            $sheet->setCellValue('F' . $row, $stock['opening_stock_qty']);
            $sheet->setCellValue('G' . $row, $stock['opening_stock_rate_per_unit']);
            $sheet->setCellValue('H' . $row, $stock['rate_per_unit']);
            $row++;
        }

    // Create and download file
        $writer = new Xlsx($spreadsheet);
        $filename = 'Stock_List_' . date('Y-m-d') . '.xlsx';

    // Force download
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header("Content-Disposition: attachment; filename=\"$filename\"");
        header('Cache-Control: max-age=0');

        $writer->save('php://output');
        exit();
    }
}
