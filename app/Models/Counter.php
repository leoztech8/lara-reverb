<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

/**
 * Counter Model
 *
 * @property int $id
 * @property string $name
 * @property int $count
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 */
class Counter extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'count',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'count' => 'integer',
        ];
    }

    /**
     * Scope a query to order counters by count descending, then name ascending.
     */
    public function scopeOrdered(Builder $query): Builder
    {
        return $query->orderByDesc('count')->orderBy('name');
    }

    /**
     * Scope a query to filter counters with high count.
     */
    public function scopeWithHighCount(Builder $query, int $threshold = 100): Builder
    {
        return $query->where('count', '>=', $threshold);
    }
}
