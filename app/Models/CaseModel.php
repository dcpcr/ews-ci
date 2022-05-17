<?php

namespace App\Models;

use CodeIgniter\Model;

class CaseModel extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = 'detected_case';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = false;
    protected $returnType       = 'array';
}
