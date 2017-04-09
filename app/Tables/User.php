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
            'password' => [
                'type' => 'string'
            ]
        ];

        return parent::schema();
    }

}