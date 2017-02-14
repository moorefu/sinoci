<?php

namespace App\Traits\Welcome;

trait Sinoci
{

    use CodeIgniter;

    private function sinoci()
    {
        // 修正信息
        return strtr($this->codeIgniter(), [
            ' to CodeIgniter!' => '',
            '40px' => '40px 10px',
            'application/controllers/Welcome.php' => 'controllers/' . APP_ENV . '/Welcome.php',
            'application/views/welcome_message.php' => 'app/Models/Guide.php',
            'CodeIgniter' => '<a href="//github.com/sinoci/sinoci" target="_blank"><strong>sinoci</strong></a>',
            'container"' => 'container" style="max-width:600px;margin:10px auto"',
            'user_guide/' => '//github.com/sinoci/sinoci/wiki" target="_blank',
            'Welcome to CodeIgniter<' => 'S I N O C I<',
        ]);
    }

}
