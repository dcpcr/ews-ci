<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Subreasoncategory extends Migration
{
    public function up()
    {
        //
        $this->forge->addField([
            'sub_reason_category_id' => [
                'type' => 'int',
                'unsigned' => true,
                'NOT NULL' => true,


            ],
            'sub_reason_id' => [
                'type' => 'int',
                'unsigned' => true,
                'NOT NULL' => true,


            ],

            'sub_reason_category_name' => [
                'type' => 'CHAR',
                'constraint' => '50',
                'NOT NULL' => true,

            ],



        ]);


        $this->forge->addPrimaryKey('sub_reason_category_id');
        $this->forge->createTable('sub_reason_category');
    }

    public function down()
    {
        //
        $this->forge->dropTable('sub_reason_category');
    }


}