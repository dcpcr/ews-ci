<?php

namespace App\Models;

use CodeIgniter\Model;

class SchoolMappingModel extends Model
{
    protected $DBGroup = 'master';
    protected $table = 'school_mapping';
    protected $primaryKey = 'school_id';
    protected $useAutoIncrement = false;

    protected $returnType = 'array';
    protected $allowedFields = ['school_id', 'district_id', 'zone_id'];

    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    protected $dateFormat = 'datetime';

    public function updateMappings()
    {
        $school_model = new SchoolModel();
        $schools = $school_model
            ->select([
                'school.id as school_id',
                'district.id as district_id',
                '(district.id * 100 + school.zone) as zone_id'
            ])
            ->join('district', 'school.district = district.name')
            ->join('zone', '(district.id * 100 + school.zone) = zone.id')
            ->orderBy('school_id')
            ->findAll();

        foreach ($schools as $school) {
            $this->replace($school);
        }
    }
}
