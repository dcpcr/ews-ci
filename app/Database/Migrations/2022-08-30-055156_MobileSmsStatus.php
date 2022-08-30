<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class MobileSmsStatus extends Migration
{
    protected $DBGroup = 'master';
    public function up()
    {
        $this->forge->addField([
            'mobile' => [
                'type' => 'CHAR',
                'constraint' => '20',
                'NOT NULL' => true,
            ],
            'sms_status' => [
                'type' => 'VARCHAR',
                'constraint' => '40',
                'NULL' => true,
            ],

        ]);
        $this->forge->addField(
            [
                'created_at timestamp not null default current_timestamp',
                'updated_at timestamp not null default current_timestamp on update current_timestamp',
            ]);
        $this->forge->addPrimaryKey(['mobile']);
        $this->forge->addKey('sms_status');
        $this->forge->createTable('mobile_sms_status');
    }

    public function down()
    {
        $this->forge->dropTable('mobile_sms_status');
    }
}