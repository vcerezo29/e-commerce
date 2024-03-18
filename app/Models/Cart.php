<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    use HasFactory;

    protected $fillable = [
        'product',
    ];

    public function Product (){
        return $this->hasOne(Product::class, 'id', 'product');
    }
}
