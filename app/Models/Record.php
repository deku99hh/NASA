<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Record extends Model
{
    public function location(): BelongsTo
    {
        return $this->BelongsTo(User::class);
    }
}
