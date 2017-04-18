<?php

namespace App\Tables;

use App\Services\Table;
use App\Widgets\Query\GetList;

class Login extends Table
{

    use GetList;

    protected function schema()
    {
        $this->schema = [
            'phone' => [
                'type' => 'string'
            ],
            'password' => [
                'type' => 'string'
            ]
        ];

        return parent::schema();
    }

    public function user()
    {
        return $this->hasOne(User::class, 'login_id');
    }

}