<?php

namespace App\Http\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;


class ExternalNotificationApiController extends Controller
{
    public function notifyExternal(Request $request)
    {

        //probabilidad de error
        /* if (rand(0, 1)) {
            return response()->json(['error' => 'fail'], 500);
        } */

        try {

            return response()->json([
                'success' => true,
                'data' => $request->all()
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'data' => $e->getMessage()
            ], 500);
        }
    }
}