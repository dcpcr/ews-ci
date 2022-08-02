<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class SchoolAttendance extends Migration
{
    public function up()
    {
        //  SCHOOL ATTENDANCE TABLE
        $this->forge->addField([
            'school_id' => [
                'type' => 'int',
                'unsigned' => true,
                'NOT NULL' => true,
            ],
            'class' => [
                'type' => 'char',
                'constraint' => '10',
                'NOT NULL' => true,
            ],
            'date' => [
                'type' => 'date',
                'NOT NULL' => true,
            ],
            'attendance_count' => [
                'type' => 'int',
                'unsigned' => true,
                'NOT NULL' => true,
            ],
            'total_marked_students' => [
                'type' => 'int',
                'unsigned' => true,
                'NOT NULL' => true,
            ],
        ]);
        $this->forge->addPrimaryKey(['school_id', 'class', 'date']);
        $this->forge->addKey(['school_id', 'class']);
        $this->forge->addKey('school_id');
        $this->forge->addKey('class');
        $this->forge->addKey('date');
        $this->forge->createTable('school_attendance');
    }

    public function down()
    {
        $this->forge->dropTable('school_attendance');
    }
}
