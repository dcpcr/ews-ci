<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CallAnswer extends Migration
{
    //CALL ANSWER TABLE
    public function up()
    {
        $this->forge->addField([
            'call_id' => [
                'type' => 'INT',
                'unsigned' => true,
                'NOT NULL' => true,


            ],

            'question_id' => [
                'type' => 'INT',
                'unsigned' => true,
                'NOT NULL' => true,

            ],
            'answer_id' => [
                'type' => 'INT',
                'unsigned' => true,
                'NOT NULL' => true,


            ],
            'text_answer' => [
                'type' => 'varchar',
                'constraint' => '500',
            ],


        ]);

        $this->forge->addKey('answer_id');
        $this->forge->createTable('call_answer');
    }

    public function down()
    {
        //
        $this->forge->dropTable('call_answer');
    }
}
