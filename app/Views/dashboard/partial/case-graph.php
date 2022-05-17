<div class="row">
    <div class="col-12">
        <!-- Custom Tabs -->
        <div class="card">
            <div class="card-header d-flex p-0">
                <h4 class="p-3">Summary</h4>
            </div><!-- /.card-header -->
            <div class="card-body">
                <div class="card-body">
                    <div class="row">
                        <div class="col-6 col-md-3 text-center">
                            <div class="knob-label"><strong>Total No. of Detected Cases</strong></div>

                            <?php
                            echo '<input disabled type="text" class="knob" value="'.count($data).'" data-skin="tron"
                                   data-thickness="0.2" data-width="100"
                                   data-height="100" data-fgColor="#efb155" data-readonly="true">';
                            ?>

                            <div class="knob-label">Male---------10(8%)</div>
                            <div class="knob-label">Female-------55(90%)</div>
                            <div class="knob-label">Transgender--5(2%)</div>
                        </div>
                        <div class="col-6 col-md-3 text-center">
                            <div class="knob-label"><strong>Suo Moto Cases</strong></div>
                            <input disabled type="text" class="knob" value="80" data-skin="tron"
                                   data-thickness="0.2" data-width="100"
                                   data-height="100" data-fgColor="#cd4949" data-readonly="true">

                            <div class="knob-label text-center">Male---------10(8%)</div>
                            <div class="knob-label text-center">Female-------55(90%)</div>
                            <div class="knob-label text-center">Transgender--5(2%)</div>
                        </div>
                        <div class="col-6 col-md-3 text-center">
                            <div class="knob-label"><strong>Back to School</strong></div>
                            <input disabled type="text" class="knob" value="80" data-skin="tron"
                                   data-thickness="0.2" data-width="100"
                                   data-height="100" data-fgColor="#28a745" data-readonly="true">

                            <div class="knob-label">Male---------10(8%)</div>
                            <div class="knob-label">Female-------55(90%)</div>
                            <div class="knob-label">Transgender--5(2%)</div>
                        </div>
                        <div class="col-6 col-md-3 text-center">
                            <div class="knob-label"><strong>Pending / Untraceable Cases</strong></div>
                            <input disabled type="text" class="knob" value="80" data-skin="tron"
                                   data-thickness="0.2" data-width="100"
                                   data-height="100" data-fgColor="#6d726e" data-readonly="true">

                            <div class="knob-label">Male---------10(8%)</div>
                            <div class="knob-label">Female-------55(90%)</div>
                            <div class="knob-label">Transgender--5(2%)</div>
                        </div>

                    </div>
                    <!-- ./col -->
                </div>
            </div>
        </div><!-- /.card-body -->
    </div>
    <!-- ./card -->
</div>
<!-- /.col -->
</div>
<!-- /.row -->
		