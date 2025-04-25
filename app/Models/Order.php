<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    /** @use HasFactory<\Database\Factories\OrderFactory> */
    use HasFactory;


    protected $fillable = [
        'user_id',
        'table_id',
        'order_status_id',
        'total_price',
        'payment_method',
        'payment_status',

    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    public function users()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    public function table()
    {
        return $this->belongsTo(Table::class, 'table_id');
    }

    public function status()
    {
        return $this->belongsTo(OrderStatus::class, 'order_status_id');
    }
}
