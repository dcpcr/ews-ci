<?php

namespace App\Models;

use CodeIgniter\Model;

class YetToBeContactedModel extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = 'yettobecontacteds';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $insertID         = 0;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [];

}
