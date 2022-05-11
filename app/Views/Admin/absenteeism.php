
<?php
    $this->extend("layout/master-layout");
    $this->section("filters-section");
    echo $this->include('admin/partial/filters');
    $this->endSection();
    $this->section("report-section");
    echo $this->include('admin/partial/absenteeism-report');
    $this->endSection();
    $this->section("page-specific-script-section");
    echo $this->include('admin/partial/page-graph-table-script/absenteeism-graph-script');
    $this->endSection();
?>


