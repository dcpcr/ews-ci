<?php
$this->extend("layout/master-layout");
$this->section("report-section");
echo $this->include('admin/partial/case-report');
$this->endSection();
$this->section("page-specific-script-section");
echo $this->include('admin/partial/page-graph-table-script/data-table-script');
$this->endSection();
