<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Api\PaymentApiController;
use App\Http\Api\BankNotificationApiController;
use App\Http\Api\ExternalNotificationApiController;
use App\Http\Api\SettlementsApiController;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


# API
Route::middleware('api.token')->prefix('v1')->group(function () {
    Route::post('/payments', [PaymentApiController::class, 'save']);
    Route::post('/bank/notifications', [BankNotificationApiController::class, 'notify']);
    Route::post('/bank/reconciliation', [BankNotificationApiController::class, 'reconciliation']);
    Route::get('/settlements/candidates', [SettlementsApiController::class, 'settlementsCandidates']);
});


Route::prefix('v1/external')->group(function () {
    Route::post('/notifications', [ExternalNotificationApiController::class, 'notifyExternal'])->name("external.notify");
});
