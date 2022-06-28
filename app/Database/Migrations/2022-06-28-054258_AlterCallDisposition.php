<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AlterCallDisposition extends Migration
{
    public function up()
    {
        $alter_fields = [
            'disposition_type' => ['type' => 'varchar', 'constraint' => 255, 'null' => true],
        ];
        $this->forge->modifyColumn('call_disposition', $alter_fields);

    }

    public function down()
    {
        $this->forge->dropColumn('call_disposition', 'disposition_type');
    }
}
