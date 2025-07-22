<?php

namespace App\Controllers;

use App\Models\DailyMilkingModel;

class DailyMilkingController extends BaseController
{
    public function dailyMilkingList()
    {
        $model = new DailyMilkingModel();

        $data['daily_milking'] = $model->orderBy('date', 'DESC')->findAll();

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
        ];

        $model->insert($data);

        return redirect()->to('/dailyMilk')->with('success', 'Daily milking record added successfully.');
    }

    public function editDailyMilking($id)
    {
        $model = new DailyMilkingModel();

        $data = [
            'date'         => $this->request->getPost('date'),
            'milk_product' => $this->request->getPost('milk_product'),
            'milk_1'       => $this->request->getPost('milk_1'),
            'milk_2'       => $this->request->getPost('milk_2'),
            'milk_3'       => $this->request->getPost('milk_3'),
        ];

        $model->update($id, $data);

        return redirect()->to('/dailyMilk')->with('success', 'Daily milking record updated successfully.');
    }

    public function deleteDailyMilking($id)
    {
        $model = new DailyMilkingModel();
        $model->delete($id);

        return redirect()->to('/dailyMilk')->with('success', 'Daily milking record deleted successfully.');
    }
}