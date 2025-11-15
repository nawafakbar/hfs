<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'invoice_number',
        'subtotal',
        'total_amount',
        'shipping_address',
        'shipping_cost',
        'shipping_method',
        'status',
        'payment_method',
        'payment_proof',
        'payment_confirmed_at',
    ];

    // Relasi: Satu Order milik satu User
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relasi: Satu Order punya banyak OrderItem
    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }
}