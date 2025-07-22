<?php 

namespace App\Models;

use CodeIgniter\Model;

class DailyMilkingModel extends Model
{
    protected $table = 'daily_milking';
    protected $primaryKey = 'id';

    protected $allowedFields = ['date', 'milk_product', 'milk_1', 'milk_2', 'milk_3'];

    protected $useTimestamps = true;
}
