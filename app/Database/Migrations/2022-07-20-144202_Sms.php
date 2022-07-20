<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Sms extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type' => 'BIGINT',
                'constraint' => 10,
                'unsigned' => true,
                'AUTO_INCREMENT' => true,
            ],
            'message_id' => [
                'type' => 'VARCHAR',
                'constraint' => 30,
                'NOT NULL' => true,
            ],
            'status_code' => [
                'type' => 'INT',
                'constraint' => 5,
                'NOT NULL' => true,
            ],
            'offset' => [
                'type' => 'BIGINT',
                'constraint' => 10,
                'unsigned' => true,
                'NOT NULL' => true,
            ],
            'limit' => [
                'type' => 'BIGINT',
                'constraint' => 10,
                'unsigned' => true,
                'NOT NULL' => true,
            ],
            'delivery_report' => [
                'type' => 'BIGINT',
                'constraint' => 10,
                'unsigned' => true,
                'NOT NULL' => true,
                'Default'=>'0',
            ],
            'updated_at' => [
                'type' => 'datetime',
                'null' => true,
            ],
            'created_at datetime default current_timestamp',
        ]);
        $this->forge->addPrimaryKey('id');
        $this->forge->createTable('sms');
    }

    public function down()
    {
        $this->forge->dropTable('sms');
    }
}
