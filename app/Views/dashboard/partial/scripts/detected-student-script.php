<?php
$classLabel = $classWiseCountData = "";
foreach ($response['detected_case_class_wise_count_array'] as $row) {
    $classLabel .= "'" . $row['class'] . "',";
    $classWiseCountData .= "'" . $row['count'] . "',";
}
$classLabel = substr_replace($classLabel, "", -1) . "";
$classWiseCountData = substr_replace($classWiseCountData, "", -1) . "";
?>
<script>
    const detectedStudentGenderData = ["<?=$response['detected_case_male_count']?>", "<?=$response['detected_case_female_count']?>", "<?=$response['detected_case_transgender_count']?>"];
    const detectedCaseClassLabel = [<?=$classLabel?>];
    const detectedCaseClassWiseCount = [<?=$classWiseCountData?>];
</script>
<script src='/assets-adminlte/ews-js/detected-student.js'></script>