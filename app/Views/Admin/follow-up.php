<?php
    $this->extend("layout/master-layout");
    $this->section("filters-section");
    echo $this->include('admin/partial/filters');
    $this->endSection();
    $this->section("report-section");
    echo $this->include('admin/partial/follow-up-report');
    echo $this->include('admin/partial/follow-up-report-table');
    $this->endSection();
    $this->section("footer-section");
    echo $this->include('admin/partial/footer');
    $this->endSection();
    $this->section("add-js-file-section");
    echo $this->include('admin/partial/page-graph-table-script/follow-graph-table-script');
    $this->endSection();
?>


