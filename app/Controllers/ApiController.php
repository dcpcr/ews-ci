<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\CaseModel;

class ApiController extends BaseController
{
    public function index()
    {
        //TODO: Add Authentication for API request
        //TODO: Fix the limit and offset issue
        // TODO: Error handeling
        // date fromat and url for api request => localhost/APIcontroller/?fromdate=2022-01-01&todate=2022-05-25
        //header('Content-Type:application/json');
        if ( $_GET['fromdate'] and $_GET['todate'] !== null) {
            if(!isset($_GET['limit'])){
                $no_of_records_per_page=1000;
            }
            else{
                $no_of_records_per_page=$_GET['limit'];
            }
            if(!isset($_GET['pageno'])){
                $pageno=1;

            }else{
                $pageno=$_GET['pageno'];
            }
            $case_model = new CaseModel();
            $total_rows=$case_model->getTotalNumberofCaseData($_GET['fromdate'],$_GET['todate']);
            $total_pages = ceil($total_rows / $no_of_records_per_page);
            $duration = $_GET['fromdate'] . " to " . $_GET['todate'];
            $offset = ($pageno - 1) * $no_of_records_per_page;
            $data=$case_model->getApiCaseData($_GET['fromdate'],$_GET['todate'],$offset,$no_of_records_per_page);
            echo json_encode(["status" => "true", "pageno" => "$pageno", "total_pages" => "$total_pages", "total_rows" => "$total_rows", "limit" => "$no_of_records_per_page", "result" => "data found", "dutation" => "$duration", "data" => $data]);
        }

    }
}
