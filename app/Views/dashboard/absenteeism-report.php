<?php
$this->extend("dashboard/layout/dashboard-layout");
$this->section("graph-section");
echo $this->include('dashboard/partial/absenteeism-report-table-partial-view');
$this->endSection();
$this->section("page-specific-script-section");
//echo $this->include('dashboard/partial/scripts/absenteeism-script');
$this->endSection();