<?php

namespace App\Controllers;

use App\Models\ScheduleModel;
use App\Models\AnimalModel;
use App\Models\EventModel;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class ScheduleController extends BaseController
{
    public function scheduleList()
    {
        $scheduleModel = new ScheduleModel();
        $animalModel   = new AnimalModel();
        $eventModel    = new EventModel();

        $data['schedules'] = $scheduleModel
        ->select('schedules.*, animals.tag_id as animal_tag, events.name as event_name')
        ->join('animals', 'animals.id = schedules.tag_id')
        ->join('events', 'events.id = schedules.event_id')
        ->orderBy('schedules.date', 'DESC')
        ->findAll();

        $data['animals'] = $animalModel->findAll();
        $data['events']  = $eventModel->findAll();

        return view('schedule-events/schedule', $data);
    }

    public function addSchedule()
    {
        $scheduleModel = new ScheduleModel();

        $data = [
            'tag_id'    => $this->request->getPost('tag_id'),
            'date'      => $this->request->getPost('date'),
            'time'      => $this->request->getPost('time'),
            'event_id'  => $this->request->getPost('event_id'),
            'comments'  => $this->request->getPost('comments'),
        ];

        $scheduleModel->insert($data);

        return redirect()->to('/schedule-events/schedule')->with('success', 'Schedule added successfully.');
    }

    public function editSchedule($id)
    {
        $scheduleModel = new ScheduleModel();

        $data = [
            'tag_id'    => $this->request->getPost('tag_id'),
            'date'      => $this->request->getPost('date'),
            'time'      => $this->request->getPost('time'),
            'event_id'  => $this->request->getPost('event_id'),
            'comments'  => $this->request->getPost('comments'),
        ];

        $scheduleModel->update($id, $data);

        return redirect()->to('/schedule-events/schedule')->with('success', 'Schedule updated successfully.');
    }

    public function deleteSchedule($id)
    {
        $scheduleModel = new ScheduleModel();
        $scheduleModel->delete($id);

        return redirect()->to('/schedule-events/schedule')->with('success', 'Schedule deleted successfully.');
    }

    public function exportSchedules()
    {
        $scheduleModel = new ScheduleModel();
        $schedules = $scheduleModel
        ->select('schedules.date, schedules.time, animals.tag_id as animal_tag, events.name as event_name, schedules.comments')
        ->join('animals', 'animals.id = schedules.tag_id')
        ->join('events', 'events.id = schedules.event_id')
        ->orderBy('schedules.date', 'DESC')
        ->findAll();

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

    // Header
        $sheet->setCellValue('A1', 'Date');
        $sheet->setCellValue('B1', 'Time');
        $sheet->setCellValue('C1', 'Animal Tag');
        $sheet->setCellValue('D1', 'Event');
        $sheet->setCellValue('E1', 'Comments');

    // Data
        $row = 2;
        foreach ($schedules as $s) {
            $sheet->setCellValue("A{$row}", $s['date']);
            $sheet->setCellValue("B{$row}", $s['time']);
            $sheet->setCellValue("C{$row}", $s['animal_tag']);
            $sheet->setCellValue("D{$row}", $s['event_name']);
            $sheet->setCellValue("E{$row}", $s['comments']);
            $row++;
        }

        $writer = new Xlsx($spreadsheet);
        $filename = 'schedules.xlsx';

    // Output file
        header('Content-Type: application/vnd.ms-excel');
        header("Content-Disposition: attachment;filename=\"{$filename}\"");
        header('Cache-Control: max-age=0');
        $writer->save('php://output');
        exit;
    }
}