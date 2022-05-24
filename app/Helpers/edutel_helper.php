<?php


function fetch_schools_from_edutel(): array
{
    $url = "https://www.edudel.nic.in/mis/eis/frmSchoolList.aspx?type=8v6AC39/z0ySjVIkvfDJzvxkdDvmSsz7pgALKMjL3UI=";
    $html = get_curl_response($url);

    $dom = new DOMDocument();
    $dom->strictErrorChecking = false;
    $internalErrors = libxml_use_internal_errors(true);
    $dom->loadHtml($html);
    libxml_use_internal_errors($internalErrors);

    $finder = new DomXPath($dom);
    $classname = "MISDataGridBody";
    $table_nodes = $finder->query("//*[contains(concat(' ', normalize-space(@class), ' '), ' $classname ')]");
    return get_array_from_dom_table($table_nodes[0], true);
}

function fetch_student_data_for_school_from_edutel($school_id)
{
    $url = "https://www.edudel.nic.in/mis/EduWebService_Other/Smc_MITTRA.asmx/Student_Schoolwise_details_Json?schid=$school_id&password=Ukr@7520";
    return fetch_api_response($url);
}

function fetch_attendance_from_edutel($date, $school_id)
{
    $url = "https://www.edudel.nic.in/mis/EduWebService_Other/Smc_MITTRA.asmx/Student_Attendence_School?School_ID=$school_id&Date=$date";
    return fetch_api_response($url);
}

function fetch_api_response($url)
{
    //Todo: extract password to a config file
    $url .= "&password=Ukr@7520";
    $response = get_curl_response($url);
    if ($response) {
        $decoded_json = json_decode($response, true);
        return $decoded_json['Cargo'];
    } else {
        log_message("error", "The API call failed " . $url);
        return null;
    }

}
