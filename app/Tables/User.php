<?php

namespace App\Tables;

use App\Services\Table;

class User extends Table
{

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