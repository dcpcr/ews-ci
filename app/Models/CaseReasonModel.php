<?php

namespace App\Models;

use CodeIgniter\Model;

class CaseReasonModel extends Model
{
    protected $DBGroup = 'default';
    protected $table = 'case_reason';
    protected $returnType = 'array';
    protected $allowedFields = [
        'case_id',
        'agent_name',
        'student_id',
        'did_respondent_receive_call ',
        'did_disconnected_call ',
        'relation_with_student ',
        'your_name ',
        'reason_of_absense ',
        'other_reason_of_absense ',
        'suffereing_from_sickness ',
        'sickness ',
        'does_student_need_relief ',
        'name_sickness ',
        'treatment ',
        'date_of_join ',
        'reason ',
        'support_needed ',
        'primary_care_during_this_time ',
        'primary_care_now ',
        'incarcerated ',
        'police_station_registered ',
        'committed ',
        'location_of_student ',
        'police_station ',
        'movement ',
        'reason_movement ',
        'return_scheduled ',
        'student_relocated ',
        'otherrelation ',
        'marriagehappened ',
        'when_marriage_happen ',
        'student_residing_now ',
        'when_marriage_scheduled ',
        'where_marriage_scheduled ',
        'reason_of_deny ',
        'support_relief_expected ',
        'have_received_subsidy_for_book_unif ',
        'why_were_denied_of_the_same ',
        'studies_absence_resource ',
        'barriers_prevent ',
        'what_has_happened ',
        'corporal_punishment ',
        'infrastructural ',
        'mid_day_meal ',
        'frequent_issue ',
        'interest_studies ',
        'engage_inside_school ',
        'engagements ',
        'engaged_stated_activity ',
        'child_consume ',
        'child_to_the_mentioned_substance ',
        'student_missing ',
        'student_earn ',
        'student_presently ',
        'their_name ',
        'since_student_missing ',
        'reported_to_police ',
        'fir_number ',
        'fir_location ',
        'will_student_be_able_to_join_school ',
        'raised_ticket ',
        'name_division ',
        'sub_name_division ',
        'nature_case ',
        'elaborate_expected_support ',
        'elaborate_on_resource_infrastructural_need ',
        'is_home_visit_required ',
        'call_dis ',
        'voice_caller '
    ];

    public function __construct()
    {
        parent::__construct();
        helper('general');
    }

    function getReasonsCount(array $school_ids, $classes, $start, $end, $gender): array
    {
        helper('general');
        $master_db = get_database_name_from_db_group('master');
        return $this->select(['reason.name as reason_name', 'count(*) as count'])
            ->join('reason', 'reason.id=case_reason.reason_id')
            ->join('detected_case', 'detected_case.id=case_reason.case_id')
            ->join($master_db . '.student as student', 'student.id = detected_case.student_id')
            ->join($master_db . '.school as school', 'student.school_id = school.id')
            ->whereIn('student.school_id', $school_ids)
            ->whereIn('student.class', $classes)
            ->where("day BETWEEN STR_TO_DATE('" . $start . "' , '%m/%d/%Y') and STR_TO_DATE('" . $end . "', '%m/%d/%Y') and master.student.gender='$gender'")
            ->groupBy('reason_name')
            ->orderBy('reason_name')
            ->findAll();
    }


