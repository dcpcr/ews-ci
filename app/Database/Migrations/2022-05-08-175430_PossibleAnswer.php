<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Possibleanswer extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'answer_id' => [
                'type' => 'INT',
                'unsigned' => true,
                'NOT NULL' => true,



            ],
            'question_id' => [
                'type' => 'INT',
                'unsigned' => true,
                'NOT NULL' => true,

            ],
            'possible_answer' => [
                'type' => 'varchar',
                'constraint' => '200',
            ],



        ]);

        $this->forge->addPrimaryKey('answer_id');
        $this->forge->createTable('possible_answer');
    }

    public function down()
    {
        //
        $this->forge->dropTable('possible_answer');
    }
}
