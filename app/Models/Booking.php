<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Booking extends Model
{
    use HasFactory;

    protected $fillable = [
        'booking_code',
        'user_id',
        'movie_id',
        'seats',
        'total_price',
        'show_time',
        'status',
    ];

    protected $casts = [
        'show_time'   => 'datetime',
        'total_price' => 'decimal:2',
    ];

    /**
     * Generate a unique booking code like BK-20260101-A1B2C3
     */
    public static function generateBookingCode(): string
    {
        do {
            $code = 'BK-' . now()->format('Ymd') . '-' . strtoupper(Str::random(6));
        } while (self::where('booking_code', $code)->exists());

        return $code;
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function movie()
    {
        return $this->belongsTo(Movie::class);
    }
}