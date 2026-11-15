<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Location extends Model
{
    public function location(): BelongsTo
    {
        return $this->BelongsTo(User::class);
    }

    public function record(): HasMany
    {
        return $this->HasMany(Record::class);
    }
}
