<?php

namespace App\Http\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ExternalNotificationRequest;
use Illuminate\Http\JsonResponse;


class ExternalNotificationApiController extends Controller
{
    /**
     * Notificación a servicio externo
     */

    public function notifyExternal(ExternalNotificationRequest $request): JsonResponse
    {
        try {
            /**
             * Simular una notificación a un servicio externo cuando una operación queda PAID
             */

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