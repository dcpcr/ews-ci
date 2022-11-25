<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class HomeVisit extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'case_id' => [
                'type' => 'BIGINT',
                'unsigned' => true,
                'NOT NULL' => true,
            ],
           ]);
        $this->forge->addField(
            [
                'created_at timestamp not null default current_timestamp',
            ]);
        $this->forge->addPrimaryKey('case_id');
        $this->forge->createTable('home_visit');
    }

    public function down()
    {
        $this->forge->dropTable('home_visit');
    }
}