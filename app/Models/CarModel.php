<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CarModel extends Model
{
    use HasFactory;

    public $table = 'car_models';

    public $primaryKey = 'id';

    public function car()
    {
        return $this->belongsTo(Car::class);
    }
}
