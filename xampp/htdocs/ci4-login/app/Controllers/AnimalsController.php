<?php

namespace App\Controllers;

use App\Models\PermissionModel;
use App\Models\PermissionGroupModel;
use App\Models\PermissionGroupPermissionModel;
use App\Models\UserModel;
use App\Models\AnimalModel;
use App\Models\PenModel;
use App\Models\TenantsModel;
use App\Models\AnimalTypeModel;
use App\Models\BreedModel;
use App\Models\CompanyModel;
use App\Models\CountryModel;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class AnimalsController extends BaseController
{
    public function animalsList()
    {
        $db           = \Config\Database::connect();
        $tenantModel  = new TenantsModel();

        if (isSuperAdmin()) {
            $data['tenants'] = $tenantModel->findAll();

            $selectedTenantId = $this->request->getGet('tenant_id'); 

            $builder = $db->table('animals');
            $builder->select('
                animals.*,
                pens.name AS pen,
                animal_types.name AS animal_type,
                breeds.name AS breed,
                companies.name AS company,
                countries.name AS country,
                tenants.name AS tenant_name
                ');
            $builder->join('pens', 'pens.id = animals.pen_id', 'left');
            $builder->join('animal_types', 'animal_types.id = animals.animal_type_id', 'left');
            $builder->join('breeds', 'breeds.id = animals.breed_id', 'left');
            $builder->join('companies', 'companies.id = animals.company_id', 'left');
            $builder->join('countries', 'countries.id = animals.country_id', 'left');
            $builder->join('tenants', 'tenants.id = animals.tenant_id', 'left');

            if ($selectedTenantId) {
                $builder->where('animals.tenant_id', $selectedTenantId);
            }

            $data['animals'] = $builder->get()->getResultArray();
            $data['selectedTenantId'] = $selectedTenantId;
        } else {
            $tid = currentTenantId();

            $builder = $db->table('animals');
            $builder->select('
                animals.*,
                pens.name AS pen,
                animal_types.name AS animal_type,
                breeds.name AS breed,
                companies.name AS company,
                countries.name AS country,
                tenants.name AS tenant_name
                ');
            $builder->join('pens', 'pens.id = animals.pen_id', 'left');
            $builder->join('animal_types', 'animal_types.id = animals.animal_type_id', 'left');
            $builder->join('breeds', 'breeds.id = animals.breed_id', 'left');
            $builder->join('companies', 'companies.id = animals.company_id', 'left');
            $builder->join('countries', 'countries.id = animals.country_id', 'left');
            $builder->join('tenants', 'tenants.id = animals.tenant_id', 'left');
            $builder->where('animals.tenant_id', $tid);

            $data['animals'] = $builder->get()->getResultArray();
        }

        $penModel       = new PenModel();
        $typeModel      = new AnimalTypeModel();
        $breedModel     = new BreedModel();
        $companyModel   = new CompanyModel();
        $countryModel   = new CountryModel();

        $data['pens']        = isSuperAdmin()?$penModel->findAll():$penModel->where('tenant_id', currentTenantId())->findAll();
        $data['animalTypes'] = isSuperAdmin()?$typeModel->findAll():$typeModel->where('tenant_id', currentTenantId())->findAll();
        $data['breeds']      = isSuperAdmin()?$breedModel->findAll():$breedModel->where('tenant_id', currentTenantId())->findAll();
        $data['companies']   = isSuperAdmin()?$companyModel->findAll():$companyModel->where('tenant_id', currentTenantId())->findAll();
        $data['countries']   = isSuperAdmin()?$countryModel->findAll():$countryModel->where('tenant_id', currentTenantId())->findAll();

        return view('animals/animalsList', $data);
    }

    public function addAnimal()
    {
        $model = new AnimalModel();

        $data = [
            'pen_id'         => $this->request->getPost('pen_id'),
            'tag_id'         => $this->request->getPost('tag_id'),
            'electronic_id'  => $this->request->getPost('electronic_id'),
            'name'           => $this->request->getPost('name'),
            'animal_type_id' => $this->request->getPost('animal_type_id'),
            'breed_id'       => $this->request->getPost('breed_id'),
            'company_id'     => $this->request->getPost('company_id'),
            'country_id'     => $this->request->getPost('country_id'),
            'sex'            => $this->request->getPost('sex'),
            'status'         => $this->request->getPost('status'),
            'insertion_date' => $this->request->getPost('insertion_date'),
            'birth_date'     => $this->request->getPost('birth_date'),
            'price'          => $this->request->getPost('price'),
            'pedigree_info'  => $this->request->getPost('pedigree_info') === 'yes' ? 1 : 0,

            'tenant_id'  => isSuperAdmin()
            ? ($this->request->getPost('tenant_id') !== '' ? $this->request->getPost('tenant_id') : null)
            : currentTenantId(),
            'created_by' => session()->get('user_id'),
            'updated_by' => session()->get('user_id'),
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ];

        $img = $this->request->getFile('picture');
        if ($img && $img->isValid() && !$img->hasMoved()) {
            $uploadPath = FCPATH . 'uploads/animals/';
            if (!is_dir($uploadPath)) {
                mkdir($uploadPath, 0777, true);
            }

            $newName = $img->getRandomName();
            $img->move($uploadPath, $newName);
            $data['picture'] = $newName;
        }

        if ($model->insert($data)) {
            return redirect()->to('/animals/animalsList')->with('success', 'Animal added successfully.');
        } else {
            return redirect()->back()->with('error', 'Failed to add animal.');
        }
    }

    public function editAnimal($id)
    {
        $model = new \App\Models\AnimalModel();

        if (!isSuperAdmin()) {
            $exists = $model->where('id', $id)
            ->where('tenant_id', currentTenantId())
            ->first();
            if (!$exists) {
                return redirect()->back()->with('error', 'Unauthorized.');
            }
        }

        $data = [
            'pen_id'         => $this->request->getPost('pen_id'),
            'tag_id'         => $this->request->getPost('tag_id'),
            'electronic_id'  => $this->request->getPost('electronic_id'),
            'name'           => $this->request->getPost('name'),
            'animal_type_id' => $this->request->getPost('animal_type_id'),
            'breed_id'       => $this->request->getPost('breed_id'),
            'company_id'     => $this->request->getPost('company_id'),
            'country_id'     => $this->request->getPost('country_id'),
            'sex'            => $this->request->getPost('sex'),
            'status'         => $this->request->getPost('status'),
            'insertion_date' => $this->request->getPost('insertion_date'),
            'birth_date'     => $this->request->getPost('birth_date'),
            'price'          => $this->request->getPost('price'),
            'pedigree_info'  => $this->request->getPost('pedigree_info'),
            'updated_by'     => session()->get('user_id'),
            'updated_at'     => date('Y-m-d H:i:s'),
        ];

        if (isSuperAdmin() && $this->request->getPost('tenant_id')) {
            $data['tenant_id'] = (int) $this->request->getPost('tenant_id');
        }

        $uploadPath = FCPATH . 'uploads/animals/';
        $img = $this->request->getFile('picture');

        if (!is_dir($uploadPath)) {
            mkdir($uploadPath, 0777, true);
        }

        $old = $model->find($id);

        if ($this->request->getPost('remove_picture')) {
            if ($old && $old['picture']) {
                $oldPath = $uploadPath . $old['picture'];
                if (file_exists($oldPath)) {
                    unlink($oldPath);
                }
                $data['picture'] = null;
            }
        }

        if ($img && $img->isValid() && !$img->hasMoved()) {
            $newName = $img->getRandomName();
            $img->move($uploadPath, $newName);
            $data['picture'] = $newName;

            if ($old && $old['picture']) {
                $oldPath = $uploadPath . $old['picture'];
                if (file_exists($oldPath)) {
                    unlink($oldPath);
                }
            }
        }

        $model->update($id, $data);

        return redirect()->to('/animals/animalsList')->with('success', 'Animal updated successfully.');
    }

    public function getBreeds($animalTypeId)
    {
        $breedModel = new BreedModel();
        $breeds = $breedModel->where('animal_type_id', $animalTypeId)->findAll();

        return $this->response->setJSON($breeds);
    }


    public function deleteAnimal($id)
    {
        $model = new \App\Models\AnimalModel();

        $animal = $model->find($id);
        if ($animal && !empty($animal['picture'])) {
            $path = FCPATH . 'uploads/animals/' . $animal['picture'];
            if (file_exists($path)) {
                unlink($path);
            }
        }

        $model->delete($id);

        return redirect()->to('/animals/animalsList')->with('success', 'Animal deleted successfully.');
    }

    public function exportAnimals()
    {
        $animalModel = new \App\Models\AnimalModel();

        $tenantId = $this->request->getGet('tenant_id');

        $db = \Config\Database::connect();
        $builder = $db->table('animals');
        $builder->select('
            animals.id, animals.name, animals.tag_id, animals.electronic_id,
            pens.name AS pen, animal_types.name AS animal_type,
            breeds.name AS breed, companies.name AS company, countries.name AS country,
            animals.sex, animals.status, animals.insertion_date, animals.birth_date, animals.price,
            animals.tenant_id
            ');
        $builder->join('pens', 'pens.id = animals.pen_id', 'left');
        $builder->join('animal_types', 'animal_types.id = animals.animal_type_id', 'left');
        $builder->join('breeds', 'breeds.id = animals.breed_id', 'left');
        $builder->join('companies', 'companies.id = animals.company_id', 'left');
        $builder->join('countries', 'countries.id = animals.country_id', 'left');

        if (isSuperAdmin() && !empty($tenantId)) {
            $builder->where('animals.tenant_id', $tenantId);
        } elseif (!isSuperAdmin()) {
            $builder->where('animals.tenant_id', currentTenantId());
        }

        $animals = $builder->get()->getResultArray();

        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

    // Header (added Tenant ID)
        $sheet->fromArray([[
            'ID','Name','Tag ID','Electronic ID','Pen','Animal Type','Breed','Company','Country','Sex','Status','Insertion Date','Birth Date','Price','Tenant ID'
        ]], NULL, 'A1');

    // Data
        $row = 2;
        foreach ($animals as $a) {
            $sheet->fromArray([array_values($a)], NULL, "A$row");
            $row++;
        }

        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        $filename = "animals.xlsx";

        header('Content-Type: application/vnd.ms-excel');
        header("Content-Disposition: attachment; filename=\"$filename\"");
        $writer->save("php://output");
        exit;
    }

}