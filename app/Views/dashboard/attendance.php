<?php
$this->extend("dashboard/layout/dashboard-layout");
$this->section("graph-section");
$this->endSection();
$this->section("table-section-1");
echo $this->include('dashboard/partial/attendance-marked-table');
$this->endSection();
$this->section("page-specific-script-section");
echo $this->include('dashboard/partial/scripts/attendance-script');
$this->endSection();


