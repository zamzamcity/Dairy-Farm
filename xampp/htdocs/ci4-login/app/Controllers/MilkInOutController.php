<?php

namespace App\Controllers;

use App\Models\DailyMilkingModel;
use App\Models\MilkConsumptionModel;
use App\Models\TenantsModel;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class MilkInOutController extends BaseController
{
    public function milkInOutDetails()
    {
        $date = $this->request->getGet('date') ?? date('Y-m-d');

        $milkingModel     = new DailyMilkingModel();
        $consumptionModel = new MilkConsumptionModel();
        $tenantModel      = new TenantsModel();

        if (isSuperAdmin()) {
            $data['tenants'] = $tenantModel->findAll();

            $selectedTenantId = $this->request->getGet('tenant_id');

            if ($selectedTenantId) {
                $data['daily_milking'] = $milkingModel
                ->select('daily_milking.*, tenants.name as tenant_name')
                ->join('tenants', 'tenants.id = daily_milking.tenant_id', 'left')
                ->where('daily_milking.date', $date)
                ->where('daily_milking.tenant_id', $selectedTenantId)
                ->findAll();

                $data['milk_consumption'] = $consumptionModel
                ->select('milk_consumption.*, farm_head.head_name, tenants.name as tenant_name')
                ->join('farm_head', 'milk_consumption.farm_head_id = farm_head.id', 'left')
                ->join('tenants', 'tenants.id = milk_consumption.tenant_id', 'left')
                ->where('milk_consumption.date', $date)
                ->where('milk_consumption.tenant_id', $selectedTenantId)
                ->findAll();
            } else {
                $data['daily_milking'] = $milkingModel
                ->select('daily_milking.*, tenants.name as tenant_name')
                ->join('tenants', 'tenants.id = daily_milking.tenant_id', 'left')
                ->where('daily_milking.date', $date)
                ->findAll();

                $data['milk_consumption'] = $consumptionModel
                ->select('milk_consumption.*, farm_head.head_name, tenants.name as tenant_name')
                ->join('farm_head', 'milk_consumption.farm_head_id = farm_head.id', 'left')
                ->join('tenants', 'tenants.id = milk_consumption.tenant_id', 'left')
                ->where('milk_consumption.date', $date)
                ->findAll();
            }

            $data['selectedTenantId'] = $selectedTenantId;
        } else {
            $tid = currentTenantId();

            $data['daily_milking'] = $milkingModel
            ->select('daily_milking.*, tenants.name as tenant_name')
            ->join('tenants', 'tenants.id = daily_milking.tenant_id', 'left')
            ->where('daily_milking.date', $date)
            ->where('daily_milking.tenant_id', $tid)
            ->findAll();

            $data['milk_consumption'] = $consumptionModel
            ->select('milk_consumption.*, farm_head.head_name, tenants.name as tenant_name')
            ->join('farm_head', 'milk_consumption.farm_head_id = farm_head.id', 'left')
            ->join('tenants', 'tenants.id = milk_consumption.tenant_id', 'left')
            ->where('milk_consumption.date', $date)
            ->where('milk_consumption.tenant_id', $tid)
            ->findAll();
        }

        $data['total_milking'] = array_sum(array_map(static function ($r) {
            return (float)($r['total_milk'] ?? 0);
        }, $data['daily_milking'] ?? []));

        $data['total_consumption'] = array_sum(array_map(static function ($r) {
            return (float)($r['milk_litres'] ?? 0);
        }, $data['milk_consumption'] ?? []));

        $data['selected_date'] = $date;

        return view('milkInOut', $data);
    }

    public function exportMilkInOut()
    {
        $date = $this->request->getGet('date') ?? date('Y-m-d');
        $tenantId = $this->request->getGet('tenant_id');

        $milkingModel     = new DailyMilkingModel();
        $consumptionModel = new MilkConsumptionModel();

        if (isSuperAdmin()) {
            if (!empty($tenantId)) {
                $dailyMilking = $milkingModel->where('date', $date)
                ->where('tenant_id', $tenantId)
                ->findAll();

                $milkConsumption = $consumptionModel
                ->select('milk_consumption.*, farm_head.head_name')
                ->join('farm_head', 'milk_consumption.farm_head_id = farm_head.id')
                ->where('milk_consumption.date', $date)
                ->where('milk_consumption.tenant_id', $tenantId)
                ->findAll();
            } else {
                $dailyMilking = $milkingModel->where('date', $date)->findAll();

                $milkConsumption = $consumptionModel
                ->select('milk_consumption.*, farm_head.head_name')
                ->join('farm_head', 'milk_consumption.farm_head_id = farm_head.id')
                ->where('milk_consumption.date', $date)
                ->findAll();
            }
        } else {
            $tid = currentTenantId();

            $dailyMilking = $milkingModel->where('date', $date)
            ->where('tenant_id', $tid)
            ->findAll();

            $milkConsumption = $consumptionModel
            ->select('milk_consumption.*, farm_head.head_name')
            ->join('farm_head', 'milk_consumption.farm_head_id = farm_head.id')
            ->where('milk_consumption.date', $date)
            ->where('milk_consumption.tenant_id', $tid)
            ->findAll();
        }

        $totalMilking     = array_sum(array_column($dailyMilking, 'total_milk'));
        $totalConsumption = array_sum(array_column($milkConsumption, 'milk_litres'));

        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $sheet->setCellValue('A1', "Milk In/Out Report - $date");

    // ---------------- Daily Milking ----------------
        $sheet->setCellValue('A3', 'Tenant ID');
        $sheet->setCellValue('B3', 'Product');
        $sheet->setCellValue('C3', 'Milk 1 (L)');
        $sheet->setCellValue('D3', 'Milk 2 (L)');
        $sheet->setCellValue('E3', 'Milk 3 (L)');
        $sheet->setCellValue('F3', 'Total (L)');

        $row = 4;
        foreach ($dailyMilking as $m) {
            $sheet->setCellValue("A$row", $m['tenant_id']);
            $sheet->setCellValue("B$row", $m['milk_product']);
            $sheet->setCellValue("C$row", $m['milk_1']);
            $sheet->setCellValue("D$row", $m['milk_2']);
            $sheet->setCellValue("E$row", $m['milk_3']);
            $sheet->setCellValue("F$row", $m['total_milk']);
            $row++;
        }
        $sheet->setCellValue("E$row", "Total Milking");
        $sheet->setCellValue("F$row", $totalMilking);

    // ---------------- Milk Consumption ----------------
        $row += 2;
        $sheet->setCellValue("A$row", "Tenant ID");
        $sheet->setCellValue("B$row", "Head");
        $sheet->setCellValue("C$row", "Milk (L)");
        $row++;

        foreach ($milkConsumption as $c) {
            $sheet->setCellValue("A$row", $c['tenant_id']);
            $sheet->setCellValue("B$row", $c['head_name']);
            $sheet->setCellValue("C$row", $c['milk_litres']);
            $row++;
        }
        $sheet->setCellValue("B$row", "Total Consumption");
        $sheet->setCellValue("C$row", $totalConsumption);

        foreach (range('A', 'F') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        $fileName = 'Milk_InOut_' . $date . '.xlsx';

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header("Content-Disposition: attachment; filename=\"$fileName\"");
        header('Cache-Control: max-age=0');

        $writer->save('php://output');
        exit;
    }
}