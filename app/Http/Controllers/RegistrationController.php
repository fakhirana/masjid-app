<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Registration;
use Illuminate\Http\Request;
use App\Traits\ApiResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class RegistrationController extends Controller
{
    use ApiResponse, AuthorizesRequests;

    /**
     * Konfirmasi kehadiran event
     */
    public function confirmPresence(Request $request)
    {
        $validated = $request->validate([
            'event_id' => 'required|exists:events,id',
        ]);

        $event = Event::findOrFail($validated['event_id']);

        // 🔐 Policy check (INI KUNCI)
        $this->authorize('create', [Registration::class, $event]);

        // Cegah double konfirmasi
        $exists = Registration::where('user_id', Auth::id())
            ->where('event_id', $event->id)
            ->exists();

        if ($exists) {
            return $this->errorResponse(
                'Anda sudah melakukan konfirmasi kehadiran.',
                409
            );
        }

        $registration = Registration::create([
            'user_id'  => Auth::id(),
            'event_id' => $event->id,
        ]);

        return $this->successResponse(
            $registration,
            'Konfirmasi kehadiran berhasil!',
            201
        );
    }
}
