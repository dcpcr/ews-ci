<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Student extends Migration
{
    public function up()
    {
        //STUDENT TABLE
        $this->forge->addField([
            'district' => [
                'type' => 'char',
                'constraint' => '20',
            ],
            'id' => [
                'type' => 'BIGINT',
                'unsigned' => true,
                'NOT NULL' => true,
            ],
            'name' => [
                'type' => 'char',
                'constraint' => '100',
                'NOT NULL' => true,
            ],
            'dob' => [
                'type' => 'char',
                'constraint' => '20',
            ],
            'class' => [
                'type' => 'char',
                'constraint' => '10',
                'NOT NULL' => true,
            ],
            'section' => [
                'type' => 'char',
                'constraint' => '1',
            ],
            'gender' => [
                'type' => 'char',
                'constraint' => '20',
            ],
            'father' => [
                'type' => 'char',
                'constraint' => '100',
            ],
            'mother' => [
                'type' => 'char',
                'constraint' => '100',
            ],
            'address' => [
                'type' => 'varchar',
                'constraint' => '255',
            ],
            'mobile' => [
                'type' => 'CHAR',
                'constraint' => '20',
                'NOT NULL' => true,
            ],
            'school_id' => [
                'type' => 'INT',
                'unsigned' => true,
                'NOT NULL' => true,
            ],
        ]);

        $this->forge->addPrimaryKey('id');
        $this->forge->addKey('class');
        $this->forge->addKey('school_id');
        $this->forge->addKey('gender');
        $this->forge->createTable('student');
    }

    public function down()
    {
        $this->forge->dropTable('student');
    }
}