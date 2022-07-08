<?= $this->include('dashboard/partial/scripts/data-table-script'); ?>
<?php
$lable = $dataMale = $dataFemale = $dataTransgender = '';
foreach ($response['reason_male_count'] as $row) {
    $lable .= "'" . $row['reason_name'] . "',";
    $dataMale .= "'" . $row['count'] . "',";
}
$lable = substr_replace($lable, "", -1) . "";
$dataMale = substr_replace($dataMale, "", -1) . "";
foreach ($response['reason_female_count'] as $row) {
    $dataFemale .= "'" . $row['count'] . "',";
}
$dataFemale = substr_replace($dataFemale, "", -1) . "";
foreach ($response['reason_transgender_count'] as $row) {
    $dataTransgender .= "'" . $row['count'] . "',";
}
$dataTransgender = substr_replace($dataTransgender, "", -1) . "";

?>
<script>
    const reasonslable = [<?=$lable?>];
    const dataMale = [<?=$dataMale?>];
    const dataFemale = [<?=$dataFemale?>];
    const dataTransgender = [<?=$dataTransgender?>];
</script>
<script src='/assets-adminlte/ews-js/absenteeism.js'></script>
