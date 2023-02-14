<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Customer extends Model
{
    use HasFactory;
    protected $guarded = [];

    public $table = 'customers';

    const CC = "CC";
    const PASSPORT = "PASSPORT";
    const DNI = "DNI";

    public static function identificationType()
    {
        //set values of columns identification type
        return [
            self::CC,
            self::PASSPORT,
            self::DNI
        ];
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class, 'customer_id', 'id');
    }
}
