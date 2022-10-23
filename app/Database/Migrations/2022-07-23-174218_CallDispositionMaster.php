<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CallDispositionMaster extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type' => 'BIGINT',
                'unsigned' => true,
                'NOT NULL' => true,
            ],
            'name' => [
                'type' => 'varchar',
                'constraint' => '50',
                'default' => '',
            ],
            'active' => [
                'type' => 'INT',
                'constrain' => '2',
                'NOT NULL' => true,
            ],
        ]);
        $this->forge->addField(
            [
                'created_at timestamp not null default current_timestamp',
                'updated_at timestamp not null default current_timestamp on update current_timestamp',
            ]);
        $this->forge->addPrimaryKey('id');
        $this->forge->createTable('call_disposition_master');
    }

    public function down()
    {
        $this->forge->dropTable('call_disposition_master');
    }
}