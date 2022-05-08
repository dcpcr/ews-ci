<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class OperatorDistrictMapping extends Migration
{
    public function up()
    {
        //
        $this->forge->addField([
            'operator_id' => [
                'type' => 'BIGINT',
                'unsigned' => true,
                'NOT NULL' => true,


            ],
            'district_id' => [
                'type' => 'INT',
                'unsigned' => true,
                'NOT NULL' => true,

            ],

            ]);
        $this->forge->addKey('operator_id');
        $this->forge->createTable('operator_district_mapping');
    }

    public function down()
    {
        //
        $this->forge->dropTable('operator_district_mapping');
    }
}
