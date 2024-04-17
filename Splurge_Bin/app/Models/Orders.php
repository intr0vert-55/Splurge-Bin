<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\Products;
use App\Models\User;
use App\Models\Reviews;

use Illuminate\Support\Facades\Auth;

class Orders extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'product_id',
        'seller_id',
        'customer_detail_id',
        'status',
    ];

    public function product(){
        return $this -> hasOne(Products::class, 'id', 'product_id');
    }

    public function seller(){
        return $this -> hasOne(User::class, 'id', 'seller_id');
    }

    public function customer(){
        return $this -> hasOne(User::class, 'id', 'customer_id');
    }

    public function review(){
        return $this -> belongsTo(Reviews::class, 'product_id', 'product_id') -> where('customer_id', Auth::user()->id);
    }

    public function reviews(){
        return $this -> hasMany(Reviews::class, 'product_id', 'product_id');
    }
}
