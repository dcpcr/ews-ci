<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CaseReason extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'case_id' => [
                'type' => 'BIGINT',
                'constraint' => 10,
                'unsigned' => true,
            ],
            'reason_id' => [
                'type' => 'INT',
                'constraint' => '3',
                'null' => false
            ],
            'agent_name' => [
                'type' => 'VARCHAR',
                'constraint' => '25',
                'NOT NULL' => true
            ],

            'student_id' => [
                'type' => 'BIGINT',
                'unsigned' => true,
                'NOT NULL' => true,

            ],

            'did_respondent_receive_call' => [
                'type' => 'INT',
                'constraint' => '2',
                'NOT NULL' => true,

            ],

            'did_disconnected_call' => [
                'type' => 'INT',
                'constraint' => '2',
                'NOT NULL' => true,
            ],

            'reason_of_absense' => [
                'type' => 'char',
                'constraint' => '25',
                'NOT NULL' => true,
            ],

            'other_reason_of_absense' => [
                'type' => 'char',
                'constraint' => '25',
                'NOT NULL' => true,
            ],

            'suffereing_from_sickness' => [
                'type' => 'char',
                'constraint' => '25',
                'NOT NULL' => true,
            ],

            'sickness' => [
                'type' => 'char',
                'constraint' => '25',
                'NOT NULL' => true,
            ],

            'does_student_need_relief' => [
                'type' => 'char',
                'constraint' => '25',
                'NOT NULL' => true,
            ],

            'name_sickness' => [
                'type' => 'char',
                'constraint' => '25',
                'NOT NULL' => true,
            ],

            'treatment' => [
                'type' => 'char',
                'constraint' => '25',
                'NOT NULL' => true,
            ],

            'date_of_join' => [
                'type' => 'char',
                'constraint' => '25',
                'NOT NULL' => true,
            ],

            'reason' => [
                'type' => 'char',
                'constraint' => '25',
                'NOT NULL' => true,
            ],

            'primary_care_now' => [
                'type' => 'char',
                'constraint' => '25',
                'NOT NULL' => true,
            ],

            'support_needed' => [
                'type' => 'char',
                'constraint' => '25',
                'NOT NULL' => true,
            ],

            'primary_care_during_this_time' => [
                'type' => 'char',
                'constraint' => '25',
                'NOT NULL' => true,
            ],

            'incarcerated' => [
                'type' => 'char',
                'constraint' => '25',
                'NOT NULL' => true,
            ],
            'police_station_registered' => [
                'type' => 'char',
                'constraint' => '25',
                'NOT NULL' => true,
            ],

            'committed' => [
                'type' => 'char',
                'constraint' => '25',
                'NOT NULL' => true,
            ],

            'location_of_student' => [
                'type' => 'char',
                'constraint' => '25',
                'NOT NULL' => true,
            ],

            'police_station' => [
                'type' => 'char',
                'constraint' => '25',
                'NOT NULL' => true,
            ],

            'movement' => [
                'type' => 'char',
                'constraint' => '25',
                'NOT NULL' => true,
            ],

            'reason_movement' => [
                'type' => 'char',
                'constraint' => '25',
                'NOT NULL' => true,
            ],

            'return_scheduled' => [
                'type' => 'char',
                'constraint' => '25',
                'NOT NULL' => true,
            ],

            'student_relocated' => [
                'type' => 'char',
                'constraint' => '25',
                'NOT NULL' => true,
            ],

            'otherrelation' => [
                'type' => 'char',
                'constraint' => '25',
                'NOT NULL' => true,
            ],

            'marriagehappened' => [
                'type' => 'char',
                'constraint' => '25',
                'NOT NULL' => true,
            ],

            'when_marriage_happen' => [
                'type' => 'char',
                'constraint' => '25',
                'NOT NULL' => true,
            ],

            'student_residing_now' => [
                'type' => 'char',
                'constraint' => '25',
                'NOT NULL' => true,
            ],

            'when_marriage_scheduled' => [
                'type' => 'char',
                'constraint' => '25',
                'NOT NULL' => true,
            ],

            'where_marriage_scheduled' => [
                'type' => 'char',
                'constraint' => '25',
                'NOT NULL' => true,
            ],

            'reason_of_deny' => [
                'type' => 'char',
                'constraint' => '25',
                'NOT NULL' => true,
            ],

            'support_relief_expected' => [
                'type' => 'char',
                'constraint' => '25',
                'NOT NULL' => true,
            ],

            'have_received_subsidy_for_book_unif' => [
                'type' => 'char',
                'constraint' => '25',
                'NOT NULL' => true,
            ],

            'why_were_denied_of_the_same' => [
                'type' => 'char',
                'constraint' => '25',
                'NOT NULL' => true,
            ],

            'studies_absence_resource' => [
                'type' => 'char',
                'constraint' => '25',
                'NOT NULL' => true,
            ],

            'barriers_prevent' => [
                'type' => 'char',
                'constraint' => '25',
                'NOT NULL' => true,
            ],

            'what_has_happened' => [
                'type' => 'char',
                'constraint' => '25',
                'NOT NULL' => true,
            ],

            'corporal_punishment' => [
                'type' => 'char',
                'constraint' => '25',
                'NOT NULL' => true,
            ],

            'infrastructural' => [
                'type' => 'char',
                'constraint' => '25',
                'NOT NULL' => true,
            ],

            'mid_day_meal' => [
                'type' => 'char',
                'constraint' => '25',
                'NOT NULL' => true,
            ],

            'frequent_issue' => [
                'type' => 'char',
                'constraint' => '25',
                'NOT NULL' => true,
            ],

            'interest_studies' => [
                'type' => 'char',
                'constraint' => '25',
                'NOT NULL' => true,
            ],

            'engage_inside_school' => [
                'type' => 'char',
                'constraint' => '25',
                'NOT NULL' => true,
            ],

            'engagements' => [
                'type' => 'char',
                'constraint' => '25',
                'NOT NULL' => true,
            ],

            'engaged_stated_activity' => [
                'type' => 'char',
                'constraint' => '25',
                'NOT NULL' => true,
            ],

            'child_consume' => [
                'type' => 'char',
                'constraint' => '25',
                'NOT NULL' => true,
            ],

            'child_to_the_mentioned_substance' => [
                'type' => 'char',
                'constraint' => '25',
                'NOT NULL' => true,
            ],

            'student_missing' => [
                'type' => 'char',
                'constraint' => '25',
                'NOT NULL' => true,
            ],

            'student_earn' => [
                'type' => 'char',
                'constraint' => '25',
                'NOT NULL' => true,
            ],

            'student_presently' => [
                'type' => 'char',
                'constraint' => '25',
                'NOT NULL' => true,
            ],

            'their_name' => [
                'type' => 'char',
                'constraint' => '25',
                'NOT NULL' => true,
            ],

            'since_student_missing' => [
                'type' => 'char',
                'constraint' => '25',
                'NOT NULL' => true,
            ],

            'reported_to_police' => [
                'type' => 'char',
                'constraint' => '25',
                'NOT NULL' => true,
            ],

            'fir_number' => [
                'type' => 'char',
                'constraint' => '25',
                'NOT NULL' => true,
            ],

            'fir_location' => [
                'type' => 'char',
                'constraint' => '25',
                'NOT NULL' => true,
            ],

            'will_student_be_able_to_join_school' => [
                'type' => 'char',
                'constraint' => '25',
                'NOT NULL' => true,
            ],

            'raised_ticket' => [
                'type' => 'char',
                'constraint' => '25',
                'NOT NULL' => true,
            ],

            'name_division' => [
                'type' => 'char',
                'constraint' => '25',
                'NOT NULL' => true,
            ],

            'sub_name_division' => [
                'type' => 'char',
                'constraint' => '25',
                'NOT NULL' => true,
            ],

            'nature_case' => [
                'type' => 'char',
                'constraint' => '25',
                'NOT NULL' => true,
            ],

            'elaborate_expected_support' => [
                'type' => 'char',
                'constraint' => '25',
                'NOT NULL' => true,
            ],

            'elaborate_on_resource_infrastructural_need' => [
                'type' => 'char',
                'constraint' => '25',
                'NOT NULL' => true,
            ],

            'is_home_visit_required' => [
                'type' => 'char',
                'constraint' => '25',
                'NOT NULL' => true,
            ],

            'call_dis' => [
                'type' => 'char',
                'constraint' => '25',
                'NOT NULL' => true,
            ],

            'voice_caller' => [
                'type' => 'char',
                'constraint' => '25',
                'NOT NULL' => true,
            ],

            'relation_with_student' => [
                'type' => 'char',
                'constraint' => '25',
                'NOT NULL' => true,
            ],

            'your_name' => [
                'type' => 'char',
                'constraint' => '25',
                'NOT NULL' => true,
            ],

            'updated_at' => [
                'type' => 'datetime',
                'null' => true,
            ],
            'created_at datetime default current_timestamp',
        ]);
        $this->forge->addPrimaryKey('case_id');
        $this->forge->createTable('case_reason');
    }

    public function down()
    {
        $this->forge->dropTable('case_reason');
    }
}
