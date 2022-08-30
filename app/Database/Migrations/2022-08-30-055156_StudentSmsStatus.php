<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class StudentSmsStatus extends Migration
{
    protected $DBGroup = 'master';
    public function up()
    {
        $this->forge->addField([
            'student_id' => [
                'type' => 'BIGINT',
                'unsigned' => true,
                'auto_increment' => true,

            ],
            'sms_status' => [
                'type' => 'VARCHAR',
                'constraint' => '40',
            ],

        ]);
        $this->forge->addField(
            [
                'created_at timestamp not null default current_timestamp',
                'updated_at timestamp not null default current_timestamp on update current_timestamp',
            ]);
        $this->forge->addPrimaryKey(['student_id']);
        $this->forge->addKey('sms_status');
        $this->forge->createTable('student_sms_status');
    }

    public function down()
    {
        $this->forge->dropTable('student_sms_status');
    }
}