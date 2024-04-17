<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\User;

use App\Models\WishListBin;

use App\Models\Reviews;

use App\Models\Orders;

use Illuminate\Support\Facades\Auth;

class Products extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'info',
        'price',
        'seller_id',
        'category_id',
        'image'
    ];

    public function seller(){
        return $this -> hasOne(User::class, 'id', 'seller_id');
    }


    public function wishListBin(){
        return $this
        -> hasOne(WishListBin::class, 'product_id', 'id')
        -> where('customer_id', Auth::user()->id);
    }

    public function wishList(){
        return $this
        -> hasOne(WishListBin::class, 'product_id', 'id')
        -> where('customer_id', Auth::user()->id)
        -> where('wishlist', 1);
    }

    public function bin(){
        return $this
        -> hasOne(WishListBin::class, 'product_id', 'id')
        -> where('customer_id', Auth::user()->id)
        -> where('bin', 1);
    }

    public function reviews(){
        return $this
        -> hasMany(Reviews::class, 'product_id', 'id');
    }

    public function orders(){
        return $this
        -> hasMany(Orders::class, 'product_id', 'id');
    }

    public function wlBin(){
        return $this
        -> hasMany(WishListBin::class, 'product_id', 'id');
    }
}
