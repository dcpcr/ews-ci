<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class SchoolMapping extends Migration
{
    protected $DBGroup = 'master';
    public function up()
    {
        //SCHOOL MAPPING TABLE WITH ZONE & DISTRICT
        $this->forge->addField([
            'school_id' => [
                'type' => 'INT',
                'unsigned' => true,
                'NOT NULL' => true,
            ],
            'district_id' => [
                'type' => 'INT',
                'unsigned' => true,
                'NOT NULL' => true,
            ],
            'zone_id' => [
                'type' => 'INT',
                'unsigned' => true,
                'NOT NULL' => true,
            ],
        ]);
        $this->forge->addField(
            [
                'created_at datetime default current_timestamp',
                'updated_at datetime default current_timestamp on update current_timestamp',
            ]);
        $this->forge->addPrimaryKey('school_id');
        $this->forge->addKey('district_id');
        $this->forge->addKey('zone_id');
        $this->forge->createTable('school_mapping');
    }

    public function down()
    {
        $this->forge->dropTable('school_mapping');
    }
}