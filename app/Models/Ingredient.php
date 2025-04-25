<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ingredient extends Model
{
    /** @use HasFactory<\Database\Factories\IngredientFactory> */
    use HasFactory;
    protected $fillable = [
        'name',
        'slug',
        'description',
        'stock',
    ];
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
    public function menus()
    {
        return $this->belongsToMany(Menu::class)->withPivot('quantity');
    }
}
