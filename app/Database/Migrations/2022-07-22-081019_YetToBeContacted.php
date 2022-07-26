<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class YetToBeContacted extends Migration
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
                'type' => 'char',
                'constraint' => '3',
                'default' => '',
            ],]);
        $this->forge->addField(
            [
                'created_at timestamp not null default current_timestamp',
                'updated_at timestamp not null default current_timestamp on update current_timestamp',
            ]);
        $this->forge->addKey('case_id');
        $this->forge->createTable('yet_to_be_contacted');
    }

    public function down()
    {
        $this->forge->dropTable('yet_to_be_contacted');
    }
}