<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Menu extends Model
{
    protected $fillable = [
        'name',
        'description',
        'image',
        'category_id',
        'price',
        'created_at',
        'updated_at',
    ];
    /** @use HasFactory<\Database\Factories\MenuFactory> */
    use HasFactory;

    public function category()
    {
        return $this->belongsTo(MenuCategory::class);
    }

    public function ingredients(): BelongsToMany
    {
        return $this->belongsToMany(Ingredient::class)->withPivot('quantity');
    }
    public function orders()
    {
        return $this->belongsToMany(Order::class)->withPivot('quantity', 'subtotal');
    }
}
