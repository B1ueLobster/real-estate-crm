<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class Lead extends Model
{
    use HasFactory;
    //Allowed to fill
    protected $guarded = [];

    //Connect: Lead to Manager (User)
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function getFullNameAttributes():  string
    {
        return trim("{$this->first_name} ' ' {$this->last_name}");
    }
}
