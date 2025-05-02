<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Table extends Model
{
    const STATUS_AVAILABLE = 1;
    const STATUS_OCCUPIED = 2;
    const STATUS_RESERVED = 3;

    function getStatusName()
    {
        return [
            self::STATUS_AVAILABLE => 'Available',
            self::STATUS_OCCUPIED => 'Occupied',
            self::STATUS_RESERVED => 'Reserved',
        ];
    }
    function getStatusColor()
    {
        return [
            self::STATUS_AVAILABLE => 'success',
            self::STATUS_OCCUPIED => 'danger',
            self::STATUS_RESERVED => 'warning',
        ];
    }
    function getStatusNameAttribute()
    {
        return $this->getStatusName()[$this->status_id];
    }
    function getStatusColorAttribute()
    {
        return $this->getStatusColor()[$this->status_id];
    }
    protected $fillable = [
        'number',
        'capacity',
        'status_id',
    ];
    public function tableStatus()
    {
        return $this->belongsTo(TableStatus::class, 'status_id');
    }
    public function tableOrders()
    {
        return $this->hasMany(Order::class);
    }
}
