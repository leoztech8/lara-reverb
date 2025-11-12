<?php

declare(strict_types=1);

namespace App\Actions;

use App\Models\Counter;

class IncrementCounter
{
    /**
     * Increment the counter by 1.
     *
     * @param  int  $id
     * @return \App\Models\Counter
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function execute(int $id): Counter
    {
        $counter = Counter::findOrFail($id);

        // Use atomic increment to prevent race conditions
        $counter->increment('count');

        // Refresh to get updated timestamp
        $counter->refresh();

        // Event will be dispatched here (to be implemented in task 3.3)
        // event(new CounterIncremented($counter));

        return $counter;
    }
}
