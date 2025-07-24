<?php

namespace App\Controllers;

use App\Models\StockRegistrationModel;
use App\Models\StockHeadModel;
use App\Models\StockUnitModel;

class StockRegistrationController extends BaseController
{
    public function stockList()
    {
        $model = new StockRegistrationModel();

        $data['stock_registration'] = $model
        ->select('stock_registration.*, stock_heads.name AS head_name, stock_units.name AS unit_name')
        ->join('stock_heads', 'stock_heads.id = stock_registration.head_id')
        ->join('stock_units', 'stock_units.id = stock_registration.unit_id')
        ->orderBy('stock_registration.created_at', 'DESC')
        ->findAll();

        $headModel = new StockHeadModel();
        $unitModel = new StockUnitModel();

        $data['stock_heads'] = $headModel->findAll();
        $data['stock_units'] = $unitModel->findAll();

        return view('stock/stockList', $data);
    }

    public function addStock()
    {
        $model = new StockRegistrationModel();

        $isStockItem = (int) $this->request->getPost('is_stock_item');

        $data = [
            'product_name'             => $this->request->getPost('product_name'),
            'head_id'                  => $this->request->getPost('head_id'),
            'unit_id'                  => $this->request->getPost('unit_id'),
            'is_stock_item'            => $isStockItem,
            'opening_stock_qty'       => $isStockItem ? $this->request->getPost('opening_stock_qty') : null,
            'opening_stock_rate_per_unit' => $isStockItem ? $this->request->getPost('opening_stock_rate_per_unit') : null,
            'rate_per_unit'           => $this->request->getPost('rate_per_unit'),
        ];

        $model->insert($data);

        return redirect()->to('/stock/stockList')->with('success', 'Stock registered successfully.');
    }

    public function editStock($id)
    {
        $model = new StockRegistrationModel();

        $isStockItem = (int) $this->request->getPost('is_stock_item');

        $data = [
            'product_name'             => $this->request->getPost('product_name'),
            'head_id'                  => $this->request->getPost('head_id'),
            'unit_id'                  => $this->request->getPost('unit_id'),
            'is_stock_item'            => $isStockItem,
            'opening_stock_qty'       => $isStockItem ? $this->request->getPost('opening_stock_qty') : null,
            'opening_stock_rate_per_unit' => $isStockItem ? $this->request->getPost('opening_stock_rate_per_unit') : null,
            'rate_per_unit'           => $this->request->getPost('rate_per_unit'),
        ];

        $model->update($id, $data);

        return redirect()->to('/stock/stockList')->with('success', 'Stock updated successfully.');
    }

    public function deleteStock($id)
    {
        $model = new StockRegistrationModel();
        $model->delete($id);

        return redirect()->to('/stock/stockList')->with('success', 'Stock deleted successfully.');
    }

    public function addHead()
    {
        $headModel = new StockHeadModel();

        $data = [
            'name' => $this->request->getPost('head_name'),
        ];

        $headModel->insert($data);

        return redirect()->back()->with('success', 'New stock head added successfully.');
    }

    public function addUnit()
    {
        $unitModel = new StockUnitModel();

        $data = [
            'name' => $this->request->getPost('unit_name'),
        ];

        $unitModel->insert($data);

        return redirect()->back()->with('success', 'New stock unit added successfully.');
    }
}
