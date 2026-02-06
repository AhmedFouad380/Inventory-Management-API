<?php

namespace Database\Seeders;

use App\Models\InventoryItem;
use App\Models\Stock;
use App\Models\Warehouse;
use Illuminate\Database\Seeder;

class InventorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create 3 Warehouses
        $warehouses = Warehouse::factory()->count(3)->create();

        // Create 10 Inventory Items
        $items = InventoryItem::factory()->count(10)->create();

        // Assign random stocks to each warehouse
        foreach ($warehouses as $warehouse) {
            foreach ($items as $item) {
                Stock::factory()->create([
                    'warehouse_id' => $warehouse->id,
                    'inventory_item_id' => $item->id,
                    'quantity' => rand(5, 50),
                ]);
            }
        }
    }
}
