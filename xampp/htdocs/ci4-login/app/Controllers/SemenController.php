<?php

namespace App\Controllers;

use App\Models\CompanyModel;
use App\Models\SemenModel;

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

}