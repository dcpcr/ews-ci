<?php
$this->extend("dashboard/layout/dashboard-layout");
$this->section("graph-section");
echo $this->include('dashboard/partial/call-graph');
$this->endSection();
$this->section("table-section-1");
echo $this->include('dashboard/partial/call-table');
$this->endSection();
$this->section("page-specific-script-section");
echo $this->include('dashboard/partial/scripts/call-script');
$this->endSection();


