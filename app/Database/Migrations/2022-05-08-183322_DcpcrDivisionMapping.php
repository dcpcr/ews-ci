<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class DcpcrDivisionMapping extends Migration
{
    public function up()
    {
        //
        $this->forge->addField([
            'division_id' => [
                'type' => 'int',
                'unsigned' => true,
                'NOT NULL' => true,


            ],

            'division_name' => [
                'type' => 'CHAR',
                'constraint' => '50',
                'NOT NULL' => true,

            ],
            'reason_id' => [
                'type' => 'int',
                'unsigned' => true,
                'NOT NULL' => true,


            ],



        ]);


        $this->forge->addPrimaryKey('division_id');
        $this->forge->createTable('dcpcr_division_mapping');
    }

    public function down()
    {
        //
        $this->forge->dropTable('dcpcr_division_mapping');
    }


}