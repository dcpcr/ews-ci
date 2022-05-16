<?php
$this->extend("dashboard/layout/dashboard-layout");
$this->section("graph-section");
echo $this->include('dashboard/partial/absenteeism-graph');
$this->endSection();
$this->section("page-specific-script-section");
echo $this->include('dashboard/partial/scripts/absenteeism-script');
$this->endSection();


