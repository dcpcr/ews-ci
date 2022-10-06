<?php

namespace App\Models;

class HomeVisitModel extends CaseDetailsModel
{
    protected $DBGroup = 'default';
    protected $table = 'home_visit';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $insertID = 0;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = ['case_id', 'status'];

    protected function getKeys(): array
    {
        return array('case_id', 'is_home_visit_required');
    }

    protected function getKeyMappings(): array
    {
        return array(
            "is_home_visit_required" => "status"
        );
    }
}
