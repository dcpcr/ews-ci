<?php

namespace App\Controllers;

use App\Models\AttendanceModel;
use App\Models\AttendanceReportModel;
use App\Models\CallDispositionModel;
use App\Models\CaseModel;
use App\Models\CaseReasonModel;
use App\Models\DcpcrHelplineTicketModel;
use App\Models\DistrictModel;
use App\Models\HighRiskModel;
use App\Models\HomeVisitModel;
use App\Models\LatestStudentStatusModel;
use App\Models\ReasonForAbsenteeismModel;
use App\Models\SchoolMappingModel;
use App\Models\SchoolModel;
use App\Models\YetToBeContactedModel;
use App\Models\ZoneModel;
use DateTime;

class AdminController extends AuthController
{
    /*    private array $permissions = [
            ['name' => 'manageUsers', 'description' => 'Create operators and other users and assign them groups'],
            ['name' => 'viewAllReports', 'description' => 'View all reports'],
            ['name' => 'viewReportsDistricts', 'description' => 'View reports of a district'],
            ['name' => 'viewReportsZone', 'description' => 'View reports of a zone'],
            ['name' => 'viewReportsSchool', 'description' => 'View reports of a school'],
            ['name' => 'manageCases', 'description' => 'Access Operator functionality']
        ];*/

    protected static array $valid_permissions_for_dashboard = ['viewAllReports', 'viewReportsDistricts', 'viewReportsZone', 'viewReportsSchool'];
    protected array $filters;
    protected array $districts;
    protected array $zones;
    protected array $schools;
    protected array $classes;
    protected array $duration;
    protected array $view_data;
    protected string $view_name;

    protected SchoolMappingModel $school_mapping_model;
    protected SchoolModel $school_model;
    protected DistrictModel $district_model;
    protected ZoneModel $zone_model;

    public function __construct()
    {
        parent::__construct();
        $this->school_mapping_model = new SchoolMappingModel();
        $this->school_model = new SchoolModel();
        $this->district_model = new DistrictModel();
        $this->zone_model = new ZoneModel();
    }


    private function doesUserHavePermission(): bool
    {
        $userId = $this->authenticate->id();
        $result = false;
        foreach ($this::$valid_permissions_for_dashboard as $permission) {
            $isPermitted = $this->authorize->doesUserHavePermission($userId, $permission);
            $result |= $isPermitted;
            $this->filters[$permission] = $isPermitted;
        }
        return $result;
    }

    public function ajax($report_type)
    {
        if ($this->request->isAJAX()) {
            log_message('info', "Ajax request");
            if ($this->doesUserHavePermission()) {
                $this->initializeFilterData();
                if ($report_type == 'case') {
                    if ($this->request->getGet('dl') == 'Yes') {
                        echo json_encode($this->getCaseTableData()['data']);
                    } else {
                        echo json_encode($this->getCaseTableData());
                    }

                } else {
                    log_message("notice", "Wrong report type in Ajax call - " . $report_type);
                }
            } else {
                //TODO: Send error json
                log_message("notice", "The user - " . user()->username . " - does not have the permission to view this page.");
            }
        } else {
            log_message('info', "Access to this functionally without Ajax Call is not allowed");
        }
    }

    public function index($report_type)
    {
        if ($this->doesUserHavePermission()) {
            $this->initializeFilterData();
            switch ($report_type) {
                case 'case':
                    $this->prepareCaseData();
                    break;
                case 'summary':
                    $this->prepareSummaryPageData();
                    break;
                case 'absenteeism-reason':
                    $this->prepareReasonForAbsenteeismPageData();
                    break;
                case 'frequent-absenteeism':
                    $this->prepareFrequentAbsenteeismPageData();
                    break;
                case 'highrisk':
                    $this->prepareHighRiskData();
                    break;
                case 'call':
                    $this->prepareFollowupData();
                    break;
                case 'attendance':
                    $this->prepareAttendanceData();
                    break;
                case 'online-attendance':
                    $this->prepareOnlineAttendancePageData();
                    break;
                case 'homevisits':
                    $this->homeVisitsReport();
                    break;
                default:
                    $this->error_404();

            }
            $this->view_data['user_name'] = user()->username;
            return view($this->view_name, $this->view_data);
        } else {
            //TODO: Show Forbidden Page
            log_message("notice", "The user - " . user()->username . " - does not have the permission to view this page.");
        }
    }

