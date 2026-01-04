<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Traits\ApiResponse;
use App\Models\WargaVerification;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    use ApiResponse;

    /**
     * GET /me
     * Info user + status verifikasi warga
     */
    public function me(Request $request)
    {
        $user = $request->user();

        $verification = WargaVerification::where('user_id', $user->id)
            ->latest()
            ->first();

        return $this->successResponse([
            'user' => $user,
            'warga_verification' => $verification,
        ], 'Profile user');
    }

    /**
     * PUT /me
     * Edit profile dasar
     */
    public function update(Request $request)
    {
        $user = $request->user();

        $data = $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:50|unique:users,username,' . $user->id,

            // PASSWORD OPTIONAL
            'current_password' => 'nullable|required_with:password',
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        // update basic profile
        $user->update([
            'name' => $data['name'],
            'username' => $data['username'],
        ]);

        // kalau user mau ganti password
        if (!empty($data['password'])) {

            if (!Hash::check($data['current_password'], $user->password)) {
                return $this->errorResponse(
                    'Password lama tidak sesuai',
                    422
                );
            }

            $user->update([
                'password' => Hash::make($data['password']),
            ]);
        }

        return $this->successResponse(
            $user->fresh(),
            'Profile berhasil diperbarui'
        );
    }
}