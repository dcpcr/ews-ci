<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class BackToSchoolMaster extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'constrain' => '2',
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
        $this->forge->createTable('back_to_school_master');
    }

    public function down()
    {
        $this->forge->dropTable('back_to_school_master');
    }
}