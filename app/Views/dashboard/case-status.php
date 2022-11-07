<?php
$this->extend("dashboard/layout/dashboard-layout");
$this->section("graph-section");
echo $this->include('dashboard/partial/case-info-box');
$this->endSection();
$this->section("graph-section");
echo $this->include('dashboard/partial/detected-student-graph');
echo $this->include('dashboard/partial/bts-student-graph');
$this->endSection();
$this->section("page-specific-script-section");
echo $this->include('dashboard/partial/scripts/detected-student-script');
echo $this->include('dashboard/partial/scripts/bts-student-script');
echo $this->include('dashboard/partial/scripts/case-status-script');
$this->endSection();
