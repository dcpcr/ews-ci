<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class YetToBeTakenUp extends Migration
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
        $this->forge->createTable('yet_to_be_taken_up');
    }

    public function down()
    {
        $this->forge->dropTable('yet_to_be_taken_up');
    }
}