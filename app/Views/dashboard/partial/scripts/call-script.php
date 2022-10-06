<?= $this->include('dashboard/partial/scripts/data-table-script');
$lable = $data = '';
foreach ($response as $row) {
    $lable .= "'" . $row['name'] . "-" . $row['count'] . "',";
    $data .= "'" . $row['count'] . "',";
}
$lable = substr_replace($lable, "", -1) . "";
$data = substr_replace($data, "", -1) . "";
?>
<script>
    const lablevalue = [<?=$lable?>];
    const labledata = [<?=$data?>];
</script>
<script src='/assets-adminlte/ews-js/call.js'></script>
