<?php

namespace App\Controllers;

use App\Models\MilkConsumptionModel;
use App\Models\FarmHeadModel;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class MilkConsumptionController extends BaseController
{
    public function milkConsumptionList()
    {
        $model = new MilkConsumptionModel();

        $selectedDate = $this->request->getGet('date') ?? date('Y-m-d');

        $data['milk_consumption'] = $model
        ->select('milk_consumption.*, farm_head.head_name')
        ->join('farm_head', 'farm_head.id = milk_consumption.farm_head_id')
        ->where('milk_consumption.date', $selectedDate)
        ->orderBy('milk_consumption.date', 'DESC')
        ->findAll();

        $farmHeadModel = new FarmHeadModel();
        $data['farm_heads'] = $farmHeadModel->findAll();

        $totalMilk = 0;
        foreach ($data['milk_consumption'] as $record) {
            $totalMilk += floatval($record['milk_litres']);
        }

        $data['total_milk'] = $totalMilk;
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
        ];

        $model->insert($data);

        return redirect()->to('/milk-consumption/milkConsumption')->with('success', 'Milk consumption added successfully.');
    }

    public function editMilkConsumption($id)
    {
        $model = new MilkConsumptionModel();

        $data = [
            'date'         => $this->request->getPost('date'),
            'farm_head_id' => $this->request->getPost('farm_head_id'),
            'milk_litres'  => $this->request->getPost('milk_litres'),
        ];

        $model->update($id, $data);

        return redirect()->to('/milk-consumption/milkConsumption')->with('success', 'Milk consumption updated successfully.');
    }

    public function deleteMilkConsumption($id)
    {
        $model = new MilkConsumptionModel();
        $model->delete($id);

        return redirect()->to('/milk-consumption/milkConsumption')->with('success', 'Milk consumption deleted successfully.');
    }

    public function exportMilkConsumption()
    {
        $model = new MilkConsumptionModel();

        $selectedDate = $this->request->getGet('date') ?? date('Y-m-d');

        $records = $model
        ->select('milk_consumption.*, farm_head.head_name')
        ->join('farm_head', 'farm_head.id = milk_consumption.farm_head_id')
        ->where('milk_consumption.date', $selectedDate)
        ->orderBy('milk_consumption.date', 'DESC')
        ->findAll();

    // create spreadsheet
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

    // header row
        $sheet->setCellValue('A1', 'ID');
        $sheet->setCellValue('B1', 'Date');
        $sheet->setCellValue('C1', 'Head Name');
        $sheet->setCellValue('D1', 'Milk (Litres)');

    // fill rows
        $row = 2;
        $totalMilk = 0;

        foreach ($records as $rec) {
            $sheet->setCellValue('A' . $row, $rec['id']);
            $sheet->setCellValue('B' . $row, $rec['date']);
            $sheet->setCellValue('C' . $row, $rec['head_name']);
            $sheet->setCellValue('D' . $row, $rec['milk_litres']);

            $totalMilk += floatval($rec['milk_litres']);
            $row++;
        }

    // add Grand Total row if data exists
        if (!empty($records)) {
            $sheet->setCellValue('C' . $row, 'Grand Total');
            $sheet->setCellValue('D' . $row, $totalMilk);
            $sheet->getStyle("C{$row}:D{$row}")->getFont()->setBold(true);
        }

    // output as excel
        $filename = 'Milk_Consumption_' . $selectedDate . '.xlsx';
        $writer = new Xlsx($spreadsheet);

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header("Content-Disposition: attachment; filename=\"$filename\"");
        header('Cache-Control: max-age=0');

        $writer->save('php://output');
        exit;
    }
}