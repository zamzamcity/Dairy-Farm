<?php

namespace App\Controllers;

use App\Models\DailyMilkingModel;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class DailyMilkingController extends BaseController
{
    public function dailyMilkingList()
    {
        $model = new DailyMilkingModel();

        $start_date = $this->request->getGet('start_date');
        $end_date   = $this->request->getGet('end_date');

        $query = $model->orderBy('date', 'DESC');

        if ($start_date && $end_date) {
            $query->where('date >=', $start_date)->where('date <=', $end_date);
        } elseif ($start_date) {
            $query->where('date >=', $start_date);
        } elseif ($end_date) {
            $query->where('date <=', $end_date);
        }

        $data['daily_milking'] = $query->findAll();
        $data['start_date'] = $start_date;
        $data['end_date'] = $end_date;

        return view('dailyMilk', $data);
    }

    public function addDailyMilking()
    {
        $model = new DailyMilkingModel();

        $data = [
            'date'         => $this->request->getPost('date'),
            'milk_product' => $this->request->getPost('milk_product'),
            'milk_1'       => $this->request->getPost('milk_1'),
            'milk_2'       => $this->request->getPost('milk_2'),
            'milk_3'       => $this->request->getPost('milk_3'),
        ];

        $model->insert($data);

        return redirect()->to('/dailyMilk')->with('success', 'Daily milking record added successfully.');
    }

    public function editDailyMilking($id)
    {
        $model = new DailyMilkingModel();

        $data = [
            'date'         => $this->request->getPost('date'),
            'milk_product' => $this->request->getPost('milk_product'),
            'milk_1'       => $this->request->getPost('milk_1'),
            'milk_2'       => $this->request->getPost('milk_2'),
            'milk_3'       => $this->request->getPost('milk_3'),
        ];

        $model->update($id, $data);

        return redirect()->to('/dailyMilk')->with('success', 'Daily milking record updated successfully.');
    }

    public function deleteDailyMilking($id)
    {
        $model = new DailyMilkingModel();
        $model->delete($id);

        return redirect()->to('/dailyMilk')->with('success', 'Daily milking record deleted successfully.');
    }

    public function exportDailyMilk()
    {
        $model = new DailyMilkingModel();

        $start_date = $this->request->getGet('start_date');
        $end_date   = $this->request->getGet('end_date');

        $query = $model->orderBy('date', 'DESC');

        if ($start_date && $end_date) {
            $query->where('date >=', $start_date)->where('date <=', $end_date);
        } elseif ($start_date) {
            $query->where('date >=', $start_date);
        } elseif ($end_date) {
            $query->where('date <=', $end_date);
        }

        $records = $query->findAll();

    // create spreadsheet
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

    // header row
        $sheet->setCellValue('A1', 'ID');
        $sheet->setCellValue('B1', 'Date');
        $sheet->setCellValue('C1', 'Milk Product');
        $sheet->setCellValue('D1', 'Milk 1 (L)');
        $sheet->setCellValue('E1', 'Milk 2 (L)');
        $sheet->setCellValue('F1', 'Milk 3 (L)');
        $sheet->setCellValue('G1', 'Total Milk (L)');

    // fill rows
        $row = 2;
        foreach ($records as $rec) {
            $sheet->setCellValue('A' . $row, $rec['id']);
            $sheet->setCellValue('B' . $row, $rec['date']);
            $sheet->setCellValue('C' . $row, $rec['milk_product']);
            $sheet->setCellValue('D' . $row, $rec['milk_1']);
            $sheet->setCellValue('E' . $row, $rec['milk_2']);
            $sheet->setCellValue('F' . $row, $rec['milk_3']);
            $sheet->setCellValue('G' . $row, $rec['total_milk']);
            $row++;
        }

    // output as excel
        $filename = 'Daily_Milk_' . date('Y-m-d_H-i-s') . '.xlsx';
        $writer = new Xlsx($spreadsheet);

    // headers
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header("Content-Disposition: attachment; filename=\"$filename\"");
        header('Cache-Control: max-age=0');

        $writer->save('php://output');
        exit;
    }
}