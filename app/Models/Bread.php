<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Bread extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function getColumnsAttribute()
    {
        return json_decode($this->columns_json);
    }
}
