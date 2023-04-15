<?php


function fetch_schools_from_edutel(bool $retry = true)
{
    $url = "https://www.edudel.nic.in/mis/eis/frmSchoolList.aspx?type=8v6AC39/z0ySjVIkvfDJzvxkdDvmSsz7pgALKMjL3UI=";
    $html = get_curl_response($url);

    if ($html) {
        log_message("info", "School scraping success, url - " . $url);
        $dom = new DOMDocument();
        $dom->strictErrorChecking = false;
        $internalErrors = libxml_use_internal_errors(true);
        $dom->loadHtml($html);
        libxml_use_internal_errors($internalErrors);

        $finder = new DomXPath($dom);
        $classname = "MISDataGridBody";
        $table_nodes = $finder->query("//*[contains(concat(' ', normalize-space(@class), ' '), ' $classname ')]");
        return get_array_from_dom_table($table_nodes[0], true);
    } else {
        log_message("error", "The School scraping call failed, url - " . $url);
        if ($retry) {
            log_message("notice", "Retrying the School scraping Call - " . $url);
            sleep(1);
            return fetch_schools_from_edutel(false);
        } else {
            return null;
        }
    }
}

function fetch_student_data_for_school_from_edutel($school_id)
{
    $url = "https://www.edudel.nic.in/mis/EduWebService_Other/Smc_MITTRA.asmx/Student_Schoolwise_details_Json?schid=$school_id";
    return fetch_api_response($url);
}

function fetch_student_data_for_school_from_json_file($school_id)
{
    $file_path = FCPATH . "/data-files/$school_id.json";
    if(file_exists($file_path)){
        $json_string = file_get_contents($file_path);
        $decoded_json = json_decode($json_string, true);
        if (!empty($decoded_json)) {
            log_message("info", "Student Data fetched for school id: $school_id");
            return $decoded_json['Cargo'];
        } else {
            log_message("notice", "No student data for school id: $school_id in json file.");
            return 0;
        }
    }

}

function fetch_school_leaving_certificate_data_from_edudel()
{
    $url = getenv('edudel.slc');
    return fetch_api_response($url, true, false);
}

function fetch_name_struck_out_data_from_edudel()
{
    $url = getenv('edudel.nso');
    return fetch_api_response($url, true, false);
}

function fetch_attendance_from_edutel($date, $school_id)
{
    $url = "https://www.edudel.nic.in/mis/EduWebService_Other/Smc_MITTRA.asmx/Student_Attendence_School?School_ID=$school_id&Date=$date";
    return fetch_api_response($url);
}

function fetch_attendance_in_json_file_from_edudel($school_id)
{
    $url = "https://www.edudel.nic.in/mis/EduWebService_Other/Smc_MITTRA.asmx/Student_Schoolwise_details_Json?schid=$school_id";
    return dump_in_json_file($url, $school_id);
}

function dump_in_json_file($url, $file_name)
{
    $path = FCPATH;
    $command = 'curl --location ' . "'$url&password=Ukr@7520' > $path/data-files/$file_name.json";
    sleep("0.5");
    return exec("$command");
}

function fetch_api_response($url, bool $retry = true, $password = true)
{
    helper("general");
    //Todo: extract password to a config file
    if ($password) {
        $url .= "&password=Ukr@7520";
    }
    $response = get_curl_response($url);
    if ($response) {
        log_message("info", "API call success, url - " . $url);
        $decoded_json = json_decode($response, true);
        return $decoded_json['Cargo'];
    } else {
        log_message("error", "The API call failed, url - " . $url);
        if ($retry) {
            log_message("info", "Retrying the API Call - " . $url);
            sleep(1);
            return fetch_api_response($url, false);
        } else {
            return null;
        }
    }
}
