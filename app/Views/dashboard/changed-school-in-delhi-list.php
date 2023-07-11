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
                        <li>Contact the student's parents/ guardians to confirm</li>
                        <li>Issue transfer certificate/school leaving certificate</li>
                        <li>Ensure parent/guardian has received the student's original marksheets, certificates and other relevant documents</li>
                        <li>Update the attendance register (offline and online)</li>
                    </div>
                </div>
            </div>
        </div>
    </div>

<?php

echo $this->include('dashboard/partial/home-page-list');
$this->endSection();
$this->section("page-specific-script-section");
echo $this->include('dashboard/partial/scripts/changed-school-in-delhi-list-script');
$this->endSection();