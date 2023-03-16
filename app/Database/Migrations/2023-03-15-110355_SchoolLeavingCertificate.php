<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class SchoolLeavingCertificate extends Migration
{
    public function up()
    {
        //
        $this->forge->addField([

            'student_id' => [
                'type' => 'BIGINT',
                'unsigned' => true,
                'NOT NULL' => true,
            ],
            'student_name' => [
                'type' => 'CHAR',
                'constraint' => '100',
                'NOT NULL' => true,

            ],
            'last_attendance_date' => [
                'type' => 'DATETIME',
                'NOT NULL' => true,
            ],

            'transferred_to' => [
                'type' => 'CHAR',
                'constraint' => '255',
                'NOT NULL' => true,

            ]
        ]);
        $this->forge->addPrimaryKey('student_id');
        $this->forge->createTable('school_leaving_certificate');
    }

    public function down()
    {
        //
        $this->forge->dropTable('school_leaving_certificate');
    }
}
