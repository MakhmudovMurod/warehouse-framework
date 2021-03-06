<?php

namespace Just\Warehouse\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Just\Warehouse\Models\Inventory;
use Just\Warehouse\Models\OrderLine;

class PairOrderLine implements ShouldQueue
{
    use Dispatchable, SerializesModels;

    public int $tries = 3;

    public OrderLine $line;

    public function __construct(OrderLine $line)
    {
        $this->line = $line;
    }

    public function handle(): void
    {
        $inventory = Inventory::join('reservation', 'inventories.id', '=', 'reservation.inventory_id', 'left')
            ->select('inventories.id')
            ->where('inventories.gtin', '=', $this->line->gtin)
            ->whereNull('reservation.inventory_id')
            ->orderBy('inventories.created_at')
            ->first();

        if (! is_null($inventory)) {
            $this->line->reservation->fill([
                'inventory_id' => $inventory->id,
            ]);
        }

        $this->line->reserve();
    }
}
