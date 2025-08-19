<?php

namespace App\Controllers;
use App\Models\AccountHeadModel;
use CodeIgniter\Controller;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

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

    public function exportAccountHeads()
    {
        $model = new AccountHeadModel();
        $accountHeads = $model->orderBy('id', 'DESC')->findAll();

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

    // Headers
        $sheet->setCellValue('A1', 'ID');
        $sheet->setCellValue('B1', 'Account Code');
        $sheet->setCellValue('C1', 'Name');
        $sheet->setCellValue('D1', 'Type');
        $sheet->setCellValue('E1', 'Opening Balance');
        $sheet->setCellValue('F1', 'Description');

    // Data
        $row = 2;
        foreach ($accountHeads as $head) {
            $sheet->setCellValue('A' . $row, $head['id']);
            $sheet->setCellValue('B' . $row, $head['account_code']);
            $sheet->setCellValue('C' . $row, $head['name']);
            $sheet->setCellValue('D' . $row, $head['type']);
            $sheet->setCellValue('E' . $row, $head['opening_balance']);
            $sheet->setCellValue('F' . $row, $head['description']);
            $row++;
        }

        $writer = new Xlsx($spreadsheet);
        $filename = 'Account_Heads_' . date('Y-m-d') . '.xlsx';

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header("Content-Disposition: attachment; filename=\"$filename\"");
        header('Cache-Control: max-age=0');
        $writer->save('php://output');
        exit();
    }
}