<?php

namespace App\Controllers;

use App\Models\TechnicianModel;
use App\Models\TenantsModel;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class TechnicianController extends BaseController
{
    public function technicianList()
    {
        $technicianModel = new TechnicianModel();
        $tenantModel     = new TenantsModel();

        if (isSuperAdmin()) {
            $data['tenants'] = $tenantModel->findAll();
            $selectedTenantId = $this->request->getGet('tenant_id');

            if ($selectedTenantId) {
                $data['technicians'] = $technicianModel
                ->select('technicians.*, tenants.name as tenant_name')
                ->join('tenants', 'tenants.id = technicians.tenant_id', 'left')
                ->where('technicians.tenant_id', $selectedTenantId)
                ->findAll();
            } else {
                $data['technicians'] = $technicianModel
                ->select('technicians.*, tenants.name as tenant_name')
                ->join('tenants', 'tenants.id = technicians.tenant_id', 'left')
                ->findAll();
            }

            $data['selectedTenantId'] = $selectedTenantId;
        } else {
            $tid = currentTenantId();

            $data['technicians'] = $technicianModel
            ->select('technicians.*, tenants.name as tenant_name')
            ->join('tenants', 'tenants.id = technicians.tenant_id', 'left')
            ->where('technicians.tenant_id', $tid)
            ->findAll();
        }

        return view('pen-semen-tech/technician', $data);
    }

    public function addTechnician()
    {
        $technicianModel = new TechnicianModel();

        $data = [
            'name'       => $this->request->getPost('name'),
            'status'     => $this->request->getPost('status'),
            'tenant_id'  => isSuperAdmin()
            ? ($this->request->getPost('tenant_id') !== '' ? $this->request->getPost('tenant_id') : null)
            : currentTenantId(),
            'created_by' => session()->get('user_id'),
            'updated_by' => session()->get('user_id'),
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ];

        if ($technicianModel->insert($data)) {
            return redirect()->to('/pen-semen-tech/technician')->with('success', 'Technician added successfully.');
        } else {
            return redirect()->back()->with('error', 'Failed to add technician.');
        }
    }

    public function editTechnician($id)
    {
        $technicianModel = new TechnicianModel();

        if (!isSuperAdmin()) {
            $exists = $technicianModel->where('id', $id)
            ->where('tenant_id', currentTenantId())
            ->first();
            if (!$exists) {
                return redirect()->back()->with('error', 'Unauthorized.');
            }
        }

        $data = [
            'name'       => $this->request->getPost('name'),
            'status'     => $this->request->getPost('status'),
            'updated_by' => session()->get('user_id'),
            'updated_at' => date('Y-m-d H:i:s'),
        ];

        if (isSuperAdmin() && $this->request->getPost('tenant_id')) {
            $data['tenant_id'] = (int) $this->request->getPost('tenant_id');
        }

        $technicianModel->update($id, $data);

        return redirect()->to('/pen-semen-tech/technician')->with('success', 'Technician updated successfully.');
    }

    public function deleteTechnician($id)
    {
        $technicianModel = new TechnicianModel();

        if (!isSuperAdmin()) {
            $exists = $technicianModel->where('id', $id)
            ->where('tenant_id', currentTenantId())
            ->first();
            if (!$exists) {
                return redirect()->back()->with('error', 'Unauthorized.');
            }
        }

        if ($technicianModel->delete($id)) {
            return redirect()->to('/pen-semen-tech/technician')->with('success', 'Technician deleted successfully.');
        } else {
            return redirect()->to('/pen-semen-tech/technician')->with('error', 'Failed to delete technician.');
        }
    }

    public function exportTechnicians()
    {
        $technicianModel = new TechnicianModel();

        $tenantId = $this->request->getGet('tenant_id');

        if (isSuperAdmin()) {
            if (!empty($tenantId)) {
                $techs = $technicianModel->where('tenant_id', $tenantId)->findAll();
            } else {
                $techs = $technicianModel->findAll();
            }
        } else {
            $techs = $technicianModel->where('tenant_id', currentTenantId())->findAll();
        }

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $sheet->fromArray([['ID', 'Name', 'Status', 'Tenant ID']], NULL, 'A1');

        $row = 2;
        foreach ($techs as $t) {
            $sheet->fromArray([$t['id'], $t['name'], $t['status'], $t['tenant_id']], NULL, "A$row");
            $row++;
        }

        $writer = new Xlsx($spreadsheet);
        $filename = "technicians.xlsx";

        header('Content-Type: application/vnd.ms-excel');
        header("Content-Disposition: attachment; filename=\"$filename\"");
        $writer->save("php://output");
        exit;
    }
}