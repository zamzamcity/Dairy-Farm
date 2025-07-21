<?php

namespace App\Controllers;

use App\Models\AnimalModel;
use App\Models\PenModel;

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

}