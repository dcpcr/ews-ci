<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\CaseModel;
use App\Models\DistrictModel;
use App\Models\SchoolMappingModel;
use App\Models\SchoolModel;
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

            $this->districts = empty($this->request->getVar('district')) ? 'All' : $this->request->getVar('district');
            $this->zones = empty($this->request->getVar('zone')) ? 'All' : $this->request->getVar('zone');;
            $this->schools = empty($this->request->getVar('school')) ? 'All' : $this->request->getVar('school');
            $this->classes = empty($this->request->getVar('class')) ? 'All' : $this->request->getVar('class');
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

        $school_model = new SchoolModel();
        $school_mapping_model = new SchoolMappingModel();

        if ($this->schools == "All") {
            $this->schools = array();
            if ($this->zones == "All") {
                if ($this->districts == "All") {
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

        if (!empty($this->duration)) {
            $this->duration = explode(' - ', $this->duration);
            $this->duration['start'] = trim($this->duration[0]);
            $this->duration['end'] = trim($this->duration[1]);
        } else {
            $begin = new DateTime();
            $this->duration['end'] = $begin->format("d-m-Y");
            $begin = $begin->sub(new DateInterval('P30D'));
            $this->duration['start'] = $begin->format("d-m-Y");
        }

        $this->response_data = $case_model->join('student', 'student.id = detected_case.student_id')->
            join('school_mapping', 'student.school_id = school_mapping.school_id')->
            whereIn('student.school_id', $this->schools)->
            where("day BETWEEN STR_TO_DATE('" . $this->duration['start'] . "' , '%d-%m-%Y') and STR_TO_DATE('" .
                $this->duration['end'] . "', '%d-%m-%Y');")->findAll();

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

        $data['data'] = $this->response_data;

        return view($view_name, $data);
    }


}
