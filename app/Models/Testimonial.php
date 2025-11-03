<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Testimonial extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'product_id', 'rating', 'comment', 'status'];
    
    // Relasi: Satu Testimonial milik satu User
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relasi: Satu Testimonial merujuk ke satu Produk
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}