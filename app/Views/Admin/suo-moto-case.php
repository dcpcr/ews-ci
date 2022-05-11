
<?php

    $this->extend("layout/master-layout");
    $this->section("report-section");
    echo $this->include('admin/partial/suomoto-chart-data');
    echo $this->include('admin/partial/suomoto-table');
    $this->endSection();
    $this->section("page-specific-script-section");
    echo $this->include('admin/partial/page-graph-table-script/suo-moto-case-graph-script.php');
    $this->endSection();
?>


