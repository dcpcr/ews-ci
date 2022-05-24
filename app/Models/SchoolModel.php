<?php

namespace App\Models;

use CodeIgniter\Model;

class SchoolModel extends Model
{
    protected $DBGroup = 'default';
    protected $table = 'school';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = false;

    protected $returnType = 'array';
    protected $allowedFields = ['id', 'name'];

    //TODO: add created and updated fields to the school table

    public function updateSchools($file_name)
    {
        $response_array = fetch_schools_from_edutel();
        if ($response_array) {
            dump_array_in_file($response_array, $file_name, false);
            import_data_into_db($file_name, $this->table); //TODO: This can possibly just iterate and update the model since the table length is not much
        }
    }

}