    function downloadOperatorFormData()
    {

        $token = getCyfutureToken();
        $url = getenv('cyfuture_ewsrecord_url');
        $username = getenv('cyfuture_username');
        $password = getenv('cyfuture_password');
        $method = getenv('method');
        $from_date = '2022-07-01';
        $to_date = date("Y-m-d");
        $response = get_curl_response($url, $username, $password, $method, $from_date, $to_date, $token);
        $data = json_decode($response, true);

        foreach ($data as $row) {
            for ($i = 0; $i < count($row); $i++) {
                $case_id = $row["$i"]["case_id"];
                $agent_name = $row["$i"]["agent_name"];
                $student_id = $row["$i"]["student_ID"];
                $did_respondent_receive_call = $row["$i"]["did_respondent_receive_call"];
                $did_disconnected_call = $row["$i"]["did_disconnected_call"];
                $relation_with_student = $row["$i"]["relation_with_student"];
                $your_name = $row["$i"]["your_name"];
                $reason_of_absense = $row["$i"]["reason_of_absense"];
                $other_reason_of_absense = $row["$i"]["other_reason_of_absense"];
                $suffereing_from_sickness = $row["$i"]["suffereing_from_sickness"];
                $sickness = $row["$i"]["sickness"];
                $does_student_need_relief = $row["$i"]["does_student_need_relief"];
                $name_sickness = $row["$i"]["name_sickness"];
                $treatment = $row["$i"]["treatment"];
                $date_of_join = $row["$i"]["date_of_join"];
                $reason = $row["$i"]["reason"];
                $support_needed = $row["$i"]["support_needed"];
                $primary_care_during_this_time = $row["$i"]["primary_care_during_this_time"];
                $primary_care_now = $row["$i"]["primary_care_now"];
                $incarcerated = $row["$i"]["incarcerated"];
                $police_station_registered = $row["$i"]["police_station_registered"];
                $committed = $row["$i"]["committed"];
                $location_of_student = $row["$i"]["location_of_student"];
                $police_station = $row["$i"]["police_station"];
                $movement = $row["$i"]["movement"];
                $reason_movement = $row["$i"]["reason_movement"];
                $return_scheduled = $row["$i"]["return_scheduled"];
                $student_relocated = $row["$i"]["student_relocated"];
                $otherrelation = $row["$i"]["otherrelation"];
                $marriagehappened = $row["$i"]["marriagehappened"];
                $when_marriage_happen = $row["$i"]["when_marriage_happen"];
                $student_residing_now = $row["$i"]["student_residing_now"];
                $when_marriage_scheduled = $row["$i"]["when_marriage_scheduled"];
                $where_marriage_scheduled = $row["$i"]["where_marriage_scheduled"];
                $reason_of_deny = $row["$i"]["reason_of_deny"];
                $support_relief_expected = $row["$i"]["support_relief_expected"];
                $have_received_subsidy_for_book_unif = $row["$i"]["have_received_subsidy_for_book_unif"];
                $why_were_denied_of_the_same = $row["$i"]["why_were_denied_of_the_same"];
                $studies_absence_resource = $row["$i"]["studies_absence_resource"];
                $barriers_prevent = $row["$i"]["barriers_prevent"];
                $what_has_happened = $row["$i"]["what_has_happened"];
                $corporal_punishment = $row["$i"]["corporal_punishment"];
                $infrastructural = $row["$i"]["infrastructural"];
                $mid_day_meal = $row["$i"]["mid_day_meal"];
                $frequent_issue = $row["$i"]["frequent_issue"];
                $interest_studies = $row["$i"]["interest_studies"];
                $engage_inside_school = $row["$i"]["engage_inside_school"];
                $engagements = $row["$i"]["engagements"];
                $engaged_stated_activity = $row["$i"]["engaged_stated_activity"];
                $child_consume = $row["$i"]["child_consume"];
                $child_to_the_mentioned_substance = $row["$i"]["child_to_the_mentioned_substance"];
                $student_missing = $row["$i"]["student_missing"];
                $student_earn = $row["$i"]["student_earn"];
                $student_presently = $row["$i"]["student_presently"];
                $their_name = $row["$i"]["their_name"];
                $since_student_missing = $row["$i"]["since_student_missing"];
                $reported_to_police = $row["$i"]["reported_to_police"];
                $fir_number = $row["$i"]["fir_number"];
                $fir_location = $row["$i"]["fir_location"];
                $will_student_be_able_to_join_school = $row["$i"]["will_student_be_able_to_join_school"];
                $raised_ticket = $row["$i"]["raised_ticket"];
                $name_division = $row["$i"]["name_division"];
                $sub_name_division = $row["$i"]["sub_name_division"];
                $nature_case = $row["$i"]["nature_case"];
                $elaborate_expected_support = $row["$i"]["elaborate_expected_support"];
                $elaborate_on_resource_infrastructural_need = $row["$i"]["elaborate_on_resource_infrastructural_need"];
                $is_home_visit_required = $row["$i"]["is_home_visit_required"];
                $call_dis = $row["$i"]["call_dis"];
                $voice_caller = $row["$i"]["voice_caller"];
                $formData[] = array(
                    'case_id' => "$case_id",
                    'agent_name' => "$agent_name",
                    "student_id" => "$student_id",
                    "did_respondent_receive_call " => "$did_respondent_receive_call ",
                    "did_disconnected_call " => "$did_disconnected_call ",
                    "relation_with_student " => "$relation_with_student ",
                    "your_name " => "$your_name",
                    "reason_of_absense " => "$reason_of_absense ",
                    "other_reason_of_absense " => "$other_reason_of_absense ",
                    "suffereing_from_sickness " => "$suffereing_from_sickness ",
                    "sickness " => "$sickness ",
                    "does_student_need_relief " => "$does_student_need_relief ",
                    "name_sickness " => "$name_sickness ",
                    "treatment " => "$treatment ",
                    "date_of_join " => "$date_of_join ",
                    "reason " => "$reason ",
                    "support_needed " => "$support_needed ",
                    "primary_care_during_this_time " => "$primary_care_during_this_time ",
                    "primary_care_now " => "$primary_care_now ",
                    "incarcerated " => "$incarcerated ",
                    "police_station_registered " => "$police_station_registered ",
                    "committed " => "$committed ",
                    "location_of_student " => "$location_of_student ",
                    "police_station " => "$police_station ",
                    "movement " => "$movement ",
                    "reason_movement " => "$reason_movement ",
                    "return_scheduled " => "$return_scheduled ",
                    "student_relocated " => "$student_relocated ",
                    "otherrelation " => "$otherrelation ",
                    "marriagehappened " => "$marriagehappened ",
                    "when_marriage_happen " => "$when_marriage_happen ",
                    "student_residing_now " => "$student_residing_now ",
                    "when_marriage_scheduled " => "$when_marriage_scheduled ",
                    "where_marriage_scheduled " => "$where_marriage_scheduled ",
                    "reason_of_deny " => "$reason_of_deny ",
                    "support_relief_expected " => "$support_relief_expected ",
                    "have_received_subsidy_for_book_unif " => "$have_received_subsidy_for_book_unif ",
                    "why_were_denied_of_the_same " => "$why_were_denied_of_the_same ",
                    "studies_absence_resource " => "$studies_absence_resource ",
                    "barriers_prevent " => "$barriers_prevent ",
                    "what_has_happened " => "$what_has_happened ",
                    "corporal_punishment " => "$corporal_punishment ",
                    "infrastructural " => "$infrastructural ",
                    "mid_day_meal " => "$mid_day_meal ",
                    "frequent_issue " => "$frequent_issue ",
                    "interest_studies " => "$interest_studies ",
                    "engage_inside_school " => "$engage_inside_school ",
                    "engagements " => "$engagements ",
                    "engaged_stated_activity " => "$engaged_stated_activity ",
                    "child_consume " => "$child_consume ",
                    "child_to_the_mentioned_substance " => "$child_to_the_mentioned_substance ",
                    "student_missing " => "$student_missing ",
                    "student_earn " => "$student_earn ",
                    "student_presently " => "$student_presently ",
                    "their_name " => "$their_name ",
                    "since_student_missing " => "$since_student_missing ",
                    "reported_to_police " => "$reported_to_police ",
                    "fir_number " => "$fir_number ",
                    "fir_location " => "$fir_location ",
                    "will_student_be_able_to_join_school " => "$will_student_be_able_to_join_school ",
                    "raised_ticket " => "$raised_ticket ",
                    "name_division " => "$name_division ",
                    "sub_name_division " => "$sub_name_division ",
                    "nature_case " => "$nature_case ",
                    "elaborate_expected_support " => "$elaborate_expected_support ",
                    "elaborate_on_resource_infrastructural_need " => "$elaborate_on_resource_infrastructural_need ",
                    "is_home_visit_required " => "$is_home_visit_required ",
                    "call_dis " => "$call_dis ",
                    "voice_caller " => "$voice_caller "

                );
            }
        }

        $this->ignore(true)->insertBatch($formData);
        $this->updateBatch($formData,'case_id');

    }
}
