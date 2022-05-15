<?php

namespace App\Models;

use CodeIgniter\Model;

class SchoolMappingModel extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = 'school_mapping';
    protected $primaryKey       = 'school_id';
    protected $useAutoIncrement = false;

    protected $returnType       = 'array';
    protected $allowedFields    = ['school_id', 'district_id', 'zone_id'];

    protected $useTimestamps    = true;
    protected $createdField     = 'created_at';
    protected $updatedField     = 'updated_at';
    protected $dateFormat       = 'datetime';
}
