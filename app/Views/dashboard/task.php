<?php
$this->extend("dashboard/layout/dashboard-layout");
$this->section("graph-section");
echo $this->include('dashboard/partial/task-info-box');
$this->endSection();
$this->section("table-section-1");
echo $this->include('dashboard/partial/task-wigets');
echo $this->include('dashboard/partial/task-action-info');
$this->endSection();
$this->section("page-specific-script-section");
$this->endSection();