<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UsersProducts extends Model
{
    use HasFactory;

    protected $fillable = [
        'product',
        'user',
    ];

    public function User () {
        return $this->hasOne(User::class, 'id', 'user');
    }
    public function Product () {
        return $this->hasOne(Product::class, 'id', 'product');
    }
}
