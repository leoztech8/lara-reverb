<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Counter;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Counter>
 */
class CounterFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var class-string<\Illuminate\Database\Eloquent\Model>
     */
    protected $model = Counter::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->unique()->words(2, true),
            'count' => fake()->numberBetween(0, 1000),
        ];
    }

    /**
     * Indicate that the counter should have a high count.
     */
    public function withHighCount(): static
    {
        return $this->state(fn (array $attributes) => [
            'count' => fake()->numberBetween(500, 10000),
        ]);
    }

    /**
     * Indicate that the counter should start at zero.
     */
    public function withZeroCount(): static
    {
        return $this->state(fn (array $attributes) => [
            'count' => 0,
        ]);
    }

    /**
     * Indicate that the counter should have a specific name.
     */
    public function withName(string $name): static
    {
        return $this->state(fn (array $attributes) => [
            'name' => $name,
        ]);
    }
}
