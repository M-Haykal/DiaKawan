<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NutrisionProduct extends Model
{
    protected $table = 'nutrision_product';

    protected $fillable = [
        'nutrision',
        'content_quantity',
        'unit',
        'product_id',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
