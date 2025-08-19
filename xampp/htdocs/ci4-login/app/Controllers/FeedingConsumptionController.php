<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\FeedConsumptionModel;
use App\Models\StockRegistrationModel;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class FeedingConsumptionController extends BaseController
{
    public function feedingConsumptionList()
    {
        $model = new FeedConsumptionModel();
        $stockModel = new StockRegistrationModel();

        $selectedDate = $this->request->getGet('date') ?? date('Y-m-d');

        $totalQuantity = $model
        ->selectSum('quantity')
        ->where('date', $selectedDate)
        ->first()['quantity'] ?? 0;

        $feedingProducts = $stockModel
        ->select('stock_registration.*')
        ->join('stock_heads', 'stock_heads.id = stock_registration.head_id')
        ->where('stock_heads.name', 'Feeding')
        ->findAll();

        $consumptions = $model
        ->select('feed_consumption.*, stock_registration.product_name')
        ->join('stock_registration', 'stock_registration.id = feed_consumption.product_id')
        ->where('feed_consumption.date', $selectedDate)
        ->findAll();

        $data = [
            'selected_date' => $selectedDate,
            'feeding_products' => $feedingProducts,
            'feeding_consumptions' => $consumptions,
            'total_quantity' => $totalQuantity,
        ];

        return view('feeding-consumption/feedingConsumption', $data);
    }

    public function addFeedingConsumption()
    {
        $model = new FeedConsumptionModel();

        $data = [
            'product_id' => $this->request->getPost('product_id'),
            'quantity'   => $this->request->getPost('quantity'),
            'date'       => $this->request->getPost('date'),
        ];

        $model->insert($data);

        return redirect()->to('/feeding-consumption/feedingConsumption')->with('success', 'Feeding consumption added successfully.');
    }

    public function editFeedingConsumption($id)
    {
        $model = new FeedConsumptionModel();

        $data = [
            'product_id' => $this->request->getPost('product_id'),
            'quantity'   => $this->request->getPost('quantity'),
            'date'       => $this->request->getPost('date'),
        ];

        $model->update($id, $data);

        return redirect()->to('/feeding-consumption/feedingConsumption')->with('success', 'Feeding consumption updated successfully.');
    }

    public function deleteFeedingConsumption($id)
    {
        $model = new FeedConsumptionModel();
        $model->delete($id);

        return redirect()->to('/feeding-consumption/feedingConsumption')->with('success', 'Feeding consumption deleted.');
    }

    public function downloadExcel()
    {
        $model = new FeedConsumptionModel();

        $selectedDate = $this->request->getGet('date') ?? date('Y-m-d');

        $consumptions = $model
        ->select('feed_consumption.*, stock_registration.product_name')
        ->join('stock_registration', 'stock_registration.id = feed_consumption.product_id')
        ->where('feed_consumption.date', $selectedDate)
        ->findAll();

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

    // Headers
        $sheet->setCellValue('A1', 'ID');
        $sheet->setCellValue('B1', 'Date');
        $sheet->setCellValue('C1', 'Product');
        $sheet->setCellValue('D1', 'Quantity');

    // Data
        $row = 2;
        foreach ($consumptions as $consumption) {
            $sheet->setCellValue('A' . $row, $consumption['id']);
            $sheet->setCellValue('B' . $row, $consumption['date']);
            $sheet->setCellValue('C' . $row, $consumption['product_name']);
            $sheet->setCellValue('D' . $row, $consumption['quantity']);
            $row++;
        }

        $writer = new Xlsx($spreadsheet);
        $filename = 'Feeding_Consumption_' . $selectedDate . '.xlsx';

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header("Content-Disposition: attachment; filename=\"$filename\"");
        header('Cache-Control: max-age=0');

        $writer->save('php://output');
        exit();
    }
}