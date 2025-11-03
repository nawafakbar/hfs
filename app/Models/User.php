<?php
namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name', 'email', 'password', 'role', 'profile_photo_path', 
        'phone_number', 'address', 'google_id',
    ];

    protected $hidden = [ 'password', 'remember_token', ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // Relasi: Satu User bisa punya banyak Order
    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    // Relasi: Satu User bisa punya banyak Testimonial
    public function testimonials()
    {
        return $this->hasMany(Testimonial::class);
    }
}