<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Product extends Model
{
    use HasFactory;
    protected $guarded = [];

    public $table = 'products';

    public static function booted(){
        static::creating(function ($model){
            $model->code_product = Str::uuid();
        });
    }
}
