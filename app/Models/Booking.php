<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    protected $table = 'bookings';

    protected $fillable = [
        'name',
        'email',
        'phone',
        'booking_date',
        'booking_time',
        'meet_link',
        'location',
        'note',
        'user_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
