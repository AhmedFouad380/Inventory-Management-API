<?php

namespace Tests\Unit;

use App\Actions\TransferStockAction;
use App\Models\InventoryItem;
use App\Models\Stock;
use App\Models\Warehouse;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StockTransferActionTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_transfers_stock_between_warehouses(): void
    {
        $item = InventoryItem::factory()->create();
        $warehouseA = Warehouse::factory()->create();
        $warehouseB = Warehouse::factory()->create();

        Stock::create([
            'warehouse_id' => $warehouseA->id,
            'inventory_item_id' => $item->id,
            'quantity' => 100,
        ]);

        $action = new TransferStockAction();
        $action->execute($warehouseA->id, $warehouseB->id, $item->id, 40);

        $this->assertEquals(60, Stock::where('warehouse_id', $warehouseA->id)->where('inventory_item_id', $item->id)->first()->quantity);
        $this->assertEquals(40, Stock::where('warehouse_id', $warehouseB->id)->where('inventory_item_id', $item->id)->first()->quantity);
    }

    public function test_it_fails_if_insufficient_stock(): void
    {
        $item = InventoryItem::factory()->create();
        $warehouseA = Warehouse::factory()->create();
        $warehouseB = Warehouse::factory()->create();

        Stock::create([
            'warehouse_id' => $warehouseA->id,
            'inventory_item_id' => $item->id,
            'quantity' => 20,
        ]);

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Insufficient stock in source warehouse.');

        $action = new TransferStockAction();
        $action->execute($warehouseA->id, $warehouseB->id, $item->id, 40);
    }
}
