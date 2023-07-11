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
                        Suggested Action: Absent due to parental death
                    </h3>
                </div>
                <div class="card-body">
                    <div class="callout callout-danger">
                        <li>Contact the student or guardian of the student, as the case may be, to offer support.</li>
                        <li>Ensure class teacher provides extra academic support to the student once the student returns to school</li>
                        <li>Support the student with counselling through student counsellors at school</li>

                    </div>
                </div>
            </div>
        </div>
    </div>

<?php
echo $this->include('dashboard/partial/home-page-list');
$this->endSection();
$this->section("page-specific-script-section");
echo $this->include('dashboard/partial/scripts/parental-death-list-script');
$this->endSection();