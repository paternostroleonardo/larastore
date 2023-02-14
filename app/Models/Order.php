<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Order extends Model
{
    use HasFactory;
    protected $guarded = [];

    public $table = 'orders';

    const CREATED = "CREATED";
    const PAYED = "PAYED";
    const REJECTED = "REJECTD";

    public static function statusType()
    {
        //set values of columns status order
        return [
            self::CREATED,
            self::PAYED,
            self::REJECTED
        ];
    }

    public static function booted(){
        static::creating(function ($model){
            $model->code_order = Str::uuid();
        });
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class, 'id', 'customer_id');
    }

    public function seller(): HasOne
    {
        return $this->hasOne(User::class, 'id', 'seller_id');
    }
}
