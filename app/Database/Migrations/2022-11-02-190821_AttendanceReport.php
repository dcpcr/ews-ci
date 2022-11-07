<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AttendanceReport extends Migration
{
    public function up()
    {
        //  ATTENDANCE TABLE
        $this->forge->addField([
            'date' => [
                'type' => 'date',
                'NOT NULL' => true,
            ],
            'school_id' => [
                'type' => 'BIGINT',
                'unsigned' => true,
                'NOT NULL' => true,
            ],
            'class' => [
                'type' => 'CHAR',
                'constraint' => '10',
            ],
            'total_count' => [
                'type' => 'BIGINT',
                'unsigned' => true,
                'NOT NULL' => true,
            ],
            'present_count' => [
                'type' => 'BIGINT',
                'unsigned' => true,
                'NOT NULL' => true,
            ],
            'absent_count' => [
                'type' => 'BIGINT',
                'unsigned' => true,
                'NOT NULL' => true,
            ],
            'leave_count' => [
                'type' => 'BIGINT',
                'unsigned' => true,
                'NOT NULL' => true,
            ],

        ]);
        $this->forge->addPrimaryKey(['school_id', 'class', 'date']);
        $this->forge->addKey('school_id');
        $this->forge->addKey('date');
        $this->forge->addkey('class');
        $this->forge->createTable('attendance_report');
    }

    public function down()
    {
        $this->forge->dropTable('attendance_report');
    }
}
