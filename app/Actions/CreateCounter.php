<?php

declare(strict_types=1);

namespace App\Actions;

use App\Events\CounterCreated;
use App\Models\Counter;

class CreateCounter
{
    /**
     * Create a new counter with the given name.
     *
     * @param  string  $name
     * @return \App\Models\Counter
     */
    public function execute(string $name): Counter
    {
        $counter = Counter::create([
            'name' => $name,
            'count' => 0,
        ]);

        // Broadcast counter creation to all connected clients
        event(new CounterCreated($counter));

        return $counter;
    }
}
