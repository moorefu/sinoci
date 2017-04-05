<?php

namespace App\Traits\Query;

trait GetList
{

    public function scopeGetList($query, $perPage = null)
    {
        $query->orderBy($this->primaryKey, 'desc');

        return $perPage ? $query->paginate($perPage) : $query->get();
    }

}
