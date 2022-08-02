<?php

namespace App\Models;

use CodeIgniter\Model;
use ReflectionException;

class ClassModel extends Model
{
    protected $DBGroup = 'master';
    protected $table = 'class';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;

    protected $returnType = 'array';
    protected $allowedFields = ['id', 'name'];

    //protected $order = ['XII', 'XI', 'X', 'IX', 'VIII', 'VII', 'VI', 'V', 'IV', 'III', 'II', 'I', 'KG', 'Nursery'];

    /**
     * @throws ReflectionException
     */
    public function updateClasses()
    {
        $student_model = new StudentModel();
        $classes = $student_model->getClasses();
        if ($classes) {
            $this->truncate();
            $class_array = array();
            foreach ($classes as $class) {
                $class_in_table = $this->where('name', $class['class'])->first();
                if (!$class_in_table) {
                    $data = [
                        //'id' => $this->get_order_of_class(),
                        'name' => $class['class'],
                    ];
                    $class_array [] = $data;
                }
            }
            $this->insertBatch($class_array);
        }
    }

    public function getClasses() {
        return $this->select('name')->findAll();
    }

    private function get_order_of_class()
    {
    }
}
