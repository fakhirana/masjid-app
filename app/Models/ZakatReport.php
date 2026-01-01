<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ZakatReport extends Model
{
    use HasFactory;

    protected $table = 'zakat_reports';

    protected $fillable = [
        'zakat_type',
        'total_amount',
        'total_muzakki',
        'report_year',
        'created_by'
    ];

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
