<?php
$this->extend("dashboard/layout/dashboard-layout");
$this->section("table-section-1");
?>
    <div class="row">
        <div class="col-md-12">
            <div class="card card-default">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-bullhorn"></i>
                        Suggested Action: <?=$page_title?>
                    </h3>
                </div>
                <div class="card-body">
                    <div class="callout callout-danger">
                        <li>Contact the student or their parents/ guardians to confirm the reason and take immediate action when informed about a grievance.</li>
                        <li>Direct the class teachers to closely monitor the students involved in bullying/physical abuse and take action, if required</li>
                        <li>Provide counselling to the students involved in bullying/physical abuse. </li>
                        <li>Take early action to prevent bullying/physical abuse in the school. Eg: monitoring through CCTV.</li>
                    </div>
                </div>
            </div>
        </div>
    </div>

<?php
echo $this->include('dashboard/partial/home-page-list');
$this->endSection();
$this->section("page-specific-script-section");
echo $this->include('dashboard/partial/scripts/bullying-harassment-list-script');
$this->endSection();