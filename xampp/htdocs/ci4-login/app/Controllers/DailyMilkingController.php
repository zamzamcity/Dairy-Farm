<?php

namespace App\Controllers;

use App\Models\DailyMilkingModel;
use App\Models\TenantsModel;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class DailyMilkingController extends BaseController
{
    public function dailyMilkingList()
    {
        $model       = new DailyMilkingModel();
        $tenantModel = new TenantsModel();

        $start_date = $this->request->getGet('start_date');
        $end_date   = $this->request->getGet('end_date');

        if (isSuperAdmin()) {
            $data['tenants'] = $tenantModel->findAll();

            $selectedTenantId = $this->request->getGet('tenant_id'); 

            if ($selectedTenantId) {
                $data['daily_milking'] = $model
                ->select('daily_milking.*, tenants.name as tenant_name')
                ->join('tenants', 'tenants.id = daily_milking.tenant_id', 'left')
                ->where('daily_milking.tenant_id', $selectedTenantId)
                ->orderBy('date', 'DESC');

                if ($start_date && $end_date) {
                    $data['daily_milking']->where('date >=', $start_date)->where('date <=', $end_date);
                } elseif ($start_date) {
                    $data['daily_milking']->where('date >=', $start_date);
                } elseif ($end_date) {
                    $data['daily_milking']->where('date <=', $end_date);
                }

                $data['daily_milking'] = $data['daily_milking']->findAll();
            } else {
                $data['daily_milking'] = $model
                ->select('daily_milking.*, tenants.name as tenant_name')
                ->join('tenants', 'tenants.id = daily_milking.tenant_id', 'left')
                ->orderBy('date', 'DESC');

                if ($start_date && $end_date) {
                    $data['daily_milking']->where('date >=', $start_date)->where('date <=', $end_date);
                } elseif ($start_date) {
                    $data['daily_milking']->where('date >=', $start_date);
                } elseif ($end_date) {
                    $data['daily_milking']->where('date <=', $end_date);
                }

                $data['daily_milking'] = $data['daily_milking']->findAll();
            }

            $data['selectedTenantId'] = $selectedTenantId;
        } else {
            $tid = currentTenantId();

            $query = $model
            ->select('daily_milking.*, tenants.name as tenant_name')
            ->join('tenants', 'tenants.id = daily_milking.tenant_id', 'left')
            ->where('daily_milking.tenant_id', $tid)
            ->orderBy('date', 'DESC');

            if ($start_date && $end_date) {
                $query->where('date >=', $start_date)->where('date <=', $end_date);
            } elseif ($start_date) {
                $query->where('date >=', $start_date);
            } elseif ($end_date) {
                $query->where('date <=', $end_date);
            }

            $data['daily_milking'] = $query->findAll();
        }

        $data['start_date'] = $start_date;
        $data['end_date']   = $end_date;

        return view('dailyMilk', $data);
    }

    public function addDailyMilking()
    {
        $model = new DailyMilkingModel();

        $data = [
            'date'         => $this->request->getPost('date'),
            'milk_product' => $this->request->getPost('milk_product'),
            'milk_1'       => $this->request->getPost('milk_1'),
            'milk_2'       => $this->request->getPost('milk_2'),
            'milk_3'       => $this->request->getPost('milk_3'),
            'tenant_id'    => isSuperAdmin()
            ? ($this->request->getPost('tenant_id') !== '' ? $this->request->getPost('tenant_id') : null)
            : currentTenantId(),
            'created_by'   => session()->get('user_id'),
            'updated_by'   => session()->get('user_id'),
            'created_at'   => date('Y-m-d H:i:s'),
            'updated_at'   => date('Y-m-d H:i:s'),
        ];

        if ($model->insert($data)) {
            return redirect()->to('/dailyMilk')->with('success', 'Daily milking record added successfully.');
        } else {
            return redirect()->back()->with('error', 'Failed to add daily milking record.');
        }
    }

    public function editDailyMilking($id)
    {
        $model = new DailyMilkingModel();

        if (!isSuperAdmin()) {
            $exists = $model->where('id', $id)
            ->where('tenant_id', currentTenantId())
            ->first();
            if (!$exists) {
                return redirect()->back()->with('error', 'Unauthorized.');
            }
        }

        $data = [
            'date'         => $this->request->getPost('date'),
            'milk_product' => $this->request->getPost('milk_product'),
            'milk_1'       => $this->request->getPost('milk_1'),
            'milk_2'       => $this->request->getPost('milk_2'),
            'milk_3'       => $this->request->getPost('milk_3'),
            'updated_by'   => session()->get('user_id'),
            'updated_at'   => date('Y-m-d H:i:s'),
        ];

        if (isSuperAdmin() && $this->request->getPost('tenant_id')) {
            $data['tenant_id'] = (int) $this->request->getPost('tenant_id');
        }

        $model->update($id, $data);

        return redirect()->to('/dailyMilk')->with('success', 'Daily milking record updated successfully.');
    }

    public function deleteDailyMilking($id)
    {
        $model = new DailyMilkingModel();

        if (!isSuperAdmin()) {
            $exists = $model->where('id', $id)
            ->where('tenant_id', currentTenantId())
            ->first();
            if (!$exists) {
                return redirect()->back()->with('error', 'Unauthorized.');
            }
        }

        if ($model->delete($id)) {
            return redirect()->to('/dailyMilk')->with('success', 'Daily milking record deleted successfully.');
        } else {
            return redirect()->to('/dailyMilk')->with('error', 'Failed to delete daily milking record.');
        }
    }

    public function exportDailyMilk()
    {
        $model = new DailyMilkingModel();

        $tenantId   = $this->request->getGet('tenant_id');
        $start_date = $this->request->getGet('start_date');
        $end_date   = $this->request->getGet('end_date');

        if (isSuperAdmin()) {
            if (!empty($tenantId)) {
                $query = $model->where('tenant_id', $tenantId);
            } else {
                $query = $model;
            }
        } else {
            $query = $model->where('tenant_id', currentTenantId());
        }

        if ($start_date && $end_date) {
            $query->where('date >=', $start_date)->where('date <=', $end_date);
        } elseif ($start_date) {
            $query->where('date >=', $start_date);
        } elseif ($end_date) {
            $query->where('date <=', $end_date);
        }

        $records = $query->orderBy('date', 'DESC')->findAll();

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

    // Header Row
        $sheet->setCellValue('A1', 'ID')
        ->setCellValue('B1', 'Date')
        ->setCellValue('C1', 'Milk Product')
        ->setCellValue('D1', 'Milk 1 (L)')
        ->setCellValue('E1', 'Milk 2 (L)')
        ->setCellValue('F1', 'Milk 3 (L)')
        ->setCellValue('G1', 'Total Milk (L)')
        ->setCellValue('H1', 'Tenant ID');

    // Fill Rows
        $row = 2;
        foreach ($records as $rec) {
            $sheet->setCellValue('A'.$row, $rec['id'])
            ->setCellValue('B'.$row, $rec['date'])
            ->setCellValue('C'.$row, $rec['milk_product'])
            ->setCellValue('D'.$row, $rec['milk_1'])
            ->setCellValue('E'.$row, $rec['milk_2'])
            ->setCellValue('F'.$row, $rec['milk_3'])
            ->setCellValue('G'.$row, $rec['total_milk'])
            ->setCellValue('H'.$row, $rec['tenant_id']);
            $row++;
        }

        $writer = new Xlsx($spreadsheet);
        $filename = 'daily_milk_'.date('Y-m-d_H-i-s').'.xlsx';

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header("Content-Disposition: attachment; filename=\"$filename\"");
        $writer->save('php://output');
        exit;
    }
}