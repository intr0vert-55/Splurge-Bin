<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\User;

class Reviews extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'stars',
        'customer_id',
        'product_id',
        'images'
    ];

    public function customer(){
        return $this
                ->hasOne(User::class, 'id', 'customer_id');
    }
}
