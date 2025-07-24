<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\FeedConsumptionModel;
use App\Models\StockRegistrationModel;

class FeedingConsumptionController extends BaseController
{
    public function feedingConsumptionList()
    {
        $model = new FeedConsumptionModel();
        $stockModel = new StockRegistrationModel();

        $selectedDate = $this->request->getGet('date') ?? date('Y-m-d');

        $totalQuantity = $model
        ->selectSum('quantity')
        ->where('date', $selectedDate)
        ->first()['quantity'] ?? 0;

        $feedingProducts = $stockModel
        ->select('stock_registration.*')
        ->join('stock_heads', 'stock_heads.id = stock_registration.head_id')
        ->where('stock_heads.name', 'Feeding')
        ->findAll();

        $consumptions = $model
        ->select('feed_consumption.*, stock_registration.product_name')
        ->join('stock_registration', 'stock_registration.id = feed_consumption.product_id')
        ->where('feed_consumption.date', $selectedDate)
        ->findAll();

        $data = [
            'selected_date' => $selectedDate,
            'feeding_products' => $feedingProducts,
            'feeding_consumptions' => $consumptions,
            'total_quantity' => $totalQuantity,
        ];

        return view('feeding-consumption/feedingConsumption', $data);
    }

    public function addFeedingConsumption()
    {
        $model = new FeedConsumptionModel();

        $data = [
            'product_id' => $this->request->getPost('product_id'),
            'quantity'   => $this->request->getPost('quantity'),
            'date'       => $this->request->getPost('date'),
        ];

        $model->insert($data);

        return redirect()->to('/feeding-consumption/feedingConsumption')->with('success', 'Feeding consumption added successfully.');
    }

    public function editFeedingConsumption($id)
    {
        $model = new FeedConsumptionModel();

        $data = [
            'product_id' => $this->request->getPost('product_id'),
            'quantity'   => $this->request->getPost('quantity'),
            'date'       => $this->request->getPost('date'),
        ];

        $model->update($id, $data);

        return redirect()->to('/feeding-consumption/feedingConsumption')->with('success', 'Feeding consumption updated successfully.');
    }

    public function deleteFeedingConsumption($id)
    {
        $model = new FeedConsumptionModel();
        $model->delete($id);

        return redirect()->to('/feeding-consumption/feedingConsumption')->with('success', 'Feeding consumption deleted.');
    }
}