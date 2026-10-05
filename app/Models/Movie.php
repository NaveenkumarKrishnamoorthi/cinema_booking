<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Movie extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'genre',
        'duration',
        'release_date',
        'poster',
        'price',
        'total_seats',
        'available_seats',
        'status',
    ];

    protected $casts = [
        'release_date' => 'date',
        'price'        => 'decimal:2',
    ];

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    public function isActive(): bool
    {
        return $this->status === 'active';
    }

    public function hasSeats(int $count): bool
    {
        return $this->available_seats >= $count;
    }

    public function organizer()
{
    return $this->belongsTo(User::class, 'organizer_id');
}

public function isAdminOwned(): bool
{
    return is_null($this->organizer_id);
}
}