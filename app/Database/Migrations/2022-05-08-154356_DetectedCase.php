<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class DetectedCase extends Migration
{
    public function up()
    {
        //DETECTED CASE INFORMATION TABLE
        $this->forge->addField([
            'id' => [
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
            'seven_days_criteria' => [
                'type' => 'BIT',
                'default' => 0,
            ],
            'thirty_days_criteria' => [
                'type' => 'BIT',
                'default' => 0,
            ],
            'status' => [
                'type' => 'ENUM("Fresh","In Pursuit","Untraceable","Home Visit","Ticket-Raised-Normal-Resolved","Ticket-Raised-SOS","To Be Back To School","Not Sure","Refused","Back To School")',
                'default' => 'Fresh',
            ],
            'date_of_bts' => [
                'type' => 'date',
                'default' => null,
            ],
            'system_bts' => [
                'type' => 'int',
                'default' => '0',
            ],
            'priority' => [
                'type' => 'ENUM("High","Low","Medium")',
                'default' => 'Medium',
            ],
        ]);

        $this->forge->addField(['`day` date default (curdate()) NOT NULL']);

        $this->forge->addPrimaryKey('id');
        $this->forge->addKey('student_id');
        $this->forge->createTable('detected_case');
    }

    public function down()
    {
        //
        $this->forge->dropTable('detected_case');
    }
}