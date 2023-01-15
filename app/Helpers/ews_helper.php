<?php

use App\Models\SchoolModel;

function get_school_ids(): array
{
    $school_model = new SchoolModel();
    return $school_model->select('id')->orderBy('id')->findAll();
}

function remove_slash_from_string($string)
{
    return preg_replace('/\//', '', rtrim(rtrim($string, "/ "), "\\\\")); // Replaces all "\" with space.
}
