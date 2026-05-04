<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Request;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    public function getDataResponseSuccess($data)
    {
        return [
            'success' => true,
            'message' => null,
            'data' => $data,
        ];
    }

    public function getDataResponseFail($message)
    {

        return [
            'success' => false,
            'message' => config('app.env') == 'local' ? $message->getMessage() : 'Error inesperado',
            'data' => null,
        ];
    }

    public function getDataResponse($success, $message, $data)
    {
        return [
            'success' => $success,
            'message' => $message,
            'data' => $data,
        ];
    }
}
