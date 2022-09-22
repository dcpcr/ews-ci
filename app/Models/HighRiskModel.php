<?php

namespace App\Models;

use CodeIgniter\Model;

class HighRiskModel extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = 'high_risk';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $insertID         = 0;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['case_id','status'];
//if status=1 then ticket raised

    /**
     * @throws \ReflectionException
     */
    function insertUpdateHighRisk($cases)
   {
       helper('cyfuture');
       if ($cases) {
           $highRiskData = extract_high_risk_data_from_cases($cases);
           $keyMapping = array(
               "raised_ticket" => "status"
           );
           $tableData = prepare_data_for_table($highRiskData, $keyMapping);
           $count = $this->ignore()->insertBatch($tableData,null,2000);
           log_message("info", "$count New Records inserted in high_risk table.");
           $count = $this->updateBatch($tableData, 'case_id', 2000);
           log_message("info", "$count Records updated in high_risk table.");
       }
   }

    public function getHighRiskCasesCountGenderWise(array $school_ids, $classes, $start, $end): array
    {
        helper('general');
        $master_db = get_database_name_from_db_group('master');
        return $this->select([
            'count(*) as count',
            'student.gender as gender',
        ])
            ->join('detected_case as case', 'case.id = ' . $this->table . '.case_id')
            ->join($master_db . '.student as student', 'student.id = case.student_id')
            ->whereIn('student.school_id', $school_ids)
            ->whereIn('student.class', $classes)
            ->where("day BETWEEN STR_TO_DATE('" . $start . "' , '%m/%d/%Y') and STR_TO_DATE('" .
                $end . "', '%m/%d/%Y')")
            ->where($this->table.'.status','1')
            ->groupBy('gender')
            ->findAll();
    }
}
