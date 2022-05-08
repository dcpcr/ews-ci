<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class DetectedCase extends Migration
{
    public function up()
    {
        //DETECTED CASE INFORMATION TABLE
        $this->forge->addField([
            'case_id' => [
                'type' => 'BIGINT',
                'unsigned' => true,
                'NOT NULL' => true,
                'AUTO_INCREMENT' => true,

            ],
            'student_id' => [
                'type' => 'BIGINT',
                'unsigned' => true,
                'NOT NULL' => true,

            ],
            'detection_criteria' => [
                'type' => 'ENUM("7 Consecutive Days Absent ","<60% Attendance","0")',
                'default' => '0',

            ],
            'status' => [
                'type' => 'ENUM("Fresh","In Pursuit","Untraceable","Home Visit","Ticket-Raised-Normal-Resolved","Ticket-Raised-SOS","To Be Back To School","Not Sure","Refused","Back To School")',
                'default' => 'Fresh',

            ],
            'date_of_bts' => [
                'type' => 'varchar',
                'constraint' => '10',

            ],
            'system_bts' => [
                'type' => 'ENUM("Yes","No")',
                'default' => 'No',

            ],



        ]);

        $this->forge->addPrimaryKey('case_id');
        $this->forge->createTable('detected_case');
    }

    public function down()
    {
        //
        $this->forge->dropTable('detected_case');
    }


}