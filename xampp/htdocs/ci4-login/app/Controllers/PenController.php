<?php

namespace App\Controllers;

use App\Models\AnimalModel;
use App\Models\PenModel;
use App\Models\TenantsModel;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class PenController extends BaseController
{
    public function penList()
    {
        $penModel    = new PenModel();
        $animalModel = new AnimalModel();
        $tenantModel = new TenantsModel();

        if (isSuperAdmin()) {
            $data['tenants'] = $tenantModel->findAll();
            $selectedTenantId = $this->request->getGet('tenant_id');

            if ($selectedTenantId) {
                $data['pens'] = $penModel
                ->select('pens.*, tenants.name as tenant_name')
                ->join('tenants', 'tenants.id = pens.tenant_id', 'left')
                ->where('pens.tenant_id', $selectedTenantId)
                ->findAll();

                $animals = $animalModel
                ->select('id, name, pen_id')
                ->where('tenant_id', $selectedTenantId)
                ->findAll();
            } else {
                $data['pens'] = $penModel
                ->select('pens.*, tenants.name as tenant_name')
                ->join('tenants', 'tenants.id = pens.tenant_id', 'left')
                ->findAll();

                $animals = $animalModel
                ->select('id, name, pen_id')
                ->findAll();
            }

            $data['selectedTenantId'] = $selectedTenantId;
        } else {
            $tid = currentTenantId();

            $data['pens'] = $penModel
            ->select('pens.*, tenants.name as tenant_name')
            ->join('tenants', 'tenants.id = pens.tenant_id', 'left')
            ->where('pens.tenant_id', $tid)
            ->findAll();

            $animals = $animalModel
            ->select('id, name, pen_id')
            ->where('tenant_id', $tid)
            ->findAll();
        }

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
            'name'       => $this->request->getPost('name'),
            'tenant_id'  => isSuperAdmin()
            ? ($this->request->getPost('tenant_id') !== '' ? $this->request->getPost('tenant_id') : null)
            : currentTenantId(),
            'created_by' => session()->get('user_id'),
            'updated_by' => session()->get('user_id'),
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ];

        if ($penModel->insert($data)) {
            return redirect()->to('/pen-semen-tech/pen')->with('success', 'Pen added successfully.');
        } else {
            return redirect()->back()->with('error', 'Failed to add pen.');
        }
    }

    public function editPen($id)
    {
        $penModel = new PenModel();

        if (!isSuperAdmin()) {
            $exists = $penModel->where('id', $id)
            ->where('tenant_id', currentTenantId())
            ->first();
            if (!$exists) {
                return redirect()->back()->with('error', 'Unauthorized.');
            }
        }

        $data = [
            'name'       => $this->request->getPost('name'),
            'updated_by' => session()->get('user_id'),
            'updated_at' => date('Y-m-d H:i:s'),
        ];

        if (isSuperAdmin() && $this->request->getPost('tenant_id')) {
            $data['tenant_id'] = (int) $this->request->getPost('tenant_id');
        }

        $penModel->update($id, $data);

        return redirect()->to('/pen-semen-tech/pen')->with('success', 'Pen updated successfully.');
    }

    public function deletePen($id)
    {
        $penModel = new PenModel();

        if (!isSuperAdmin()) {
            $exists = $penModel->where('id', $id)
            ->where('tenant_id', currentTenantId())
            ->first();
            if (!$exists) {
                return redirect()->back()->with('error', 'Unauthorized.');
            }
        }

        if ($penModel->delete($id)) {
            return redirect()->to('/pen-semen-tech/pen')->with('success', 'Pen deleted successfully.');
        } else {
            return redirect()->to('/pen-semen-tech/pen')->with('error', 'Failed to delete pen.');
        }
    }

    public function exportPens()
    {
        $penModel = new PenModel();

        $tenantId = $this->request->getGet('tenant_id');

        if (isSuperAdmin()) {
            if (!empty($tenantId)) {
                $pens = $penModel->where('tenant_id', $tenantId)->findAll();
            } else {
                $pens = $penModel->findAll();
            }
        } else {
            $pens = $penModel->where('tenant_id', currentTenantId())->findAll();
        }

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $sheet->fromArray([['ID', 'Pen Name', 'Tenant ID']], NULL, 'A1');

        $row = 2;
        foreach ($pens as $p) {
            $sheet->fromArray([$p['id'], $p['name'], $p['tenant_id']], NULL, "A$row");
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