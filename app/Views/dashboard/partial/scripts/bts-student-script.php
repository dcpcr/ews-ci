<?php
$classLabel = $classWiseCountData = "";
foreach ($response['bts_case_class_wise_count_array'] as $row) {
    $classLabel .= "'" . $row['class'] . "',";
    $classWiseCountData .= "'" . $row['count'] . "',";
}
$btsClassLabel = substr_replace($classLabel, "", -1) . "";
$btsCaseClassWiseCountData = substr_replace($classWiseCountData, "", -1) . "";
?>
<script>
    const btsStudentGenderData = ["<?=$response['bts_case_male_count']?>", "<?=$response['bts_case_female_count']?>", "<?=$response['bts_case_transgender_count']?>"];
    const btsCaseClassLabel = [<?=$btsClassLabel?>];
    const btsCaseClassWiseCount = [<?=$btsCaseClassWiseCountData?>];
</script>
<script src='/assets-adminlte/ews-js/bts-student.js'></script>