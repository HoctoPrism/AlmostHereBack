<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Favorites extends Model
{
    use HasFactory;

    protected $fillable = ['favorite_id', 'name', 'route_id', 'user_id'];

    public function route(): BelongsTo
    {
        return $this->BelongsTo(Routes::class, 'route_id', 'route_id');
    }

    public function user(): BelongsTo
    {
        return $this->BelongsTo(User::class, 'user_id');
    }

    public function messages(): HasMany
    {
        return $this->HasMany(Messages::class);
    }
}
