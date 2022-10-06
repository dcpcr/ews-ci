<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class ReasonForAbsenteeism extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'case_id' => [
                'type' => 'BIGINT',
                'unsigned' => true,
                'NOT NULL' => true,
            ],
            'reason_id' => [
                'type' => 'char',
                'constraint' => '2',
                'default' => null
            ],
            'other_reason' => [
                'type' => 'varchar',
                'constraint' => '1000',
                'default' => null,
            ],
            ]);
        $this->forge->addField(
            [
                'created_at timestamp not null default current_timestamp',
                'updated_at timestamp not null default current_timestamp on update current_timestamp',
            ]);
        $this->forge->addPrimaryKey('case_id');
        $this->forge->createTable('reason_for_absenteeism');
    }

    public function down()
    {
        $this->forge->dropTable('reason_for_absenteeism');
    }
}