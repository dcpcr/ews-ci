<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\AttendanceModel;
use App\Models\AttendanceReportModel;
use App\Models\CaseModel;
use App\Models\DcpcrHelplineTicketModel;
use App\Models\DetectedCaseModel;
use App\Models\HomeVisitModel;
use App\Models\LatestStudentStatusModel;
use App\Models\ReasonForAbsenteeismModel;
use App\Models\StudentModel;
use App\Models\YetToBeContactedModel;

class SchoolController extends BaseController
{
    protected array $view_data;
    protected string $view_name;


    public function prepareHomePageData(array $permission, array $school_id, array $classes, $start_date, $end_date, array $view_data): array
    {
        $this->view_data = $view_data;
        $this->view_data['filter_permissions'] = $permission;
        $this->view_data['details'] = "Scroll down to see quick links and overview of your school's attendance performance and daily tasks!";
        $this->view_data['page_title'] = 'Home';
        //Attendance data
        $col_name = "class";
        $graph_lable = "class";
        $table_title = "class";
        $attendance_model = new AttendanceModel();
        $latest_marked_attendance_date = $attendance_model->getLatestMarkedAttendanceDateFor($school_id);
        $attendance_report_model = new AttendanceReportModel();
        $attendance_data_day_wise = $attendance_report_model->getDateWiseMarkedStudentAttendanceCount($school_id, $classes, $start_date, $end_date);
        $attendance_data_class_wise = $attendance_report_model->getMarkedStudentAttendanceDataGroupByCount($school_id, $classes, $latest_marked_attendance_date, "class", false);
        $attendance_data_for_graph = $attendance_report_model->getMarkedStudentAttendanceDataGroupByCount($school_id, $classes, $latest_marked_attendance_date, "class", true);
        //Attendance data end

        //Total students count
        $student_model = new StudentModel();
        $total_number_of_student = $student_model->getStudentCountFor($school_id, $classes, "school_id");
        //total student count end

        //attendance marked
        $total_attendance_count = $attendance_model->getDateWiseMarkedStudentAttendanceCountForHomePage($school_id, $classes, $latest_marked_attendance_date[0]['date']);

        //dtected by EWS

        $detected_case_model = new DetectedCaseModel();
        $total_number_of_detected_students = $detected_case_model->getTotalNumberOfDetectedStudentsFor($school_id, $classes, $start_date, $end_date);

        //bts
        $bts_with_intervention = $detected_case_model->getBTSCaseCount($school_id, $classes, $start_date, $end_date);
        $bts_without_intervention = $detected_case_model->getBTSCaseCount($school_id, $classes, $start_date, $end_date, true);
        //YET TO BE BROUGHT BACK TO SCHOOL
        $yet_to_be_brought_back_to_school_by_call = $detected_case_model->getYetToBeBroughtBackToSchoolByCall($school_id, $classes, $start_date, $end_date);
        $yet_to_be_brought_back_to_school_by_sms = $detected_case_model->getYetToBeBroughtBackToSchoolBySMS($school_id, $classes, $start_date, $end_date);
        $this->view_data['response'] = [
            "table_title" => $table_title,
            "graph_lable" => $graph_lable,
            "col_name" => $col_name,
            "attendance_data_day_wise" => $attendance_data_day_wise,
            'latest_marked_attendance_date' => $latest_marked_attendance_date,
            "attendance_data_class_wise" => $attendance_data_class_wise,
            "attendance_data_for_graph" => $attendance_data_for_graph,
            "total_number_of_students" => $total_number_of_student,
            "total_attendance" => $total_attendance_count,
            "attendance_percentage" => round($total_attendance_count['attendance_count'] / $total_number_of_student[0]['count_total'], "2") . '%',
            "total_number_of_detected_students" => $total_number_of_detected_students,
            "bts_with_intervention" => $bts_with_intervention,
            "bts_without_intervention" => $bts_without_intervention,
            "yet_to_be_brought_back_to_school_via_call_count" => count($yet_to_be_brought_back_to_school_by_call),
            "yet_to_be_brought_back_to_school_via_call_list" => $yet_to_be_brought_back_to_school_by_call,
            "yet_to_be_brought_back_to_school_via_sms_count" => count($yet_to_be_brought_back_to_school_by_sms),
            "yet_to_be_brought_back_to_school_via_sms_list" => $yet_to_be_brought_back_to_school_by_sms,
        ];
        $this->view_name = 'dashboard/home';
        return ["view_name" => $this->view_name, "view_data" => $this->view_data];
    }

