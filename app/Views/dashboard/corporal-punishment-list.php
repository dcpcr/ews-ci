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
                        Suggested Action: Corporal punishment
                    </h3>
                </div>
                <div class="card-body">
                    <div class="callout callout-danger">
                        <li>Contact the student or their parents/ guardians to confirm the reason and take immediate action when informed about a grievance.</li>
                        <li>Encourage the practice of a “Grievance/Suggestion Box” that allows students to share their concerns and take action if required.</li>
                        <li>Closely monitor the behaviour of suspected teachers/staff and ensure that no teacher is physically or mentally harassing the students.</li>
                    </div>
                </div>
            </div>
        </div>
    </div>

<?php
echo $this->include('dashboard/partial/home-page-list');
$this->endSection();
$this->section("page-specific-script-section");
echo $this->include('dashboard/partial/scripts/corporal-punishment-list-script');
$this->endSection();