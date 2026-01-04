<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Attendance;
use Illuminate\Http\Request;
use App\Traits\ApiResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class AttendanceController extends Controller
{
    use ApiResponse, AuthorizesRequests;

    /**
     * Konfirmasi kehadiran event
     */
    public function confirm(Request $request)
    {
        $validated = $request->validate([
            'event_id' => 'required|exists:events,id',
        ]);

        $event = Event::findOrFail($validated['event_id']);

        // 🔐 Policy check
        $this->authorize('create', [Attendance::class, $event]);

        // Cegah double konfirmasi
        $exists = Attendance::where('user_id', Auth::id())
            ->where('event_id', $event->id)
            ->exists();

        if ($exists) {
            return $this->errorResponse(
                'Anda sudah melakukan konfirmasi kehadiran.',
                409
            );
        }

        $attendance = Attendance::create([
            'user_id'  => Auth::id(),
            'event_id' => $event->id,
        ]);

        return $this->successResponse(
            $attendance,
            'Konfirmasi kehadiran berhasil!',
            201
        );
    }
}
