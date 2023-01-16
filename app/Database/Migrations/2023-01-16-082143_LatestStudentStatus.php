<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class LatestStudentStatus extends Migration

{
    public function up()
    {
        //DETECTED CASE INFORMATION TABLE
        $this->forge->addField([

            'case_id' => [
                'type' => 'BIGINT',
                'unsigned' => true,
                'NOT NULL' => true,
            ],
            'student_id' => [
                'type' => 'BIGINT',
                'unsigned' => true,
                'NOT NULL' => true,
            ],

            'status' => [
                'type' => 'ENUM("Fresh","In Pursuit","Untraceable","Home Visit","Ticket-Raised-Normal-Resolved","Ticket-Raised-SOS","To Be Back To School","Not Sure","Refused","Back To School")',
                'default' => 'Fresh',
            ],
        ]);
        $this->forge->addPrimaryKey('student_id');
        $this->forge->addKey('student_id');
        $this->forge->addKey('case_id');
        $this->forge->createTable('latest_student_status');
    }

    public function down()
    {
        //
        $this->forge->dropTable('latest_student_status');
    }
}

