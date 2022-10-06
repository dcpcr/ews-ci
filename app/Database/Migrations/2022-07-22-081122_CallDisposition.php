<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CallDisposition extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'case_id' => [
                'type' => 'BIGINT',
                'unsigned' => true,
                'NOT NULL' => true,
            ],
            'call_disposition_id' => [
                'type' => 'INT',
                'constraint' => '2',
                'default' => null,
            ],]);
        $this->forge->addField(
            [
                'created_at timestamp not null default current_timestamp',
                'updated_at timestamp not null default current_timestamp on update current_timestamp',
            ]);
        $this->forge->addPrimaryKey('case_id');
        $this->forge->createTable('call_disposition');
    }

    public function down()
    {
        $this->forge->dropTable('call_disposition');
    }
}