<?php
$this->extend("dashboard/layout/dashboard-layout");
$this->section("graph-section");
echo $this->include('dashboard/partial/online-attendance-graph');
$this->endSection();
$this->section("table-section-1");
echo $this->include('dashboard/partial/online-attendance-class-wise-table');
$this->endSection();
$this->section("table-section-2");
//echo $this->include('dashboard/partial/online-attendance-class-wise-graph');
echo $this->include('dashboard/partial/online-attendance-class-wise-datatable');
$this->endSection();
$this->section("page-specific-script-section");
echo $this->include('dashboard/partial/scripts/online-attendance-script');
$this->endSection();