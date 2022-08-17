<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AlterTableStudent extends Migration
{
    protected $DBGroup = 'master';
    public function up()
    {
        $fields = [
            'sms_status' => ['type' => 'VARCHAR', 'constraint' => 100, 'after' => 'school_id'],
        ];
        $this->forge->addColumn('student', $fields);
        $this->forge->addKey('sms_status');

    }

    public function down()
    {
        //
    }
}
