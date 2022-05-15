<?php

namespace App\Models;

use CodeIgniter\Model;

class SchoolModel extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = 'school';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = false;

    protected $returnType       = 'array';
    protected $allowedFields    = ['id', 'name'];
}
