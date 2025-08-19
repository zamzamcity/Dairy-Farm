<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\UserModel;
use App\Models\AccountHeadModel;
use App\Models\EmployeePayrollModel;
use App\Models\VoucherModel;
use App\Models\VoucherEntryModel;
use CodeIgniter\I18n\Time;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class PayrollController extends BaseController
{
    protected $userModel;
    protected $accountHeadModel;
    protected $employeePayrollModel;
    protected $voucherModel;
    protected $voucherEntryModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
        $this->accountHeadModel = new AccountHeadModel();
        $this->employeePayrollModel = new EmployeePayrollModel();
        $this->voucherModel = new VoucherModel();
        $this->voucherEntryModel = new VoucherEntryModel();
    }

    public function salaryPayments()
    {
        $payrollModel = new EmployeePayrollModel();
        $userModel = new UserModel();

        $salaryPayments = $payrollModel
        ->select('employee_payrolls.*, users.firstname, users.lastname')
        ->join('users', 'users.id = employee_payrolls.user_id')
        ->orderBy('employee_payrolls.salary_month', 'DESC')
        ->findAll();

        $employees = $userModel->where('is_active', 1)->orderBy('firstname')->findAll();

        return view('payroll/salaryPayments', [
            'salaryPayments' => $salaryPayments,
            'employees' => $employees
        ]);
    }

    public function addSalaryPayment()
    {
        $data = $this->request->getPost();

        $payrollModel = new EmployeePayrollModel();
        $voucherModel = new VoucherModel();
        $voucherEntryModel = new VoucherEntryModel();

        $lastVoucher = $voucherModel->where('voucher_type', 'payment')->orderBy('id', 'DESC')->first();
        $lastId = $lastVoucher ? $lastVoucher['id'] + 1 : 1;
        $voucherNumber = 'PV-' . str_pad($lastId, 4, '0', STR_PAD_LEFT);

        $user_id = $this->request->getPost('user_id');
        $salary_month = $data['salary_month'] . '-01';
        $working_days = $this->request->getPost('working_days');
        $salary_amount = $this->request->getPost('salary_amount');
        $date = $this->request->getPost('date') ?? date('Y-m-d');

        $user = $this->userModel->find($user_id);
        $employee_account = $this->accountHeadModel->where('linked_user_id', $user_id)->first();
        $salary_expense_account = $this->accountHeadModel->where('type', 'Expense')->like('name', 'Salary')->first();

        $voucherData = [
            'voucher_number' => $voucherNumber,
            'voucher_type'   => 'payment',
            'date'           => $date,
            'description'    => 'Salary Payment for ' . $user['firstname'] . ' ' . $user['lastname'] . ' - ' . $salary_month,
            'created_at'     => Time::now(),
            'updated_at'     => Time::now()
        ];
        $voucher_id = $this->voucherModel->insert($voucherData);

        if ($voucher_id) {
            $voucherEntryModel->insert([
                'voucher_id'      => $voucher_id,
                'account_head_id' => $employee_account['id'],
                'type'            => 'debit',
                'amount'          => $salary_amount,
                'narration'       => 'Salary Payment',
                'created_at'      => Time::now(),
                'updated_at'      => Time::now()
            ]);

            $voucherEntryModel->insert([
                'voucher_id'      => $voucher_id,
                'account_head_id' => $salary_expense_account['id'],
                'type'            => 'credit',
                'amount'          => $salary_amount,
                'narration'       => 'Salary Expense',
                'created_at'      => Time::now(),
                'updated_at'      => Time::now()
            ]);

            $payrollModel->insert([
                'user_id'       => $user_id,
                'salary_month'  => $salary_month,
                'salary_type'   => $user['salary_type'],
                'working_days'  => $working_days,
                'salary_amount' => $salary_amount,
                'voucher_id'    => $voucher_id,
                'status'        => 'paid',
                'created_at'    => Time::now(),
                'updated_at'    => Time::now()
            ]);

            return redirect()->back()->with('success', 'Salary paid and voucher created.');
        }

        return redirect()->back()->with('error', 'Failed to process salary payment.');
    }

    public function exportSalaryPayments()
    {
        $payrollModel = new EmployeePayrollModel();
        $salaryPayments = $payrollModel
        ->select('employee_payrolls.*, users.firstname, users.lastname, vouchers.voucher_number')
        ->join('users', 'users.id = employee_payrolls.user_id')
        ->join('vouchers', 'vouchers.id = employee_payrolls.voucher_id', 'left')
        ->orderBy('employee_payrolls.salary_month', 'DESC')
        ->findAll();

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

    // Headers
        $sheet->setCellValue('A1', 'Employee');
        $sheet->setCellValue('B1', 'Month');
        $sheet->setCellValue('C1', 'Working Days');
        $sheet->setCellValue('D1', 'Salary Type');
        $sheet->setCellValue('E1', 'Amount');
        $sheet->setCellValue('F1', 'Status');
        $sheet->setCellValue('G1', 'Voucher No');
        $sheet->setCellValue('H1', 'Paid On');

    // Fill Data
        $row = 2;
        foreach ($salaryPayments as $payment) {
            $sheet->setCellValue('A' . $row, $payment['firstname'] . ' ' . $payment['lastname']);
            $sheet->setCellValue('B' . $row, $payment['salary_month']);
            $sheet->setCellValue('C' . $row, $payment['working_days']);
            $sheet->setCellValue('D' . $row, ucfirst($payment['salary_type']));
            $sheet->setCellValue('E' . $row, $payment['salary_amount']);
            $sheet->setCellValue('F' . $row, $payment['status']);
            $sheet->setCellValue('G' . $row, $payment['voucher_number'] ?? 'N/A');
            $sheet->setCellValue('H' . $row, date('d M Y', strtotime($payment['created_at'])));
            $row++;
        }

    // File Download
        $writer = new Xlsx($spreadsheet);
        $filename = 'salary_payments_' . date('Ymd_His') . '.xlsx';

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        header('Cache-Control: max-age=0');

        $writer->save('php://output');
        exit;
    }

    public function salaryLedger()
    {
        $payrollModel = new EmployeePayrollModel();
        $userModel = new UserModel();
        $voucherModel = new VoucherModel();

        $db = \Config\Database::connect();
        $builder = $db->table('users');

        $employee_id = $this->request->getGet('employee_id');
        $salary_month = $this->request->getGet('salary_month');
        $status = $this->request->getGet('status');

        $builder->select('
            users.id,
            users.firstname,
            users.lastname,
            users.salary_amount as base_salary,
            ep.salary_month,
            ep.salary_type,
            ep.working_days,
            ep.salary_amount,
            ep.status,
            v.voucher_number,
            v.date as voucher_date
            ');

        if (!empty($salary_month)) {
            $builder->join(
                "(SELECT * FROM employee_payrolls WHERE salary_month LIKE '{$salary_month}%' ) ep",
                'ep.user_id = users.id',
                'left'
            );
        } else {
            $builder->join('employee_payrolls ep', 'ep.user_id = users.id', 'left');
        }

        $builder->join('vouchers v', 'v.id = ep.voucher_id', 'left');

        if (!empty($employee_id)) {
            $builder->where('users.id', $employee_id);
        }

        if ($status === 'paid') {
            $builder->where('ep.status', 'paid');
        } elseif ($status === 'unpaid') {
            $builder->groupStart();
            $builder->where('ep.status IS NULL', null, false);
            if (!empty($salary_month)) {
                $builder->orWhere('ep.status !=', 'paid');
            } else {
                $builder->orWhere('ep.status !=', 'paid')->orWhere('ep.salary_month IS NULL', null, false);
            }
            $builder->groupEnd();
        }

        $builder->orderBy('users.firstname', 'ASC');
        $payrolls = $builder->get()->getResultArray();

        $employees = $userModel->orderBy('firstname')->findAll();

        return view('payroll/salaryLedger', [
            'payrolls' => $payrolls,
            'employees' => $employees,
            'filter_employee_id' => $employee_id,
            'filter_salary_month' => $salary_month,
            'filter_status' => $status
        ]);
    }
    public function exportSalaryLedger()
    {
        $db = \Config\Database::connect();
        $employee_id = $this->request->getGet('employee_id');
        $salary_month = $this->request->getGet('salary_month');
        $status = $this->request->getGet('status');

        $builder = $db->table('users u');
        $builder->select('
            u.firstname,
            u.lastname,
            u.salary_amount as base_salary,
            ep.salary_month,
            ep.salary_type,
            ep.working_days,
            ep.salary_amount,
            ep.status,
            v.voucher_number,
            v.date
            ');

        if (!empty($salary_month)) {
            $builder->join(
                "(SELECT * FROM employee_payrolls WHERE salary_month LIKE '{$salary_month}%') ep",
                'ep.user_id = u.id',
                'left'
            );
        } else {
            $builder->join('employee_payrolls ep', 'ep.user_id = u.id', 'left');
        }

        $builder->join('vouchers v', 'v.id = ep.voucher_id', 'left');

        if (!empty($employee_id)) {
            $builder->where('u.id', $employee_id);
        }

        if ($status === 'paid') {
            $builder->where('ep.status', 'paid');
        } elseif ($status === 'unpaid') {
            $builder->where('(ep.status IS NULL OR ep.status != "paid")', null, false);
        }

        $builder->orderBy('u.firstname', 'ASC');
        $data = $builder->get()->getResultArray();

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $headers = ['Employee Name', 'Base Salary', 'Salary Month', 'Salary Type', 'Working Days', 'Salary Paid', 'Status', 'Voucher #', 'Voucher Date'];
        $sheet->fromArray($headers, null, 'A1');

        $row = 2;
        foreach ($data as $item) {
            $sheet->setCellValue("A{$row}", $item['firstname'] . ' ' . $item['lastname']);
            $sheet->setCellValue("B{$row}", $item['base_salary']);
            $sheet->setCellValue("C{$row}", $item['salary_month']);
            $sheet->setCellValue("D{$row}", $item['salary_type']);
            $sheet->setCellValue("E{$row}", $item['working_days']);
            $sheet->setCellValue("F{$row}", $item['salary_amount']);
            $sheet->setCellValue("G{$row}", $item['status'] ?? 'Unpaid');
            $sheet->setCellValue("H{$row}", $item['voucher_number']);
            $sheet->setCellValue("I{$row}", $item['date']);
            $row++;
        }

        $filename = 'Salary_Ledger_' . date('Ymd_His') . '.xlsx';

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header("Content-Disposition: attachment; filename=\"$filename\"");
        header('Cache-Control: max-age=0');

        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');
        exit;
    }

}