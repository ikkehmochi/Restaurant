<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderStatus extends Model
{
    /** @use HasFactory<\Database\Factories\OrderStatusFactory> */
    use HasFactory;
    protected $fillable = [
        'title',
        'slug'
    ];
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    public function orders()
    {
        return $this->hasMany(Order::class);
    }
}
