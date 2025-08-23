<?php

namespace App\Controllers;

use App\Models\AnimalMilkModel;
use App\Models\AnimalModel;
use App\Models\TenantsModel;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class AnimalMilkController extends BaseController
{
    public function animalMilkList()
    {
        $milkModel   = new AnimalMilkModel();
        $animalModel = new AnimalModel();
        $tenantModel = new TenantsModel();

        $date = $this->request->getGet('date') ?? date('Y-m-d');

        if (isSuperAdmin()) {
            $data['tenants'] = $tenantModel->findAll();

            $selectedTenantId = $this->request->getGet('tenant_id'); 

            if ($selectedTenantId) {
                $data['animal_milk'] = $milkModel
                ->select('animal_milk.*, animals.tag_id, tenants.name as tenant_name')
                ->join('animals', 'animals.id = animal_milk.animal_id', 'left')
                ->join('tenants', 'tenants.id = animal_milk.tenant_id', 'left')
                ->where('animal_milk.tenant_id', $selectedTenantId)
                ->where('animal_milk.date', $date)
                ->orderBy('animal_milk.date', 'DESC')
                ->findAll();

                $data['female_animals'] = $animalModel->where('sex', 'Female')->where('tenant_id', $selectedTenantId)->findAll();
            } else {
                $data['animal_milk'] = $milkModel
                ->select('animal_milk.*, animals.tag_id, tenants.name as tenant_name')
                ->join('animals', 'animals.id = animal_milk.animal_id', 'left')
                ->join('tenants', 'tenants.id = animal_milk.tenant_id', 'left')
                ->where('animal_milk.date', $date)
                ->orderBy('animal_milk.date', 'DESC')
                ->findAll();

                $data['female_animals'] = $animalModel->where('sex', 'Female')->findAll();
            }

            $data['selectedTenantId'] = $selectedTenantId;
        } else {
            $tid = currentTenantId();

            $data['animal_milk'] = $milkModel
            ->select('animal_milk.*, animals.tag_id, tenants.name as tenant_name')
            ->join('animals', 'animals.id = animal_milk.animal_id', 'left')
            ->join('tenants', 'tenants.id = animal_milk.tenant_id', 'left')
            ->where('animal_milk.tenant_id', $tid)
            ->where('animal_milk.date', $date)
            ->orderBy('animal_milk.date', 'DESC')
            ->findAll();

            $data['female_animals'] = $animalModel->where('sex', 'Female')->where('tenant_id', $tid)->findAll();
        }

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
            'tenant_id'          => isSuperAdmin()
            ? ($this->request->getPost('tenant_id') !== '' ? $this->request->getPost('tenant_id') : null)
            : currentTenantId(),
            'created_by'         => session()->get('user_id'),
            'updated_by'         => session()->get('user_id'),
            'created_at'         => date('Y-m-d H:i:s'),
            'updated_at'         => date('Y-m-d H:i:s'),
        ];

        if ($milkModel->insert($data)) {
            return redirect()->to('/animal-milking/animalMilk')->with('success', 'Animal milk record added successfully.');
        } else {
            return redirect()->back()->with('error', 'Failed to add animal milk record.');
        }
    }

    public function editAnimalMilk($id)
    {
        $milkModel = new AnimalMilkModel();

        if (!isSuperAdmin()) {
            $exists = $milkModel->where('id', $id)
            ->where('tenant_id', currentTenantId())
            ->first();
            if (!$exists) {
                return redirect()->back()->with('error', 'Unauthorized.');
            }
        }

        $data = [
            'date'               => $this->request->getPost('date'),
            'animal_id'          => $this->request->getPost('animal_id'),
            'first_calving_date' => $this->request->getPost('first_calving_date'),
            'last_calving_date'  => $this->request->getPost('last_calving_date'),
            'milk_1'             => $this->request->getPost('milk_1'),
            'milk_2'             => $this->request->getPost('milk_2'),
            'milk_3'             => $this->request->getPost('milk_3'),
            'updated_by'         => session()->get('user_id'),
            'updated_at'         => date('Y-m-d H:i:s'),
        ];

        if (isSuperAdmin() && $this->request->getPost('tenant_id')) {
            $data['tenant_id'] = (int) $this->request->getPost('tenant_id');
        }

        $milkModel->update($id, $data);

        return redirect()->to('/animal-milking/animalMilk')->with('success', 'Animal milk record updated successfully.');
    }

    public function deleteAnimalMilk($id)
    {
        $milkModel = new AnimalMilkModel();

        if (!isSuperAdmin()) {
            $exists = $milkModel->where('id', $id)
            ->where('tenant_id', currentTenantId())
            ->first();
            if (!$exists) {
                return redirect()->back()->with('error', 'Unauthorized.');
            }
        }

        if ($milkModel->delete($id)) {
            return redirect()->to('/animal-milking/animalMilk')->with('success', 'Animal milk record deleted successfully.');
        } else {
            return redirect()->to('/animal-milking/animalMilk')->with('error', 'Failed to delete animal milk record.');
        }
    }

    public function exportAnimalMilk()
    {
        $milkModel = new AnimalMilkModel();

        $tenantId = $this->request->getGet('tenant_id');
    $date     = $this->request->getGet('date'); // from filter

    if (isSuperAdmin()) {
        if (!empty($tenantId)) {
            $builder = $milkModel->where('animal_milk.tenant_id', $tenantId);
        } else {
            $builder = $milkModel;
        }
    } else {
        $builder = $milkModel->where('animal_milk.tenant_id', currentTenantId());
    }

    $builder->select('animal_milk.*, animals.tag_id')
    ->join('animals', 'animals.id = animal_milk.animal_id');

    if (!empty($date)) {
        $builder->where('animal_milk.date', $date);
    }

    $records = $builder->orderBy('animal_milk.date', 'DESC')->findAll();

    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();

    // Headers
    $sheet->setCellValue('A1', 'ID')
    ->setCellValue('B1', 'Date')
    ->setCellValue('C1', 'Tag ID')
    ->setCellValue('D1', 'First Calving Date')
    ->setCellValue('E1', 'Last Calving Date')
    ->setCellValue('F1', 'Milk 1 (L)')
    ->setCellValue('G1', 'Milk 2 (L)')
    ->setCellValue('H1', 'Milk 3 (L)')
    ->setCellValue('I1', 'Tenant ID');

    // Fill Data
    $row = 2;
    foreach ($records as $record) {
        $sheet->setCellValue('A'.$row, $record['id'])
        ->setCellValue('B'.$row, $record['date'])
        ->setCellValue('C'.$row, $record['tag_id'])
        ->setCellValue('D'.$row, $record['first_calving_date'])
        ->setCellValue('E'.$row, $record['last_calving_date'])
        ->setCellValue('F'.$row, $record['milk_1'])
        ->setCellValue('G'.$row, $record['milk_2'])
        ->setCellValue('H'.$row, $record['milk_3'])
        ->setCellValue('I'.$row, $record['tenant_id']);
        $row++;
    }

    $writer = new Xlsx($spreadsheet);
    $filename = 'animal_milk_'.date('Y-m-d_H-i-s').'.xlsx';

    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header("Content-Disposition: attachment; filename=\"$filename\"");
    $writer->save('php://output');
    exit;
}

}