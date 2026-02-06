<?php

namespace Tests\Feature;

use App\Events\LowStockDetected;
use App\Models\InventoryItem;
use App\Models\Stock;
use App\Models\Warehouse;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Tests\TestCase;
use App\Actions\TransferStockAction;

class LowStockEventTest extends TestCase
{
    use RefreshDatabase;

    public function test_low_stock_event_is_dispatched(): void
    {
        Event::fake();

        $item = InventoryItem::factory()->create();
        $warehouseA = Warehouse::factory()->create();
        $warehouseB = Warehouse::factory()->create();

        // Initial stock 15
        Stock::create([
            'warehouse_id' => $warehouseA->id,
            'inventory_item_id' => $item->id,
            'quantity' => 15,
        ]);

        $action = new TransferStockAction();
        // Transfer 10, remaining 5 (below threshold 10)
        $action->execute($warehouseA->id, $warehouseB->id, $item->id, 10);

        Event::assertDispatched(LowStockDetected::class);
    }
}
