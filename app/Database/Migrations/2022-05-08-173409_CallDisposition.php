<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CallDisposition extends Migration
{
    public function up()
    {
        //CALL DISPOSITION INFORMATION TABLE
        $this->forge->addField([
            'call_id' => [
                'type' => 'INT',
                'unsigned' => true,
                'NOT NULL' => true,


            ],
            'case_id' => [
                'type' => 'BIGINT',
                'unsigned' => true,
                'NOT NULL' => true,
            ],
            'operator_id' => [
                'type' => 'BIGINT',
                'unsigned' => true,
                'NOT NULL' => true,
            ],
            'disposition_type' => [
                'type' => 'ENUM("Wrong Phone Number","Invalid Number","Not Answered","Busy","DND","Connected","other")',


            ],



        ]);

        $this->forge->addPrimaryKey('call_id');
        $this->forge->createTable('call_disposition');
    }

    public function down()
    {
        //
        $this->forge->dropTable('call_disposition');
    }
}