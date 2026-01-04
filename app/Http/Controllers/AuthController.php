<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Traits\ApiResponse;

class AuthController extends Controller
{
    use ApiResponse;

    /**
     * Registrasi akun (default role: tamu)
     */
    public function register(Request $request)
    {
        $validated = $request->validate([
            'name'                  => 'required|string|max:255',
            'username'              => 'required|string|max:50|unique:users,username',
            'password'              => 'required|string|min:8|confirmed',
        ]);

        $user = User::create([
            'name'     => $validated['name'],
            'username' => $validated['username'],
            'password' => Hash::make($validated['password']),
            'role'     => 'tamu',
        ]);

        return $this->successResponse(
            $user,
            'Registrasi berhasil. Silakan ajukan verifikasi warga.',
            201
        );
    }
}
