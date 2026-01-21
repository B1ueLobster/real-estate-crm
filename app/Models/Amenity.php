<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
class Amenity extends Model
{
    protected $guarded = [];  // Дозвіл для масового  заповнення полів ( для Filament щоб зберігав дані)
    public function properties(): BelongsToMany
    {
        return $this->belongsToMany(Property::class,'amenity_property');
    }
    //
}