    private function getCaseTableData(): array
    {
        $school_ids = array_keys($this->schools);
        $case_model = new CaseModel();
        return $case_model
            ->getDetectedCasesForDataTable($school_ids, $this->classes, $this->duration['start'], $this->duration['end']);
    }

    private function prepareCaseData(): void
    {
        $this->view_data['details'] = "The Early Warning System detects long absenteeism, i.e., uninformed absence of 7 consecutive days 
            or for more than 66.67% days in a month (i.e., 20/30 days), across students in Delhi Government schools. 
            It is based  on the premise that low attendance is the earliest indicator of a student at risk, which their 
            family is not able to overcome. The following report shows the status of such EWS cases detected in the selected 
            duration and other key parameters such as the number of students at high risk, the number of students who have 
            returned back to school, and students who couldn’t be contacted due to various reasons.";
        $this->view_data['page_title'] = 'Case Status';

        $school_ids = array_keys($this->schools);
        $high_risk_model = new HighRiskModel();
        $this->view_data['high_risk_count'] = $high_risk_model
            ->getHighRiskCasesCountGenderWise($school_ids, $this->classes, $this->duration['start'], $this->duration['end']);
        $case_model = new CaseModel();
        $this->view_data['detected_case_count'] = $case_model
            ->getDetectedCasesCountGenderWise($school_ids, $this->classes, $this->duration['start'], $this->duration['end']);
        $this->view_data['detected_case_count'][] = [
            'count' => array_sum(array_column($this->view_data['detected_case_count'], "count")),
            'gender' => 'Total'
        ];
        $this->view_data['high_risk_count'][] = [
            'count' => array_sum(array_column($this->view_data['high_risk_count'], "count")),
            'gender' => 'Total'
        ];
        $this->view_data['response'] = [
            'detected_case_count' => $this->view_data['detected_case_count'],
            'high_risk_count' => $this->view_data['high_risk_count'],
        ];

        $this->view_name = 'dashboard/case';
    }

