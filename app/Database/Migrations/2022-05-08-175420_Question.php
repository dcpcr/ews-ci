<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Question extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'question_id' => [
                'type' => 'INT',
                'unsigned' => true,
                'NOT NULL' => true,


            ],
            'question' => [
                'type' => 'varchar',
                'constraint' => '200',
            ],


        ]);

        $this->forge->addPrimaryKey('question_id');
        $this->forge->createTable('question');
    }

    public function down()
    {
        //
        $this->forge->dropTable('question');
    }
}