    public function prepareTaskPageData(array $permission, array $school_id, array $classes, $start_date, $end_date, array $view_data): array
    {
        $this->view_data = $view_data;
        $this->view_data['filter_permissions'] = $permission;
        $this->view_data['details'] = "Scroll down to see quick links and overview of your school's attendance performance and daily tasks!";
        $this->view_data['page_title'] = 'Task';
        $detected_case_model = new DetectedCaseModel();
        $moved_out_of_delhi_list = $detected_case_model->getListFor($school_id, $classes, $start_date, $end_date, ['3']);
        $changed_school_list = $detected_case_model->getListFor($school_id, $classes, $start_date, $end_date, ['23']);
        $dropped_out_list = $detected_case_model->getListFor($school_id, $classes, $start_date, $end_date, ['6']);
        $parental_death_count_list = $detected_case_model->getParentalDeathCaseListFor($school_id, $classes, $start_date, $end_date, ['22']);
        $this->view_data['response'] = [
            "moved_out_of_delhi_count" => count($moved_out_of_delhi_list),
            "changed_school_count" => count($changed_school_list),
            "dropped_out_count" => count($dropped_out_list),
            "parental_death_count" => count($parental_death_count_list)
        ];

        $this->view_name = 'dashboard/task';
        return ["view_name" => $this->view_name, "view_data" => $this->view_data];
    }

    public function prepareAbsenteeismReportPageData(array $permission, array $school_id, array $classes, $start_date, $end_date, array $view_data): array
    {
        $this->view_data = $view_data;
        $this->view_data['filter_permissions'] = $permission;
        $this->view_data['details'] = "Scroll down to see quick links and overview of your school's attendance performance and daily tasks!";
        $this->view_data['page_title'] = 'Absenteeism Report';
        $reason_wise_case_count = $this->getGenderWiseReasonsCount($school_id, $classes, $start_date, $end_date, ['Female', 'Transgender', 'Male']);
        $detected_case_model = new CaseModel();
        $total_detected_cases = $detected_case_model->getCaseCount($school_id, $classes, $start_date, $end_date, ["Back to school", "Fresh"]);
        $dcpcr_helpline_ticket_model = new DcpcrHelplineTicketModel();
        $sub_division_wise_total_dcpcr_helpline_case_count = $dcpcr_helpline_ticket_model->getDcpcrHelplineCaseDetails($school_id, $classes, $start_date, $end_date, ["New", "Closed", 'Open'], "total_ticket_count");
        $sub_division_wise_in_total_progress_dcpcr_helpline_case_count = $dcpcr_helpline_ticket_model->getDcpcrHelplineCaseDetails($school_id, $classes, $start_date, $end_date, ["New", 'Open'], "total_in_progress_ticket_count");
        $this->view_data['response'] = [
            'reason_wise_case_count' => $reason_wise_case_count,
            'total_detected_cases' => $total_detected_cases,
            'sub_division_wise_total_dcpcr_helpline_case_count' => $sub_division_wise_total_dcpcr_helpline_case_count,
            'sub_division_wise_in_total_progress_dcpcr_helpline_case_count' => $sub_division_wise_in_total_progress_dcpcr_helpline_case_count,
        ];

        $this->view_name = 'dashboard/absenteeism-report';
        return ["view_name" => $this->view_name, "view_data" => $this->view_data];
    }

    public function prepareReasonListByReasonId(array $permission, array $school_id, array $classes, $start_date, $end_date, array $view_data, $reason_name, $id): array
    {
        $this->view_data = $view_data;
        $this->view_data['page_title'] = str_replace("*", "/", $reason_name);

        $reason_model = new ReasonForAbsenteeismModel();
        $list = $reason_model->getCaseListByReasonId($id, $school_id, $classes, $start_date, $end_date);
        $this->view_data['response'] = [
            "reason_for_absenteeism" => $list,
        ];
        $this->view_name = 'dashboard/list.php';
        return ["view_name" => $this->view_name, "view_data" => $this->view_data];


    }

    private function getGenderWiseReasonsCount($school_id, $classes, $start_date, $end_date, $gender): array
    {
        $case_reason_model = new ReasonForAbsenteeismModel();
        return $case_reason_model->getReasonsCount($school_id, $classes, $start_date, $end_date, $gender);

    }

