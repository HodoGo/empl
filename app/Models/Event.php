<?php

namespace App\Models;

use App\Enums\EventType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Event extends Model
{
    use HasFactory;
    protected $fillable = ['start','end','name','employee_id','type_event'];

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }
    protected $casts = [
        'type_event' =>  EventType::class,
    ];
}