    private function prepareSummaryPageData()
    {
        $this->view_data['details'] = "Students with frequent absenteeism are at a high risk of danger and compromised well-being. These are students who, without prior information, are absent for more than 20 days in a month or are absent for 7 consecutive days.";
        $this->view_data['page_title'] = 'Case Status';

        $school_ids = array_keys($this->schools);
        $latest_student_status_model = new LatestStudentStatusModel();
        $total_detected_student_count = $latest_student_status_model->getDetectedStudentCount($school_ids, $this->classes, $this->duration['start'], $this->duration['end'], ["Back to school", "Fresh"]);
        $total_bts_student_count = $latest_student_status_model->getDetectedStudentCount($school_ids, $this->classes, $this->duration['start'], $this->duration['end'], ["Back to school"]);
        $detected_case_model = new CaseModel();
        $total_detected_case_count = $detected_case_model->getCaseCount($school_ids, $this->classes, $this->duration['start'], $this->duration['end'], ["Back to school", "Fresh"]);
        $yet_to_be_contacted_model = new YetToBeContactedModel();
        $total_yet_to_be_contacted_cases = $yet_to_be_contacted_model->getYetToBeContactedCaseCount($school_ids, $this->classes, $this->duration['start'], $this->duration['end']);
        $reason_for_absenteeism_model = new ReasonForAbsenteeismModel();
        $total_moved_out_of_village_count = $reason_for_absenteeism_model->getReasonCategoryCount($school_ids, $this->classes, $this->duration['start'], $this->duration['end'], ['3', '23']);
        $total_denial_of_admission_registration_name_struck_out = $reason_for_absenteeism_model->getReasonCategoryCount($school_ids, $this->classes, $this->duration['start'], $this->duration['end'], ['6']);
        $home_visit_model = new HomeVisitModel();
        $home_visit_count = $home_visit_model->getHomeVisitCount($school_ids, $this->classes, $this->duration['start'], $this->duration['end']);
        $detected_case_male_count = $latest_student_status_model->getDetectedStudentGenderWiseCount($school_ids, $this->classes, $this->duration['start'], $this->duration['end'], 'Male', ["Back to school", "Fresh"]);
        $detected_case_female_count = $latest_student_status_model->getDetectedStudentGenderWiseCount($school_ids, $this->classes, $this->duration['start'], $this->duration['end'], 'Female', ["Back to school", "Fresh"]);
        $detected_case_transgender_count = $latest_student_status_model->getDetectedStudentGenderWiseCount($school_ids, $this->classes, $this->duration['start'], $this->duration['end'], 'Transgender', ["Back to school", "Fresh"]);
        $detected_case_class_wise_count_array = $latest_student_status_model->getDetectedStudentCountGroupBy("class", $school_ids, $this->classes, $this->duration['start'], $this->duration['end'], ["Back to school", "Fresh"]);
        $bts_case_male_count = $latest_student_status_model->getDetectedStudentGenderWiseCount($school_ids, $this->classes, $this->duration['start'], $this->duration['end'], 'Male', ["Back to school"]);
        $bts_case_female_count = $latest_student_status_model->getDetectedStudentGenderWiseCount($school_ids, $this->classes, $this->duration['start'], $this->duration['end'], 'Female', ["Back to school"]);
        $bts_case_transgender_count = $latest_student_status_model->getDetectedStudentGenderWiseCount($school_ids, $this->classes, $this->duration['start'], $this->duration['end'], 'Transgender', ["Back to school"]);
        $bts_case_class_wise_count_array = $latest_student_status_model->getDetectedStudentCountGroupBy("class", $school_ids, $this->classes, $this->duration['start'], $this->duration['end'], ["Back to school"]);
        $this->view_data['response'] = [
            'total_detected_student_count' => $total_detected_student_count,
            'total_detected_case_count' => $total_detected_case_count,
            'total_bts_case_count' => $total_bts_student_count,
            'total_moved_out_of_village_count' => $total_moved_out_of_village_count,
            'dropped_out_and_in_contact_to_bring_them_school' => $total_denial_of_admission_registration_name_struck_out,
            'contact_not_established_with_dcpcr' => $contact_not_established = $home_visit_count - $total_moved_out_of_village_count - $total_denial_of_admission_registration_name_struck_out,
            'yet_to_be_contacted_cases' => $total_yet_to_be_contacted_cases,
            'detected_case_male_count' => $detected_case_male_count,
            'detected_case_female_count' => $detected_case_female_count,
            'detected_case_transgender_count' => $detected_case_transgender_count,
            'detected_case_class_wise_count_array' => $detected_case_class_wise_count_array,
            'bts_case_male_count' => $bts_case_male_count,
            'bts_case_female_count' => $bts_case_female_count,
            'bts_case_transgender_count' => $bts_case_transgender_count,
            'bts_case_class_wise_count_array' => $bts_case_class_wise_count_array,
            'enrolled_and_in_contact_to_bring_them_back_to' => $total_detected_student_count - $total_bts_student_count - $contact_not_established - $total_moved_out_of_village_count - $total_denial_of_admission_registration_name_struck_out - $total_yet_to_be_contacted_cases,
        ];
        $this->view_name = 'dashboard/summary';
    }

    private function getGenderWiseReasonsCount(array $gender): array
    {
        $school_ids = array_keys($this->schools);
        $case_reason_model = new ReasonForAbsenteeismModel();
        return $case_reason_model->getReasonsCount($school_ids, $this->classes, $this->duration['start'], $this->duration['end'], $gender);
    }

