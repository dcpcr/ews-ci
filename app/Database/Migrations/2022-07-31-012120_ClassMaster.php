<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class ClassMaster extends Migration
{
    protected $DBGroup = 'master';
    public function up()
    {
        //District INFORMATION TABLE
        $this->forge->addField([
            'id' => [
                'type' => 'int',
                'unsigned' => true,
                'NOT NULL' => true,
                'AUTO_INCREMENT' => true,
            ],
            'name' => [
                'type' => 'char',
                'constraint' => '10',
                'NOT NULL' => true,
            ],
        ]);
        $this->forge->addPrimaryKey('id');
        $this->forge->addKey('name');
        $this->forge->createTable('class');
    }

    public function down()
    {
        $this->forge->dropTable('class');
    }
}