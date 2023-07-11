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
                        Suggested Action: Update contact numbers
                    </h3>
                </div>
                <div class="card-body">
                    <div class="callout callout-danger">
                        <li>Contact the student or their parents/ guardians to confirm their contact number and address</li>
                        <li>Inform your district’s concerned DDE and share updated contact numbers and addresses of students with them</li>
                        <li>Ensure the updated contact numbers and addresses of students are updated on EduDel</li>
                    </div>
                </div>
            </div>
        </div>
    </div>

<?php
echo $this->include('dashboard/partial/home-page-list');
$this->endSection();
$this->section("page-specific-script-section");
echo $this->include('dashboard/partial/scripts/wrong-mobile-number-list-script');
$this->endSection();