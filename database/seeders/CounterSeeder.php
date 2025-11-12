<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Counter;
use Illuminate\Database\Seeder;

class CounterSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create counters with varied counts for demonstration
        Counter::factory()->withName('Laravel')->create(['count' => 500]);
        Counter::factory()->withName('PHP')->create(['count' => 350]);
        Counter::factory()->withName('Livewire')->create(['count' => 280]);
        Counter::factory()->withName('Reverb')->create(['count' => 150]);
        Counter::factory()->withName('Tailwind CSS')->create(['count' => 420]);
        Counter::factory()->withName('Alpine.js')->create(['count' => 95]);
        Counter::factory()->withName('Volt')->create(['count' => 185]);
        Counter::factory()->withName('Pest')->create(['count' => 220]);
        Counter::factory()->withName('Flux UI')->create(['count' => 310]);
        Counter::factory()->withName('WebSockets')->create(['count' => 75]);

        // Create some additional random counters
        Counter::factory(5)->create();
    }
}
