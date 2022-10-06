<?php

namespace App\Models;

class BackToSchoolModel extends CaseDetailsModel
{
    protected $DBGroup = 'default';
    protected $table = 'back_to_school';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $insertID = 0;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = ['case_id', 'status'];

    protected function getKeys(): array
    {
        return array('case_id', 'will_student_be_able_to_join_school');
    }

    protected function getKeyMappings(): array
    {
        return array(
            "will_student_be_able_to_join_school" => "status"
        );
    }
}
