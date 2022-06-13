<?= $this->include('dashboard/partial/scripts/data-table-script'); ?>
<script>
    let max = <?=count($response)?>;
    const casedata = <?=json_encode($response)?>;
</script>
<script src='/assets-adminlte/ews-js/case.js'></script>