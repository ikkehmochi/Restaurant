<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TableStatus extends Model
{
    protected $fillable = [
        'title',
    ];
    public function table()
    {
        return $this->hasMany(Table::class, 'status_id');
    }
}
