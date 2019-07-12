<?php

namespace Just\Warehouse\Models\Concerns;

use Just\Warehouse\Exceptions\InvalidStatusException;

trait HasOrderStatuses
{
    /**
     * Available order statuses.
     *
     * @var array
     */
    private $statuses = [
        'backorder',
        'created',
        'open',
    ];

    /**
     * Determine if a status is valid.
     *
     * @param  string  $value
     * @return bool
     */
    public function isValidStatus($value)
    {
        return in_array($value, $this->statuses);
    }

    /**
     * Set the status attribute.
     *
     * @param  string  $value
     * @return void
     *
     * @throws \Just\Warehouse\Exceptions\InvalidStatusException
     */
    public function setStatusAttribute($value)
    {
        if (! $this->isValidStatus($value)) {
            throw (new InvalidStatusException)->setModel(self::class, $value);
        }

        $this->status = $value;
    }
}