<?php

namespace App\Actions;

use App\Events\LowStockDetected;
use App\Models\Stock;
use App\Models\StockTransfer;
use Illuminate\Support\Facades\DB;
use Exception;

class TransferStockAction
{
    public function execute(int $fromWarehouseId, int $toWarehouseId, int $inventoryItemId, int $quantity)
    {
        return DB::transaction(function () use ($fromWarehouseId, $toWarehouseId, $inventoryItemId, $quantity) {
            // Get source stock
            $sourceStock = Stock::where('warehouse_id', $fromWarehouseId)
                ->where('inventory_item_id', $inventoryItemId)
                ->lockForUpdate()
                ->first();

            if (!$sourceStock || $sourceStock->quantity < $quantity) {
                throw new Exception('Insufficient stock in source warehouse.');
            }

            // Deduct from source
            $sourceStock->decrement('quantity', $quantity);

            // Add to destination
            $destStock = Stock::firstOrCreate(
                ['warehouse_id' => $toWarehouseId, 'inventory_item_id' => $inventoryItemId],
                ['quantity' => 0]
            );
            $destStock->increment('quantity', $quantity);

            // Log transfer
            $transfer = StockTransfer::create([
                'from_warehouse_id' => $fromWarehouseId,
                'to_warehouse_id' => $toWarehouseId,
                'inventory_item_id' => $inventoryItemId,
                'quantity' => $quantity,
            ]);

            // Check for low stock (threshold is 10 for demonstration)
            if ($sourceStock->fresh()->quantity < 10) {
                event(new LowStockDetected($sourceStock));
            }

            return $transfer;
        });
    }
}
