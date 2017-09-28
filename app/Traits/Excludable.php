<?php

namespace App\Traits;

use Illuminate\Support\Facades\DB;

trait Excludable
{
    /**
     * Exclude column from query.
     *
     * @param [type] $query   [description]
     * @param [type] $columns [description]
     * @return [type]          [description]
     */
    public function scopeExclude($query, $columns)
    {
        $tableColumns = $this->getConnection()->getSchemaBuilder()->getColumnListing($this->getTable());
        $tableColumns = array_diff($tableColumns, (array) $columns);

        // Modify location query to work with geo spatial
        if (($index = array_search('location', $tableColumns)) !== false) {
            $tableColumns[$index] =  DB::raw('astext(location) as location');
        }

        return $query->select($tableColumns);
    }
}
