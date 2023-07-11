<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AlterTableReasonForAbsenteeism extends Migration
{
    public function up()
    {
        $fields = [
            'who_passed_away' => [
                'type' => 'varchar',
                'constraint' => '100',
                'default' => ""
            ],
            'who_is_suffering' => [
                'type' => 'varchar',
                'constraint' => '100',
                'default' => ""
            ],

            ];
        $this->forge->addColumn('reason_for_absenteeism', $fields);
    }

    public function down()
    {
        //
    }
}
