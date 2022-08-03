<?php

namespace App\Models;

use CodeIgniter\Model;

class UntraceableModel extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = 'untraceables';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $insertID         = 0;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [];

}
