<?php
$this->extend("dashboard/layout/dashboard-layout");
$this->section("table-section-1");
echo $this->include('dashboard/partial/home-page-list');
$this->endSection();
$this->section("page-specific-script-section");
echo $this->include('dashboard/partial/scripts/detected-student-list-script');
$this->endSection();