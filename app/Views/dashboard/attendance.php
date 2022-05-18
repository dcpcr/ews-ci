<?php
$this->extend("dashboard/layout/dashboard-layout");
$this->section("graph-section");
$this->endSection();
$this->section("table-section");
echo $this->include('dashboard/partial/attendance-table');
$this->endSection();
$this->section("page-specific-script-section");
echo $this->include('dashboard/partial/scripts/data-table-script');
$this->endSection();


