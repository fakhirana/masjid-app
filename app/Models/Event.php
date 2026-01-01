<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;

    protected $table = 'events';

    protected $fillable = [
        'name', 
        'event_date', 
        'location', 
        'description', 
        'user_id',   // baru
        'status',    // baru
        'joined_at'  // baru
    ];

    public function registrations()
    {
        return $this->hasMany(Registration::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
