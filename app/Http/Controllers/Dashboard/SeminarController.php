<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Seminar;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

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

    public function store(Request $request)
    {
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
        $seminar = Seminar::findOrFail($id);
        return view('dashboard.action.seminar.detail_seminar', compact('seminar'));
    }

    public function update(Request $request, Seminar $seminar)
    {
        // Validasi input
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'subtitle' => 'required|string|max:255',
            'description' => 'required|string',
            'host_name' => 'required|string|max:255',
            'seminar_date' => 'required|date',
            'seminar_time_start' => 'required|date_format:H:i',
            'seminar_time_end' => 'required|date_format:H:i|after:seminar_time_start',
            'location' => 'required|in:online,offline',
            'meet_link' => $request->location == 'online' ? 'required|url' : 'nullable|url',
            'location_link' => $request->location == 'offline' ? 'required|url' : 'nullable|url',
            'latitude' => $request->location == 'offline' ? 'required|numeric' : 'nullable|numeric',
            'longitude' => $request->location == 'offline' ? 'required|numeric' : 'nullable|numeric',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // max 2MB
        ], [
            'seminar_time_end.after' => 'Waktu selesai harus setelah waktu mulai.',
            'meet_link.required' => 'Link meeting wajib diisi untuk seminar online.',
            'location_link.required' => 'Link lokasi wajib diisi untuk seminar offline.',
            'latitude.required' => 'Koordinat lokasi tidak lengkap (latitude).',
            'longitude.required' => 'Koordinat lokasi tidak lengkap (longitude).',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // Handle upload gambar
        $imagePath = $seminar->image; // pertahankan gambar lama jika tidak diubah
        if ($request->hasFile('image')) {
            // Hapus gambar lama jika ada
            if ($seminar->image) {
                Storage::disk('public')->delete($seminar->image);
            }
            $imagePath = $request->file('image')->store('seminars/images', 'public');
        }

        // Update data seminar
        $seminar->update([
            'title' => $request->title,
            'subtitle' => $request->subtitle,
            'description' => $request->description,
            'host_name' => $request->host_name,
            'seminar_date' => $request->seminar_date,
            'seminar_time_start' => $request->seminar_time_start,
            'seminar_time_end' => $request->seminar_time_end,
            'location' => $request->location,
            'meet_link' => $request->location == 'online' ? $request->meet_link : null,
            'location_link' => $request->location == 'offline' ? $request->location_link : null,
            'latitude' => $request->location == 'offline' ? $request->latitude : null,
            'longitude' => $request->location == 'offline' ? $request->longitude : null,
            'image' => $imagePath,
        ]);

        return redirect()->back()->with('success', 'Seminar berhasil diperbarui.');
    }

}
