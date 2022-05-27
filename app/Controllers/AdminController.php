<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\AttendanceModel;
use App\Models\CaseModel;
use App\Models\DistrictModel;
use App\Models\SchoolMappingModel;
use App\Models\SchoolModel;
use App\Models\StudentModel;
use App\Models\ZoneModel;
use DateTime;
use DateInterval;

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
    protected $districts;
    protected $zones;
    protected $schools;
    protected $classes;
    protected $duration;
    protected $response_data;

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

    public function index($report_type)
    {
        if ($this->doesUserHavePermission()) {
            //TODO: The filters will change based on the permission that the user has.

            $this->districts = empty($this->request->getGet('district')) ? ['All'] : $this->request->getGet('district');
            $this->zones = empty($this->request->getGet('zone')) ? ['All'] : $this->request->getGet('zone');;
            $this->schools = empty($this->request->getGet('school')) ? ['All'] : $this->request->getGet('school');
            $this->classes = empty($this->request->getGet('class')) ? ['All'] : $this->request->getGet('class');
            $this->duration = $this->request->getGet('duration');

            switch ($report_type) {
                case 'case':
                    return $this->caseReport();
                    break;
                case 'absenteeism':
                    return $this->absenteeismReport();
                    break;
                case 'highrisk':
                    return $this->highRiskReport();
                    break;
                case 'call':
                    return $this->followupReport();
                    break;
                case 'attendance':
                    return $this->attendanceReport();
                    break;
                case 'homevisits':
                    return $this->homeVisitsReport();
                    break;
            }
        } else {
            //TODO: Show Forbidden Page
        }
    }

    private function caseReport(): string
    {
        $pageText = "The Early Warning System detects long absenteeism, i.e., uninformed absence of 7 consecutive days 
            or for more than 66.67% days in a month (i.e., 20/30 days), across students in Delhi Government schools. 
            It is based  on the premise that low attendance is the earliest indicator of a student at risk, which their 
            family is not able to overcome. The following report shows the status of such EWS cases detected in the selected 
            duration and other key parameters such as the number of students at high risk, the number of students who have 
            returned back to school, and students who couldn’t be contacted due to various reasons.";
        $this->initializeFilterData();
        $case_model = new CaseModel();
        $this->response_data = $case_model->select(['student.id as student_id', 'student.name as student_name', 'student.gender', 'student.class', 'student.section', 'detected_case.id as case_id', 'detected_case.status', 'school.id as school_id', 'school.name as school_name', 'detected_case.detection_criteria', 'detected_case.day'])->
        join('student', 'student.id = detected_case.student_id')->
        join('school', 'student.school_id = school.id')->
        whereIn('student.school_id', $this->schools)->
        where("day BETWEEN STR_TO_DATE('" . $this->duration['start'] . "' , '%m/%d/%Y') and STR_TO_DATE('" .
            $this->duration['end'] . "', '%m/%d/%Y');")->findAll();

        return $this->prepareViewData('Case Status', 'dashboard/case', $pageText);
    }

    private function absenteeismReport(): string
    {
        $pageText = "The Early Warning System has laid out a process for ascertaining the various reasons that lead to 
            long absenteeism among students. The following report shows the distribution of such reasons, including the 
            frequency of cases detected across genders.";
        $this->initializeFilterData();
        return $this->prepareViewData('Reasons of Absenteeism', 'dashboard/absenteeism', $pageText);
    }

    private function highRiskReport(): string
    {
        $pageText = "Once the reason for absenteeism is ascertained for a student under the Early Warning System, 
            they may need assistance from the Government to address the problem that is preventing them from going to 
            school. In such cases, DCPCR takes Suo Moto cognizance of such ‘high-risk’ cases. The following report shows 
            the distribution of Suo Moto cases across the Commission’s divisions.";
        $this->initializeFilterData();

        return $this->prepareViewData('High Risk Cases', 'dashboard/highrisk', $pageText);
    }

    private function followupReport(): string
    {
        $this->initializeFilterData();

        return $this->prepareViewData('Call Disposition', 'dashboard/call');
    }

    private function attendanceReport(): string
    {
        $pageText = "The Early Warning System functions on the key input of school attendance. Hence, it is critical 
            that the schools must mark attendance daily, and across classes. The following reports show the performance 
            of schools vis-a-vis marking students’ attendance.";
        $this->initializeFilterData();
        $studentModel = new StudentModel();
        $schoolWiseStudentCount = $studentModel->getSchoolWiseStudentCount();

        $attendanceModel = new AttendanceModel();
        $markedAttendanceCount = $attendanceModel->getMarkedSchoolAttendance($this->duration['start'], $this->duration['end'], $this->schools);

        $this->response_data = ['schoolWiseStudentCount' => $schoolWiseStudentCount, 'markedAttendanceCount' => $markedAttendanceCount];
        return $this->prepareViewData('Attendance Report', 'dashboard/attendance', $pageText);
    }

    private function homeVisitsReport(): string
    {
        $pageText = "If a student under the Early Warning System is untraceable, such cases are referred to respective 
            School Mitra (parent volunteers) to gather details about the student concerned and assist them in seeking 
            support. The following reports provide the status of these home visits.";
        $this->initializeFilterData();
        return $this->prepareViewData('Home Visits', 'dashboard/homevisits', $pageText);
    }

    private function prepareViewData($page_title, $view_name, $details = "Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book."): string
    {

        $data = $this->getFilterData();
        $data['page_title'] = $page_title;
        $data['details'] = $details;
        $data['user_name'] = user()->username;
        $data['response'] = $this->response_data;

        return view($view_name, $data);
    }

    private function initializeDuration(): void
    {
        if (!empty($this->duration)) {
            $this->duration = explode(' - ', $this->duration);
            $this->duration['start'] = trim($this->duration[0]);
            $this->duration['end'] = trim($this->duration[1]);
        } else {
            $begin = new DateTime();
            $this->duration['end'] = $begin->format("m/d/Y");
            $begin = $begin->sub(new DateInterval('P30D'));
            $this->duration['start'] = $begin->format("m/d/Y");
            //TODO: send this back to the clinet.
        }
    }

    private function initializeFilterData(): void
    {
        $school_model = new SchoolModel();
        $school_mapping_model = new SchoolMappingModel();

        if ($this->schools[0] == "All") {
            $this->schools = array();
            if ($this->zones[0] == "All") {
                if ($this->districts[0] == "All") {
                    $schools = $school_model->findAll();
                    foreach ($schools as $school) {
                        $this->schools[] = $school['id'];
                    }
                } else {
                    foreach ($this->districts as $district) {
                        $school_mappings = $school_mapping_model->where('district_id', $district)->findAll();
                        foreach ($school_mappings as $school_mapping) {
                            $this->schools[] = $school_mapping['school_id'];
                        }
                    }
                }
            } else {
                foreach ($this->zones as $zone) {
                    $school_mappings = $school_mapping_model->where('zone_id', $zone)->findAll();
                    foreach ($school_mappings as $school_mapping) {
                        $this->schools[] = $school_mapping['school_id'];
                    }
                }
            }
        }
        $this->initializeDuration();
    }

    private function getFilterData(): array
    {
        $data['filter_permissions'] = $this->filters;
        $school_mapping_model = new SchoolMappingModel();
        $data['school_mappings'] = $school_mapping_model->findAll();
        $school_model = new SchoolModel();
        $data['schools'] = $school_model->findAll();
        $district_model = new DistrictModel();
        $data['districts'] = $district_model->findAll();
        $zone_model = new ZoneModel();
        $data['zones'] = $zone_model->findAll();

        if ($this->authorize->inGroup('Level5', user()->id)) {
            $data['user_type'] = 'school';
            $school = $school_model->where('id', user()->username)->first();
            $data['user_school_name'] = $school['name'];
            $data['user_school_id'] = $school['id'];

            $school_mapping = $school_mapping_model->where('school_id', $school['id'])->first();
            $zone = $zone_model->where('id', $school_mapping['zone_id'])->first();
            $data['user_zone_name'] = $zone['name'];
            $data['user_zone_id'] = $zone['id'];

            $district = $district_model->where('id', $school_mapping['district_id'])->first();
            $data['user_district_name'] = $district['name'];
            $data['user_district_id'] = $district['id'];
        } else if ($this->authorize->inGroup('Level4', user()->id)) {
            $data['user_type'] = 'zone';
            $zone = $zone_model->where('id', user()->username)->first();
            $data['user_zone_name'] = $zone['name'];
            $data['user_zone_id'] = $zone['id'];

            $school_mapping = $school_mapping_model->where('zone_id', $zone['id'])->first();

            $district = $district_model->where('id', $school_mapping['district_id'])->first();
            $data['user_district_name'] = $district['name'];
            $data['user_district_id'] = $district['id'];


            $school_mappings = $school_mapping_model->where('zone_id', $zone['id'])->findAll();

            $user_schools = array();
            foreach ($school_mappings as $school_mapping) {
                $school = $school_model->where('id', $school_mapping['school_id'])->first();
                $user_schools [] = [
                    'id' => $school['id'],
                    'name' => $school['name']
                ];
            }
            $data['user_schools'] = $user_schools;

        } else if ($this->authorize->inGroup('Level3', user()->id)) {
            $data['user_type'] = 'district';
            $district = $district_model->where('id', user()->username)->first();
            $data['user_district_name'] = $district['name'];
            $data['user_district_id'] = $district['id'];
            $school_mappings = $school_mapping_model->where('district_id', $district['id'])->findAll();
            $user_zones = array();
            foreach ($school_mappings as $school_mapping) {
                $zone = $zone_model->where('id', $school_mapping['zone_id'])->first();
                $user_zones[$zone['id']] = $zone['name'];
            }
            $data['user_zones'] = $user_zones;
            $user_schools = array();
            foreach ($school_mappings as $school_mapping) {
                $school = $school_model->where('id', $school_mapping['school_id'])->first();
                $user_schools [] = [
                    'id' => $school['id'],
                    'name' => $school['name']
                ];
            }
            $data['user_schools'] = $user_schools;
        }

        $data['selected_districts'] = $this->request->getGet('district');
        $data['selected_zones'] = $this->request->getGet('zone');
        $data['selected_schools'] = $this->request->getGet('school');
        $data['selected_classes'] = $this->request->getGet('class');
        $data['selected_duration'] = $this->request->getGet('duration');
        return $data;
    }


}
