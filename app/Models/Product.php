<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id', 'name', 'slug', 'description', 'image', 
        'price', 'discount_price', 'stock',
    ];

    // Relasi: Satu Produk milik satu Kategori
    public function category()
    {
        return $this->belongsTo(Category::class);
    }
    
    // Relasi: Satu Produk bisa ada di banyak OrderItem
    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    // Relasi: Satu Produk bisa punya banyak Testimonial
    public function testimonials()
    {
        return $this->hasMany(Testimonial::class);
    }
}