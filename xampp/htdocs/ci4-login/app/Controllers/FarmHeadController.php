<?php

namespace App\Controllers;

use App\Models\FarmHeadModel;

class FarmHeadController extends BaseController
{
    public function farmHeadList()
    {
        $model = new FarmHeadModel();

        $data['farm_head'] = $model->orderBy('created_at', 'DESC')->findAll();

        return view('milk-consumption/farmHead', $data);
    }

    public function addFarmHead()
    {
        $model = new FarmHeadModel();

        $data = [
            'head_name' => $this->request->getPost('head_name'),
        ];

        $model->insert($data);

        return redirect()->to('/milk-consumption/farmHead')->with('success', 'Farm head added successfully.');
    }

    public function editFarmHead($id)
    {
        $model = new FarmHeadModel();

        $data = [
            'head_name' => $this->request->getPost('head_name'),
        ];

        $model->update($id, $data);

        return redirect()->to('/milk-consumption/farmHead')->with('success', 'Farm head updated successfully.');
    }

    public function deleteFarmHead($id)
    {
        $model = new FarmHeadModel();
        $model->delete($id);

        return redirect()->to('/milk-consumption/farmHead')->with('success', 'Farm head deleted successfully.');
    }
}