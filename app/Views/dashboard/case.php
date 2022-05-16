<?php
$this->extend("layout/dashboard-layout");
$this->section("graph-section");
echo $this->include('dashboard/partial/case-graph');
$this->endSection();
$this->section("table-section");
echo $this->include('dashboard/partial/case-table');
$this->endSection();
$this->section("page-specific-script-section");
echo $this->include('dashboard/partial/scripts/case-script');
$this->endSection();
