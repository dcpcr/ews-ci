<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\AttendanceModel;
use App\Models\CaseModel;
use App\Models\DistrictModel;
use App\Models\SchoolMappingModel;
use App\Models\SchoolModel;
use App\Models\Student;
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
    protected $response_total_student;
    protected $response_attendance;


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

            $this->districts = empty($this->request->getVar('district')) ? ['All'] : $this->request->getVar('district');
            $this->zones = empty($this->request->getVar('zone')) ? ['All'] : $this->request->getVar('zone');;
            $this->schools = empty($this->request->getVar('school')) ? ['All'] : $this->request->getVar('school');
            $this->classes = empty($this->request->getVar('class')) ? ['All'] : $this->request->getVar('class');
            $this->duration = $this->request->getVar('duration');

            switch ($report_type) {
                case 'case':
                    return $this->caseReport();
                    break;
                case 'absenteeism':
                    return $this->absenteeismReport();
                    break;
                case 'suomoto':
                    return $this->suomotoReport();
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
        $pageText = "Long absenteeism, i.e., uninformed absence of 7 consecutive days or for more than 66.67% days in a 
        month (i.e., 20/30 days), is the earliest indicator that the student is facing a risk that the family is not able to overcome. 
        This report shows the status of all the cases detected including total number of detected cases as per the absenteeism 
        criteria, the number of students at high risk, for which the commission has raised suo moto complaints and the cases 
        where the students have gone back to school";
        $case_model = new CaseModel();
        $this->filterData();


        $this->response_data = $case_model->select(['student.id as student_id','student.name as student_name','student.gender','student.class','student.section','detected_case.case_id','detected_case.status','school.id as school_id','school.name as school_name','detected_case.detection_criteria','detected_case.day'])->
            join('student', 'student.id = detected_case.student_id')->
            join('school', 'student.school_id = school.id')->
            whereIn('student.school_id', $this->schools)->
            where("day BETWEEN STR_TO_DATE('" . $this->duration['start'] . "' , '%m/%d/%Y') and STR_TO_DATE('" .
                $this->duration['end'] . "', '%m/%d/%Y');")->findAll();

        return $this->prepareViewData('Case Status', 'dashboard/case', $pageText);
    }

    private function absenteeismReport(): string
    {
        return $this->prepareViewData('Reasons of Absenteeism', 'dashboard/absenteeism');
    }

    private function suomotoReport(): string
    {
        return $this->prepareViewData('Suo-Moto (High Risk) Cases', 'dashboard/suomoto');
    }

    private function followupReport(): string
    {
        return $this->prepareViewData('Call Disposition', 'dashboard/call');
    }

    private function attendanceReport(): string
    {
        $this->filterData();
        $total_student= new Student();
        $this->response_total_student=$total_student->getSchoolTotalStudent();

        $attendance = new AttendanceModel();
        $this->response_attendance=$attendance->getSchoolAttendance($this->duration['start'],$this->duration['end'],$this->schools);

        return $this->prepareViewData('Attendance Performance', 'dashboard/attendance');

    }

    private function homeVisitsReport(): string
    {
        return $this->prepareViewData('Home Visits', 'dashboard/homevisits');
    }

    private function prepareViewData($page_title, $view_name, $details = "Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book."): string
    {
        $data['page_title'] = $page_title;
        $data['details'] = $details;
        $data['user_name'] = user()->username;

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

        $data['selected_districts'] = $this->request->getVar('district');
        $data['selected_zones'] = $this->request->getVar('zone');
        $data['selected_schools'] = $this->request->getVar('school');
        $data['selected_classes'] = $this->request->getVar('class');
        $data['selected_duration'] = $this->request->getVar('duration');
        $data['case'] = $this->response_data;
        $data['total_student'] = $this->response_total_student;
        $data['total_attendance'] = $this->response_attendance;

        return view($view_name, $data);
    }

    private function duration(): void
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

    private function filterData(): void
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
        $this->duration();
    }


}
