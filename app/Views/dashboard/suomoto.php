<?php
$this->extend("layout/dashboard-layout");
$this->section("graph-section");
echo $this->include('dashboard/partial/suomoto-graph');
$this->endSection();
$this->section("table-section");
echo $this->include('dashboard/partial/suomoto-table');
$this->endSection();
$this->section("page-specific-script-section");
echo $this->include('dashboard/partial/scripts/suomoto-script');
$this->endSection();


