<?php

use App\Models\ClassModel;
use App\Models\SchoolModel;

function get_school_ids(): array
{
    $school_model = new SchoolModel();
    return $school_model->select('id')->orderBy('id')->findAll();
}

function get_all_classes(): array
{
    $class_model = new ClassModel();
    return $class_model->select('name')->findAll();
}