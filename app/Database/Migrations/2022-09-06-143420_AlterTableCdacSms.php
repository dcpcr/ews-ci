<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AlterTableCdacSms extends Migration
{
    protected $DBGroup = 'master';

    public function up()
    {
        $fields = [
            'download_report' => ['type' => 'INT', 'constraint' => 1, 'after' => 'report_fetched', 'default' => 0],
            'sms_count' => ['type' => 'Varchar', 'constraint' => 10, 'after' => 'download_report', 'default' => 0],
        ];
        $this->forge->addColumn('cdac_sms', $fields);
    }

    public function down()
    {
        //
    }
}
