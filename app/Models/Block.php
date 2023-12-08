<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Block extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function assets(): BelongsToMany
    {
        return $this->belongsToMany(Asset::class, 'block_asset');
    }
}
