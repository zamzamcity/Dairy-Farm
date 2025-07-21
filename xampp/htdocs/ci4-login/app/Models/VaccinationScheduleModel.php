<?php

namespace App\Models;

use CodeIgniter\Model;

class VaccinationScheduleModel extends Model
{
    protected $table = 'vaccination_schedules';
    protected $primaryKey = 'id';

    protected $allowedFields = ['month', 'date', 'vaccination_id', 'comments'];
    protected $useTimestamps = true;
}
