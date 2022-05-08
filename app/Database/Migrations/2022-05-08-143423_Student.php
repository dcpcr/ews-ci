<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Student extends Migration
{
    public function up()
    {
        //STUDENT TABLE
        $this->forge->addField([
            'school_id' => [
                'type' => 'INT',
                'unsigned' => true,
                'NOT NULL' => true,

            ],
            'student_id' => [
                'type' => 'BIGINT',
                'unsigned' => true,
                'NOT NULL' => true,

            ],
            'student_name' => [
                'type' => 'char',
                'constraint' => '50',
                'NOT NULL' => true,

            ],
            'class' => [
                'type' => 'char',
                'constraint' => '10',
                'NOT NULL' => true,

            ],
            'section' => [
                'type' => 'char',
                'constraint' => '2',

            ],
            'dob' => [
                'type' => 'char',
                'constraint' => '10',

            ],
            'gender' => [
                'type' => 'char',
                'constraint' => '15',

            ],
            'mobile' => [
                'type' => 'char',
                'constraint' => '20',

            ],
            'father_name' => [
                'type' => 'char',
                'constraint' => '100',

            ],
            'mother_name' => [
                'type' => 'char',
                'constraint' => '100',

            ],
            'address' => [
                'type' => 'varchar',
                'constraint' => '200',

            ],
            'pincode' => [
                'type' => 'int',
                'constraint' => '10',

            ],

        ]);
        $this->forge->addField(
            [
            'created_at datetime default current_timestamp',
            'updated_at datetime default current_timestamp on update current_timestamp',
            ]);

        $this->forge->addPrimaryKey('id');
        $this->forge->addKey('school_id');
        $this->forge->addKey('gender');
        $this->forge->createTable('student');
    }

    public function down()
    {
        //
        $this->forge->dropTable('student');
    }
}