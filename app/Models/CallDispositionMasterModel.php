<?php

namespace App\Models;

use CodeIgniter\Model;

class CallDispositionMasterModel extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = 'call_disposition_master';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $insertID         = 0;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['id','name','status'];

}
