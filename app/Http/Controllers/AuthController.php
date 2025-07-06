<?php
// app/Http/Controllers/AuthController.php
namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Driver;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($request->only('email', 'password'))) {
            $user = Auth::user();
            
            // Redirect based on role
            switch ($user->role) {
                case 'admin':
                    return redirect()->route('admin.dashboard');
                case 'driver':
                    return redirect()->route('driver.dashboard');
                default:
                    return redirect()->route('user.dashboard');
            }
        }

        throw ValidationException::withMessages([
            'email' => ['The provided credentials are incorrect.'],
        ]);
    }

    public function showRegister()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|in:user,driver',
            'phone' => 'required|string|max:20',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'phone' => $request->phone,
        ]);

        // If registering as driver, create driver record
        if ($request->role === 'driver') {
            Driver::create([
                'user_id' => $user->id,
                'vehicle_type' => $request->vehicle_type,
                'vehicle_plate' => $request->vehicle_plate,
                'license_number' => $request->license_number,
            ]);
        }

        Auth::login($user);

        return redirect()->route($request->role . '.dashboard');
    }

    public function logout()
    {
        Auth::logout();
        return redirect()->route('login');
    }
}