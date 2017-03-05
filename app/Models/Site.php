<?php

namespace App\Models;

use App\Services\Model;
use App\Logics\Model\Site as Logic;
use App\Traits\Welcome\Sinoci;

/**
 * 网站业务模型
 *
 * @package App\Models
 */
class Site extends Model implements Logic
{

    use Sinoci;

    public function showWelcome()
    {
        return $this->sinoci();
    }

}
