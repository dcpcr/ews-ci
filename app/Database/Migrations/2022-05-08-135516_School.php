<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class School extends Migration
{
    public function up()
    {
        //  SCHOOL TABLE
        $this->forge->addField([
            'serial' => [
                'type' => 'int',
                'unsigned' => true,
                'NOT NULL' => true,

            ],
            'district' => [
                'type' => 'char',
                'NOT NULL' => true,
                'constraint' => '20',

            ],
            'zone' => [
                'type' => 'int',
                'unsigned' => true,
                'NOT NULL' => true,

            ],
            'id' => [
                'type' => 'int',
                'unsigned' => true,
                'NOT NULL' => true,

            ],
            'building-id' => [
                'type' => 'int',
                'unsigned' => true,


            ],
            'name' => [
                'type' => 'CHAR',
                'constraint' => '200',
                'NOT NULL' => true,

            ],
            'address' => [
                'type' => 'CHAR',
                'constraint' => '255',
                'NOT NULL' => true,
            ],
            'shift' => [
                'type' => 'CHAR',
                'constraint' => '20',
                'NOT NULL' => true,
            ],
            'level' => [
                'type' => 'CHAR',
                'constraint' => '50',
                'NOT NULL' => true,
            ],
            'gender' => [
                'type' => 'CHAR',
                'constraint' => '50',
                'NOT NULL' => true,
            ],
            'phone' => [
                'type' => 'CHAR',
                'constraint' => '20',
                'NOT NULL' => true,
            ],
            'hos' => [
                'type' => 'CHAR',
                'constraint' => '50',
                'NOT NULL' => true,
            ],
            'mobile' => [
                'type' => 'CHAR',
                'constraint' => '20',
                'NOT NULL' => true,
            ],
            'email' => [
                'type' => 'CHAR',
                'constraint' => '100',
                'NOT NULL' => true,
            ],
            'latitude' => [
                'type' => 'double',
                'NOT NULL' => true,
            ],
            'longitude' => [
                'type' => 'double',
                'NOT NULL' => true,
            ],

        ]);
        $this->forge->addPrimaryKey('id');
        $this->forge->addUniqueKey('serial');
        $this->forge->createTable('school');
    }

    public function down()
    {

        $this->forge->dropTable('school');
    }
}
