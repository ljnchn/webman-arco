<?php

namespace App\Admin\Controller;

use App\Enums\HttpCode;

class Monitor
{
    public function loginInfo()
    {
        return json([
            'code' => HttpCode::SUCCESS(),
            'msg' => 'success',
            'rows' => [],
            'total' => 0,
        ]);
    }
}