<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MenuCategory extends Model
{
    /** @use HasFactory<\Database\Factories\MenuCategoryFactory> */
    use HasFactory;
    protected $fillable = [
        'title',
        'slug',
        'created_at',
        'updated_at',
    ];
    public function menus()
    {
        return $this->hasMany(Menu::class);
    }
}
