<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WargaVerification extends Model
{
    use HasFactory;

    protected $table = 'warga_verifications';

    protected $fillable = [
        'user_id',
        'email',
        'no_kk',
        'rt',
        'rw',
        'address',
        'mother_name',
        'father_name',
        'marital_status',
        'status',
        'verified_by',
        'verified_at',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function verifier()
    {
        return $this->belongsTo(User::class, 'verified_by');
    }
}
