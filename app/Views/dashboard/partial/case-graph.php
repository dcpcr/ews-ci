<?php
$male_count=$female_count=$transgender_count=0;
if(count($case)>0){
    foreach ($case as $row){

        if($row['gender']=='Male'){
            $male_count++;
        }
        if($row['gender']=='Female'){
            $female_count++;
        }
        if($row['gender']=="Transgender"){
            $transgender_count++;
        }

    }
}

?>
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

                            <input disabled type="text" class="knob" id="case" value="<?=count($case)?>" data-skin="tron"
                                   data-thickness="0.2" data-width="100"
                                   data-height="100" data-fgColor="#efb155" data-readonly="true">'


                            <div class="knob-label">Male-----------<?=$male_count?> (<?= (count($case)>0)? "".floor($male_count/count($case)*100)."":"0";?>%)</div>
                            <div class="knob-label">Female--------<?=$female_count?>(<?= (count($case)>0)? "".floor($female_count/count($case)*100)."":"0";?>%)</div>
                            <div class="knob-label">Transgender------<?=$transgender_count?> (<?= (count($case)>0)? "".floor($transgender_count/count($case)*100)."":"0";?>%)</div>

                        </div>
                        <div class="col-6 col-md-3 text-center">
                            <div class="knob-label"><strong>Suo Moto Cases</strong></div>
                            <input disabled id="suomoto" type="text" class="knob" value="0" data-skin="tron"
                                   data-thickness="0.2" data-width="100"
                                   data-height="100" data-fgColor="#cd4949" data-readonly="true">

                            <div class="knob-label text-center">Male---------0(0%)</div>
                            <div class="knob-label text-center">Female-------0(0%)</div>
                            <div class="knob-label text-center">Transgender--0(0%)</div>
                        </div>
                        <div class="col-6 col-md-3 text-center">
                            <div class="knob-label"><strong>Back to School</strong></div>
                            <input disabled type="text" id="bts" class="knob" value="0" data-skin="tron"
                                   data-thickness="0.2" data-width="100"
                                   data-height="100" data-fgColor="#28a745" data-readonly="true">

                            <div class="knob-label">Male---------0(0%)</div>
                            <div class="knob-label">Female-------0(0%)</div>
                            <div class="knob-label">Transgender--0(0%)</div>
                        </div>
                        <div class="col-6 col-md-3 text-center">
                            <div class="knob-label"><strong>Pending / Untraceable Cases</strong></div>
                            <input disabled type="text" id="pending" class="knob" value="0" data-skin="tron"
                                   data-thickness="0.2" data-width="100"
                                   data-height="100" data-fgColor="#6d726e" data-readonly="true">

                            <div class="knob-label">Male----------0(0%)</div>
                            <div class="knob-label">Female--------0(0%)</div>
                            <div class="knob-label">Transgender---0(0%)</div>
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
		