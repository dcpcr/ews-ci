<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class StudentCount extends Migration
{
    public function up()
    {
        //DETECTED CASE INFORMATION TABLE
        $this->forge->addField([

            'school_id' => [
                'type' => 'BIGINT',
                'unsigned' => true,
                'NOT NULL' => true,
            ],
            'class' => [
                'type' => 'CHAR',
                'constraint' => '5',
                'NOT NULL' => true,

            ],
            'total_student' => [
                'type' => 'BIGINT',
                'unsigned' => true,
                'NOT NULL' => true,
            ],

            'district_name' => [
                'type' => 'CHAR',
                'constraint' => '50',
                'NOT NULL' => true,

            ],

            'zone_name' => [
                'type' => 'CHAR',
                'constraint' => '50',
                'NOT NULL' => true,

            ],
            'school_name' => [
                'type' => 'CHAR',
                'constraint' => '50',
                'NOT NULL' => true,

            ],
        ]);
        $this->forge->addPrimaryKey('school_id,class');
        $this->forge->createTable('student_count');
    }

    public function down()
    {
        //
        $this->forge->dropTable('student_count');
    }
}
