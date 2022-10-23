<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CaseReason extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'case_id' => [
                'type' => 'BIGINT',
                'constraint' => 10,
                'unsigned' => true,
            ],
            'reason_id' => [
                'type' => 'INT',
                'constraint' => '3',
                'null' => false
            ],

            'updated_at' => [
                'type' => 'datetime',
                'null' => true,
            ],
            'created_at datetime default current_timestamp',
        ]);
        $this->forge->addPrimaryKey('case_id');
        $this->forge->createTable('case_reason');
    }

    public function down()
    {
        $this->forge->dropTable('case_reason');
    }
}
