<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class HighRisk extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'case_id' => [
                'type' => 'BIGINT',
                'unsigned' => true,
                'NOT NULL' => true,
            ],
            'status' => [
                'type' => 'int',
                'constraint' => '3',
            ],]);
        $this->forge->addField(
            [
                'created_at timestamp not null default current_timestamp',
                'updated_at timestamp not null default current_timestamp on update current_timestamp',
            ]);
        $this->forge->addKey('case_id');
        $this->forge->createTable('high_risk');
    }

    public function down()
    {
        $this->forge->dropTable('high_risk');
    }
}
