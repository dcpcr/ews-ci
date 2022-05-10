
<?php
    $this->extend("layout/master-layout");
    $this->section("filters-section");
    echo $this->include('admin/partial/filters');
    $this->endSection();
    $this->section("report-section");
    echo $this->include('admin/partial/student-report');
    $this->endSection();
?>


