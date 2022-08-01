<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class DcpcrHelplineTicket extends Migration
{
    public function up()
    {
        $this->forge->addField([

            'case_id' => [
                'type' => 'BIGINT',
                'unsigned' => true,
            ],
            'ticket_number' => [
                'type' => 'varchar',
                'constraint' => '25',
                'default' => '',
            ],
            'status' => [
                'type' => 'char',
                'constraint' => '25',
                'default' => '',
            ],
            'sub_status' => [
                'type' => 'char',
                'constraint' => '25',
                'default' => '',
            ],
            'division' => [
                'type' => 'char',
                'constraint' => '25',
                'default' => '',
            ],
            'sub_division' => [
                'type' => 'char',
                'constraint' => '25',
                'default' => '',
            ],
            'nature' => [
                'type' => 'char',
                'constraint' => '10',
                'default' => '',
            ],
            ]);
        $this->forge->addField(
            [
                'created_at timestamp not null default current_timestamp',
                'updated_at timestamp not null default current_timestamp on update current_timestamp',
            ]);
        $this->forge->addPrimaryKey('case_id');
        $this->forge->createTable('dcpcr_helpline_ticket');
    }

    public function down()
    {
        $this->forge->dropTable('dcpcr_helpline_ticket');
    }
}