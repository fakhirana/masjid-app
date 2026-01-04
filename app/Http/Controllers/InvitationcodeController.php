<?php

namespace App\Http\Controllers;

use App\Models\InvitationCode;
use Illuminate\Http\Request;
use App\Traits\ApiResponse;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth; // 1. Tambahkan ini
use Illuminate\Foundation\Auth\Access\AuthorizesRequests; // 2. Tambahkan ini

class InvitationCodeController extends Controller
{
    // 3. Tambahkan AuthorizesRequests di sini
    use ApiResponse, AuthorizesRequests;

    // pengurus buat kode
    public function generate()
    {
        // Sekarang ini tidak akan error lagi
        $this->authorize('generate', InvitationCode::class);

        $code = InvitationCode::create([
            'code'       => Str::upper(Str::random(8)),
            'created_by' => Auth::id(), // 4. Gunakan Auth::id() agar IDE tenang
        ]);

        return $this->successResponse($code, 'Kode undangan dibuat');
    }

    // warga pakai kode
    public function use(Request $request)
    {
        $request->validate([
            'code' => 'required|string'
        ]);

        $invitation = InvitationCode::where('code', $request->code)
            ->whereNull('used_at')
            ->firstOrFail();

        /** @var \App\Models\User $user */ // 5. Tambahkan komentar ini jika 'user' masih merah
        $user = Auth::user(); // 6. Gunakan Auth::user()

        if ($user->role !== 'warga') {
            return $this->errorResponse('Hanya warga yang bisa jadi pengurus', 403);
        }

        $invitation->update([
            'used_by' => $user->id,
            'used_at' => now(),
        ]);

        $user->update([
            'role' => 'pengurus'
        ]);

        return $this->successResponse(null, 'Role berhasil menjadi pengurus');
    }
}