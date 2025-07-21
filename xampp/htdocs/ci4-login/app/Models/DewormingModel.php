<?php

namespace App\Models;

use CodeIgniter\Model;

class DewormingModel extends Model
{
    protected $table            = 'deworming';
    protected $primaryKey       = 'id';

    protected $allowedFields    = ['name'];

    protected $useTimestamps    = true;
    protected $createdField     = 'created_at';
    protected $updatedField     = 'updated_at';
}
