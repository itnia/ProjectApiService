<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function responseSuccess($msg = '')
    {
        return json_encode([
            'success' => $msg,
        ]);
    }

    public function responseErrors($errors = '')
    {
        return json_encode([
            'errors' => $errors,
        ]);
    }
}
