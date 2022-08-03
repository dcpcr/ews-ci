<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class SmsBatch extends Migration
{

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

        ]);
        $this->forge->addPrimaryKey(['id']);
        $this->forge->addKey('message_id');
        $this->forge->addKey('sms_template_id');
        $this->forge->createTable('sms_batch');
    }

    public function down()
    {
        $this->forge->dropTable('sms_batch');
    }
}
