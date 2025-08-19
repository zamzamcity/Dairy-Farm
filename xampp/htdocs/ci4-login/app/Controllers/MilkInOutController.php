<?php

namespace App\Controllers;

use App\Models\DailyMilkingModel;
use App\Models\MilkConsumptionModel;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class MilkInOutController extends BaseController
{
    public function milkInOutDetails()
    {
        $date = $this->request->getGet('date') ?? date('Y-m-d');

        $milkingModel = new DailyMilkingModel();
        $consumptionModel = new MilkConsumptionModel();

        $dailyMilking = $milkingModel->where('date', $date)->findAll();
        $milkConsumption = $consumptionModel->select('milk_consumption.*, farm_head.head_name')
        ->join('farm_head', 'milk_consumption.farm_head_id = farm_head.id')
        ->where('milk_consumption.date', $date)
        ->findAll();

        $totalMilking = array_sum(array_column($dailyMilking, 'total_milk'));
        $totalConsumption = array_sum(array_column($milkConsumption, 'milk_litres'));

        return view('milkInOut', [
            'selected_date' => $date,
            'daily_milking' => $dailyMilking,
            'milk_consumption' => $milkConsumption,
            'total_milking' => $totalMilking,
            'total_consumption' => $totalConsumption
        ]);
    }

    public function exportMilkInOut()
    {
        $date = $this->request->getGet('date') ?? date('Y-m-d');

        $milkingModel = new DailyMilkingModel();
        $consumptionModel = new MilkConsumptionModel();

        $dailyMilking = $milkingModel->where('date', $date)->findAll();
        $milkConsumption = $consumptionModel->select('milk_consumption.*, farm_head.head_name')
        ->join('farm_head', 'milk_consumption.farm_head_id = farm_head.id')
        ->where('milk_consumption.date', $date)
        ->findAll();

        $totalMilking = array_sum(array_column($dailyMilking, 'total_milk'));
        $totalConsumption = array_sum(array_column($milkConsumption, 'milk_litres'));

    // Load PhpSpreadsheet
        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

    // Title
        $sheet->setCellValue('A1', "Milk In/Out Report - $date");

    // ---------------- Daily Milking ----------------
        $sheet->setCellValue('A3', 'Product');
        $sheet->setCellValue('B3', 'Milk 1 (L)');
        $sheet->setCellValue('C3', 'Milk 2 (L)');
        $sheet->setCellValue('D3', 'Milk 3 (L)');
        $sheet->setCellValue('E3', 'Total (L)');

        $row = 4;
        foreach ($dailyMilking as $m) {
            $sheet->setCellValue("A$row", $m['milk_product']);
            $sheet->setCellValue("B$row", $m['milk_1']);
            $sheet->setCellValue("C$row", $m['milk_2']);
            $sheet->setCellValue("D$row", $m['milk_3']);
            $sheet->setCellValue("E$row", $m['total_milk']);
            $row++;
        }
        $sheet->setCellValue("D$row", "Total Milking");
        $sheet->setCellValue("E$row", $totalMilking);

    // ---------------- Milk Consumption ----------------
        $row += 2;
        $sheet->setCellValue("A$row", "Head");
        $sheet->setCellValue("B$row", "Milk (L)");
        $row++;

        foreach ($milkConsumption as $c) {
            $sheet->setCellValue("A$row", $c['head_name']);
            $sheet->setCellValue("B$row", $c['milk_litres']);
            $row++;
        }
        $sheet->setCellValue("A$row", "Total Consumption");
        $sheet->setCellValue("B$row", $totalConsumption);

    // Auto width
        foreach (range('A', 'E') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

    // Download Excel
        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        $fileName = 'Milk_InOut_' . $date . '.xlsx';

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header("Content-Disposition: attachment; filename=\"$fileName\"");
        header('Cache-Control: max-age=0');

        $writer->save('php://output');
        exit;
    }
}