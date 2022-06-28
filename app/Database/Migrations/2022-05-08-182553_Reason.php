<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Reason extends Migration
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


            'reason_name' => [
                'type' => 'CHAR',
                'constraint' => '50',
                'NOT NULL' => true,

            ],


            'active' => [
                'type' => 'int',
                'constraint' => '1',
                'NOT NULL' => true,

            ],
        ]);


        $this->forge->addPrimaryKey('id');
        $this->forge->createTable('reason');
    }

    public function down()
    {
        //
        $this->forge->dropTable('reason');
    }


}
