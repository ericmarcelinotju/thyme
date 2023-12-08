<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Page extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function blocks(): BelongsToMany
    {
        return $this->belongsToMany(Block::class, 'page_block');
    }
}
