<?php

namespace App\Tables;

use App\Services\Table;
use App\Widgets\Query\GetList;

class User extends Table
{

    use GetList;

    protected function schema()
    {
        $this->schema = [
            'username' => [
                'type' => 'string'
            ],
            'login_id' => [
                'type' => 'integer'
            ]
        ];

        return parent::schema();
    }

    public function login()
    {
        return $this->belongsTo(Login::class, 'login_id');
    }

}