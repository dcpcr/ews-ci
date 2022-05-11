<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AlterTableUsers extends Migration
{
    public function up()
    {
        $fields = [
            'firstname' => ['type' => 'VARCHAR', 'constraint' => 63, 'after' => 'username'],
            'lastname' => ['type' => 'VARCHAR', 'constraint' => 63, 'after' => 'firstname'],
        ];
        $this->forge->addColumn('users', $fields);

        $alter_fields = [
            'email' => ['type' => 'varchar', 'constraint' => 255, 'null' => true],
            'username' => ['type' => 'varchar', 'constraint' => 30, 'null' => 0],
        ];
        $this->forge->modifyColumn('users', $alter_fields);

    }

    public function down()
    {
        $this->forge->dropColumn('users', 'firstname');
        $this->forge->dropColumn('users', 'lastname');
    }
}
