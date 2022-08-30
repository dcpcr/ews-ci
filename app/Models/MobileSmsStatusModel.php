<?php

namespace App\Models;

use CodeIgniter\Model;

class MobileSmsStatusModel extends Model
{
    protected $DBGroup = 'master';
    protected $table = 'mobile_sms_status';
    protected $primaryKey = 'mobile';
    protected $returnType = 'array';
    protected $allowedFields = ['mobile, sms_status'];

    // Validation
    protected $validationRules = [];
    protected $validationMessages = [];
    protected $skipValidation = false;
    protected $cleanValidationRules = true;

    /**
     * @throws \ReflectionException
     */
    public function updateMobiles()
    {
        $mobiles = $this->select([
            'student.mobile as mobile',
        ])->distinct()
            ->join('student', 'student.mobile != ' . $this->table . '.mobile')
            ->findAll();


        $sub_query = $this->select([
            'mobile'
        ]);

        $builder = $this->select([
            'mobile'
        ])
            ->whereNotIn('mobile', $sub_query)
            ->findAll();
        $this->insertBatch($mobiles);
    }
}
