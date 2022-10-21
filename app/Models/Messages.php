<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Messages extends Model
{
    use HasFactory;

    protected $primaryKey = 'message_id';
    protected $fillable = ['message_id', 'message', 'favorite_id'];

    public function favorite(): BelongsTo
    {
        return $this->BelongsTo(Favorites::class, 'favorite_id', 'favorite_id');
    }
}
