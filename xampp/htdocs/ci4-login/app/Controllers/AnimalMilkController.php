<?php

namespace App\Controllers;

use App\Models\AnimalMilkModel;
use App\Models\AnimalModel;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

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

public function exportAnimalMilk()
{
    $milkModel    = new AnimalMilkModel();

    $date = $this->request->getGet('date'); // from filter

    $builder = $milkModel
    ->select('animal_milk.*, animals.tag_id')
    ->join('animals', 'animals.id = animal_milk.animal_id');

    if (!empty($date)) {
        $builder->where('animal_milk.date', $date);
    }

    $records = $builder->orderBy('animal_milk.date', 'DESC')->findAll();

    // Create spreadsheet
    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();

    // Headers
    $sheet->setCellValue('A1', 'ID');
    $sheet->setCellValue('B1', 'Date');
    $sheet->setCellValue('C1', 'Tag ID');
    $sheet->setCellValue('D1', 'First Calving Date');
    $sheet->setCellValue('E1', 'Last Calving Date');
    $sheet->setCellValue('F1', 'Milk 1 (L)');
    $sheet->setCellValue('G1', 'Milk 2 (L)');
    $sheet->setCellValue('H1', 'Milk 3 (L)');

    // Fill data
    $row = 2;
    foreach ($records as $record) {
        $sheet->setCellValue('A'.$row, $record['id']);
        $sheet->setCellValue('B'.$row, $record['date']);
        $sheet->setCellValue('C'.$row, $record['tag_id']);
        $sheet->setCellValue('D'.$row, $record['first_calving_date']);
        $sheet->setCellValue('E'.$row, $record['last_calving_date']);
        $sheet->setCellValue('F'.$row, $record['milk_1']);
        $sheet->setCellValue('G'.$row, $record['milk_2']);
        $sheet->setCellValue('H'.$row, $record['milk_3']);
        $row++;
    }

    // Export file
    $writer = new Xlsx($spreadsheet);
    $filename = 'animal_milk_export_'.date('YmdHis').'.xlsx';

    // Set headers for download
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment;filename="'. $filename .'"'); 
    header('Cache-Control: max-age=0');

    $writer->save('php://output');
    exit();
}

}