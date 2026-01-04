<?php

namespace App\Http\Controllers;

use App\Models\WargaVerification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Traits\ApiResponse;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class WargaVerificationController extends Controller
{
    use ApiResponse, AuthorizesRequests;

    /**
     * TAMU mengajukan verifikasi warga
     */
    public function store(Request $request)
    {
        $this->authorize('create', WargaVerification::class);

        $data = $request->validate([
            'email'           => 'required|email|unique:warga_verifications,email',
            'no_kk'           => 'required|string|min:16|max:20',
            'rt'              => 'required|string|max:5',
            'rw'              => 'required|string|max:5',
            'address'         => 'required|string',
            'mother_name'     => 'required|string|max:100',
            'father_name'     => 'required|string|max:100',
            'marital_status'  => 'required|in:belum_kawin,kawin,cerai_hidup,cerai_mati',
        ]);

        $verification = WargaVerification::create([
            ...$data,
            'user_id' => $request->user()->id,
            'status'  => 'pending',
        ]);

        return $this->successResponse(
            $verification,
            'Pengajuan verifikasi warga berhasil dikirim'
        );
    }

    /**
     * PENGURUS melihat daftar pengajuan verifikasi
     */
    public function index(Request $request)
    {
        $this->authorize('viewAny', WargaVerification::class);

        $status = $request->query('status', 'pending');

        $query = WargaVerification::with('user');

        if ($status) {
            $query->where('status', $status);
        }

        return $this->successResponse(
            $query->latest()->get(),
            'Daftar verifikasi warga'
        );
    }

    /**
     * PENGURUS menyetujui verifikasi warga
     */
    public function approve(Request $request, WargaVerification $verification)
    {
        $this->authorize('approve', $verification);

        $verification->update([
            'status'      => 'approved',
            'verified_by' => $request->user()->id,
            'verified_at' => now(),
        ]);

        $verification->user->update([
            'role'  => 'warga',
            'email' => $verification->email,
        ]);

        return $this->successResponse(
            null,
            'Warga berhasil diverifikasi'
        );
    }

    /**
     * PENGURUS menolak verifikasi
     */
    public function reject(Request $request, WargaVerification $verification)
    {
        $this->authorize('approve', $verification);

        $verification->update([
            'status'      => 'rejected',
            'verified_by' => $request->user()->id,
            'verified_at' => now(),
        ]);

        return $this->successResponse(
            null,
            'Verifikasi warga ditolak'
        );
    }
}
