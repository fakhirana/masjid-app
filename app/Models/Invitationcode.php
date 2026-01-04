<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InvitationCode extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'created_by',
        'used_by',
        'used_at',
    ];

    protected $casts = [
        'used_at' => 'datetime',
    ];

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'used_by');
    }
}
