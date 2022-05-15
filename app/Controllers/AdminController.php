<?php

namespace App\Controllers;

use App\Controllers\BaseController;

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

    private static array $valid_permissions_for_dashboard = ['viewAllReports', 'viewReportsDistricts', 'viewReportsZone', 'viewReportsSchool'];

    private array $filters;

    private function doesUserHavePermission(): bool
    {
        $userId = $this->authenticate->id();
        $result = false;
        foreach ($this::$valid_permissions_for_dashboard as $permission) {
            $isPermitted = $this->authorize->doesUserHavePermission($userId, $permission);
            $result |= $isPermitted;
            $this->filters[] = [$permission => $isPermitted];
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
                    break;
                case 'absenteeism':
                    return $this->absenteeismReport();
                    break;
                case 'suomoto':
                    return $this->suomotoReport();
                    break;
                case 'followup':
                    return $this->followupReport();
                    break;
                case 'attendance':
                    return $this->attendanceReport();
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
        return $this->prepareViewData('Case Status', 'admin/case', $pageText);
    }

    private function absenteeismReport(): string
    {
        return $this->prepareViewData('Absenteeism Report', 'admin/absenteeism');
    }

    private function suomotoReport(): string
    {
        return $this->prepareViewData('Suomoto Cases Report', 'admin/suo-moto-case');
    }

    private function followupReport(): string
    {
        return $this->prepareViewData('Follow Up Report', 'admin/follow-up');
    }

    private function attendanceReport(): string
    {
        return $this->prepareViewData('Attendance Report', 'admin/attendance-report');
    }

    private function prepareViewData($page_title, $view_name, $details = "Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book."): string
    {
        $data['page_title'] = $page_title;
        $data['details'] = $details;
        $data['user_name'] = user()->username;
        $data['filter_permissions'] = $this->filters;
        return view($view_name, $data);
    }
}
