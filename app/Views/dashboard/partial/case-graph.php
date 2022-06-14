<?php
$male_count = $female_count = $transgender_count = 0;
if (count($response) > 0) {
    foreach ($response as $row) {

        if ($row['gender'] == 'Male') {
            $male_count++;
        }
        if ($row['gender'] == 'Female') {
            $female_count++;
        }
        if ($row['gender'] == "Transgender") {
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
            <div class="card-body px-5 py-5">

                <div class="row text-center">
                    <div class="col mx-1">
                        <div class="knob-label pb-3"><strong>Detected Cases</strong>
                            <span class="fa fa-info-circle" data-toggle="tooltip" data-placement="right"
                                  title="Students who have been absent for 7 consecutive days OR more than 66.67% days in a month (i.e. 20/30 days)"></span>
                        </div>
                        <input disabled type="text" class="knob" id="total" value="<?= count($response) ?>"
                               data-skin="tron"
                               data-thickness="0.2" data-width="100"
                               data-height="100" data-fgColor="#efb155" data-readonly="true">

                        <div class="knob-label">Male <?= $male_count ?>
                            (<?= (count($response) > 0) ? "" . floor($male_count / count($response) * 100) . "" : "0"; ?>
                            %)
                        </div>
                        <div class="knob-label">Female <?= $female_count ?>
                            (<?= (count($response) > 0) ? "" . floor($female_count / count($response) * 100) . "" : "0"; ?>
                            %)
                        </div>
                        <div class="knob-label">Transgender <?= $transgender_count ?>
                            (<?= (count($response) > 0) ? "" . floor($transgender_count / count($response) * 100) . "" : "0"; ?>
                            %)
                        </div>
                    </div>
                    <div class="col mx-1">
                        <div class="knob-label pb-3"><strong>High Risk</strong>
                            <span class="fa fa-info-circle" data-toggle="tooltip" data-placement="right"
                                  title="Students in need of urgent intervention, typically includes cases- Parental death, Parental Incarceration, Parent(s)' or Student's severe sickness or disability , Child labour, Child marriage, Children victim of sexual violence, Substance abuse, Children in conflict with law or any other such adversity for children not listed here.
"></span>
                        </div>
                        <input disabled id="highrisk" type="text" class="knob" value="0" data-skin="tron"
                               data-thickness="0.2" data-width="100"
                               data-height="100" data-fgColor="#cd4949" data-readonly="true">

                        <div class="knob-label text-center">Male 0(0%)</div>
                        <div class="knob-label text-center">Female 0(0%)</div>
                        <div class="knob-label text-center">Transgender 0(0%)</div>
                    </div>
                    <div class="col mx-1">
                        <div class="knob-label pb-3"><strong>Back to School</strong> <span class="fa fa-info-circle"
                                                                                           data-toggle="tooltip"
                                                                                           data-placement="right"
                                                                                           title="When a high risk student re-joins the school (attends the school at least a day from the long absence)."></span>
                        </div>
                        <input disabled type="text" id="bts" class="knob" value="0" data-skin="tron"
                               data-thickness="0.2" data-width="100"
                               data-height="100" data-fgColor="#28a745" data-readonly="true">

                        <div class="knob-label">Male 0(0%)</div>
                        <div class="knob-label">Female 0(0%)</div>
                        <div class="knob-label">Transgender 0(0%)</div>
                    </div>
                    <div class="col mx-1">
                        <div class="knob-label pb-3"><strong>Untraceable</strong> <span class="fa fa-info-circle"
                                                                                        data-toggle="tooltip"
                                                                                        data-placement="right"
                                                                                        title="Cases wherein no contact could be established throughout (phone calls and home-visit)."></span>
                        </div>
                        <input disabled type="text" id="untraceable" class="knob" value="0" data-skin="tron"
                               data-thickness="0.2" data-width="100"
                               data-height="100" data-fgColor="#6d726e" data-readonly="true">

                        <div class="knob-label">Male 0(0%)</div>
                        <div class="knob-label">Female 0(0%)</div>
                        <div class="knob-label">Transgender 0(0%)</div>
                    </div>
                    <div class="col mx-1">
                        <div class="knob-label pb-3"><strong>Yet to be contacted</strong> <span
                                    class="fa fa-info-circle" data-toggle="tooltip" data-placement="right"
                                    title="Fresh cases or the cases wherein the first call is yet to be attempted."></span>
                        </div>
                        <input disabled type="text" id="fresh" class="knob" value="0" data-skin="tron"
                               data-thickness="0.2" data-width="100"
                               data-height="100" data-fgColor="#6d726e" data-readonly="true">

                        <div class="knob-label">Male 0(0%)</div>
                        <div class="knob-label">Female 0(0%)</div>
                        <div class="knob-label">Transgender 0(0%)</div>
                    </div>
                </div>
            </div>
        </div>