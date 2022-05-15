<?php

namespace App\Database\Seeds;

use App\Models\DistrictModel;
use App\Models\SchoolMappingModel;
use App\Models\SchoolModel;
use App\Models\ZoneModel;
use CodeIgniter\Database\Seeder;

class SchoolMappingSeeder extends Seeder
{
    public function run()
    {
        $school_model = new SchoolModel();
        $district_model = new DistrictModel();
        $zone_model = new ZoneModel();
        $school_mapping_model = new SchoolMappingModel();


        $rows = array(
            array('id' => '10', 'name' => 'East'),
            array('id' => '11', 'name' => 'North East'),
            array('id' => '12', 'name' => 'North'),
            array('id' => '13', 'name' => 'North West A'),
            array('id' => '14', 'name' => 'North West B'),
            array('id' => '15', 'name' => 'West A'),
            array('id' => '16', 'name' => 'West B'),
            array('id' => '17', 'name' => 'South West A'),
            array('id' => '18', 'name' => 'South West B'),
            array('id' => '19', 'name' => 'South'),
            array('id' => '20', 'name' => 'New Delhi'),
            array('id' => '21', 'name' => 'Central'),
            array('id' => '22', 'name' => 'South East')
        );
        foreach ($rows as $row) {
            $district = $district_model->where('name', $row['name'])->first();
            if (empty($district)) {
                $district_model->insert($row);
            }
        }

        $rows = array(
            array('id' => '1001', 'name' => 'zone01'),
            array('id' => '1002', 'name' => 'zone02'),
            array('id' => '1003', 'name' => 'zone03'),
            array('id' => '1104', 'name' => 'zone04'),
            array('id' => '1105', 'name' => 'zone05'),
            array('id' => '1106', 'name' => 'zone06'),
            array('id' => '1207', 'name' => 'zone07'),
            array('id' => '1208', 'name' => 'zone08'),
            array('id' => '1309', 'name' => 'zone09'),
            array('id' => '1310', 'name' => 'zone10'),
            array('id' => '1411', 'name' => 'zone11'),
            array('id' => '1412', 'name' => 'zone12'),
            array('id' => '1413', 'name' => 'zone13'),
            array('id' => '1514', 'name' => 'zone14'),
            array('id' => '1515', 'name' => 'zone15'),
            array('id' => '1516', 'name' => 'zone16'),
            array('id' => '1617', 'name' => 'zone17'),
            array('id' => '1618', 'name' => 'zone18'),
            array('id' => '1719', 'name' => 'zone19'),
            array('id' => '1720', 'name' => 'zone20'),
            array('id' => '1821', 'name' => 'zone21'),
            array('id' => '1822', 'name' => 'zone22'),
            array('id' => '1923', 'name' => 'zone23'),
            array('id' => '1924', 'name' => 'zone24'),
            array('id' => '2026', 'name' => 'zone26'),
            array('id' => '2127', 'name' => 'zone27'),
            array('id' => '2128', 'name' => 'zone28'),
            array('id' => '2225', 'name' => 'zone25'),
            array('id' => '2229', 'name' => 'zone29')
        );
        foreach ($rows as $row) {
            $zone = $zone_model->where('name', $row['name'])->first();
            if (empty($zone)) {
                $zone_model->insert($row);
            }
        }

        $schools = $school_model->findAll();
        foreach ($schools as $school) {
            $district_name = $school['district'];
            $district = $district_model->where('name', $district_name)->first();
            if (!empty($district)) {
                $zone_id = $district['id'] . sprintf("%'.02d", $school['zone']);
                $zone = $zone_model->where('id', $zone_id)->first();
                if (!empty($zone)) {
                    $school_mapping = $school_mapping_model->where('school_id', $school['id'])->first();
                    if (empty($school_mapping)) {
                        $data = array('school_id' => $school['id'], 'district_id' => $district['id'], 'zone_id' => $zone_id);
                        $school_mapping_model->insert($data);
                    } else {
                        $data = array('district_id' => $district['id'], 'zone_id' => $zone_id);
                        $school_mapping_model->update($school['id'], $data);
                    }
                }
            }
        }
    }
}
