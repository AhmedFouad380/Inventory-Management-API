<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\WarehouseResource;
use App\Models\Warehouse;
use Illuminate\Support\Facades\Cache;

class WarehouseController extends Controller
{
    public function inventory($id)
    {
        $warehouse = Cache::remember("warehouse_inventory_{$id}", 3600, function () use ($id) {
            return Warehouse::with('stocks.inventoryItem')->find($id);
        });

        if (!$warehouse) {
            return response()->json([
                'error' => 'Warehouse not found.',
                'message' => "No warehouse found with ID: {$id}"
            ], 404);
        }

        return new WarehouseResource($warehouse);
    }
}
