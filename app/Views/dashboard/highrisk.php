<?php
$this->extend("dashboard/layout/dashboard-layout");
$this->section("graph-section");
echo $this->include('dashboard/partial/highrisk-graph');
$this->endSection();
$this->section("table-section");
echo $this->include('dashboard/partial/highrisk-table');
$this->endSection();
$this->section("page-specific-script-section");
echo $this->include('dashboard/partial/scripts/highrisk-script');
$this->endSection();


