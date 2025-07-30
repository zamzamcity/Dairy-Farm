<?php

namespace App\Models;

use CodeIgniter\Model;

class EmployeePayrollModel extends Model
{
    protected $table = 'employee_payrolls';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'user_id',
        'salary_month',
        'salary_type',
        'working_days',
        'salary_amount',
        'voucher_id',
        'status',
        'created_at',
        'updated_at'
    ];

    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
}