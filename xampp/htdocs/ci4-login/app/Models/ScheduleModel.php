<?php

namespace App\Models;

use CodeIgniter\Model;

class ScheduleModel extends Model
{
    protected $table = 'schedules';
    protected $primaryKey = 'id';
    protected $allowedFields = ['tag_id', 'date', 'time', 'event_id', 'comments'];
    protected $useTimestamps = true;
}
