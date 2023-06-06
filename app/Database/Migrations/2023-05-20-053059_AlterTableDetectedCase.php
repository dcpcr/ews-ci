<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AlterTableDetectedCase extends Migration
{
    public function up()
    {

        $fields = [
            'date_of_bts' => [
                'name' => 'date_of_bts',
                'type' => 'date',
                'default' => null,
            ],
        ];

        $this->forge->modifyColumn('detected_case', $fields);
        $fields = [
            'name' => [
                'type' => 'char',
                'constraint' => '100',
                'NOT NULL' => true,
            ],
            'dob' => [
                'type' => 'char',
                'constraint' => '20',
            ],
            'class' => [
                'type' => 'char',
                'constraint' => '10',
                'NOT NULL' => true,
            ],
            'section' => [
                'type' => 'char',
                'constraint' => '1',
            ],
            'gender' => [
                'type' => 'char',
                'constraint' => '20',
            ],
            'father' => [
                'type' => 'char',
                'constraint' => '100',
            ],
            'mother' => [
                'type' => 'char',
                'constraint' => '100',
            ],
            'guardian' => [
                'type' => 'char',
                'constraint' => '100',
            ],
            'guardian_relation' => [
                'type' => 'char',
                'constraint' => '100',
            ],
            'address' => [
                'type' => 'varchar',
                'constraint' => '255',
            ],
            'mobile' => [
                'type' => 'CHAR',
                'constraint' => '20',
                'NOT NULL' => true,
            ],
            'cwsn' => [
                'type' => 'CHAR',
                'constraint' => '20',
                'NOT NULL' => true,
            ],
            'disability_type' => [
                'type' => 'CHAR',
                'constraint' => '20',
                'NOT NULL' => true,
            ],
            'school_id' => [
                'type' => 'INT',
                'unsigned' => true,
                'NOT NULL' => true,
            ],
            'sms_delivery_status' => [
                'type' => 'varchar',
                'constraint' => '20',
                'NOT NULL' => true,
            ],
            'first_present_date_after_detection' => [
                'type' => 'char',
                'constraint' => '20',
            ],
            'number_of_time_sms_delivery_report_fetched' => [
                'type' => 'int',
                'constraint' => '1',
            ],
            'message_id' => [
                'type' => 'VARCHAR',
                'constraint' => '40',
            ],
            'sms_template_id' => [
                'type' => 'varchar',
                'constraint' => '20',
            ],

            'status_code' => [
                'type' => 'VARCHAR',
                'constraint' => '8',
            ],
            'report_fetched' => [
                'type' => 'int',
                'constraint' => '2',
                'default' => '0',
            ],
            'download_report' => [
                'type' => 'INT',
                'constraint' => 1,
                'after' => 'report_fetched',
                'default' => 0
            ],
            'sms_delivery_verification_date' => [
                'type' => 'char',
                'constraint' => '20',
            ],
            'sms_sent_date' => [
                'type' => 'char',
                'constraint' => '20',
            ],

        ];
        $this->forge->addColumn('detected_case', $fields);
        $this->forge->addColumn('detected_case', ['`modified_detection_date` date default (curdate()) NOT NULL']);
    }

    public function down()
    {
        //
    }
}
