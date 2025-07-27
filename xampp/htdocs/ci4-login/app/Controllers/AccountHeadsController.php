<?php

namespace App\Controllers;
use App\Models\AccountHeadModel;
use CodeIgniter\Controller;

class AccountHeadsController extends BaseController
{
    public function accountHeadsList()
    {
        $model = new AccountHeadModel();
        $data['account_heads'] = $model->findAll();

        return view('chart-of-accounts/accountHeads', $data);
    }

    public function addAccountHeads()
    {
        $model = new AccountHeadModel();

        $data = [
            'account_code'     => $this->request->getPost('account_code'),
            'name'             => $this->request->getPost('name'),
            'type'             => $this->request->getPost('type'),
            'opening_balance'  => $this->request->getPost('opening_balance'),
            'description'      => $this->request->getPost('description'),
        ];

        if ($model->insert($data)) {
            return redirect()->back()->with('success', 'Account Head added successfully.');
        } else {
            return redirect()->back()->with('error', 'Failed to add Account Head.');
        }
    }

    public function editAccountHeads($id)
    {
        $model = new AccountHeadModel();

        $data = [
            'account_code'     => $this->request->getPost('account_code'),
            'name'             => $this->request->getPost('name'),
            'type'             => $this->request->getPost('type'),
            'opening_balance'  => $this->request->getPost('opening_balance'),
            'description'      => $this->request->getPost('description'),
        ];

        if ($model->update($id, $data)) {
            return redirect()->back()->with('success', 'Account Head updated successfully.');
        } else {
            return redirect()->back()->with('error', 'Failed to update Account Head.');
        }
    }

    public function deleteAccountHeads($id)
    {
        $model = new AccountHeadModel();

        if ($model->delete($id)) {
            return redirect()->back()->with('success', 'Account Head deleted successfully.');
        } else {
            return redirect()->back()->with('error', 'Failed to delete Account Head.');
        }
    }
}