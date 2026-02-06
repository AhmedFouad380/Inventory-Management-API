<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\InventoryController;
use App\Http\Controllers\Api\StockTransferController;
use App\Http\Controllers\Api\WarehouseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:api');

Route::post('/login', [AuthController::class, 'login']);

Route::get('/inventory', [InventoryController::class, 'index']);
Route::get('/warehouses/{id}/inventory', [WarehouseController::class, 'inventory']);

Route::middleware('auth:api')->group(function () {
    Route::post('/stock-transfers', [StockTransferController::class, 'store']);
});
