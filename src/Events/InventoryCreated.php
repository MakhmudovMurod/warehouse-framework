<?php

namespace Just\Warehouse\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Just\Warehouse\Models\Inventory;

class InventoryCreated
{
    use Dispatchable, SerializesModels;

    public Inventory $inventory;

    public function __construct(Inventory $inventory)
    {
        $this->inventory = $inventory;
    }
}