    private function prepareReasonForAbsenteeismPageData(): void
    {
        $this->view_data['details'] = "The Early Warning System has laid out a process for ascertaining the various reasons that lead to 
            long absenteeism among students. The following report shows the distribution of such reasons, including the 
            frequency of cases detected across genders.";
        $this->view_data['page_title'] = 'Reasons of Absenteeism';
        $school_ids = array_keys($this->schools);
        $maleCount = $this->getGenderWiseReasonsCount(['Male']);
        $femaleCount = $this->getGenderWiseReasonsCount(['Female']);
        $transgenderCount = $this->getGenderWiseReasonsCount(['Transgender']);
        $reason_wise_case_count = $this->getGenderWiseReasonsCount(['Female', 'Transgender', 'Male']);
        $detected_case_model = new CaseModel();
        $total_detected_cases = $detected_case_model->getCaseCount($school_ids, $this->classes, $this->duration['start'], $this->duration['end'], ["Back to school", "Fresh"]);
        $dcpcr_helpline_ticket_model = new DcpcrHelplineTicketModel();
        $sub_division_wise_total_dcpcr_helpline_case_count = $dcpcr_helpline_ticket_model->getDcpcrHelplineCaseDetails($school_ids, $this->classes, $this->duration['start'], $this->duration['end'], ["New", "Closed", 'Open'], "total_ticket_count");
        $sub_division_wise_in_total_progress_dcpcr_helpline_case_count = $dcpcr_helpline_ticket_model->getDcpcrHelplineCaseDetails($school_ids, $this->classes, $this->duration['start'], $this->duration['end'], ["New", 'Open'], "total_in_progress_ticket_count");
        $this->view_data['response'] = [
            'reason_male_count' => $maleCount,
            'reason_female_count' => $femaleCount,
            'reason_transgender_count' => $transgenderCount,
            'reason_wise_case_count' => $reason_wise_case_count,
            'total_detected_cases' => $total_detected_cases,
            'sub_division_wise_total_dcpcr_helpline_case_count' => $sub_division_wise_total_dcpcr_helpline_case_count,
            'sub_division_wise_in_total_progress_dcpcr_helpline_case_count' => $sub_division_wise_in_total_progress_dcpcr_helpline_case_count,
        ];
        $this->view_name = 'dashboard/absenteeism-reason';
    }

    public function prepareFrequentAbsenteeismPageData()
    {
        $this->view_data['details'] = "List of students with frequent absenteeism.";
        $this->view_data['page_title'] = 'Frequent Absenteeism';
        $school_ids = array_keys($this->schools);
        $case_model = new CaseModel();
        $frequent_detected_case_list = $case_model->getFrequentDetectedCases($school_ids, $this->classes, $this->duration['start'], $this->duration['end']);
        $this->view_data['response'] = [
            "frequent_detected_cases" => $frequent_detected_case_list,
        ];
        $this->view_name = 'dashboard/frequent-absenteeism.php';

    }


    private function prepareHighRiskData(): void
    {
        $this->view_data['details'] = "Once the reason for absenteeism is ascertained for a student under the Early Warning System, 
            they may need assistance from the Government to address the problem that is preventing them from going to 
            school. In such cases, DCPCR takes Suo Moto cognizance of such ‘high-risk’ cases. The following report shows 
            the distribution of Suo Moto cases across the Commission’s divisions.";
        $this->view_data['page_title'] = 'High Risk Cases';
        $this->view_data['response'] = [];
        $this->view_name = 'dashboard/highrisk';
    }

    private function prepareFollowupData(): void
    {
        $this->view_data['details'] = "This is Dummy text";
        $this->view_data['page_title'] = 'Call Disposition';
        $school_ids = array_keys($this->schools);
        $call_disposition_model = new CallDispositionModel();
        $this->view_data['response'] = $call_disposition_model
            ->getCallDisposition($school_ids, $this->classes, $this->duration['start'], $this->duration['end']);
        $this->view_name = 'dashboard/call';
    }

    private function prepareAttendanceData(): void
    {
        $this->view_data['details'] = "The Early Warning System functions on the key input of school attendance. Hence, it is critical 
            that the schools must mark attendance daily, and across classes. The following reports show the performance 
            of schools vis-a-vis marking students’ attendance.";
        $this->view_data['page_title'] = 'Attendance Report';

        $school_ids = array_keys($this->schools);
        $attendance_report_model = new AttendanceReportModel();
        $attendance_data = $attendance_report_model->getMarkedSchoolAttendanceNew($school_ids, $this->classes, $this->duration['start'], $this->duration['end']);
        $this->view_data['response'] = [
            'attendance_data' => $attendance_data,
        ];
        $this->view_name = 'dashboard/attendance';
    }

