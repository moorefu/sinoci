<?php

namespace App\Models;

use App\Services\Model;
use App\Traits\Welcome\Sinoci;

/**
 * 网站业务模型
 *
 * @package App\Models
 */
class Site extends Model
{

    use Sinoci;

    public function showWelcome()
    {
        return $this->sinoci();
    }

}
