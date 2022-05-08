<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CallDetails extends Migration
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
            'respondent_relationship' => [
                'type' => 'varchar',
                'constraint' => '50',
            ],
            'respondent_name' => [
                'type' => 'varchar',
                'constraint' => '50',
            ],
            'reason_id' => [
                'type' => 'int',
                'unsigned' => true,
                'NOT NULL' => true,
            ],
            'call_note' => [
                'type' => 'varchar',
                'constraint' => '1000',
            ],



        ]);

        $this->forge->addKey('call_id');
        $this->forge->addKey('reason_id');
        $this->forge->createTable('call_details');
    }

    public function down()
    {
        //
        $this->forge->dropTable('call_details');
    }
}