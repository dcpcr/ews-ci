<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class DcpcrSubdivision extends Migration
{
    public function up()
    {
        //
        $this->forge->addField([
            'sub_division_id' => [
                'type' => 'int',
                'unsigned' => true,
                'NOT NULL' => true,


            ],

            'sub_division_name' => [
                'type' => 'CHAR',
                'constraint' => '100',
                'NOT NULL' => true,

            ],
            'division_id' => [
                'type' => 'int',
                'unsigned' => true,
                'NOT NULL' => true,


            ],



        ]);


        $this->forge->addPrimaryKey('sub_division_id');
        $this->forge->createTable('dcpcr_sub_division_mapping');
    }

    public function down()
    {
        //
        $this->forge->dropTable('dcpcr_sub_division_mapping');
    }


}
