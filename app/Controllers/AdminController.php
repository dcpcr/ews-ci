<?php

namespace App\Controllers;

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
    protected array $districts;
    protected array $zones;
    protected array $schools;
    protected array $classes;
    protected array $duration;
    protected array $response_data;

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

    public function index($report_type)
    {
        if ($this->doesUserHavePermission()) {
            //TODO: The filters will change based on the permission that the user has.

            switch ($report_type) {
                case 'case':
                    return $this->caseReport();
                case 'absenteeism':
                    return $this->absenteeismReport();
                case 'highrisk':
                    return $this->highRiskReport();
                case 'call':
                    return $this->followupReport();
                case 'attendance':
                    return $this->attendanceReport();
                case 'homevisits':
                    return $this->homeVisitsReport();
            }
        } else {
            //TODO: Show Forbidden Page
            log_message("notice", "The user - " . user()->username . " - does not have the permission to view this page.");
        }
    }

    private function caseReport(): string
    {
        $data = $this->initializeFilterData();

        $pageText = "The Early Warning System detects long absenteeism, i.e., uninformed absence of 7 consecutive days 
            or for more than 66.67% days in a month (i.e., 20/30 days), across students in Delhi Government schools. 
            It is based  on the premise that low attendance is the earliest indicator of a student at risk, which their 
            family is not able to overcome. The following report shows the status of such EWS cases detected in the selected 
            duration and other key parameters such as the number of students at high risk, the number of students who have 
            returned back to school, and students who couldn’t be contacted due to various reasons.";
        $case_model = new CaseModel();
        $school_ids = array_keys($this->schools);

        $this->response_data = $case_model->select([
            'detected_case.id as case_id',
            'detected_case.detection_criteria',
            'detected_case.day',
            'detected_case.status',
            'student.id as student_id',
            'student.name as student_name',
            'student.gender',
            'student.class',
            'student.section',
            'student.section',
            'student.dob',
            'student.mother',
            'student.father',
            'student.mobile',
            'student.address',
            'school.id as school_id',
            'school.name as school_name',
            'school.district',
            'school.zone'
        ])
            ->join('student', 'student.id = detected_case.student_id')
            ->join('school', 'student.school_id = school.id')
            ->whereIn('student.school_id', $school_ids)
            ->whereIn('student.class', $this->classes)
            ->where("day BETWEEN STR_TO_DATE('" . $this->duration['start'] . "' , '%m/%d/%Y') and STR_TO_DATE('" .
                $this->duration['end'] . "', '%m/%d/%Y');")->findAll();
        return $this->prepareViewData('Case Status', 'dashboard/case', $data, $pageText);
    }

    private function absenteeismReport(): string
    {
        $data = $this->initializeFilterData();
        $pageText = "The Early Warning System has laid out a process for ascertaining the various reasons that lead to 
            long absenteeism among students. The following report shows the distribution of such reasons, including the 
            frequency of cases detected across genders.";
        return $this->prepareViewData('Reasons of Absenteeism', 'dashboard/absenteeism', $data, $pageText);
    }

    private function highRiskReport(): string
    {
        $data = $this->initializeFilterData();
        $pageText = "Once the reason for absenteeism is ascertained for a student under the Early Warning System, 
            they may need assistance from the Government to address the problem that is preventing them from going to 
            school. In such cases, DCPCR takes Suo Moto cognizance of such ‘high-risk’ cases. The following report shows 
            the distribution of Suo Moto cases across the Commission’s divisions.";
        return $this->prepareViewData('High Risk Cases', 'dashboard/highrisk', $data, $pageText);
    }

    private function followupReport(): string
    {
        $data = $this->initializeFilterData();
        return $this->prepareViewData('Call Disposition', 'dashboard/call', $data);
    }

    private function attendanceReport(): string
    {
        $data = $this->initializeFilterData();
        $pageText = "The Early Warning System functions on the key input of school attendance. Hence, it is critical 
            that the schools must mark attendance daily, and across classes. The following reports show the performance 
            of schools vis-a-vis marking students’ attendance.";
        $studentModel = new StudentModel();
        $schoolWiseStudentCount = $studentModel->getSchoolWiseStudentCount();

        $attendanceModel = new AttendanceModel();
        $markedAttendanceCount = $attendanceModel->getMarkedSchoolAttendance($this->duration['start'], $this->duration['end'], $this->schools);

        $this->response_data = ['schoolWiseStudentCount' => $schoolWiseStudentCount, 'markedAttendanceCount' => $markedAttendanceCount];
        return $this->prepareViewData('Attendance Report', 'dashboard/attendance', $data, $pageText);
    }

    private function homeVisitsReport(): string
    {
        $data = $this->initializeFilterData();
        $pageText = "If a student under the Early Warning System is untraceable, such cases are referred to respective 
            School Mitra (parent volunteers) to gather details about the student concerned and assist them in seeking 
            support. The following reports provide the status of these home visits.";
        return $this->prepareViewData('Home Visits', 'dashboard/homevisits', $data, $pageText);
    }

    private function prepareViewData($page_title, $view_name, $data, $details = "Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book."): string
    {
        $data['page_title'] = $page_title;
        $data['details'] = $details;
        $data['user_name'] = user()->username;
        $data['response'] = $this->response_data;

        return view($view_name, $data);
    }

    private function initializeClasses(): array
    {
        return ['XII', 'XI', 'X', 'IX', 'VIII', 'VII', 'VI', 'V', 'IV', 'III', 'II', 'I'];
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
            $begin = $begin->modify('-6 month');
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

    protected function initializeFilterData(): array
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

        return $user_jurisdiction_data;

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
        $zone_get = $this->request->getGet('zone');
        $this->zones = (empty($zone_get) || ($zone_get == ['All'])) ? $data['user_zone']
            : array_intersect_key($data['user_zone'], array_flip($zone_get));
        $school_get = $this->request->getGet('school');
        $this->schools = (empty($school_get) || ($school_get == ['All'])) ? $data['user_school'] :
            array_intersect_key($data['user_school'], array_flip($school_get));

        $data['selected_districts'] = (empty($duration_get) || ($duration_get == ['All'])) ? ['All'] : $this->getSelectedDistricts();
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
            $data['user_zone'] [$zone['id']] = $zone['name'];
        }
        foreach ($school_mappings as $school_mapping) {
            $school = $this->school_model->where('id', $school_mapping['school_id'])->first();
            $data['user_school'] [$school['id']] = $school['name'];
        }

        $this->districts = $data['user_district'];
        $zone_get = $this->request->getGet('zone');
        $this->zones = (empty($zone_get) || ($zone_get == ['All'])) ? $data['user_zone']
            : array_intersect_key($data['user_zone'], array_flip($zone_get));
        $school_get = $this->request->getGet('school');
        $this->schools = (empty($school_get) || ($school_get == ['All'])) ? $data['user_school'] :
            array_intersect_key($data['user_school'], array_flip($school_get));

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
            $data['user_school'] [$school['id']] = $school['name'];
        }

        $this->districts = $data['user_district'];
        $this->zones = $data['user_zone'];
        $school_get = $this->request->getGet('school');
        $this->schools = (empty($school_get) || ($school_get == ['All'])) ? $data['user_school'] :
            array_intersect_key($data['user_school'], array_flip($school_get));

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

}
