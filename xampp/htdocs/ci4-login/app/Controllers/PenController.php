<?php

namespace App\Controllers;

use App\Models\AnimalModel;
use App\Models\PenModel;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class PenController extends BaseController
{
    public function penList()
    {
       $penModel = new PenModel();
       $animalModel = new AnimalModel();

       $data['pens'] = $penModel->findAll();

       $animals = $animalModel->select('id, name, pen_id')->findAll();

       $penAnimals = [];
       foreach ($animals as $animal) {
        $penAnimals[$animal['pen_id']][] = $animal;
    }

    $data['penAnimals'] = $penAnimals;

    return view('pen-semen-tech/pen', $data);
}

public function addPen()
{
    $penModel = new PenModel();

    $data = [
        'name' => $this->request->getPost('name')
    ];

    $penModel->insert($data);

    return redirect()->to('/pen-semen-tech/pen')->with('success', 'Pen added successfully.');
}

public function editPen($id)
{
    $penModel = new PenModel();

    $data = [
        'name' => $this->request->getPost('name')
    ];

    $penModel->update($id, $data);

    return redirect()->to('/pen-semen-tech/pen')->with('success', 'Pen updated successfully.');
}
public function deletePen($id)
{
    $penModel = new PenModel();

    $penModel->delete($id);

    return redirect()->to('/pen-semen-tech/pen')->with('success', 'Pen deleted successfully.');
}

public function exportPens()
{
    $penModel = new PenModel();
    $pens = $penModel->findAll();

    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();

    $sheet->fromArray([['ID','Pen Name']], NULL, 'A1');

    $row = 2;
    foreach ($pens as $p) {
        $sheet->fromArray([$p['id'], $p['name']], NULL, "A$row");
        $row++;
    }

    $writer = new Xlsx($spreadsheet);
    $filename = "pens.xlsx";

    header('Content-Type: application/vnd.ms-excel');
    header("Content-Disposition: attachment; filename=\"$filename\"");
    $writer->save("php://output");
    exit;
}

}