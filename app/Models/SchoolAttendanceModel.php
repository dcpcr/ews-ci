<?php

namespace App\Models;

use CodeIgniter\Database\ConnectionInterface;
use CodeIgniter\Model;
use CodeIgniter\Validation\ValidationInterface;

class SchoolAttendanceModel extends Model
{
    protected $table = 'school_attendance';
    protected $primaryKey = 'school_id';
    protected $useAutoIncrement = false;

    protected $returnType = 'array';
    protected $allowedFields = ['school_id', 'date', 'attendance_count', 'total_marked_students'];


    public function __construct(?ConnectionInterface &$db = null, ?ValidationInterface $validation = null)
    {
        parent::__construct($db, $validation);
        helper('ews');
    }

    /**
     * @throws \ReflectionException
     */
    public function updateSchoolAttendance($from_date, $to_date)
    {
        $school_model = new SchoolModel();
        for ($date = $from_date; $date <= $to_date; $date = $date->modify('+1 day')) {
            $school_data = $school_model->getMarkedSchoolAttendanceClassWise($date);
            $data_array = array();
            $insert_array = array();
            if ($school_data) {
                foreach ($school_data as $school_class) {
                    $data_array[$school_class['school_id']] [$school_class['class']] ['attendance_count'] =
                        $school_class['attendance_count'];
                }
            }
            $school_data = $school_model->getTotalMarkedStudentsClassWise($date);
            if ($school_data) {
                foreach ($school_data as $school_class) {
                    $data_array[$school_class['school_id']] [$school_class['class']] ['total_marked_students'] =
                        $school_class['total_marked_students'];
                }
            }
            $count = 0;
            foreach ($data_array as $school_id => $school_data) {
                foreach ($school_data as $class => $class_data) {
                    $data = [
                        'school_id' => $school_id,
                        'class' => $class,
                        'date' => $date->format("d/m/Y"),
                        'attendance_count' => $class_data['attendance_count'],
                        'total_marked_students' => $class_data['total_marked_students'],
                    ];
                    $insert_array [] = $data;
                    $count++;
                }
            }
            if ($count > 0) {
                $this->insertBatch($insert_array);
            }

        }
    }
}