    private function prepareOnlineAttendancePageData()
    {
        $this->view_data['details'] = "To help us identify children at risk and bring them back to school, please ensure 100% marking of online attendance.";
        $this->view_data['page_title'] = 'Online Attendance Report';
        $school_ids = array_keys($this->schools);
        $attendance_model = new AttendanceModel();
        $latest_marked_attendance_date = $attendance_model->getLatestMarkedAttendanceDate();
        $attendance_report_model = new AttendanceReportModel();
        $attendance_data_day_wise = $attendance_report_model->getDateWiseMarkedStudentAttendanceCount($school_ids, $this->classes, $this->duration['start'], $this->duration['end']);
        $attendance_data_class_wise = $attendance_report_model->getClassWiseMarkedStudentAttendanceCount($school_ids, $this->classes, $latest_marked_attendance_date);
        $this->view_data['response'] = [
            "attendance_data_day_wise" => $attendance_data_day_wise,
            "attendance_data_class_wise" => $attendance_data_class_wise,
            'latest_marked_attendance_date' => $latest_marked_attendance_date,
        ];
        $this->view_name = 'dashboard/online-attendance';
    }


    private function homeVisitsReport(): void
    {
        $this->view_data['details'] = "If a student under the Early Warning System is untraceable, such cases are referred to respective 
            School Mitra (parent volunteers) to gather details about the student concerned and assist them in seeking 
            support. The following reports provide the status of these home visits.";
        $this->view_data['page_title'] = 'Home Visits';
        $this->view_data['response'] = [];
        $this->view_name = 'dashboard/homevisits';
    }

    private function initializeClasses(): array
    {
        return ['XII', 'XI', 'X', 'IX', 'VIII', 'VII', 'VI', 'V', 'IV', 'III', 'II', 'I', 'KG', 'Nursery'];
    }

    protected function initializeDuration($duration): array
    {
        $data = [];
        if (!empty($duration)) {
            $duration = explode(' - ', $duration);
            $data['start'] = trim($duration[0]);
            $data['end'] = trim($duration[1]);
        } else {
            $begin = new DateTime();
            $data['end'] = $begin->format("m/d/Y");
            $begin = $begin->modify('-1 year');
            $data['start'] = $begin->format("m/d/Y");
        }
        return $data;
    }

    protected function getSelectedDuration(): string
    {
        return $this->duration['start'] . '-' . $this->duration['end'];
    }

    protected function getSelectedParam(array $params): array
    {
        $data = [];
        foreach ($params as $key => $value) {
            $data[] = $key;
        }
        return $data;
    }

    protected function getSelectedDistricts(): array
    {
        return $this->getSelectedParam($this->districts);
    }

    protected function getSelectedZones(): array
    {
        return $this->getSelectedParam($this->zones);
    }

    protected function getSelectedSchools(): array
    {
        return $this->getSelectedParam($this->schools);
    }

    protected function initializeFilterData(): void
    {
        $user_jurisdiction_data = $this->getJurisdictionDataForLoggedUser();

        $class_get = $this->request->getGet('class');
        $this->classes = (empty($class_get) || ($class_get == ['All']))
            ? $this->initializeClasses() : $this->request->getGet('class');

        $duration_get = $this->request->getGet('duration');
        $this->duration = $this->initializeDuration($duration_get);

        $user_jurisdiction_data['filter_permissions'] = $this->filters;
        $user_jurisdiction_data['selected_classes'] = empty($class_get) ? ['All'] : $class_get;
        $user_jurisdiction_data['selected_duration'] = $this->getSelectedDuration();

        $this->view_data = $user_jurisdiction_data;

    }

