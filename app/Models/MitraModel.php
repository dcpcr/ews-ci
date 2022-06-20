<?php

namespace App\Models;

use CodeIgniter\Model;

class MitraModel extends Model
{
    protected $DBGroup = 'parentsamvaad';
    protected $table = 'intercom_id';
    protected $primaryKey = 'student_id';
    protected $returnType = 'array';
    protected $useSoftDeletes = false;

    function getMitraDetailsFromParentSamvaad($student_id)
    {
        return $this->select(['mobile as mitra_mobile',
            'designation',
            'name as mitra_name',
            'i_id as intercom_id',
            'address as mitra_address',
            ])
            ->join('smc_member', 'intercom_id.m_mobile = smc_member.mobile')
            ->find("$student_id");
    }
}
