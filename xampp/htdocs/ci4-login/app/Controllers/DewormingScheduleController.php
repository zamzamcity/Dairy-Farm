<?php

namespace App\Controllers;

use App\Models\DewormingModel;
use App\Models\DewormingScheduleModel;
use App\Models\TenantsModel;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class DewormingScheduleController extends BaseController
{
    public function dewormingScheduleList()
    {
        $scheduleModel   = new DewormingScheduleModel();
        $dewormingModel  = new DewormingModel();
        $tenantModel     = new TenantsModel();

        if (isSuperAdmin()) {
            $data['tenants'] = $tenantModel->findAll();

            $selectedTenantId = $this->request->getGet('tenant_id');

            if ($selectedTenantId) {
                $data['deworming_schedules'] = $scheduleModel
                ->select('deworming_schedules.*, deworming.name AS deworming_name, tenants.name as tenant_name')
                ->join('deworming', 'deworming.id = deworming_schedules.deworming_id')
                ->join('tenants', 'tenants.id = deworming_schedules.tenant_id', 'left')
                ->where('deworming_schedules.tenant_id', $selectedTenantId)
                ->orderBy('deworming_schedules.date', 'DESC')
                ->findAll();

                $data['dewormings'] = $dewormingModel->where('tenant_id', $selectedTenantId)->findAll();
            } else {
                $data['deworming_schedules'] = $scheduleModel
                ->select('deworming_schedules.*, deworming.name AS deworming_name, tenants.name as tenant_name')
                ->join('deworming', 'deworming.id = deworming_schedules.deworming_id')
                ->join('tenants', 'tenants.id = deworming_schedules.tenant_id', 'left')
                ->orderBy('deworming_schedules.date', 'DESC')
                ->findAll();

                $data['dewormings'] = $dewormingModel->findAll();
            }

            $data['selectedTenantId'] = $selectedTenantId;
        } else {
            $tid = currentTenantId();

            $data['deworming_schedules'] = $scheduleModel
            ->select('deworming_schedules.*, deworming.name AS deworming_name, tenants.name as tenant_name')
            ->join('deworming', 'deworming.id = deworming_schedules.deworming_id')
            ->join('tenants', 'tenants.id = deworming_schedules.tenant_id', 'left')
            ->where('deworming_schedules.tenant_id', $tid)
            ->orderBy('deworming_schedules.date', 'DESC')
            ->findAll();

            $data['dewormings'] = $dewormingModel->where('tenant_id', $tid)->findAll();
        }
        
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
            'tenant_id'    => isSuperAdmin()
            ? ($this->request->getPost('tenant_id') !== '' ? $this->request->getPost('tenant_id') : null)
            : currentTenantId(),
            'created_by'   => session()->get('user_id'),
            'updated_by'   => session()->get('user_id'),
            'created_at'   => date('Y-m-d H:i:s'),
            'updated_at'   => date('Y-m-d H:i:s'),
        ];

        if ($scheduleModel->insert($data)) {
            return redirect()->to('/schedule-events/dewormingSchedule')->with('success', 'Deworming schedule added successfully.');
        } else {
            return redirect()->back()->with('error', 'Failed to add deworming schedule.');
        }
    }

    public function editDewormingSchedule($id)
    {
        $scheduleModel = new DewormingScheduleModel();

        if (!isSuperAdmin()) {
            $exists = $scheduleModel->where('id', $id)
            ->where('tenant_id', currentTenantId())
            ->first();
            if (!$exists) {
                return redirect()->back()->with('error', 'Unauthorized.');
            }
        }

        $data = [
            'month'        => $this->request->getPost('month'),
            'date'         => $this->request->getPost('date'),
            'deworming_id' => $this->request->getPost('deworming_id'),
            'comments'     => $this->request->getPost('comments'),
            'updated_by'   => session()->get('user_id'),
            'updated_at'   => date('Y-m-d H:i:s'),
        ];

        if (isSuperAdmin() && $this->request->getPost('tenant_id')) {
            $data['tenant_id'] = (int) $this->request->getPost('tenant_id');
        }

        $scheduleModel->update($id, $data);

        return redirect()->to('/schedule-events/dewormingSchedule')->with('success', 'Deworming schedule updated successfully.');
    }

    public function deleteDewormingSchedule($id)
    {
        $scheduleModel = new DewormingScheduleModel();

        if (!isSuperAdmin()) {
            $exists = $scheduleModel->where('id', $id)
            ->where('tenant_id', currentTenantId())
            ->first();
            if (!$exists) {
                return redirect()->back()->with('error', 'Unauthorized.');
            }
        }

        if ($scheduleModel->delete($id)) {
            return redirect()->to('/schedule-events/dewormingSchedule')->with('success', 'Deworming schedule deleted successfully.');
        } else {
            return redirect()->to('/schedule-events/dewormingSchedule')->with('error', 'Failed to delete deworming schedule.');
        }
    }

    public function exportDewormingSchedules()
    {
        $scheduleModel = new DewormingScheduleModel();
        $tenantId = $this->request->getGet('tenant_id');

        if (isSuperAdmin()) {
            if (!empty($tenantId)) {
                $schedules = $scheduleModel
                ->select('deworming_schedules.month, deworming_schedules.date, deworming.name as deworming_name, deworming_schedules.tenant_id')
                ->join('deworming', 'deworming.id = deworming_schedules.deworming_id')
                ->where('deworming_schedules.tenant_id', $tenantId)
                ->orderBy('deworming_schedules.date', 'DESC')
                ->findAll();
            } else {
                $schedules = $scheduleModel
                ->select('deworming_schedules.month, deworming_schedules.date, deworming.name as deworming_name, deworming_schedules.tenant_id')
                ->join('deworming', 'deworming.id = deworming_schedules.deworming_id')
                ->orderBy('deworming_schedules.date', 'DESC')
                ->findAll();
            }
        } else {
            $schedules = $scheduleModel
            ->select('deworming_schedules.month, deworming_schedules.date, deworming.name as deworming_name, deworming_schedules.tenant_id')
            ->join('deworming', 'deworming.id = deworming_schedules.deworming_id')
            ->where('deworming_schedules.tenant_id', currentTenantId())
            ->orderBy('deworming_schedules.date', 'DESC')
            ->findAll();
        }

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

    // Header
        $sheet->setCellValue('A1', 'Month')
        ->setCellValue('B1', 'Date')
        ->setCellValue('C1', 'Deworming')
        ->setCellValue('D1', 'Tenant ID');

    // Data
        $row = 2;
        foreach ($schedules as $s) {
            $sheet->setCellValue("A{$row}", $s['month'])
            ->setCellValue("B{$row}", $s['date'])
            ->setCellValue("C{$row}", $s['deworming_name'])
            ->setCellValue("D{$row}", $s['tenant_id']);
            $row++;
        }

        $writer = new Xlsx($spreadsheet);
        $filename = 'deworming_schedules.xlsx';

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header("Content-Disposition: attachment; filename=\"$filename\"");
        $writer->save('php://output');
        exit;
    }
}