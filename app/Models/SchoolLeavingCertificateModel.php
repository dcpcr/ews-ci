<?php

namespace App\Models;

use CodeIgniter\Model;

class SchoolLeavingCertificateModel extends Model
{
    protected $DBGroup = 'default';
    protected $table = 'school_leaving_certificate';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $insertID = 0;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = ['student_id', 'student_name', 'last_attendance_date', 'transferred_to'];


    /**
     * @throws \ReflectionException
     */
    public function fetchSchoolLeavingCertificateData()
    {
        helper('edutel');
        $data=fetch_school_leaving_certificate_data_from_edudel();
        $table_data = [];
        foreach ($data as $row) {
            $table_data[] = [
                "student_id" => $row['studentid'],
                "student_name" => $row['name'],
                "last_attendance_date" => $row['LastAttendedDate'],
                "transferred_to" => $row['SchoolTo']
            ];
        }
        $count = $this->ignore()->insertBatch($table_data, null, count($table_data));
        log_message("info", "$count new records inserted in school_leaving_certificate table.");
        $count = $this->updateBatch($table_data, 'student_id', 2000);
        log_message("info", "$count records updated in school_leaving_certificate table.");

    }


}
