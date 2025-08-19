<?php

namespace App\Controllers;

use App\Models\TenantsModel;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class TenantsController extends BaseController
{    
    public function tenants()
    {
        if (!isSuperAdmin()) {
            return redirect()->to('/')->with('error', 'Unauthorized.');
        }

        $tenantModel = new TenantsModel();
        $data['tenants'] = $tenantModel->findAll();

        return view('tenants', $data);
    }

    public function addTenant()
    {
        if (!isSuperAdmin()) return redirect()->back()->with('error', 'Unauthorized.');

        $tenantModel = new TenantsModel();

        $data = [
            'name'       => $this->request->getPost('name'),
            'status'     => $this->request->getPost('status') ?? 'active',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ];

        $tenantModel->insert($data);

        return redirect()->back()->with('success', 'Tenant created successfully.');
    }

    public function editTenant($id)
    {
        if (!isSuperAdmin()) return redirect()->back()->with('error', 'Unauthorized.');

        $tenantModel = new TenantsModel();

        $data = [
            'name'       => $this->request->getPost('name'),
            'status'     => $this->request->getPost('status'),
            'updated_at' => date('Y-m-d H:i:s'),
        ];

        $tenantModel->update($id, $data);

        return redirect()->to('/tenants')->with('success', 'Tenant updated successfully.');
    }

    public function deleteTenant($id)
    {
        if (!isSuperAdmin()) return redirect()->back()->with('error', 'Unauthorized.');

        $tenantModel = new TenantsModel();
        $tenantModel->delete($id);

        return redirect()->to('/tenants')->with('success', 'Tenant deleted successfully.');
    }

    public function downloadTenants()
    {
        $tenantModel = new TenantsModel();
        $tenants = $tenantModel->findAll();

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Header
        $sheet->setCellValue('A1', 'ID')
        ->setCellValue('B1', 'Name')
        ->setCellValue('C1', 'Status')
        ->setCellValue('D1', 'Created At')
        ->setCellValue('E1', 'Updated At');

        // Data
        $row = 2;
        foreach ($tenants as $tenant) {
            $sheet->setCellValue('A'.$row, $tenant['id'])
            ->setCellValue('B'.$row, $tenant['name'])
            ->setCellValue('C'.$row, ucfirst($tenant['status']))
            ->setCellValue('D'.$row, $tenant['created_at'])
            ->setCellValue('E'.$row, $tenant['updated_at']);
            $row++;
        }

        $writer = new Xlsx($spreadsheet);
        $filename = 'tenants.xlsx';

        // Force download
        header('Content-Type: application/vnd.ms-excel');
        header("Content-Disposition: attachment; filename=\"$filename\"");
        $writer->save('php://output');
        exit;
    }
}