<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\MedicineConsumptionModel;
use App\Models\StockRegistrationModel;

class MedicineConsumptionController extends BaseController
{
    public function medicineConsumptionList()
    {
        $model = new MedicineConsumptionModel();
        $stockModel = new StockRegistrationModel();

    $selectedDate = $this->request->getGet('date') ?? date('Y-m-d');

    $totalQuantity = $model
    ->selectSum('quantity')
    ->where('date', $selectedDate)
    ->first()['quantity'] ?? 0;

    $medicineProducts = $stockModel
    ->select('stock_registration.*')
    ->join('stock_heads', 'stock_heads.id = stock_registration.head_id')
    ->where('stock_heads.name', 'Medication')
    ->findAll();

    $consumptions = $model
    ->select('medicine_consumption.*, stock_registration.product_name')
    ->join('stock_registration', 'stock_registration.id = medicine_consumption.product_id')
    ->where('medicine_consumption.date', $selectedDate)
    ->findAll();

    $data = [
        'selected_date' => $selectedDate,
        'medicine_products' => $medicineProducts,
        'medicine_consumptions' => $consumptions,
        'total_quantity' => $totalQuantity,
    ];

    return view('medicine-consumption/medicineConsumption', $data);
}


public function addMedicineConsumption()
{
    $model = new MedicineConsumptionModel();

    $data = [
        'product_id' => $this->request->getPost('product_id'),
        'quantity'   => $this->request->getPost('quantity'),
        'date'       => $this->request->getPost('date'),
    ];

    $model->insert($data);

    return redirect()->to('/medicine-consumption/medicineConsumption')->with('success', 'Medicine consumption added successfully.');
}

public function editMedicineConsumption($id)
{
    $model = new MedicineConsumptionModel();

    $data = [
        'product_id' => $this->request->getPost('product_id'),
        'quantity'   => $this->request->getPost('quantity'),
        'date'       => $this->request->getPost('date'),
    ];

    $model->update($id, $data);

    return redirect()->to('/medicine-consumption/medicineConsumption')->with('success', 'Medicine consumption updated successfully.');
}

public function deleteMedicineConsumption($id)
{
    $model = new MedicineConsumptionModel();
    $model->delete($id);

    return redirect()->to('/medicine-consumption/medicineConsumption')->with('success', 'Medicine consumption deleted.');
}
}