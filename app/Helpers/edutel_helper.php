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

function fetch_attendance_from_edutel($date, $school_id)
{
    $url = "https://www.edudel.nic.in/mis/EduWebService_Other/Smc_MITTRA.asmx/Student_Attendence_School?School_ID=$school_id&Date=$date";
    return fetch_api_response($url);
}

function fetch_api_response($url, bool $retry = true)
{
    //Todo: extract password to a config file
    $url .= "&password=Ukr@7520";
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
