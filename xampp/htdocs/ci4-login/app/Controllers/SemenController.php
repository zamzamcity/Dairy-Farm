<?php

namespace App\Controllers;

use App\Models\CompanyModel;
use App\Models\SemenModel;
use App\Models\TenantsModel;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class SemenController extends BaseController
{
    public function semenList()
    {
        $semenModel   = new SemenModel();
        $companyModel = new CompanyModel();
        $tenantModel  = new TenantsModel();

        if (isSuperAdmin()) {
            $data['tenants'] = $tenantModel->findAll();
            $selectedTenantId = $this->request->getGet('tenant_id');

            if ($selectedTenantId) {
                $data['semen'] = $semenModel
                ->select('semen.*, companies.name as company_name, tenants.name as tenant_name')
                ->join('companies', 'companies.id = semen.company_id', 'left')
                ->join('tenants', 'tenants.id = semen.tenant_id', 'left')
                ->where('semen.tenant_id', $selectedTenantId)
                ->findAll();

                $data['companies'] = $companyModel->where('tenant_id', $selectedTenantId)->findAll();
            } else {
                $data['semen'] = $semenModel
                ->select('semen.*, companies.name as company_name, tenants.name as tenant_name')
                ->join('companies', 'companies.id = semen.company_id', 'left')
                ->join('tenants', 'tenants.id = semen.tenant_id', 'left')
                ->findAll();

                $data['companies'] = $companyModel->findAll();
            }

            $data['selectedTenantId'] = $selectedTenantId;
        } else {
            $tid = currentTenantId();

            $data['semen'] = $semenModel
            ->select('semen.*, companies.name as company_name, tenants.name as tenant_name')
            ->join('companies', 'companies.id = semen.company_id', 'left')
            ->join('tenants', 'tenants.id = semen.tenant_id', 'left')
            ->where('semen.tenant_id', $tid)
            ->findAll();

            $data['companies'] = $companyModel->where('tenant_id', $tid)->findAll();
        }

        return view('pen-semen-tech/semen', $data);
    }

    public function addSemen()
    {
        $semenModel = new SemenModel();

        $data = [
            'sire_name'      => $this->request->getPost('sire_name'),
            'rate_per_semen' => $this->request->getPost('rate_per_semen'),
            'company_id'     => $this->request->getPost('company_id'),
            'type'           => $this->request->getPost('type'),
            'tenant_id'      => isSuperAdmin()
            ? ($this->request->getPost('tenant_id') !== '' ? $this->request->getPost('tenant_id') : null)
            : currentTenantId(),
            'created_by'     => session()->get('user_id'),
            'updated_by'     => session()->get('user_id'),
            'created_at'     => date('Y-m-d H:i:s'),
            'updated_at'     => date('Y-m-d H:i:s'),
        ];

        if ($semenModel->insert($data)) {
            return redirect()->to('/pen-semen-tech/semen')->with('success', 'Semen entry added successfully.');
        } else {
            return redirect()->back()->with('error', 'Failed to add semen entry.');
        }
    }

    public function editSemen($id)
    {
        $semenModel = new SemenModel();

        if (!isSuperAdmin()) {
            $exists = $semenModel->where('id', $id)
            ->where('tenant_id', currentTenantId())
            ->first();
            if (!$exists) {
                return redirect()->back()->with('error', 'Unauthorized.');
            }
        }

        $data = [
            'sire_name'      => $this->request->getPost('sire_name'),
            'rate_per_semen' => $this->request->getPost('rate_per_semen'),
            'company_id'     => $this->request->getPost('company_id'),
            'type'           => $this->request->getPost('type'),
            'updated_by'     => session()->get('user_id'),
            'updated_at'     => date('Y-m-d H:i:s'),
        ];

        if (isSuperAdmin() && $this->request->getPost('tenant_id')) {
            $data['tenant_id'] = (int) $this->request->getPost('tenant_id');
        }

        $semenModel->update($id, $data);

        return redirect()->to('/pen-semen-tech/semen')->with('success', 'Semen entry updated successfully.');
    }

    public function deleteSemen($id)
    {
        $semenModel = new SemenModel();

        if (!isSuperAdmin()) {
            $exists = $semenModel->where('id', $id)
            ->where('tenant_id', currentTenantId())
            ->first();
            if (!$exists) {
                return redirect()->back()->with('error', 'Unauthorized.');
            }
        }

        if ($semenModel->delete($id)) {
            return redirect()->to('/pen-semen-tech/semen')->with('success', 'Semen entry deleted successfully.');
        } else {
            return redirect()->to('/pen-semen-tech/semen')->with('error', 'Failed to delete semen entry.');
        }
    }

    public function exportSemen()
    {
        $semenModel = new SemenModel();

        $tenantId = $this->request->getGet('tenant_id');

        if (isSuperAdmin()) {
            if (!empty($tenantId)) {
                $data = $semenModel
                ->select('semen.id, semen.sire_name, semen.rate_per_semen, semen.type, companies.name as company, semen.tenant_id')
                ->join('companies', 'companies.id = semen.company_id', 'left')
                ->where('semen.tenant_id', $tenantId)
                ->findAll();
            } else {
                $data = $semenModel
                ->select('semen.id, semen.sire_name, semen.rate_per_semen, semen.type, companies.name as company, semen.tenant_id')
                ->join('companies', 'companies.id = semen.company_id', 'left')
                ->findAll();
            }
        } else {
            $data = $semenModel
            ->select('semen.id, semen.sire_name, semen.rate_per_semen, semen.type, companies.name as company, semen.tenant_id')
            ->join('companies', 'companies.id = semen.company_id', 'left')
            ->where('semen.tenant_id', currentTenantId())
            ->findAll();
        }

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

    // Header
        $sheet->setCellValue('A1', 'ID')
        ->setCellValue('B1', 'Sire Name')
        ->setCellValue('C1', 'Rate per Semen')
        ->setCellValue('D1', 'Type')
        ->setCellValue('E1', 'Company')
        ->setCellValue('F1', 'Tenant ID');

    // Data
        $row = 2;
        foreach ($data as $s) {
            $sheet->setCellValue('A' . $row, $s['id'])
            ->setCellValue('B' . $row, $s['sire_name'])
            ->setCellValue('C' . $row, $s['rate_per_semen'])
            ->setCellValue('D' . $row, $s['type'])
            ->setCellValue('E' . $row, $s['company'])
            ->setCellValue('F' . $row, $s['tenant_id']);
            $row++;
        }

        $writer = new Xlsx($spreadsheet);
        $filename = 'semen.xlsx';

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header("Content-Disposition: attachment; filename=\"$filename\"");
        $writer->save('php://output');
        exit;
    }


}