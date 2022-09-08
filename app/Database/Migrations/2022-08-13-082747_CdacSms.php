<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CdacSms extends Migration
{
    protected $DBGroup = 'master';
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type' => 'BIGINT',
                'unsigned' => true,
                'auto_increment' => true,

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
        ]);
        $this->forge->addField(
            [
                'created_at timestamp not null default current_timestamp',
                'updated_at timestamp not null default current_timestamp on update current_timestamp',
            ]);
        $this->forge->addPrimaryKey(['id']);
        $this->forge->addKey('message_id');
        $this->forge->addKey('sms_template_id');
        $this->forge->createTable('cdac_sms');
    }

    public function down()
    {
        $this->forge->dropTable('cdac_sms');
    }
}
