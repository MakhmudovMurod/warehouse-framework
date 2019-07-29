<?php

namespace Just\Warehouse\Observers;

use LogicException;
use Just\Warehouse\Models\Order;
use Just\Warehouse\Jobs\PairOrderLine;
use Just\Warehouse\Jobs\ReleaseOrderLine;
use Just\Warehouse\Exceptions\InvalidOrderNumberException;

class OrderObserver
{
    /**
     * Handle the Order "creating" event.
     *
     * @param  \Just\Warehouse\Models\Order  $order
     * @return void
     */
    public function creating(Order $order)
    {
        if (empty($order->order_number)) {
            throw new InvalidOrderNumberException;
        }
    }

    /**
     * Handle the Order "deleting" event.
     *
     * @param  \Just\Warehouse\Models\Order  $order
     * @return void
     */
    public function deleting(Order $order)
    {
        if ($order->isForceDeleting()) {
            throw new LogicException('An order can not be force deleted.');
        }
    }

    /**
     * Handle the Order "deleted" event.
     *
     * @param  \Just\Warehouse\Models\Order  $order
     * @return void
     */
    public function deleted(Order $order)
    {
        $order->lines->each(function ($line) {
            ReleaseOrderLine::dispatch($line);
        });

        $order->update([
            'status' => 'deleted',
        ]);
    }

    /**
     * Handle the Order "restored" event.
     *
     * @param  \Just\Warehouse\Models\Order  $order
     * @return void
     */
    public function restored(Order $order)
    {
        $order->lines->each(function ($line) {
            PairOrderLine::dispatch($line);
        });

        $order->update([
            'status' => 'created',
        ]);
    }
}
