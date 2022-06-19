<?php

namespace App\Controllers;

use App\Models\CaseModel;
use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\API\ResponseTrait;
class ApiController extends ResourceController
{
    use ResponseTrait;
    public function apiDetectedCase()
    {
        //TODO: Add Authentication for API request
        // date fromat yyyy-mm-dd
        // test url for api request => http://localhost:8080/APIcontroller/apiDetectedCase/?fromdate=2022-01-01&todate=2022-05-25&limit=1&pageno=1
        //header('Content-Type:application/json');
        if ( $_GET['fromdate'] and $_GET['todate'] !== null) {

            preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/",$_GET['fromdate'])? :die(json_encode(["message"=>"Invalid date format for request parameter fromdate."]));
            preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/",$_GET['todate'])? :die(json_encode(["message"=>"Invalid date format for request parameter todate."]));

            if(!isset($_GET['limit'])){
                $no_of_records_per_page=1000;
            }
            else{
                $_GET['limit']<=0? die(json_encode(["message"=>"Invalid limit"])):$no_of_records_per_page=$_GET['limit'];
            }
            if(!isset($_GET['pageno'])){
                $pageno=1;

            }else{
                $_GET['pageno']<=0? die(json_encode(["message"=>"Invalid page number"])):$pageno=$_GET['pageno'];
            }
            $case_model = new CaseModel();
            $total_rows=$case_model->getTotalNumberofCaseData($_GET['fromdate'],$_GET['todate']);
            $total_pages = ceil($total_rows / $no_of_records_per_page);
            $duration = $_GET['fromdate'] . " to " . $_GET['todate'];
            $offset = ($pageno - 1) * $no_of_records_per_page;
            $data=$case_model->getApiCaseData($_GET['fromdate'],$_GET['todate'],$offset,$no_of_records_per_page);
            sizeof($data)==0? $result="Data Not Found": $result="Data Found";
            $status=$this->respondCreated()->getStatusCode();
            echo json_encode(["http status code" => "$status", "pageno" => "$pageno", "total_pages" => "$total_pages", "total_rows" => "$total_rows", "limit" => "$no_of_records_per_page", "result" => "$result", "dutation" => "$duration", "data" => $data]);
        }

    }
}
