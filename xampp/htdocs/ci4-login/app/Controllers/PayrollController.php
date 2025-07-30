<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\UserModel;
use App\Models\AccountHeadModel;
use App\Models\EmployeePayrollModel;
use App\Models\VoucherModel;
use App\Models\VoucherEntryModel;
use CodeIgniter\I18n\Time;

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

}