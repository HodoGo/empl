<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Employee extends Model
{
    use HasFactory;
    protected $fillable = ['lastname','firstname'];

    public function events(): HasMany
    {
        return $this->hasMany(Event::class);
    }
    public function fullName(): Attribute
    {
        return new Attribute(
            get: fn () => $this->lastname .' ' . $this->firstname
        );
    }
}
