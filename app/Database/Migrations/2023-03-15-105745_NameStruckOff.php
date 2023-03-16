<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class NameStruckOff extends Migration
{
    public function up()
    {
        //
        $this->forge->addField([

            'school_id' => [
                'type' => 'BIGINT',
                'unsigned' => true,
                'NOT NULL' => true,
            ],
            'school_name' =>[
                'type' => 'CHAR',
                'constraint' => '100',
            ],
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
            'nso_date' => [
                'type' => 'DATETIME',
                'NOT NULL' => true,
            ],

            'reason' => [
                'type' => 'CHAR',
                'constraint' => '255',
                'NOT NULL' => true,

            ]
        ]);
        $this->forge->addPrimaryKey('student_id');
        $this->forge->createTable('name_struck_off');
    }

    public function down()
    {
        //
        $this->forge->dropTable('name_struck_off');
    }
}
