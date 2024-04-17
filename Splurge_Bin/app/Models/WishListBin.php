<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class WishListBin extends Model
{
    use HasFactory;

    protected $table = "wishlistbin";

    protected $fillable = [
        'product_id',
        'customer_id',
        'wishlist',
        'bin',
    ];

    public function products(){
        return $this
        -> hasOne(Products::class, 'id', 'product_id');
    }

}
