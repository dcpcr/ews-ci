<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\AttendanceModel;
use App\Models\AttendanceReportModel;
use App\Models\CaseModel;
use App\Models\DcpcrHelplineTicketModel;
use App\Models\HomeVisitModel;
use App\Models\LatestStudentStatusModel;
use App\Models\ReasonForAbsenteeismModel;
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
        $col_name = "class";
        $graph_lable = "class";
        $table_title = "class";
        $attendance_model = new AttendanceModel();
        $latest_marked_attendance_date = $attendance_model->getLatestMarkedAttendanceDate();
        $attendance_report_model = new AttendanceReportModel();
        $attendance_data_day_wise = $attendance_report_model->getDateWiseMarkedStudentAttendanceCount($school_id, $classes, $start_date, $end_date);
        $attendance_data_class_wise = $attendance_report_model->getMarkedStudentAttendanceDataGroupByCount($school_id, $classes, $latest_marked_attendance_date, "class", false);
        $attendance_data_for_graph = $attendance_report_model->getMarkedStudentAttendanceDataGroupByCount($school_id, $classes, $latest_marked_attendance_date, "class", true);
        $this->view_data['response'] = [
            "table_title" => $table_title,
            "graph_lable" => $graph_lable,
            "col_name" => $col_name,
            "attendance_data_day_wise" => $attendance_data_day_wise,
            'latest_marked_attendance_date' => $latest_marked_attendance_date,
            "attendance_data_class_wise" => $attendance_data_class_wise,
            "attendance_data_for_graph" => $attendance_data_for_graph,
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
        $this->view_data['response'] = [];
        $this->view_name = 'dashboard/task';
        return ["view_name" => $this->view_name, "view_data" => $this->view_data];
    }

    public function prepareAbsenteeismReportPageData(array $permission, array $school_id, array $classes, $start_date, $end_date, array $view_data): array
    {
        $this->view_data = $view_data;
        $this->view_data['filter_permissions'] = $permission;
        $this->view_data['details'] = "Scroll down to see quick links and overview of your school's attendance performance and daily tasks!";
        $this->view_data['page_title'] = 'Absenteeism Report';
        $reason_wise_case_count = $this->getGenderWiseReasonsCount($school_id, $classes, $start_date, $end_date,['Female', 'Transgender', 'Male']);
        $detected_case_model = new CaseModel();
        $total_detected_cases = $detected_case_model->getCaseCount($school_id, $classes,$start_date, $end_date, ["Back to school", "Fresh"]);
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

}
