<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AlterTableCdacSms extends Migration
{
    protected $DBGroup = 'master';

    public function up()
    {
        $fields = [
            'download_report' => ['type' => 'INT', 'constraint' => 1, 'after' => 'report_fetched'],
        ];
        $this->forge->addColumn('cdac_sms', $fields);


    }

    public function down()
    {
        //
    }
}
