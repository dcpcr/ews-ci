<?= $this->include('dashboard/partial/scripts/data-table-script'); ?>
<?php
$case_data = $response['detected_case_count'];
?>
<script>
    let max = <?= count($case_data) > 0 ?
        $case_data[array_search('Total', array_column($case_data, 'gender'))]['count'] : 0?>;

    const query_data = MyUtil.get_query_data_from_url(1);
    const ajax_url = "<?= site_url('') ?>" + 'dashboard/ajax/case?' + query_data;
</script>
<script src='/assets-adminlte/ews-js/case.js'></script>