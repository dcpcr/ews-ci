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
            'reason_for_absent' => [
                'type' => 'varchar',
                'constraint' => '100',
                'default' => ""
            ],
            'student_name' => [
                'type' => 'varchar',
                'constraint' => '100',
                'default' => ""
            ],
            'student_dob' => [
                'type' => 'char',
                'constraint' => '20',
            ],
            'student_class' => [
                'type' => 'char',
                'constraint' => '10',
                'NOT NULL' => true,
            ],
            'student_section' => [
                'type' => 'char',
                'constraint' => '1',
            ],
            'student_gender' => [
                'type' => 'char',
                'constraint' => '20',
            ],
            'student_father' => [
                'type' => 'char',
                'constraint' => '100',
            ],
            'student_mother' => [
                'type' => 'char',
                'constraint' => '100',
            ],
            'student_guardian' => [
                'type' => 'char',
                'constraint' => '100',
            ],
            'student_guardian_relation' => [
                'type' => 'char',
                'constraint' => '100',
            ],
            'student_address' => [
                'type' => 'varchar',
                'constraint' => '255',
            ],
            'student_mobile' => [
                'type' => 'CHAR',
                'constraint' => '20',
                'NOT NULL' => true,
            ],
            'student_cwsn' => [
                'type' => 'CHAR',
                'constraint' => '20',
                'default' => "",
            ],
            'student_disability_type' => [
                'type' => 'CHAR',
                'constraint' => '20',
                'default' => "",
            ],
            'student_school_id' => [
                'type' => 'INT',
                'unsigned' => true,
                'default' => null,
            ],
            'sms_delivery_status' => [
                'type' => 'varchar',
                'constraint' => '20',
                'default' => "",
            ],
            'sms_delivery_time' => [
                'type' => 'varchar',
                'constraint' => '20',
                'default' => "",
            ],
            'first_present_date_after_detection' => [
                'type' => 'char',
                'constraint' => '20',
                'default' => '',
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
        $this->forge->addColumn('detected_case', ['`created_at` date default (curdate()) NOT NULL']);
        $this->forge->addColumn('detected_case', ['updated_at timestamp not null default current_timestamp on update current_timestamp']);
    }

    public function down()
    {
        //
    }
}
