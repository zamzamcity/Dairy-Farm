<?php

namespace App\Controllers;

use App\Models\ScheduleModel;
use App\Models\AnimalModel;
use App\Models\EventModel;
use App\Models\TenantsModel;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class ScheduleController extends BaseController
{
    public function scheduleList()
    {
        $scheduleModel = new ScheduleModel();
        $animalModel   = new AnimalModel();
        $eventModel    = new EventModel();
        $tenantModel   = new TenantsModel();

        if (isSuperAdmin()) {
            $data['tenants'] = $tenantModel->findAll();

            $selectedTenantId = $this->request->getGet('tenant_id');

            if ($selectedTenantId) {
                $data['schedules'] = $scheduleModel
                ->select('schedules.*, animals.tag_id as animal_tag, events.name as event_name, tenants.name as tenant_name')
                ->join('animals', 'animals.id = schedules.tag_id')
                ->join('events', 'events.id = schedules.event_id')
                ->join('tenants', 'tenants.id = schedules.tenant_id', 'left')
                ->where('schedules.tenant_id', $selectedTenantId)
                ->orderBy('schedules.date', 'DESC')
                ->findAll();

                $data['animals'] = $animalModel->where('tenant_id', $selectedTenantId)->findAll();
                $data['events']  = $eventModel->where('tenant_id', $selectedTenantId)->findAll();
            } else {
                $data['schedules'] = $scheduleModel
                ->select('schedules.*, animals.tag_id as animal_tag, events.name as event_name, tenants.name as tenant_name')
                ->join('animals', 'animals.id = schedules.tag_id')
                ->join('events', 'events.id = schedules.event_id')
                ->join('tenants', 'tenants.id = schedules.tenant_id', 'left')
                ->orderBy('schedules.date', 'DESC')
                ->findAll();

                $data['animals'] = $animalModel->findAll();
                $data['events']  = $eventModel->findAll();
            }

            $data['selectedTenantId'] = $selectedTenantId;
        } else {
            $tid = currentTenantId();

            $data['schedules'] = $scheduleModel
            ->select('schedules.*, animals.tag_id as animal_tag, events.name as event_name, tenants.name as tenant_name')
            ->join('animals', 'animals.id = schedules.tag_id')
            ->join('events', 'events.id = schedules.event_id')
            ->join('tenants', 'tenants.id = schedules.tenant_id', 'left')
            ->where('schedules.tenant_id', $tid)
            ->orderBy('schedules.date', 'DESC')
            ->findAll();

            $data['animals'] = $animalModel->where('tenant_id', $tid)->findAll();
            $data['events']  = $eventModel->where('tenant_id', $tid)->findAll();
        }

        return view('schedule-events/schedule', $data);
    }

    public function addSchedule()
    {
        $scheduleModel = new ScheduleModel();

        $data = [
            'tag_id'     => $this->request->getPost('tag_id'),
            'date'       => $this->request->getPost('date'),
            'time'       => $this->request->getPost('time'),
            'event_id'   => $this->request->getPost('event_id'),
            'comments'   => $this->request->getPost('comments'),
            'tenant_id'  => isSuperAdmin()
            ? ($this->request->getPost('tenant_id') !== '' ? $this->request->getPost('tenant_id') : null)
            : currentTenantId(),
            'created_by' => session()->get('user_id'),
            'updated_by' => session()->get('user_id'),
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ];

        if ($scheduleModel->insert($data)) {
            return redirect()->to('/schedule-events/schedule')->with('success', 'Schedule added successfully.');
        } else {
            return redirect()->back()->with('error', 'Failed to add schedule.');
        }
    }

    public function editSchedule($id)
    {
        $scheduleModel = new ScheduleModel();

    // Tenant security check
        if (!isSuperAdmin()) {
            $exists = $scheduleModel->where('id', $id)
            ->where('tenant_id', currentTenantId())
            ->first();
            if (!$exists) {
                return redirect()->back()->with('error', 'Unauthorized.');
            }
        }

        $data = [
            'tag_id'     => $this->request->getPost('tag_id'),
            'date'       => $this->request->getPost('date'),
            'time'       => $this->request->getPost('time'),
            'event_id'   => $this->request->getPost('event_id'),
            'comments'   => $this->request->getPost('comments'),
            'updated_by' => session()->get('user_id'),
            'updated_at' => date('Y-m-d H:i:s'),
        ];

        if (isSuperAdmin() && $this->request->getPost('tenant_id')) {
            $data['tenant_id'] = (int) $this->request->getPost('tenant_id');
        }

        $scheduleModel->update($id, $data);

        return redirect()->to('/schedule-events/schedule')->with('success', 'Schedule updated successfully.');
    }

    public function deleteSchedule($id)
    {
        $scheduleModel = new ScheduleModel();

        if (!isSuperAdmin()) {
            $exists = $scheduleModel->where('id', $id)
            ->where('tenant_id', currentTenantId())
            ->first();
            if (!$exists) {
                return redirect()->back()->with('error', 'Unauthorized.');
            }
        }

        if ($scheduleModel->delete($id)) {
            return redirect()->to('/schedule-events/schedule')->with('success', 'Schedule deleted successfully.');
        } else {
            return redirect()->to('/schedule-events/schedule')->with('error', 'Failed to delete schedule.');
        }
    }

    public function exportSchedules()
    {
        $scheduleModel = new ScheduleModel();
        $tenantId = $this->request->getGet('tenant_id');

        if (isSuperAdmin()) {
            if (!empty($tenantId)) {
                $schedules = $scheduleModel
                ->select('schedules.date, schedules.time, animals.tag_id as animal_tag, events.name as event_name, schedules.comments, schedules.tenant_id')
                ->join('animals', 'animals.id = schedules.tag_id')
                ->join('events', 'events.id = schedules.event_id')
                ->where('schedules.tenant_id', $tenantId)
                ->orderBy('schedules.date', 'DESC')
                ->findAll();
            } else {
                $schedules = $scheduleModel
                ->select('schedules.date, schedules.time, animals.tag_id as animal_tag, events.name as event_name, schedules.comments, schedules.tenant_id')
                ->join('animals', 'animals.id = schedules.tag_id')
                ->join('events', 'events.id = schedules.event_id')
                ->orderBy('schedules.date', 'DESC')
                ->findAll();
            }
        } else {
            $schedules = $scheduleModel
            ->select('schedules.date, schedules.time, animals.tag_id as animal_tag, events.name as event_name, schedules.comments, schedules.tenant_id')
            ->join('animals', 'animals.id = schedules.tag_id')
            ->join('events', 'events.id = schedules.event_id')
            ->where('schedules.tenant_id', currentTenantId())
            ->orderBy('schedules.date', 'DESC')
            ->findAll();
        }

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

    // Header
        $sheet->setCellValue('A1', 'Date')
        ->setCellValue('B1', 'Time')
        ->setCellValue('C1', 'Animal Tag')
        ->setCellValue('D1', 'Event')
        ->setCellValue('E1', 'Comments')
        ->setCellValue('F1', 'Tenant ID');

    // Data
        $row = 2;
        foreach ($schedules as $s) {
            $sheet->setCellValue("A{$row}", $s['date'])
            ->setCellValue("B{$row}", $s['time'])
            ->setCellValue("C{$row}", $s['animal_tag'])
            ->setCellValue("D{$row}", $s['event_name'])
            ->setCellValue("E{$row}", $s['comments'])
            ->setCellValue("F{$row}", $s['tenant_id']);
            $row++;
        }

        $writer = new Xlsx($spreadsheet);
        $filename = 'schedules.xlsx';

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header("Content-Disposition: attachment; filename=\"$filename\"");
        $writer->save('php://output');
        exit;
    }
}