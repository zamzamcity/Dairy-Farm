<?php

namespace App\Controllers;

use App\Models\VaccinationModel;
use App\Models\VaccinationScheduleModel;
use App\Models\TenantsModel;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class VaccinationScheduleController extends BaseController
{
    public function vaccinationScheduleList()
    {
        $scheduleModel     = new VaccinationScheduleModel();
        $vaccinationModel  = new VaccinationModel();
        $tenantModel       = new TenantsModel();

        if (isSuperAdmin()) {
            $data['tenants'] = $tenantModel->findAll();

            $selectedTenantId = $this->request->getGet('tenant_id');

            if ($selectedTenantId) {
                $data['vaccination_schedules'] = $scheduleModel
                ->select('vaccination_schedules.*, vaccinations.name AS vaccination_name, tenants.name as tenant_name')
                ->join('vaccinations', 'vaccinations.id = vaccination_schedules.vaccination_id')
                ->join('tenants', 'tenants.id = vaccination_schedules.tenant_id', 'left')
                ->where('vaccination_schedules.tenant_id', $selectedTenantId)
                ->orderBy('vaccination_schedules.date', 'DESC')
                ->findAll();

                $data['vaccinations'] = $vaccinationModel->where('tenant_id', $selectedTenantId)->findAll();
            } else {
                $data['vaccination_schedules'] = $scheduleModel
                ->select('vaccination_schedules.*, vaccinations.name AS vaccination_name, tenants.name as tenant_name')
                ->join('vaccinations', 'vaccinations.id = vaccination_schedules.vaccination_id')
                ->join('tenants', 'tenants.id = vaccination_schedules.tenant_id', 'left')
                ->orderBy('vaccination_schedules.date', 'DESC')
                ->findAll();

                $data['vaccinations'] = $vaccinationModel->findAll();
            }

            $data['selectedTenantId'] = $selectedTenantId;
        } else {
            $tid = currentTenantId();

            $data['vaccination_schedules'] = $scheduleModel
            ->select('vaccination_schedules.*, vaccinations.name AS vaccination_name, tenants.name as tenant_name')
            ->join('vaccinations', 'vaccinations.id = vaccination_schedules.vaccination_id')
            ->join('tenants', 'tenants.id = vaccination_schedules.tenant_id', 'left')
            ->where('vaccination_schedules.tenant_id', $tid)
            ->orderBy('vaccination_schedules.date', 'DESC')
            ->findAll();

            $data['vaccinations'] = $vaccinationModel->where('tenant_id', $tid)->findAll();
        }

        $data['months'] = [
            'January', 'February', 'March', 'April', 'May', 'June',
            'July', 'August', 'September', 'October', 'November', 'December'
        ];

        return view('schedule-events/vaccinationSchedule', $data);
    }

    public function addVaccinationSchedule()
    {
        $scheduleModel = new VaccinationScheduleModel();

        $data = [
            'month'          => $this->request->getPost('month'),
            'date'           => $this->request->getPost('date'),
            'vaccination_id' => $this->request->getPost('vaccination_id'),
            'comments'       => $this->request->getPost('comments'),
            'tenant_id'      => isSuperAdmin()
            ? ($this->request->getPost('tenant_id') !== '' ? $this->request->getPost('tenant_id') : null)
            : currentTenantId(),
            'created_by'     => session()->get('user_id'),
            'updated_by'     => session()->get('user_id'),
            'created_at'     => date('Y-m-d H:i:s'),
            'updated_at'     => date('Y-m-d H:i:s'),
        ];

        if ($scheduleModel->insert($data)) {
            return redirect()->to('/schedule-events/vaccinationSchedule')->with('success', 'Vaccination schedule added successfully.');
        } else {
            return redirect()->back()->with('error', 'Failed to add vaccination schedule.');
        }
    }

    public function editVaccinationSchedule($id)
    {
        $scheduleModel = new VaccinationScheduleModel();

        if (!isSuperAdmin()) {
            $exists = $scheduleModel->where('id', $id)
            ->where('tenant_id', currentTenantId())
            ->first();
            if (!$exists) {
                return redirect()->back()->with('error', 'Unauthorized.');
            }
        }

        $data = [
            'month'          => $this->request->getPost('month'),
            'date'           => $this->request->getPost('date'),
            'vaccination_id' => $this->request->getPost('vaccination_id'),
            'comments'       => $this->request->getPost('comments'),
            'updated_by'     => session()->get('user_id'),
            'updated_at'     => date('Y-m-d H:i:s'),
        ];

        if (isSuperAdmin() && $this->request->getPost('tenant_id')) {
            $data['tenant_id'] = (int) $this->request->getPost('tenant_id');
        }

        $scheduleModel->update($id, $data);

        return redirect()->to('/schedule-events/vaccinationSchedule')->with('success', 'Vaccination schedule updated successfully.');
    }

    public function deleteVaccinationSchedule($id)
    {
        $scheduleModel = new VaccinationScheduleModel();

        if (!isSuperAdmin()) {
            $exists = $scheduleModel->where('id', $id)
            ->where('tenant_id', currentTenantId())
            ->first();
            if (!$exists) {
                return redirect()->back()->with('error', 'Unauthorized.');
            }
        }

        if ($scheduleModel->delete($id)) {
            return redirect()->to('/schedule-events/vaccinationSchedule')->with('success', 'Vaccination schedule deleted successfully.');
        } else {
            return redirect()->to('/schedule-events/vaccinationSchedule')->with('error', 'Failed to delete vaccination schedule.');
        }
    }

    public function exportVaccinationSchedules()
    {
        $scheduleModel = new VaccinationScheduleModel();
        $tenantId = $this->request->getGet('tenant_id');

        if (isSuperAdmin()) {
            if (!empty($tenantId)) {
                $schedules = $scheduleModel
                ->select('vaccination_schedules.month, vaccination_schedules.date, vaccinations.name as vaccination_name, vaccination_schedules.comments, vaccination_schedules.tenant_id')
                ->join('vaccinations', 'vaccinations.id = vaccination_schedules.vaccination_id')
                ->where('vaccination_schedules.tenant_id', $tenantId)
                ->orderBy('vaccination_schedules.date', 'DESC')
                ->findAll();
            } else {
                $schedules = $scheduleModel
                ->select('vaccination_schedules.month, vaccination_schedules.date, vaccinations.name as vaccination_name, vaccination_schedules.comments, vaccination_schedules.tenant_id')
                ->join('vaccinations', 'vaccinations.id = vaccination_schedules.vaccination_id')
                ->orderBy('vaccination_schedules.date', 'DESC')
                ->findAll();
            }
        } else {
            $schedules = $scheduleModel
            ->select('vaccination_schedules.month, vaccination_schedules.date, vaccinations.name as vaccination_name, vaccination_schedules.comments, vaccination_schedules.tenant_id')
            ->join('vaccinations', 'vaccinations.id = vaccination_schedules.vaccination_id')
            ->where('vaccination_schedules.tenant_id', currentTenantId())
            ->orderBy('vaccination_schedules.date', 'DESC')
            ->findAll();
        }

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

    // Header
        $sheet->setCellValue('A1', 'Month')
        ->setCellValue('B1', 'Date')
        ->setCellValue('C1', 'Vaccination')
        ->setCellValue('D1', 'Comments')
        ->setCellValue('E1', 'Tenant ID');

    // Data
        $row = 2;
        foreach ($schedules as $s) {
            $sheet->setCellValue("A{$row}", $s['month'])
            ->setCellValue("B{$row}", $s['date'])
            ->setCellValue("C{$row}", $s['vaccination_name'])
            ->setCellValue("D{$row}", $s['comments'])
            ->setCellValue("E{$row}", $s['tenant_id']);
            $row++;
        }

        $writer = new Xlsx($spreadsheet);
        $filename = 'vaccination_schedules.xlsx';

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header("Content-Disposition: attachment; filename=\"$filename\"");
        $writer->save('php://output');
        exit;
    }
}