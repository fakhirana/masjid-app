<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QurbanAnimal extends Model
{
    use HasFactory;

    protected $table = 'qurban_animals';

    protected $fillable = [
        'type',
        'quantity',
        'status'
    ];

    public function owners()
    {
        return $this->hasMany(QurbanOwner::class, 'animal_id');
    }
}
