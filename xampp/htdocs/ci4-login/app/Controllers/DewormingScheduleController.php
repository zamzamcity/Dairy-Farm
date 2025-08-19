<?php

namespace App\Controllers;

use App\Models\DewormingModel;
use App\Models\DewormingScheduleModel;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class DewormingScheduleController extends BaseController
{
    public function dewormingScheduleList()
    {
        $scheduleModel   = new DewormingScheduleModel();
        $dewormingModel  = new DewormingModel();

        $data['deworming_schedules'] = $scheduleModel
        ->select('deworming_schedules.*, deworming.name AS deworming_name')
        ->join('deworming', 'deworming.id = deworming_schedules.deworming_id')
        ->orderBy('deworming_schedules.date', 'DESC')
        ->findAll();

        $data['dewormings'] = $dewormingModel->findAll();
        $data['months'] = [
            'January', 'February', 'March', 'April', 'May', 'June',
            'July', 'August', 'September', 'October', 'November', 'December'
        ];

        return view('schedule-events/dewormingSchedule', $data);
    }

    public function addDewormingSchedule()
    {
        $scheduleModel = new DewormingScheduleModel();

        $data = [
            'month'        => $this->request->getPost('month'),
            'date'         => $this->request->getPost('date'),
            'deworming_id' => $this->request->getPost('deworming_id'),
            'comments'     => $this->request->getPost('comments'),
        ];

        $scheduleModel->insert($data);

        return redirect()->to('/schedule-events/dewormingSchedule')->with('success', 'Deworming schedule added successfully.');
    }

    public function editDewormingSchedule($id)
    {
        $scheduleModel = new DewormingScheduleModel();

        $data = [
            'month'        => $this->request->getPost('month'),
            'date'         => $this->request->getPost('date'),
            'deworming_id' => $this->request->getPost('deworming_id'),
            'comments'     => $this->request->getPost('comments'),
        ];

        $scheduleModel->update($id, $data);

        return redirect()->to('/schedule-events/dewormingSchedule')->with('success', 'Deworming schedule updated successfully.');
    }

    public function deleteDewormingSchedule($id)
    {
        $scheduleModel = new DewormingScheduleModel();
        $scheduleModel->delete($id);

        return redirect()->to('/schedule-events/dewormingSchedule')->with('success', 'Deworming schedule deleted successfully.');
    }

    public function exportDewormingSchedules()
    {
        $scheduleModel = new DewormingScheduleModel();
        $schedules = $scheduleModel
        ->select('deworming_schedules.month, deworming_schedules.date, deworming.name as deworming_name')
        ->join('deworming', 'deworming.id = deworming_schedules.deworming_id')
        ->orderBy('deworming_schedules.date', 'DESC')
        ->findAll();

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $sheet->setCellValue('A1', 'Month');
        $sheet->setCellValue('B1', 'Date');
        $sheet->setCellValue('C1', 'Deworming');

        $row = 2;
        foreach ($schedules as $s) {
            $sheet->setCellValue("A{$row}", $s['month']);
            $sheet->setCellValue("B{$row}", $s['date']);
            $sheet->setCellValue("C{$row}", $s['deworming_name']);
            $row++;
        }

        $writer = new Xlsx($spreadsheet);
        $filename = 'deworming_schedules.xlsx';

        header('Content-Type: application/vnd.ms-excel');
        header("Content-Disposition: attachment;filename=\"{$filename}\"");
        header('Cache-Control: max-age=0');
        $writer->save('php://output');
        exit;
    }
}