<?php

namespace App\Controllers;

use App\Models\AnimalMilkModel;
use App\Models\AnimalModel;

class AnimalMilkController extends BaseController
{
    public function animalMilkList()
    {
        $milkModel    = new AnimalMilkModel();
        $animalModel  = new AnimalModel();

    $date = $this->request->getGet('date') ?? date('Y-m-d'); // default to today

    $builder = $milkModel
    ->select('animal_milk.*, animals.tag_id')
    ->join('animals', 'animals.id = animal_milk.animal_id');

    if ($date) {
        $builder->where('animal_milk.date', $date);
    }

    $data['animal_milk'] = $builder->orderBy('animal_milk.date', 'DESC')->findAll();
    $data['female_animals'] = $animalModel->where('sex', 'Female')->findAll();

    return view('animal-milking/animalMilk', $data);
}


public function addAnimalMilk()
{
    $milkModel = new AnimalMilkModel();

    $data = [
        'date'               => $this->request->getPost('date'),
        'animal_id'          => $this->request->getPost('animal_id'),
        'first_calving_date' => $this->request->getPost('first_calving_date'),
        'last_calving_date'  => $this->request->getPost('last_calving_date'),
        'milk_1'             => $this->request->getPost('milk_1'),
        'milk_2'             => $this->request->getPost('milk_2'),
        'milk_3'             => $this->request->getPost('milk_3'),
    ];

    $milkModel->insert($data);

    return redirect()->to('/animal-milking/animalMilk')->with('success', 'Animal milk record added successfully.');
}

public function editAnimalMilk($id)
{
    $milkModel = new AnimalMilkModel();

    $data = [
        'date'               => $this->request->getPost('date'),
        'animal_id'          => $this->request->getPost('animal_id'),
        'first_calving_date' => $this->request->getPost('first_calving_date'),
        'last_calving_date'  => $this->request->getPost('last_calving_date'),
        'milk_1'             => $this->request->getPost('milk_1'),
        'milk_2'             => $this->request->getPost('milk_2'),
        'milk_3'             => $this->request->getPost('milk_3'),
    ];

    $milkModel->update($id, $data);

    return redirect()->to('/animal-milking/animalMilk')->with('success', 'Animal milk record updated successfully.');
}

public function deleteAnimalMilk($id)
{
    $milkModel = new AnimalMilkModel();
    $milkModel->delete($id);

    return redirect()->to('/animal-milking/animalMilk')->with('success', 'Animal milk record deleted successfully.');
}
}