    protected function getJurisdictionDataForLoggedUser(): array
    {
        $user_id = user()->id;
        if ($this->authorize->inGroup('Level5', $user_id)) {
            return $this->getDataForSchoolJurisdiction();
        } else if ($this->authorize->inGroup('Level4', $user_id)) {
            return $this->getDataForZoneJurisdiction();
        } else if ($this->authorize->inGroup('Level3', $user_id)) {
            return $this->getDataForDistrictJurisdiction();
        } else if ($this->authorize->inGroup(['Level2', 'Level1'], $user_id)) {
            return $this->getDataForAdminJurisdiction();
        }
        return [];
    }

    protected function getDataForAdminJurisdiction(): array
    {
        $data = [];
        $data['user_type'] = 'admin';
        $districts = $this->district_model->findAll();
        foreach ($districts as $district) {
            $data['user_district'][$district['id']] = $district['name'];
        }
        $zones = $this->zone_model->findAll();
        foreach ($zones as $zone) {
            $data['user_zone'] [$zone['id']] = $zone['name'];
        }
        $schools = $this->school_model->findAll();
        foreach ($schools as $school) {
            $data['user_school'] [$school['id']] = $school['name'];
        }

        $district_get = $this->request->getGet('district');
        $this->districts = (empty($district_get) || ($district_get == ['All'])) ? $data['user_district']
            : array_intersect_key($data['user_district'], array_flip($district_get));
        if (empty($this->districts))
            die("<h1>Invalid Request</h1>");
        $zone_get = $this->request->getGet('zone');
        $this->zones = (empty($zone_get) || ($zone_get == ['All'])) ? $data['user_zone']
            : array_intersect_key($data['user_zone'], array_flip($zone_get));
        if (empty($this->zones))
            die("<h1>Invalid Request</h1>");
        $school_get = $this->request->getGet('school');
        $this->setSchools($school_get, $data['user_school'], $this->districts, $this->zones);

        $data['selected_districts'] = (empty($district_get) || ($district_get == ['All'])) ? ['All'] : $this->getSelectedDistricts();
        $data['selected_zones'] = (empty($zone_get) || ($zone_get == ['All'])) ? ['All'] : $this->getSelectedZones();
        $data['selected_schools'] = (empty($school_get) || ($school_get == ['All'])) ? ['All'] : $this->getSelectedSchools();

        return $data;
    }

    protected function getDataForDistrictJurisdiction(): array
    {
        $data = [];
        $data['user_type'] = 'district';
        $district = $this->district_model->where('id', user()->username)->first();
        $data['user_district'][$district['id']] = $district['name'];
        $school_mappings = $this->school_mapping_model->where('district_id', $district['id'])->findAll();
        foreach ($school_mappings as $school_mapping) {
            $zone = $this->zone_model->where('id', $school_mapping['zone_id'])->first();
            if ($zone) {
                $data['user_zone'] [$zone['id']] = $zone['name'];
            }
        }
        foreach ($school_mappings as $school_mapping) {
            $school = $this->school_model->where('id', $school_mapping['school_id'])->first();
            if ($school) {
                $data['user_school'] [$school['id']] = $school['name'];
            }
        }

        $this->districts = $data['user_district'];
        $zone_get = $this->request->getGet('zone');
        $this->zones = (empty($zone_get) || ($zone_get == ['All'])) ? $data['user_zone']
            : array_intersect_key($data['user_zone'], array_flip($zone_get));
        if (empty($this->zones))
            die("<h1>Invalid Request</h1>");
        $school_get = $this->request->getGet('school');
        $this->setSchools($school_get, $data['user_school'], $this->districts, $this->zones);

        $data['selected_districts'] = $this->getSelectedDistricts();
        $data['selected_zones'] = (empty($zone_get) || ($zone_get == ['All'])) ? ['All'] : $this->getSelectedZones();
        $data['selected_schools'] = (empty($school_get) || ($school_get == ['All'])) ? ['All'] : $this->getSelectedSchools();

        return $data;
    }

