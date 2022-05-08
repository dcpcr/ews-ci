<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class FOLLOWCALL extends Migration
{
    public function up()
    {
        //
        $this->forge->addField([
            'id' => [
                'type' => 'int',
                'unsigned' => true,
                'NOT NULL' => true,


            ],

            'follow_up_type' => [
                'type' => 'CHAR',
                'constraint' => '100',
                'NOT NULL' => true,

            ],


        ]);


        $this->forge->addPrimaryKey('id');
        $this->forge->createTable('follow_up_call');
    }

    public function down()
    {
        //
        $this->forge->dropTable('follow_up_call');
    }


}
