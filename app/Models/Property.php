<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class Property extends Model
{
    use HasFactory;
    protected $guarded = [];  // Дозвіл для масового  заповнення полів ( для Filament щоб зберігав дані)

    //Кастинг для автоматичного перетворення тіпів
    protected $casts =[
//      'price'=> 'decimal:15,2',
        'area'=> 'float'
        // для json  так само
    ];

    public function images(): HasMany
    {
        return $this->hasMany(PropertyImage::class);
    }
    public function amenities(): BelongsToMany
    {
        return $this->belongsToMany(Amenity::class,'amenity_property');
    }
}
