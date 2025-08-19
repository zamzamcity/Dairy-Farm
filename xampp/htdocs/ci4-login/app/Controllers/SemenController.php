<?php

namespace App\Controllers;

use App\Models\CompanyModel;
use App\Models\SemenModel;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class SemenController extends BaseController
{
    public function semenList()
    {
        $semenModel = new SemenModel();
        $companyModel = new CompanyModel();

        $data['semen'] = $semenModel
        ->select('semen.*, companies.name as company_name')
        ->join('companies', 'companies.id = semen.company_id', 'left')
        ->findAll();

        $data['companies'] = $companyModel->findAll();

        return view('pen-semen-tech/semen', $data);
    }

    public function addSemen()
    {
        $semenModel = new SemenModel();

        $data = [
            'sire_name'      => $this->request->getPost('sire_name'),
            'rate_per_semen' => $this->request->getPost('rate_per_semen'),
            'company_id'     => $this->request->getPost('company_id'),
            'type'           => $this->request->getPost('type')
        ];

        $semenModel->insert($data);

        return redirect()->to('/pen-semen-tech/semen')->with('success', 'Semen entry added successfully.');
    }

    public function editSemen($id)
    {
        $semenModel = new SemenModel();

        $data = [
            'sire_name'      => $this->request->getPost('sire_name'),
            'rate_per_semen' => $this->request->getPost('rate_per_semen'),
            'company_id'     => $this->request->getPost('company_id'),
            'type'           => $this->request->getPost('type')
        ];

        $semenModel->update($id, $data);

        return redirect()->to('/pen-semen-tech/semen')->with('success', 'Semen entry updated successfully.');
    }

    public function deleteSemen($id)
    {
        $semenModel = new SemenModel();
        $semenModel->delete($id);

        return redirect()->to('/pen-semen-tech/semen')->with('success', 'Semen entry deleted successfully.');
    }

    public function exportSemen()
    {
        $semenModel = new SemenModel();
        $data = $semenModel
        ->select('semen.id, semen.sire_name, semen.rate_per_semen, semen.type, companies.name as company')
        ->join('companies', 'companies.id = semen.company_id', 'left')
        ->findAll();

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $sheet->fromArray([['ID','Sire Name','Rate','Type','Company']], NULL, 'A1');

        $row = 2;
        foreach ($data as $s) {
            $sheet->fromArray([$s['id'],$s['sire_name'],$s['rate_per_semen'],$s['type'],$s['company']], NULL, "A$row");
            $row++;
        }

        $writer = new Xlsx($spreadsheet);
        $filename = "semen.xlsx";

        header('Content-Type: application/vnd.ms-excel');
        header("Content-Disposition: attachment; filename=\"$filename\"");
        $writer->save("php://output");
        exit;
    }


}