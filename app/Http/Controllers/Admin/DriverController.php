<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class DriverController extends Controller
{
    public function index()
    {
        $drivers = User::where('role', 'driver')->paginate(10);
        return view('admin.drivers.index', compact('drivers'));
    }

    public function create()
    {
        return view('admin.drivers.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'phone' => 'required|string|unique:users',
            'vehicle_type' => 'required|string',
            'vehicle_number' => 'required|string',
            'password' => 'required|string|min:8',
        ]);

        User::create([
            ...$validated,
            'role' => 'driver',
            'password' => bcrypt($validated['password']),
        ]);

        return redirect()->route('admin.drivers.index')
            ->with('success', 'Driver created successfully');
    }

    public function edit(User $driver)
    {
        return view('admin.drivers.edit', compact('driver'));
    }

    public function update(Request $request, User $driver)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,'.$driver->id,
            'phone' => 'required|string|unique:users,phone,'.$driver->id,
            'vehicle_type' => 'required|string',
            'vehicle_number' => 'required|string',
            'password' => 'nullable|string|min:8',
        ]);

        $updateData = [
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'vehicle_type' => $validated['vehicle_type'],
            'vehicle_number' => $validated['vehicle_number'],
        ];

        if ($request->filled('password')) {
            $updateData['password'] = bcrypt($validated['password']);
        }

        $driver->update($updateData);

        return redirect()->route('admin.drivers.index')
            ->with('success', 'Driver updated successfully');
    }

    public function destroy(User $driver)
    {
        $driver->delete();
        return redirect()->route('admin.drivers.index')
            ->with('success', 'Driver deleted successfully');
    }

    public function verify(User $driver)
    {
        $driver->update(['verified' => true]);
        return back()->with('success', 'Driver verified successfully');
    }
}