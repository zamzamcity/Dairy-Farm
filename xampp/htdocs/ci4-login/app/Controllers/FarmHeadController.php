<?php

namespace App\Controllers;

use App\Models\FarmHeadModel;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class FarmHeadController extends BaseController
{
    public function farmHeadList()
    {
        $model = new FarmHeadModel();

        $data['farm_head'] = $model->orderBy('created_at', 'DESC')->findAll();

        return view('milk-consumption/farmHead', $data);
    }

    public function addFarmHead()
    {
        $model = new FarmHeadModel();

        $data = [
            'head_name' => $this->request->getPost('head_name'),
        ];

        $model->insert($data);

        return redirect()->to('/milk-consumption/farmHead')->with('success', 'Farm head added successfully.');
    }

    public function editFarmHead($id)
    {
        $model = new FarmHeadModel();

        $data = [
            'head_name' => $this->request->getPost('head_name'),
        ];

        $model->update($id, $data);

        return redirect()->to('/milk-consumption/farmHead')->with('success', 'Farm head updated successfully.');
    }

    public function deleteFarmHead($id)
    {
        $model = new FarmHeadModel();
        $model->delete($id);

        return redirect()->to('/milk-consumption/farmHead')->with('success', 'Farm head deleted successfully.');
    }

    public function exportFarmHead()
    {
        $model = new FarmHeadModel();
        $farmHeads = $model->orderBy('id', 'ASC')->findAll();

    // Load PhpSpreadsheet
        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

    // Set Headers
        $sheet->setCellValue('A1', 'ID');
        $sheet->setCellValue('B1', 'Head Name');

    // Fill Data
        $row = 2;
        foreach ($farmHeads as $head) {
            $sheet->setCellValue('A' . $row, $head['id']);
            $sheet->setCellValue('B' . $row, $head['head_name']);
            $row++;
        }

    // Set auto column width
        foreach (range('A', 'B') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

    // Download Excel
        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        $fileName = 'Farm_Head_List_' . date('Y-m-d_H-i-s') . '.xlsx';

    // Headers
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header("Content-Disposition: attachment; filename=\"$fileName\"");
        header('Cache-Control: max-age=0');

        $writer->save('php://output');
        exit;
    }
}