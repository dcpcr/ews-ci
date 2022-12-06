<?php
$this->extend("dashboard/layout/dashboard-layout");
$this->section("graph-section");
echo $this->include('dashboard/partial/reason-for-absenteeism-table');
$this->endSection();
$this->section("page-specific-script-section");
echo $this->include('dashboard/partial/scripts/absenteeism-script');
$this->endSection();