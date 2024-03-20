<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'price',
        'quantity',
        'image_path'
    ];

    public function cart () {
        return $this->hasMany(Cart::class, 'product', 'id');
    }

    public function Owner () {
        return $this->hasMany(UsersProducts::class, 'product', 'id');
    }

}