    public function prepareListBy(array $permission, array $school_id, array $classes, $start_date, $end_date, array $view_data, $list_type, $id = ''): array
    {
        $this->view_data = $view_data;
        $this->view_data['page_title'] = str_replace("*", "/", $list_type);
        $this->view_data['page_title'] = ucwords(str_replace("_", " ", $list_type));
        if ($list_type == "students_list") {
            $student_model = new StudentModel();
            $total_student_list = $student_model->getStudentListFor($school_id);
            $this->view_data['response'] = [
                "total_student_list" => $total_student_list
            ];
            $this->view_name = 'dashboard/student-list.php';
        } elseif ($list_type == "marked_attendance_list") {
            $attendance_model = new AttendanceModel();
            $latest_marked_attendance_date = $attendance_model->getLatestMarkedAttendanceDateFor($school_id);
            $marked_attendance_student_list = $attendance_model->getAttendanceMarkedStudentListFor($school_id, $classes, $latest_marked_attendance_date[0]['date']);
            $this->view_data['response'] = [
                "marked_attendance_student_list" => $marked_attendance_student_list
            ];
            $this->view_name = 'dashboard/home-attendance-list.php';
        } elseif ($list_type == "detected_student_list") {
            $detected_case_model = new DetectedCaseModel();
            $detected_student_list = $detected_case_model->getTotalListOfDetectedStudentsFor($school_id, $classes, $start_date, $end_date);
            $this->view_data['response'] = [
                "detected_student_list" => $detected_student_list
            ];
            $this->view_name = 'dashboard/home-detected-student-list.php';
        } elseif ($list_type == "back_to_school_with_EWS_intervention_list") {
            $detected_case_model = new DetectedCaseModel();
            $bts_with_intervention_list = $detected_case_model->getBTSList($school_id, $classes, $start_date, $end_date);
            $this->view_data['response'] = [
                "bts_with_intervention_list" => $bts_with_intervention_list
            ];
            $this->view_name = 'dashboard/home-bts-student-list.php';
        } elseif ($list_type == "back_to_school_without_EWS_intervention_list") {
            $detected_case_model = new DetectedCaseModel();
            $bts_without_intervention_list = $detected_case_model->getBTSList($school_id, $classes, $start_date, $end_date, true);
            $this->view_data['response'] = [
                "bts_without_intervention_list" => $bts_without_intervention_list
            ];
            $this->view_name = 'dashboard/home-bts-student-list.php';
        } elseif ($list_type == "children_who_are_contacted_through_SMS") {
            $detected_case_model = new DetectedCaseModel();
            $yet_to_be_brought_back_to_school_by_call = $detected_case_model->getYetToBeBroughtBackToSchoolBySMS($school_id, $classes, $start_date, $end_date);
            $this->view_data['response'] = [
                "yet_to_be_brought_back_to_school_by_sms" => $yet_to_be_brought_back_to_school_by_call
            ];
            $this->view_name = 'dashboard/home-ytbtu-student-list.php';
        } elseif ($list_type == "children_who_are_contacted_through_calls") {
            $detected_case_model = new DetectedCaseModel();
            $yet_to_be_brought_back_to_school_by_call = $detected_case_model->getYetToBeBroughtBackToSchoolByCall($school_id, $classes, $start_date, $end_date);
            $this->view_data['response'] = [
                "yet_to_be_brought_back_to_school_by_call" => $yet_to_be_brought_back_to_school_by_call
            ];
            $this->view_name = 'dashboard/home-ytbtu-student-list.php';
        } elseif ($list_type == "wrong_number") {
            $student_model = new StudentModel();
            $wrong_mobile_number_list = $student_model->getInvalidAndWrongMobileNumberListFor($school_id);
            $this->view_data['response'] = [
                "wrong_mobile_number_list" => $wrong_mobile_number_list
            ];
            $this->view_name = 'dashboard/wrong-mobile-number-list.php';
        } elseif ($list_type == "corporal_punishment") {
            $detected_case_model = new DetectedCaseModel();
            $corporal_punishment_list = $detected_case_model->getListFor($school_id, $classes, $start_date, $end_date, ['8']);
            $this->view_data['response'] = [
                "corporal_punishment_list" => $corporal_punishment_list
            ];
            $this->view_name = 'dashboard/corporal-punishment-list.php';
        } elseif ($list_type == "bullying_harassment") {
            $detected_case_model = new DetectedCaseModel();
            $bullying_harassment = $detected_case_model->getListFor($school_id, $classes, $start_date, $end_date, ['19']);
            $this->view_data['response'] = [
                "bullying_harassment" => $bullying_harassment
            ];
            $this->view_name = 'dashboard/bullying-harassment-list.php';
        } elseif ($list_type == "moved_out_of_delhi") {
            $detected_case_model = new DetectedCaseModel();
            $moved_out_of_delhi_list = $detected_case_model->getListFor($school_id, $classes, $start_date, $end_date, ['3']);
            $this->view_data['response'] = [
                "moved_out_of_delhi" => $moved_out_of_delhi_list
            ];
            $this->view_name = 'dashboard/moved-out-of-delhi-list.php';
        } elseif ($list_type == "changed_school_in_delhi") {
            $detected_case_model = new DetectedCaseModel();
            $changed_school_in_delhi_list = $detected_case_model->getListFor($school_id, $classes, $start_date, $end_date, ['23']);
            $this->view_data['response'] = [
                "changed_school_in_delhi_list" => $changed_school_in_delhi_list
            ];
            $this->view_name = 'dashboard/changed-school-in-delhi-list.php';
        }
        elseif ($list_type == "student_dropped_out") {
            $detected_case_model = new DetectedCaseModel();
            $dropped_out_list = $detected_case_model->getListFor($school_id, $classes, $start_date, $end_date, ['6']);

            $this->view_data['response'] = [
                "dropped_out_list" => $dropped_out_list
            ];
            $this->view_name = 'dashboard/dropped-out-list.php';
        }


        return ["view_name" => $this->view_name, "view_data" => $this->view_data];

    }

}
