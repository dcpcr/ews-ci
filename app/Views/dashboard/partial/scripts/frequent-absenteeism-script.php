<?= $this->include('dashboard/partial/scripts/data-table-script'); ?>
<?php
$counter = 0;
$table_data = [];
foreach ($response['frequent_detected_cases'] as $case) {
    $table_data [] = [
        "serial_no" => ++$counter,
        "student_id" => $case['student_id'],
        "student_name" => $case['student_name'],
        "mobile" => $case['mobile'],
        "class" => $case['class'],
        "section" => $case['section'],
        "gender" => $case['gender'],
        "address" => $case['address'],
        "detected_count" => $case['detected_count'],
    ];
}
?>
<script>
    const frequentAbsenteeismData = <?=json_encode($table_data)?>;
</script>
<script src='/assets-adminlte/ews-js/frequent-absenteeism.js'></script>