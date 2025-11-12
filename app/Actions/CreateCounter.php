<?php

declare(strict_types=1);

namespace App\Actions;

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

        // Event will be dispatched here (to be implemented in task 3.3)
        // event(new CounterCreated($counter));

        return $counter;
    }
}
