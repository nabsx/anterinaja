<?php

namespace App\Http\Controllers\Driver;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    public function edit()
    {
        $driver = Auth::user();
        return view('driver.profile.edit', compact('driver'));
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,'.Auth::id(),
            'phone' => 'required|string|unique:users,phone,'.Auth::id(),
            'vehicle_type' => 'required|string',
            'vehicle_number' => 'required|string',
        ]);

        Auth::user()->update($validated);

        return redirect()->route('driver.profile.edit')
            ->with('success', 'Profile updated successfully');
    }

    public function updateStatus(Request $request)
    {
        $validated = $request->validate([
            'is_online' => 'required|boolean',
        ]);

        Auth::user()->update(['is_online' => $validated['is_online']]);

        return response()->json([
            'success' => true,
            'is_online' => $validated['is_online'],
            'message' => 'Status updated successfully',
        ]);
    }

    public function updateLocation(Request $request)
    {
        $validated = $request->validate([
            'lat' => 'required|numeric',
            'lng' => 'required|numeric',
            'address' => 'nullable|string',
        ]);

        Auth::user()->update([
            'current_lat' => $validated['lat'],
            'current_lng' => $validated['lng'],
            'current_address' => $validated['address'] ?? null,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Location updated successfully',
        ]);
    }
}