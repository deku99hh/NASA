<?php

namespace App\Http\Controllers;

use App\Models\Location;
use Illuminate\Http\Request;

class FernController extends Controller
{

    public function index()
    {
        return view('chat-fern-1.fern1');
    }

    public function store(Request $request)
    {
        $request->validate([
            'long'      => ['required', 'numeric'],
            'lat'       => ['required', 'numeric'],
            'last_crop' => ['nullable', 'string'],
        ]);

        // البحث عن الموقع أو إنشاؤه في جدول locations
        $location = Location::firstOrCreate([
            'latitude'  => (float) $request->input('lat'),
            'longitude' => (float) $request->input('long'),
        ]);

        // ربط اليوزر بالـ ID بتاع الموقع وحفظ آخر محصول
        $request->user()->update([
            'locations_id' => $location->id,
            'last_crop'    => $request->input('last_crop'),
        ]);

        return redirect()->route('dashboard');
    }
}