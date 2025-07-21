<?php

namespace App\Models;

use CodeIgniter\Model;

class VaccinationModel extends Model
{
    protected $table = 'vaccinations';
    protected $primaryKey = 'id';

    protected $allowedFields = ['name'];
    protected $useTimestamps = true;
}