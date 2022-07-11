<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CallDisposition extends Migration
{
    public function up()
    {
        //CALL DISPOSITION INFORMATION TABLE
        $this->forge->addField([
            'call_id' => [
                'type' => 'INT',
                'unsigned' => true,
                'NOT NULL' => true,
            ],

            'case_id' => [
                'type' => 'BIGINT',
                'unsigned' => true,
                'NOT NULL' => true,
            ],

            'operator_id' => [
                'type' => 'BIGINT',
                'unsigned' => true,
                'NOT NULL' => true,
            ],

            'disposition_type' => ['type' => 'varchar', 'constraint' => 255, 'null' => true],

        ]);

        $this->forge->addPrimaryKey('call_id');
        $this->forge->createTable('call_disposition');
    }

    public function down()
    {
        //
        $this->forge->dropTable('call_disposition');
    }
}