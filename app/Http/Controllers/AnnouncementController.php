<?php

namespace App\Http\Controllers;

use App\Models\Announcement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; 
use App\Traits\ApiResponse;
// 1. Import Trait Authorization
use Illuminate\Foundation\Auth\Access\AuthorizesRequests; 

class AnnouncementController extends Controller
{
    // 2. Gunakan Trait di sini
    use ApiResponse, AuthorizesRequests;
    
    public function index()
    {
        $data = Announcement::latest()->get();
        return $this->successResponse($data, 'Announcements retrieved successfully');
    }

    public function store(Request $request)
    {
        // Sekarang ini tidak akan error
        $this->authorize('create', Announcement::class);

        $validated = $request->validate([
            'title'   => 'required|string|max:255',
            'content' => 'required|string'
        ]);

        $announcement = Announcement::create([
            'title'      => $validated['title'],
            'content'    => $validated['content'],
            'created_by' => Auth::id()
        ]);

        return $this->successResponse($announcement, 'Announcement created successfully', 201);
    }

    public function show($id)
    {
        $announcement = Announcement::findOrFail($id);
        return $this->successResponse($announcement, 'Announcement detail retrieved successfully');
    }

    public function update(Request $request, $id)
    {
        $announcement = Announcement::findOrFail($id);

        // Sekarang ini tidak akan error
        $this->authorize('update', $announcement);

        $validated = $request->validate([
            'title'   => 'sometimes|string|max:255',
            'content' => 'sometimes|string'
        ]);

        $announcement->update($validated);

        return $this->successResponse($announcement, 'Announcement updated successfully');
    }

    public function destroy($id)
    {
        $announcement = Announcement::findOrFail($id);

        // Sekarang ini tidak akan error
        $this->authorize('delete', $announcement);

        $announcement->delete();
        
        return $this->successResponse(null, 'Announcement deleted successfully');
    }
}