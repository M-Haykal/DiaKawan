<?php

namespace App\Observers;

use App\Models\Booking;
use Illuminate\Support\Facades\Mail;
use App\Mail\MeetLinkSent;

class BookingObserver
{
    /**
     * Handle the Booking "created" event.
     */
    public function created(Booking $booking): void
    {
        //
    }

    /**
     * Handle the Booking "updated" event.
     */
    public function updated(Booking $booking): void
    {
        // Cek apakah meet_link baru saja diisi (sebelumnya null, sekarang ada)
        if ($booking->isDirty('meet_link') && $booking->meet_link) {
            // Pastikan booking ini untuk konsultasi online
            if ($booking->location === 'online') {
                // Kirim email ke user
                Mail::to($booking->email)->send(new MeetLinkSent($booking));
            }
        }
    }

    /**
     * Handle the Booking "deleted" event.
     */
    public function deleted(Booking $booking): void
    {
        //
    }

    /**
     * Handle the Booking "restored" event.
     */
    public function restored(Booking $booking): void
    {
        //
    }

    /**
     * Handle the Booking "force deleted" event.
     */
    public function forceDeleted(Booking $booking): void
    {
        //
    }
}
