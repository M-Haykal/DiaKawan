<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Mail;
use App\Mail\MeetLinkSent;

use App\Models\Booking;

class BookingController extends Controller
{
    public function index()
    {
        $bookings = Booking::latest()->simplePaginate(5);
        return view('dashboard.pages.booking', compact('bookings'));
    }

    public function edit(Request $request, Booking $booking)
    {
        $request->validate(['meet_link' => 'nullable|url']);

        $booking->update(['meet_link' => $request->meet_link]);

        // Kirim email langsung (tanpa observer)
        if ($booking->meet_link && $booking->location === 'online') {
            Mail::to($booking->email)->send(new MeetLinkSent($booking));
        }

        return redirect()->back()->with('success', 'Berhasil!');
    }
}
