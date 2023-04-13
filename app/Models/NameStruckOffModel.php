<?php

namespace App\Models;

use CodeIgniter\Model;

class NameStruckOffModel extends Model
{
    protected $DBGroup = 'default';
    protected $table = 'name_struck_off';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $insertID = 0;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = ['school_id', 'school_name', 'student_id', 'student_name', 'nso_date', 'reason'];

    /**
     * @throws \ReflectionException
     */
    public function fetchNameStruckOffData()
    {

        helper('edutel');
        $data = fetch_name_struck_out_data_from_edudel();
        $table_data = [];
        foreach ($data as $row) {
            $table_data[] = [
                "school_id" => $row['schid'],
                "school_name" => $row['schname'],
                "student_id" => $row['StudentID'],
                "student_name" => $row['name'],
                "nso_date" => $row['NSODate'],
                "reason" => $row['Reason']
            ];
        }

        $count = $this->ignore()->insertBatch($table_data, null, count($table_data));
        log_message("info", "$count new records inserted in school_leaving_certificate table.");
        $count = $this->updateBatch($table_data, 'student_id', 2000);
        log_message("info", "$count records updated in school_leaving_certificate table.");

    }

    public function getNSOList($no_of_records_per_page,$offset): array
    {
        return $this->select()
            ->join("master.student","master.student.id=student_id")
            ->limit($no_of_records_per_page, $offset)
            ->findAll();
    }

    public function getTotalNumberNSOStudent()
    {
        return $this->select(['student_id'])
            ->join("master.student","master.student.id=student_id")
            ->countAllResults();
    }


}
