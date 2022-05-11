<?php

namespace App\Controllers;

use App\Controllers\BaseController;

class AdminController extends AuthController
{
    /*
     * Permissions -
        ['name' => 'manageUsers', 'description' => 'Create operators and other users and assign them groups'],
        ['name' => 'viewAllReports', 'description' => 'View all reports'],
        ['name' => 'viewReportsDistricts', 'description' => 'View reports of a district'],
        ['name' => 'viewReportsZone', 'description' => 'View reports of a zone'],
        ['name' => 'viewReportsSchool', 'description' => 'View reports of a school'],
        ['name' => 'manageCases', 'description' => 'Access Operator functionality'],
    */


    private function doesUserHavePermission($permissions)
    {
        $userId = $this->authenticate->id();
        return $this->authorize->doesUserHavePermission($userId, 'viewAllReports');
    }

    public function index($report_type)
    {
        if ($this->doesUserHavePermission(['viewAllReports', 'viewReportsDistricts', 'viewReportsZone', 'viewReportsSchool'])) {
            //TODO: The filters will change based on the permission that the user has.

            switch ($report_type) {
                case 'student':
                    return $this->studentReport();
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
        }
        else {
            //TODO: Show Forbidden Page
        }
    }

    private function studentReport(): string
    {
        return $this->prepareViewData('Student Report', 'Student', 'admin/student');
    }

    private function absenteeismReport(): string
    {
        return $this->prepareViewData('Absenteeism Report', 'Absenteeism', 'admin/absenteeism');
    }

    private function suomotoReport(): string
    {
        return $this->prepareViewData('Suomoto Cases Report', 'Suomoto Cases', 'admin/suo-moto-case');
    }

    private function followupReport(): string
    {
        return $this->prepareViewData('Follow Up Report', 'Follow Up', 'admin/follow-up');
    }

    private function attendanceReport(): string
    {
        return $this->prepareViewData('Attendance Report', 'Attendance Report', 'admin/attendance-report');
    }

    private function prepareViewData($page_title, $breadcrumb, $view_name): string
    {
        $data['page_title'] = $page_title;
        $data['breadcrumb'] = $breadcrumb;
        $data['user_name'] = user()->username;
        return view($view_name, $data);
    }
}
