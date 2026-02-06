<?php

namespace Tests\Feature\Api;

use App\Models\InventoryItem;
use App\Models\Stock;
use App\Models\User;
use App\Models\Warehouse;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StockTransferTest extends TestCase
{
    use RefreshDatabase;

    public function test_authenticated_user_can_transfer_stock(): void
    {
        $user = User::factory()->create();
        $item = InventoryItem::factory()->create();
        $warehouseA = Warehouse::factory()->create();
        $warehouseB = Warehouse::factory()->create();

        Stock::create([
            'warehouse_id' => $warehouseA->id,
            'inventory_item_id' => $item->id,
            'quantity' => 50,
        ]);

        $response = $this->actingAs($user, 'sanctum')
            ->postJson('/api/stock-transfers', [
                'from_warehouse_id' => $warehouseA->id,
                'to_warehouse_id' => $warehouseB->id,
                'inventory_item_id' => $item->id,
                'quantity' => 20,
            ]);

        $response->assertStatus(201)
            ->assertJsonPath('message', 'Stock transferred successfully.');

        $this->assertDatabaseHas('stocks', [
            'warehouse_id' => $warehouseA->id,
            'inventory_item_id' => $item->id,
            'quantity' => 30,
        ]);
    }

    public function test_unauthenticated_user_cannot_transfer_stock(): void
    {
        $response = $this->postJson('/api/stock-transfers', []);

        $response->assertStatus(401);
    }
}
