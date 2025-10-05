<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Seminar extends Model
{
    protected $table = 'seminars';

    protected $fillable = [
        'image',
        'title',
        'subtitle',
        'description',
        'host_name',
        'location',
        'seminar_date',
        'seminar_time_start',
        'seminar_time_end',
        'meet_link',
        'location_link',
        'latitude',
        'longitude',
        'slug',
        'user_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($seminar) {
            $seminar->slug = Str::slug($seminar->title) . '-' . Str::random(5);
        });
    }
}
