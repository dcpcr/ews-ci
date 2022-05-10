
<?php
    $this->extend("layout/master-layout");
    $this->section("filters-section");
    echo $this->include('admin/partial/filters');
    $this->endSection();
    $this->section("report-section");
    echo "Nothing to show here!</br> We are working on it";
    $this->endSection();
    $this->section("footer-section");
    echo $this->include('admin/partial/footer');
    $this->endSection();
?>


