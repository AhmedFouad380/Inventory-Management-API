<?php

namespace App\Http\Controllers\Api;

use App\Actions\TransferStockAction;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

use App\Http\Requests\StoreStockTransferRequest;

class StockTransferController extends Controller
{
    public function store(StoreStockTransferRequest $request, TransferStockAction $transferStockAction)
    {
        Gate::authorize('create', \App\Models\StockTransfer::class);

        $validated = $request->validated();

        try {
            $transfer = $transferStockAction->execute(
                $validated['from_warehouse_id'],
                $validated['to_warehouse_id'],
                $validated['inventory_item_id'],
                $validated['quantity']
            );

            return response()->json([
                'message' => 'Stock transferred successfully.',
                'data' => $transfer,
            ], 201);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 422);
        }
    }
}
