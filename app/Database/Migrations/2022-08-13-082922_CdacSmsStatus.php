<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CdacSmsStatus extends Migration
{
    protected $DBGroup = 'master';
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type' => 'BIGINT',
                'unsigned' => true,
                'NOT NULL' => true,
                'auto_increment' => true,
            ],
            'batch_id' => [
                'type' => 'BIGINT',
                'unsigned' => true,
                'NOT NULL' => true,
            ],
            'mobile_number' => [
                'type' => 'char',
                'constraint' => '13',
            ],
            'status' => [
                'type' => 'Varchar',
                'constraint' => '25',
                'default' => '0',
            ],

        ]);
        $this->forge->addField(
            [
                'created_at timestamp not null default current_timestamp',
            ]);
        $this->forge->addPrimaryKey(['id']);
        $this->forge->addKey('sms_batch_id');
        $this->forge->addKey('mobile_number');
        $this->forge->addKey('status');
        $this->forge->createTable('cdac_sms_status');
    }

    public function down()
    {
        $this->forge->dropTable('cdac_sms_status');
    }
}
