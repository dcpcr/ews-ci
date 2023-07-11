<div class="row">
    <div class="col-md-6">
        <div class="card card-widget widget">
            <div class="widget-user-header bg-gradient-gray-dark text-center">
                <h3 class="widget-user-username">BROUGHT BACK TO SCHOOL</h3>

            </div>
            <div class="card-footer p-0">
                <ul class="nav flex-column">
                    <li class="nav-item">
                        <a href="<?php echo site_url('dashboard/back_to_school_with_EWS_intervention_list'); ?>"
                           class="nav-link">
                            Children who received intervention through EWS <br>
                            <button type="button" class="btn btn-outline-danger btn-sm">
                                View Details<i class="fa fa-arrow-alt-circle-right"></i>
                            </button>
                            <span class="float-right badge bg-primary">
                                <?= $response['bts_with_intervention'] ?>
                            </span>
                            </button>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="<?php echo site_url('dashboard/back_to_school_without_EWS_intervention_list'); ?>" class="nav-link">
                            Children who returned without any EWS intervention <br>
                                <button type="button" class="btn btn-outline-danger btn-sm">View Details

                            <i class="fa fa-arrow-alt-circle-right"></i>
                            </button>
                        <span
                                class="float-right badge bg-primary"><?= $response['bts_without_intervention'] ?></span>
                        </button>
                        </a>
                    </li>
                </ul>
            </div>
        </div>

    </div>
    <div class="col-md-6">
        <div class="card card-widget widget ">
            <div class="widget-user-header bg-gradient-gray-dark text-center">
                <h3 class="widget-user-username">YET TO BE BROUGHT BACK TO SCHOOL</h3>

            </div>
            <div class="card-footer p-0">
                <ul class="nav flex-column">
                    <li class="nav-item">
                        <a href="<?php echo site_url('dashboard/children_who_are_contacted_through_SMS'); ?>" class="nav-link">
                            Children who are contacted through SMS <br>
                            <button type="button" class="btn btn-outline-danger btn-sm">View Details <i
                                        class="fa fa-arrow-alt-circle-right"></i></button>
                            <span class="float-right badge bg-info"><?= $response['yet_to_be_brought_back_to_school_via_sms_count'] ?></span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="<?php echo site_url('dashboard/children_who_are_contacted_through_calls'); ?>" class="nav-link">
                            Children who are contacted though calls <br>
                            <button type="button" class="btn btn-outline-danger btn-sm">View Details <i
                                        class="fa fa-arrow-alt-circle-right"></i></button>
                            <span class="float-right badge bg-info"><?= $response['yet_to_be_brought_back_to_school_via_call_count'] ?></span>
                        </a>
                    </li>
                </ul>
            </div>
        </div>

    </div>


</div>