<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class District extends Migration
{
    protected $DBGroup = 'master';
    public function up()
    {
        //District INFORMATION TABLE
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'unsigned' => true,
                'NOT NULL' => true,
            ],
            'name' => [
                'type' => 'char',
                'constraint' => '30',
                'NOT NULL' => true,
            ],
        ]);
        $this->forge->addField(
            [
                'created_at datetime default current_timestamp',
                'updated_at datetime default current_timestamp on update current_timestamp',
            ]);
        $this->forge->addPrimaryKey('id');
        $this->forge->createTable('district');
    }

    public function down()
    {
        $this->forge->dropTable('district');
    }
}