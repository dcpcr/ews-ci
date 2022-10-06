<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class BackToschool extends Migration
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
                'constraint' => '2',
                'default' => null,
            ],]);
        $this->forge->addField(
            [
                'created_at timestamp not null default current_timestamp',
                'updated_at timestamp not null default current_timestamp on update current_timestamp',
            ]);
        $this->forge->addPrimaryKey('case_id');
        $this->forge->createTable('back_to_school');
    }

    public function down()
    {
        $this->forge->dropTable('back_to_school');
    }
}