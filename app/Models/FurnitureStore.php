<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FurnitureStore extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function furnitureItems()
    {
        return $this->hasMany(FurnitureItem::class);
    }

    public function productPages()
    {
        return $this->hasMany(ProductPage::class);
    }
}
