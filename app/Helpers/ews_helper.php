<?php

use App\Models\SchoolModel;

function get_school_ids(): array
{
    $school_model = new SchoolModel();
    return $school_model->select('id')->findAll();
}
