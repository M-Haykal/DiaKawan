<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Seminar;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class SeminarController extends Controller
{
    public function index()
    {
        $seminars = Seminar::simplePaginate(10);
        return view('dashboard.pages.seminar', compact('seminars'));
    }

    public function create()
    {
        return view('dashboard.action.seminar.create_seminar');
    }

    public function store(Request $request) {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'subtitle' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'image' => ['required', 'image', 'mimes:jpeg,png,jpg,webp,gif', 'max:2048'],
            'host_name' => ['required', 'string', 'max:255'],
            'location' => ['required', 'in:offline,online'],
            'seminar_date' => ['required', 'date'],
            'seminar_time_start' => ['required', 'date_format:H:i'],
            'seminar_time_end' => ['required', 'date_format:H:i'],
            'meet_link' => ['nullable', 'url'],
            'location_link' => ['nullable', 'url'],
            'latitude' => ['nullable', 'numeric'],
            'longitude' => ['nullable', 'numeric'],
        ]);

        try {
            $path = $request->file('image')->store('seminars', 'public');

            $seminar = Seminar::create([
                'image' => $path,
                'title' => $validated['title'],
                'subtitle' => $validated['subtitle'],
                'description' => $validated['description'],
                'host_name' => $validated['host_name'],
                'location' => $validated['location'],
                'seminar_date' => $validated['seminar_date'],
                'seminar_time_start' => $validated['seminar_time_start'],
                'seminar_time_end' => $validated['seminar_time_end'],
                'meet_link' => $validated['meet_link'],
                'location_link' => $validated['location_link'],
                'latitude' => $validated['latitude'],
                'longitude' => $validated['longitude'],
                'user_id' => Auth::id(),
            ]);

            // dd($seminar);

            return redirect()->route('seminars.index')->with('success', 'Seminar berhasil dibuat!');

        } catch (\Exception $e) {
            Log::error('Seminar creation error: ' . $e->getMessage());

            return back()->withInput()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }


    public function show($id)
    {
        return view('dashboard.action.seminar.detail_seminar');
    }

}
