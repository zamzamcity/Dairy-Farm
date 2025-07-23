<?php

namespace App\Controllers;

use App\Models\DailyMilkingModel;
use App\Models\MilkConsumptionModel;

class MilkInOutController extends BaseController
{
    public function milkInOutDetails()
    {
        $date = $this->request->getGet('date') ?? date('Y-m-d');

        $milkingModel = new DailyMilkingModel();
        $consumptionModel = new MilkConsumptionModel();

        $dailyMilking = $milkingModel->where('date', $date)->findAll();
        $milkConsumption = $consumptionModel->select('milk_consumption.*, farm_head.head_name')
            ->join('farm_head', 'milk_consumption.farm_head_id = farm_head.id')
            ->where('milk_consumption.date', $date)
            ->findAll();

        $totalMilking = array_sum(array_column($dailyMilking, 'total_milk'));
        $totalConsumption = array_sum(array_column($milkConsumption, 'milk_litres'));

        return view('milkInOut', [
            'selected_date' => $date,
            'daily_milking' => $dailyMilking,
            'milk_consumption' => $milkConsumption,
            'total_milking' => $totalMilking,
            'total_consumption' => $totalConsumption
        ]);
    }
}