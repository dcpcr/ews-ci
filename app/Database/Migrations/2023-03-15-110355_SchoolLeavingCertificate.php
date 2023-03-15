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
            'name' => [
                'type' => 'CHAR',
                'constraint' => '100',
                'NOT NULL' => true,

            ],
            'last_attended_date' => [
                'type' => 'DATETIME',
                'NOT NULL' => true,
            ],

            'school_to' => [
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
