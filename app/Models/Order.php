<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    const STATUS_PENDING = 'pending';
    const STATUS_PREPARING = 'preparing';
    const STATUS_SERVED = 'served';
    const STATUS_COMPLETED = 'completed';
    const STATUS_CANCELLED = 'cancelled';
    public function getStatusLabel($status)
    {
        return [
            self::STATUS_PENDING => 'Pending',
            self::STATUS_PREPARING => 'Preparing',
            self::STATUS_SERVED => 'Served',
            self::STATUS_COMPLETED => 'Completed',
            self::STATUS_CANCELLED => 'Cancelled'
        ][$status] ?? $status;
    }
    public function getStatuses()
    {
        return [
            self::STATUS_PENDING => 'Pending',
            self::STATUS_PREPARING => 'Preparing',
            self::STATUS_SERVED => 'Served',
            self::STATUS_COMPLETED => 'Completed',
            self::STATUS_CANCELLED => 'Cancelled'
        ];
    }
    public function getStatusLabelAttribute()
    {
        return self::getStatusLabel($this->status);
    }
    const PAYMENT_STATUS_UNPAID = 'unpaid';
    const PAYMENT_STATUS_PAID = 'paid';
    const PAYMENT_STATUS_REFUNDED = 'refunded';
    const PAYMENT_STATUS_FAILED = 'failed';

    public function getPaymentStatusLabel($payment_status)
    {
        return [
            self::PAYMENT_STATUS_UNPAID => 'Unpaid',
            self::PAYMENT_STATUS_PAID => 'Paid',
            self::PAYMENT_STATUS_REFUNDED => 'Refunded',
            self::PAYMENT_STATUS_FAILED => 'Failed'
        ][$payment_status] ?? $payment_status;
    }
    public function getPaymentStatuses()
    {
        return [
            self::PAYMENT_STATUS_UNPAID => 'Unpaid',
            self::PAYMENT_STATUS_PAID => 'Paid',
            self::PAYMENT_STATUS_REFUNDED => 'Refunded',
            self::PAYMENT_STATUS_FAILED => 'Failed'

        ];
    }
    public function getPaymentStatusLabelAttribute()
    {
        return self::getPaymentStatusLabel($this->payment_status);
    }
    const PAYMENT_METHOD_CASH = 'cash';
    const PAYMENT_METHOD_CREDIT = 'credit';
    const PAYMENT_METHOD_DEBIT = 'debit';
    const PAYMENT_METHOD_QRIS = 'qris';
    public function getPaymentMethodLabel($payment_method)
    {
        return [
            self::PAYMENT_METHOD_CASH => 'Cash',
            self::PAYMENT_METHOD_CREDIT => 'Credit',
            self::PAYMENT_METHOD_DEBIT => 'Debit',
            self::PAYMENT_METHOD_QRIS => 'QRIS'
        ][$payment_method] ?? $payment_method;
    }
    public function getPaymentMethods()
    {
        return [
            self::PAYMENT_METHOD_CASH => 'Cash',
            self::PAYMENT_METHOD_CREDIT => 'Credit',
            self::PAYMENT_METHOD_DEBIT => 'Debit',
            self::PAYMENT_METHOD_QRIS => "QRIS"
        ];
    }
    public function getPaymentMethodLabelAttribute()
    {
        return self::getPaymentMethodLabel($this->payment_method);
    }


    /** @use HasFactory<\Database\Factories\OrderFactory> */
    use HasFactory;


    protected $fillable = [
        'customer_name',
        'table_id',
        'status',
        'total_price',
        'notes',
        'payment_method',
        'payment_status',

    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    public function tables()
    {
        return $this->belongsTo(Table::class, 'table_id');
    }


    public function menus()
    {
        return $this->belongsToMany(Menu::class)->withPivot('quantity', 'subtotal');
    }
}
