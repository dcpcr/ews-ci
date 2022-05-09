<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Helplineticket extends Migration
{
    public function up()
    {
        //
        $this->forge->addField([
            'ticket_number' => [
                'type' => 'VARCHAR',
                'constraint' => '30',
                'NOT NULL' => true,

            ],
            'case_id' => [
                'type' => 'BIGINT',
                'unsigned' => true,
                'NOT NULL' => true,


            ],

            'student_id' => [
                'type' => 'BIGINT',
                'unsigned' => true,
                'NOT NULL' => true,

            ],
            'ticket_status' => [
                'type' => 'CHAR',
                'constraint' => '50',
                'NOT NULL' => true,

            ],
            'ticket_sub_status' => [
                'type' => 'CHAR',
                'constraint' => '50',
                'NOT NULL' => true,

            ],
            'voc' => [
                'type' => 'varchar',
                'constraint' => '1000',
                'NOT NULL' => true,

            ],
            'assigned_division' => [
                'type' => 'CHAR',
                'constraint' => '50',
                'NOT NULL' => true,

            ],
            'sub_division' => [
                'type' => 'CHAR',
                'constraint' => '50',
                'NOT NULL' => true,

            ],
        ]);


        $this->forge->addPrimaryKey('ticket_number');
        $this->forge->createTable('helpline_ticket');
    }

    public function down()
    {
        //
        $this->forge->dropTable('helpline_ticket');
    }


}