    protected function getDataForZoneJurisdiction(): array
    {
        $data = [];
        $data['user_type'] = 'zone';
        $zone = $this->zone_model->where('id', user()->username)->first();
        $data['user_zone'] [$zone['id']] = $zone['name'];
        $school_mapping = $this->school_mapping_model->where('zone_id', $zone['id'])->first();
        $district = $this->district_model->where('id', $school_mapping['district_id'])->first();
        $data['user_district'][$district['id']] = $district['name'];
        $school_mappings = $this->school_mapping_model->where('zone_id', $zone['id'])->findAll();
        foreach ($school_mappings as $school_mapping) {
            $school = $this->school_model->where('id', $school_mapping['school_id'])->first();
            if ($school) {
                $data['user_school'] [$school['id']] = $school['name'];
            }
        }

        $this->districts = $data['user_district'];
        $this->zones = $data['user_zone'];
        $school_get = $this->request->getGet('school');
        $this->setSchools($school_get, $data['user_school'], $this->districts, $this->zones);

        $data['selected_districts'] = $this->getSelectedDistricts();
        $data['selected_zones'] = $this->getSelectedZones();
        $data['selected_schools'] = (empty($school_get) || ($school_get == ['All'])) ? ['All'] : $this->getSelectedSchools();

        return $data;
    }

    protected function getDataForSchoolJurisdiction(): array
    {
        $data = [];
        $data['user_type'] = 'school';
        $school = $this->school_model->where('id', user()->username)->first();
        $data['user_school'] [$school['id']] = $school['name'];
        $school_mapping = $this->school_mapping_model->where('school_id', $school['id'])->first();
        $zone = $this->zone_model->where('id', $school_mapping['zone_id'])->first();
        $data['user_zone'][$zone['id']] = $zone['name'];
        $district = $this->district_model->where('id', $school_mapping['district_id'])->first();
        $data['user_district'][$district['id']] = $district['name'];

        $this->districts = $data['user_district'];
        $this->zones = $data['user_zone'];
        $this->schools = $data['user_school'];

        $data['selected_districts'] = $this->getSelectedDistricts();
        $data['selected_zones'] = $this->getSelectedZones();
        $data['selected_schools'] = $this->getSelectedSchools();

        return $data;
    }

    protected function setSchools($selected_schools, $user_school, $districts, $zones)
    {
        $schools = (empty($selected_schools) || ($selected_schools == ['All'])) ? $user_school :
            array_intersect_key($user_school, array_flip($selected_schools));
        if (empty($schools))
            die("<h1>Invalid Request</h1>");
        $school_mappings = $this->school_mapping_model
            ->whereIn('district_id', array_keys($districts))
            ->whereIn('zone_id', array_keys($zones))
            ->whereIn('school_id', array_keys($schools))
            ->findAll();

        if (count($school_mappings) == 0) {
            $this->schools = [
                '' => '', //adding a dummy school
            ];
        } else {
            foreach ($school_mappings as $school_mapping) {
                $this->schools[$school_mapping['school_id']] = $schools[$school_mapping['school_id']];
            }
        }
    }

    /**
     * @param $schoolWiseStudentCount
     * @param $markedAttendanceCount
     * @return array
     */
    private function prepareClassWiseAttendanceData($schoolWiseStudentCount, $markedAttendanceCount): array
    {
        $school_data = [];
        foreach ($schoolWiseStudentCount as $school) {

            $school_data [$school['class']] ['class'] = $school['class'];
            $school_data [$school['class']] ['student_count'] = is_null($school['count_total']) ? 0 : $school['count_total'];

        }
        foreach ($markedAttendanceCount as $school) {

            $school_data [$school['class']] ['class'] = $school['class'];
            $school_data [$school['class']] ['attendance_count'] = is_null($school['count_att']) ? 0 : $school['count_att'];
        }

        $table_data = [];
        $count = 1;
        foreach ($school_data as $classes => $class) {

            $table_data [] = [
                "Serial_no" => $count++,
                "Class" => $class['class'],
                "Attendance_Marked" => (isset($class['attendance_count'])) ? $class['attendance_count'] : 0,
                "Attendance_Marked_Percent" => (isset($class['attendance_count'])) ? floor($class['attendance_count'] / $class['student_count'] * 100) : 0,
                "Total_Students" => (isset($class['student_count'])) ? $class['student_count'] : 0,
            ];

        }
        return $table_data;
    }

    private function error_404(): string
    {
        return $this->view_name = 'errors/html/error_404';
    }


}
