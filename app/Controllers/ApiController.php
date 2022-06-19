<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\CaseModel;

class ApiController extends BaseController
{
    public function index()
    {
        //TODO: Add Authentication for API request
        // date fromat yyyy-mm-dd and url for api request => http://localhost:8080/APIcontroller/?fromdate=2022-01-01&todate=2022-05-25&limit=0&pageno=1
        //header('Content-Type:application/json');
        if ( $_GET['fromdate'] and $_GET['todate'] !== null) {

            preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/",$_GET['fromdate'])? :die("Invalid date format for request parameter fromdate.");
            preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/",$_GET['todate'])? :die("Invalid date format for request parameter todate.");

            if(!isset($_GET['limit'])){
                $no_of_records_per_page=1000;
            }
            else{
                $_GET['limit']<=0? die("Invalid Limit"):$no_of_records_per_page=$_GET['limit'];
            }
            if(!isset($_GET['pageno'])){
                $pageno=1;

            }else{
                $_GET['pageno']<=0? die("Invalid Page Number"):$pageno=$_GET['pageno'];
            }
            $case_model = new CaseModel();
            $total_rows=$case_model->getTotalNumberofCaseData($_GET['fromdate'],$_GET['todate']);
            $total_pages = ceil($total_rows / $no_of_records_per_page);
            $duration = $_GET['fromdate'] . " to " . $_GET['todate'];
            $offset = ($pageno - 1) * $no_of_records_per_page;
            $data=$case_model->getApiCaseData($_GET['fromdate'],$_GET['todate'],$offset,$no_of_records_per_page);
            sizeof($data)==0? $result="Data Not Found": $result="Data Found";
            echo json_encode(["status" => "true", "pageno" => "$pageno", "total_pages" => "$total_pages", "total_rows" => "$total_rows", "limit" => "$no_of_records_per_page", "result" => "$result", "dutation" => "$duration", "data" => $data]);
        }

    }
}
