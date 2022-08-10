<?php

use App\Http\Controllers\CashController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\MembersController;
use App\Http\Controllers\OfferingController;
use App\Http\Controllers\TitheController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::prefix('members')->group(function () {
    Route::get('/', [MembersController::class, 'index']);
    Route::post('/store', [MembersController::class, 'store']);
    Route::put('/update', [MembersController::class, 'update']);
    Route::delete('/destroy', [MembersController::class, 'destroy']);
    Route::get('/show/{id}', [MembersController::class, 'show']);
    Route::get('/data-source-member', [MembersController::class, 'data_source_member']);
});

Route::prefix('tithes')->group(function () {
    Route::get('/', [TitheController::class, 'index']);
    Route::post('/store', [TitheController::class, 'store']);
    Route::put('/update', [TitheController::class, 'update']);
    Route::delete('/destroy', [TitheController::class, 'destroy']);
    Route::get('/show/{id}', [TitheController::class, 'show']);
});

Route::prefix('offerings')->group(function () {
    Route::get('/', [OfferingController::class, 'index']);
    Route::post('/store', [OfferingController::class, 'store']);
    Route::put('/update', [OfferingController::class, 'update']);
    Route::delete('/destroy', [OfferingController::class, 'destroy']);
    Route::get('/show/{id}', [OfferingController::class, 'show']);
});

Route::prefix('expenses')->group(function () {
    Route::get('/', [ExpenseController::class, 'index']);
    Route::post('/store', [ExpenseController::class, 'store']);
    Route::put('/update', [ExpenseController::class, 'update']);
    Route::delete('/destroy', [ExpenseController::class, 'destroy']);
    Route::get('/show/{id}', [ExpenseController::class, 'show']);
    Route::get('/getCash', [ExpenseController::class, 'getCash']);
});

Route::prefix('cash')->group(function () {
    Route::get('/getCash', [CashController::class, 'getCash']);
});

Route::prefix('dashboard')->group(function () {
    Route::get('/getValueCash', [DashboardController::class, 'getValueCash']);
    Route::get('/dashboardCount', [DashboardController::class, 'dashboardCount']);
});
