<?php

namespace App\Controllers;

use App\Controllers\BaseController;

class AdminController extends BaseController
{
    public function index()
    {
        $data['page_title']='Student Report';
        $data['breadcrumb']='Home';
        $data['user_name']='DCPCR';
        return view('admin/index',$data);
    }
    public function studentreport()
    {
        $data['page_title']='Student Report';
        $data['breadcrumb']='Student Report';
        $data['user_name']='DCPCR';

        return view('admin/index',$data);
    }

    public function absenteeism()
    {
        $data['page_title']='Absenteeism Report';
        $data['breadcrumb']='Absenteeism';
        $data['user_name']='DCPCR';

        return view('admin/absenteeism',$data);
    }
    public function suomotocases()
    {
        $data['page_title']='EWS Suomoto Cases Report';
        $data['breadcrumb']='SuoMoto Cases';
        $data['user_name']='DCPCR';

        return view('admin/suo-moto-case',$data);
    }
    public function followup()
    {
        $data['page_title']='Follow Up Report';
        $data['breadcrumb']='Follow Up';
        $data['user_name']='DCPCR';

        return view('admin/follow-up',$data);
    }

public function attendancestatus()
    {
        $data['page_title']='Attendance Report';
        $data['breadcrumb']='Attendace Report';
        $data['user_name']='DCPCR';

        return view('admin/attendance-report',$data);
    }
}
