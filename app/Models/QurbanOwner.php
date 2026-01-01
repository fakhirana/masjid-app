<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QurbanOwner extends Model
{
    use HasFactory;

    protected $table = 'qurban_owners';

    protected $fillable = [
        'animal_id',
        'owner_name'
    ];

    public function animal()
    {
        return $this->belongsTo(QurbanAnimal::class, 'animal_id');
    }
}
