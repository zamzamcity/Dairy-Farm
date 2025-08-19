<?php

namespace App\Controllers;

use App\Models\TechnicianModel;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

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

    public function exportTechnicians()
    {
        $technicianModel = new TechnicianModel();
        $techs = $technicianModel->findAll();

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $sheet->fromArray([['ID','Name','Status']], NULL, 'A1');

        $row = 2;
        foreach ($techs as $t) {
            $sheet->fromArray([$t['id'],$t['name'],$t['status']], NULL, "A$row");
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