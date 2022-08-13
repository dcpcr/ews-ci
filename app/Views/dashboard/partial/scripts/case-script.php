<?= $this->include('dashboard/partial/scripts/data-table-script'); ?>
<script>
    let max = <?= count($response) > 0 ?
        $response[array_search('Total', array_column($response, 'gender'))]['count'] : 0?>;

    const query_data = MyUtil.get_query_data_from_url(1);
    const ajax_url = "<?= site_url('') ?>" + 'dashboard/ajax/case?' + query_data;
</script>
<script src='/assets-adminlte/ews-js/case.js'></script>