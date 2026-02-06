<?php

namespace App\Listeners;

use App\Events\LowStockDetected;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendLowStockNotification
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(LowStockDetected $event): void
    {
        // No need to actually send email, just trigger the logic
        \Log::info("Low stock detected for item: {$event->stock->inventoryItem->name} in warehouse: {$event->stock->warehouse->name}. Current quantity: {$event->stock->quantity}");
    }
}
