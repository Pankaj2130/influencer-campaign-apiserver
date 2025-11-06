<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\CampaignController;
use App\Http\Controllers\Api\InfluencerController;
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

Route::prefix('v1')->group(function () {
    Route::get('campaigns', [CampaignController::class, 'index']);
    Route::post('campaigns', [CampaignController::class, 'store']);
    Route::post('campaigns/{id}/assign', [CampaignController::class, 'assign']);


    Route::get('influencers', [InfluencerController::class, 'index']);
    Route::get('campaigns/{id}', [CampaignController::class, 'show']);

});

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
