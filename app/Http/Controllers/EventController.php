<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Registration;
use Illuminate\Http\Request;
use App\Traits\ApiResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests; // Tambahkan ini untuk use authorize

class EventController extends Controller
{
    use ApiResponse, AuthorizesRequests;

    public function attendances(Event $event)
    {
        $this->authorize('viewAttendance', Registration::class);

        $data = $event->registrations()
            ->with('user:id,name,email,role')
            ->get()
            ->map(function ($reg) {
                return [
                    'name'       => $reg->user->name,
                    // 'email'      => $reg->user->email,
                    'role'       => $reg->user->role,
                    'confirmed_at' => $reg->created_at,
                ];
            });

        return response()->json([
            'event' => [
                'id' => $event->id,
                'name' => $event->name,
            ],
            'total_attendance' => $data->count(),
            'attendances' => $data,
        ]);
    }
    /**
     * Menampilkan daftar kegiatan yang aktif.
     */
    public function index()
    {
        $events = Event::where('is_active', true)
            ->orderBy('event_date', 'asc')
            ->get();

        return $this->successResponse(
            $events,
            'Events retrieved successfully'
        );
    }

    /**
     * Menyimpan kegiatan baru.
     */
    public function store(Request $request)
    {
        // Pastikan User punya hak akses (Policy)
        $this->authorize('create', Event::class);

        $validated = $request->validate([
            'name'        => 'required|string|max:255',
            'event_date'  => 'required|date',
            'location'    => 'required|string',
            'description' => 'nullable|string', // Menyesuaikan input description jika ada
        ]);

        // Tambahkan ID pembuat secara otomatis
        $event = Event::create(array_merge($validated, [
            'created_by' => Auth::id()
        ]));

        return $this->successResponse(
            $event,
            'Event created successfully',
            201
        );
    }

    /**
     * Menampilkan detail satu kegiatan.
     */
    public function show($id)
    {
        $event = Event::findOrFail($id);

        return $this->successResponse(
            $event,
            'Event detail retrieved successfully'
        );
    }

    /**
     * Memperbarui data kegiatan.
     */
    public function update(Request $request, $id)
    {
        $event = Event::findOrFail($id);

        // Pastikan User punya hak akses (Policy)
        $this->authorize('update', $event);

        $validated = $request->validate([
            'name'        => 'sometimes|string|max:255',
            'event_date'  => 'sometimes|date',
            'location'    => 'sometimes|string',
            'description' => 'sometimes|string',
            'is_active'   => 'sometimes|boolean'
        ]);

        $event->update($validated);

        return $this->successResponse(
            $event,
            'Event updated successfully'
        );
    }

    /**
     * Menghapus kegiatan.
     */
    public function destroy($id)
    {
        $event = Event::findOrFail($id);

        // Pastikan User punya hak akses (Policy)
        $this->authorize('delete', $event);

        $event->delete();

        return $this->successResponse(
            null,
            'Event deleted successfully'
        );
    }
}