<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\UserModel;
use App\Models\AccountHeadModel;
use App\Models\EmployeePayrollModel;
use App\Models\VoucherModel;
use App\Models\VoucherEntryModel;
use CodeIgniter\I18n\Time;
use App\Models\TenantsModel;
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
        $userModel    = new UserModel();
        $tenantModel  = new TenantsModel();

        if (isSuperAdmin()) {
            $data['tenants'] = $tenantModel->findAll();

            $selectedTenantId = $this->request->getGet('tenant_id');

            if ($selectedTenantId) {
                $data['salaryPayments'] = $payrollModel
                ->select('employee_payrolls.*, users.firstname, users.lastname, tenants.name as tenant_name')
                ->join('users', 'users.id = employee_payrolls.user_id')
                ->join('tenants', 'tenants.id = users.tenant_id', 'left')
                ->where('users.tenant_id', $selectedTenantId)
                ->orderBy('employee_payrolls.salary_month', 'DESC')
                ->findAll();

                $data['employees'] = $userModel
                ->where('tenant_id', $selectedTenantId)
                ->where('is_active', 1)
                ->orderBy('firstname')
                ->findAll();
            } else {
                $data['salaryPayments'] = $payrollModel
                ->select('employee_payrolls.*, users.firstname, users.lastname, tenants.name as tenant_name')
                ->join('users', 'users.id = employee_payrolls.user_id')
                ->join('tenants', 'tenants.id = users.tenant_id', 'left')
                ->orderBy('employee_payrolls.salary_month', 'DESC')
                ->findAll();

                $data['employees'] = $userModel
                ->where('is_active', 1)
                ->orderBy('firstname')
                ->findAll();
            }

            $data['selectedTenantId'] = $selectedTenantId;
        } else {
            $tid = currentTenantId();

            $data['salaryPayments'] = $payrollModel
            ->select('employee_payrolls.*, users.firstname, users.lastname, tenants.name as tenant_name')
            ->join('users', 'users.id = employee_payrolls.user_id')
            ->join('tenants', 'tenants.id = users.tenant_id', 'left')
            ->where('users.tenant_id', $tid)
            ->orderBy('employee_payrolls.salary_month', 'DESC')
            ->findAll();

            $data['employees'] = $userModel
            ->where('tenant_id', $tid)
            ->where('is_active', 1)
            ->orderBy('firstname')
            ->findAll();
        }

        return view('payroll/salaryPayments', $data);
    }

    public function addSalaryPayment()
    {
        $payrollModel      = new EmployeePayrollModel();
        $voucherModel      = new VoucherModel();
        $voucherEntryModel = new VoucherEntryModel();
        $userModel         = new UserModel();
        $accountHeadModel  = new AccountHeadModel();

        $userId        = $this->request->getPost('user_id');
        $salaryMonth   = $this->request->getPost('salary_month') . '-01';
        $workingDays   = $this->request->getPost('working_days');
        $salaryAmount  = $this->request->getPost('salary_amount');
        $date          = $this->request->getPost('date') ?? date('Y-m-d');

        $user = $userModel->find($userId);

        if (!$user) {
            return redirect()->back()->with('error', 'Invalid employee selected.');
        }

        $tenantId = isSuperAdmin()
        ? ($this->request->getPost('tenant_id') !== '' ? $this->request->getPost('tenant_id') : null)
        : currentTenantId();

        $lastVoucher   = $voucherModel->where('voucher_type', 'payment')->orderBy('id', 'DESC')->first();
        $lastId        = $lastVoucher ? $lastVoucher['id'] + 1 : 1;
        $voucherNumber = 'PV-' . str_pad($lastId, 4, '0', STR_PAD_LEFT);

        $employeeAccount      = $accountHeadModel->where('linked_user_id', $userId)->first();
        $salaryExpenseAccount = $accountHeadModel
        ->where('type', 'Expense')
        ->like('name', 'Salary')
        ->where('tenant_id', $tenantId)
        ->first();

        if (!$employeeAccount || !$salaryExpenseAccount) {
            return redirect()->back()->with('error', 'Account heads not properly configured.');
        }

        $voucherData = [
            'voucher_number' => $voucherNumber,
            'voucher_type'   => 'payment',
            'date'           => $date,
            'reference_no'   => null,
            'description'    => 'Salary Payment for ' . $user['firstname'] . ' ' . $user['lastname'] . ' - ' . $salaryMonth,
            'tenant_id'      => $tenantId,
            'created_by'     => session()->get('user_id'),
            'updated_by'     => session()->get('user_id'),
            'created_at'     => date('Y-m-d H:i:s'),
            'updated_at'     => date('Y-m-d H:i:s'),
        ];

        $voucherId = $voucherModel->insert($voucherData);

        if ($voucherId) {
            $voucherEntryModel->insert([
                'voucher_id'      => $voucherId,
                'account_head_id' => $employeeAccount['id'],
                'type'            => 'debit',
                'amount'          => $salaryAmount,
                'narration'       => 'Salary Payment',
                'tenant_id'       => $tenantId,
                'created_at'      => date('Y-m-d H:i:s'),
                'updated_at'      => date('Y-m-d H:i:s'),
            ]);

            $voucherEntryModel->insert([
                'voucher_id'      => $voucherId,
                'account_head_id' => $salaryExpenseAccount['id'],
                'type'            => 'credit',
                'amount'          => $salaryAmount,
                'narration'       => 'Salary Expense',
                'tenant_id'       => $tenantId,
                'created_at'      => date('Y-m-d H:i:s'),
                'updated_at'      => date('Y-m-d H:i:s'),
            ]);

            $payrollModel->insert([
                'user_id'       => $userId,
                'salary_month'  => $salaryMonth,
                'salary_type'   => $user['salary_type'],
                'working_days'  => $workingDays,
                'salary_amount' => $salaryAmount,
                'voucher_id'    => $voucherId,
                'status'        => 'paid',
                'tenant_id'     => $tenantId,
                'created_by'    => session()->get('user_id'),
                'updated_by'    => session()->get('user_id'),
                'created_at'    => date('Y-m-d H:i:s'),
                'updated_at'    => date('Y-m-d H:i:s'),
            ]);

            return redirect()->back()->with('success', 'Salary paid and voucher created successfully.');
        }

        return redirect()->back()->with('error', 'Failed to process salary payment.');
    }

    public function exportSalaryPayments()
    {
        $payrollModel = new EmployeePayrollModel();
        $tenantId = $this->request->getGet('tenant_id');

        if (isSuperAdmin()) {
            if (!empty($tenantId)) {
                $salaryPayments = $payrollModel
                ->select('employee_payrolls.*, users.firstname, users.lastname, vouchers.voucher_number')
                ->join('users', 'users.id = employee_payrolls.user_id')
                ->join('vouchers', 'vouchers.id = employee_payrolls.voucher_id', 'left')
                ->where('employee_payrolls.tenant_id', $tenantId)
                ->orderBy('employee_payrolls.salary_month', 'DESC')
                ->findAll();
            } else {
                $salaryPayments = $payrollModel
                ->select('employee_payrolls.*, users.firstname, users.lastname, vouchers.voucher_number')
                ->join('users', 'users.id = employee_payrolls.user_id')
                ->join('vouchers', 'vouchers.id = employee_payrolls.voucher_id', 'left')
                ->orderBy('employee_payrolls.salary_month', 'DESC')
                ->findAll();
            }
        } else {
            $salaryPayments = $payrollModel
            ->select('employee_payrolls.*, users.firstname, users.lastname, vouchers.voucher_number')
            ->join('users', 'users.id = employee_payrolls.user_id')
            ->join('vouchers', 'vouchers.id = employee_payrolls.voucher_id', 'left')
            ->where('employee_payrolls.tenant_id', currentTenantId())
            ->orderBy('employee_payrolls.salary_month', 'DESC')
            ->findAll();
        }

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $sheet->setCellValue('A1', 'Employee')
        ->setCellValue('B1', 'Month')
        ->setCellValue('C1', 'Working Days')
        ->setCellValue('D1', 'Salary Type')
        ->setCellValue('E1', 'Amount')
        ->setCellValue('F1', 'Status')
        ->setCellValue('G1', 'Voucher No')
        ->setCellValue('H1', 'Paid On')
        ->setCellValue('I1', 'Tenant ID');

        $row = 2;
        foreach ($salaryPayments as $payment) {
            $sheet->setCellValue('A' . $row, $payment['firstname'] . ' ' . $payment['lastname'])
            ->setCellValue('B' . $row, $payment['salary_month'])
            ->setCellValue('C' . $row, $payment['working_days'])
            ->setCellValue('D' . $row, ucfirst($payment['salary_type']))
            ->setCellValue('E' . $row, $payment['salary_amount'])
            ->setCellValue('F' . $row, $payment['status'])
            ->setCellValue('G' . $row, $payment['voucher_number'] ?? 'N/A')
            ->setCellValue('H' . $row, date('d M Y', strtotime($payment['created_at'])))
            ->setCellValue('I' . $row, $payment['tenant_id']);
            $row++;
        }

        $writer = new Xlsx($spreadsheet);
        $filename = 'salary_payments_' . date('Ymd_His') . '.xlsx';

        header('Content-Type: application/vnd.ms-excel');
        header("Content-Disposition: attachment; filename=\"$filename\"");
        $writer->save('php://output');
        exit;
    }

    public function salaryLedger()
    {
        $payrollModel = new EmployeePayrollModel();
        $userModel    = new UserModel();
        $voucherModel = new VoucherModel();
        $tenantModel  = new TenantsModel();

        $employee_id  = $this->request->getGet('employee_id');
        $salary_month = $this->request->getGet('salary_month');
        $status       = $this->request->getGet('status');

        if (isSuperAdmin()) {
            $data['tenants'] = $tenantModel->findAll();
            $selectedTenantId = $this->request->getGet('tenant_id');

            if ($selectedTenantId) {
                $builder = $this->buildSalaryLedgerQuery($employee_id, $salary_month, $status);
                $builder->where('users.tenant_id', $selectedTenantId);
                $payrolls = $builder->get()->getResultArray();

                $employees = $userModel->where('tenant_id', $selectedTenantId)
                ->orderBy('firstname')->findAll();
            } else {
                $builder = $this->buildSalaryLedgerQuery($employee_id, $salary_month, $status);
                $payrolls = $builder->get()->getResultArray();

                $employees = $userModel->orderBy('firstname')->findAll();
            }

            $data['selectedTenantId'] = $selectedTenantId;
        } else {
            $tid = currentTenantId();

            $builder = $this->buildSalaryLedgerQuery($employee_id, $salary_month, $status);
            $builder->where('users.tenant_id', $tid);

            $payrolls = $builder->get()->getResultArray();
            $employees = $userModel->where('tenant_id', $tid)->orderBy('firstname')->findAll();
        }

        $data['payrolls']           = $payrolls;
        $data['employees']          = $employees;
        $data['filter_employee_id'] = $employee_id;
        $data['filter_salary_month']= $salary_month;
        $data['filter_status']      = $status;

        return view('payroll/salaryLedger', $data);
    }

    private function buildSalaryLedgerQuery($employee_id, $salary_month, $status)
    {
        $db = \Config\Database::connect();
        $builder = $db->table('users');

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
            v.date as voucher_date,
            tenants.name as tenant_name
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
        $builder->join('tenants', 'tenants.id = users.tenant_id', 'left');

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
                $builder->orWhere('ep.status !=', 'paid')
                ->orWhere('ep.salary_month IS NULL', null, false);
            }
            $builder->groupEnd();
        }

        $builder->orderBy('users.firstname', 'ASC');

        return $builder;
    }

    public function exportSalaryLedger()
    {
        $employee_id   = $this->request->getGet('employee_id');
        $salary_month  = $this->request->getGet('salary_month');
        $status        = $this->request->getGet('status');
        $tenantId      = $this->request->getGet('tenant_id');

        $db = \Config\Database::connect();
        $builder = $db->table('users u')
        ->select('
            u.firstname,
            u.lastname,
            u.salary_amount AS base_salary,
            ep.salary_month,
            ep.salary_type,
            ep.working_days,
            ep.salary_amount,
            ep.status,
            t.name AS tenant_name,
            v.voucher_number,
            v.date
            ');

    // Join employee payrolls
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

        $builder->join('tenants t', 't.id = u.tenant_id', 'left');

    // Filters
        if (!empty($employee_id)) {
            $builder->where('u.id', $employee_id);
        }

        if ($status === 'paid') {
            $builder->where('ep.status', 'paid');
        } elseif ($status === 'unpaid') {
            $builder->where('(ep.status IS NULL OR ep.status != "paid")', null, false);
        }

        if (isSuperAdmin()) {
            if (!empty($tenantId)) {
                $builder->where('u.tenant_id', $tenantId);
            }
        } else {
            $builder->where('u.tenant_id', currentTenantId());
        }

        $builder->orderBy('u.firstname', 'ASC');
        $data = $builder->get()->getResultArray();

    // ---------------- Excel Setup ----------------
        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $sheet->setCellValue('A1', "Salary Ledger Report");

    // Column headers including Tenant
        $headers = ['Tenant', 'Employee Name', 'Base Salary', 'Salary Month', 'Salary Type', 'Working Days', 'Salary Paid', 'Status', 'Voucher #', 'Voucher Date'];
        $sheet->fromArray($headers, null, 'A3');

        $row = 4;
        foreach ($data as $item) {
        $sheet->setCellValue("A{$row}", $item['tenant_name'] ?? 'N/A');
        $sheet->setCellValue("B{$row}", $item['firstname'] . ' ' . $item['lastname']);
        $sheet->setCellValue("C{$row}", $item['base_salary']);
        $sheet->setCellValue("D{$row}", $item['salary_month']);
        $sheet->setCellValue("E{$row}", $item['salary_type']);
        $sheet->setCellValue("F{$row}", $item['working_days']);
        $sheet->setCellValue("G{$row}", $item['salary_amount']);
        $sheet->setCellValue("H{$row}", $item['status'] ?? 'Unpaid');
        $sheet->setCellValue("I{$row}", $item['voucher_number']);
        $sheet->setCellValue("J{$row}", $item['date']);
        $row++;
    }

    foreach (range('A', 'J') as $col) {
        $sheet->getColumnDimension($col)->setAutoSize(true);
    }

    $fileName = 'Salary_Ledger_' . date('Ymd_His') . '.xlsx';

    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header("Content-Disposition: attachment; filename=\"$fileName\"");
    header('Cache-Control: max-age=0');

    $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
    $writer->save('php://output');
    exit;
}

}