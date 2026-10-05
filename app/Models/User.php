<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password'          => 'hashed',
        ];
    }

    /* ============================================================
     |  Relationships
     * ============================================================ */

    /** Bookings made by this user (as a customer) */
    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    /** Movies owned by this user (as an organizer) */
    public function movies()
    {
        return $this->hasMany(Movie::class, 'organizer_id');
    }

    /** Organizer requests submitted by this user */
    public function organizerRequests()
    {
        return $this->hasMany(OrganizerRequest::class);
    }

    /* ============================================================
     |  Role checks
     * ============================================================ */

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isOrganizer(): bool
    {
        return $this->role === 'organizer';
    }

    public function isCustomer(): bool
    {
        return $this->role === 'customer';
    }

    /* ============================================================
     |  Organizer request helpers
     * ============================================================ */

    /** Does this user have a pending organizer request? */
    public function hasPendingOrganizerRequest(): bool
    {
        return $this->organizerRequests()
            ->where('status', 'pending')
            ->exists();
    }

    /** Most recent organizer request (any status), or null */
    public function latestOrganizerRequest()
    {
        return $this->organizerRequests()->latest()->first();
    }

    /* ============================================================
     |  Convenience helpers (optional but useful)
     * ============================================================ */

    /** Total confirmed revenue from this organizer's movies */
    public function totalRevenue(): float
    {
        if (!$this->isOrganizer()) {
            return 0.0;
        }

        return (float) Booking::whereHas('movie', fn($q) => $q->where('organizer_id', $this->id))
            ->where('status', 'confirmed')
            ->sum('total_price');
    }
}