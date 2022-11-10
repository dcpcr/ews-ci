<div class="row">
    <div class="col-lg-12 col-12">
        <div>
            <div class="inner">
                <h4>Total Detected Cases: <span class="number"><?= $response['total_detected_case_count'] ?></span>
                </h4>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-lg-4 col-6">
        <div class="small-box bg-success text-center">
            <div class="inner">
                <h4><span class="number"><?= $response['total_bts_case_count'] ?></span></h4>
                <p class="mt-4">Back To School
                    <span class="fa fa-info-circle" data-toggle="tooltip"
                          data-placement="right"
                          title="Closely monitor these students to ensure that they do not have high frequency of absence again.">

                    </span>
                </p>
            </div>
        </div>
    </div>
    <div class="col-lg-4 col-6">
        <div class="small-box bg-warning text-center">
            <div class="inner">
                <h4><span class="number"><?= $response['enrolled_and_in_contact_to_bring_them_back_to'] ?></span></h4>
                <p class="mt-0"> Enrolled and in contact to </br>bring them back to school
                    <span class="fa fa-info-circle" data-toggle="tooltip"
                          data-placement="right"
                          title="Seek support from DCPCR to resolve issues restricting the student from coming to school and reach out to the parents/guardians of these students to bring them back to school."
                    </span>
                </p>
            </div>
        </div>
    </div>

    <div class="col-lg-4 col-6">
        <div class="small-box bg-danger text-center">
            <div class="inner">
                <h4><span class="number"><?= $response['home_visit_count'] ?></span></h4>
                <p class="mt-4">Contact not established with DCPCR
                    <span class="fa fa-info-circle" data-toggle="tooltip"
                          data-placement="right"
                          title="Update the phone number and/or home address of these students to assist DCPCR in reaching out to these students."
                    </span>
                </p>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-4 col-6">
        <div class="small-box bg-success text-center">
            <div class="inner">
                <h4><span class="number"><?= $response['total_moved_out_of_village_count'] ?></span></h4>
                <p class="mt-4">Moved out of Delhi/Changed school
                    <span class="fa fa-info-circle" data-toggle="tooltip"
                          data-placement="right"
                          title="Ensure that these students have received their transfer certificate/school leaving certificate.">

                    </span>
                </p>
            </div>
        </div>
    </div>
    <div class="col-lg-4 col-6">
        <div class="small-box bg-warning text-center">
            <div class="inner">
                <h4><span class="number"><?= $response['dropped_out_and_in_contact_to_bring_them_school'] ?></span>
                </h4>

                <p class="mt-0"> Dropped out and in contact to </br>bring them back to school
                    <span class="fa fa-info-circle" data-toggle="tooltip"
                          data-placement="right"
                          title="Reach out to the parents/guardians of these students to get them re-admitted to the school."
                          ;
                    </span>
                </p>
            </div>
        </div>
    </div>

    <div class="col-lg-4 col-6">
        <div class="small-box bg-danger">
            <div class="inner text-center">
                <h4><span class="number"><?= $response['yet_to_be_contacted_cases'] ?></span></h4>
                <p class="mt-4"> Yet to be contacted
                    <span class="fa fa-info-circle" data-toggle="tooltip"
                          data-placement="right"
                          title="These students are yet to be contacted by DCPCR.">

                    </span>
                </p>
            </div>
        </div>
    </div>
</div>