<?php

namespace App\Models;

use CodeIgniter\Model;

abstract class CaseDetailsModel extends Model
{

    abstract protected function getKeys(): array;

    abstract protected function getKeyMappings(): array;

    public function getTableName(): string
    {
        return $this->table;
    }

    /**
     * @throws \ReflectionException
     */
    public function updateCaseDetails(array $cases, bool $only_insert = false)
    {
        helper('cyfuture');
        if ($cases) {
            $keys = $this->getKeys();
            $key_mappings = $this->getKeyMappings();
            insert_update_case_details($cases, $keys, $key_mappings, $this, $only_insert);
        }
    }
}
