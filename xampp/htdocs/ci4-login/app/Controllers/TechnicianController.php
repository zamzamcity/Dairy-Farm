<?php

namespace App\Controllers;

use App\Models\TechnicianModel;

class TechnicianController extends BaseController
{
    public function technicianList()
    {
        $technicianModel = new TechnicianModel();

        $data['technicians'] = $technicianModel->findAll();

        return view('pen-semen-tech/technician', $data);
    }

    public function addTechnician()
    {
        $technicianModel = new TechnicianModel();

        $data = [
            'name'   => $this->request->getPost('name'),
            'status' => $this->request->getPost('status')
        ];

        $technicianModel->insert($data);

        return redirect()->to('/pen-semen-tech/technician')->with('success', 'Technician added successfully.');
    }

    public function editTechnician($id)
    {
        $technicianModel = new TechnicianModel();

        $data = [
            'name'   => $this->request->getPost('name'),
            'status' => $this->request->getPost('status')
        ];

        $technicianModel->update($id, $data);

        return redirect()->to('/pen-semen-tech/technician')->with('success', 'Technician updated successfully.');
    }

    public function deleteTechnician($id)
    {
        $technicianModel = new TechnicianModel();
        $technicianModel->delete($id);

        return redirect()->to('/pen-semen-tech/technician')->with('success', 'Technician deleted successfully.');
    }
}