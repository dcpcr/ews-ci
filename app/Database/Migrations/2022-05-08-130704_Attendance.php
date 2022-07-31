<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Attendance extends Migration
{
    public function up()
    {
        //  ATTENDANCE TABLE
        $this->forge->addField([
            'student_id' => [
                'type' => 'BIGINT',
                'unsigned' => true,
                'NOT NULL' => true,
            ],
            'attendance_status' => [
                'type' => 'CHAR',
                'constraint' => '1',
            ],
            'date' => [
                'type' => 'date',
                'NOT NULL' => true,
            ],
        ]);
        $this->forge->addPrimaryKey(['student_id', 'date']);
        $this->forge->addKey('student_id');
        $this->forge->addKey('date');
        $this->forge->addkey('attendance_status');
        $this->forge->createTable('attendance');
    }

    public function down()
    {
        $this->forge->dropTable('attendance');
    }
}
