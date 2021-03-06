<?php

namespace Just\Warehouse\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Just\Warehouse\Models\Inventory;
use Just\Warehouse\Models\OrderLine;

class ReleaseOrderLine implements ShouldQueue
{
    use Dispatchable, SerializesModels;

    public int $tries = 1;

    public OrderLine $line;

    public function __construct(OrderLine $line)
    {
        $this->line = $line;
    }

    public function handle(): void
    {
        tap($this->line->inventory, function (?Inventory $inventory) {
            if (is_null($inventory) || $inventory->trashed()) {
                return $this->line->release();
            }

            $this->line->reservation->update([
                'order_line_id' => null,
            ]);

            PairInventory::dispatch($inventory);
        });
    }
}
