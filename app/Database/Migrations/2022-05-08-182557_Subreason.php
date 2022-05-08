<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Subreason extends Migration
{
    public function up()
    {
        //
        $this->forge->addField([
            'sub_reason_id' => [
                'type' => 'int',
                'unsigned' => true,
                'NOT NULL' => true,


            ],


            'sub_reason_name' => [
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


        $this->forge->addPrimaryKey('sub_reason_id');
        $this->forge->createTable('sub_reason');
    }

    public function down()
    {
        //
        $this->forge->dropTable('sub_reason');
    }


}