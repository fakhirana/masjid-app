<?php

namespace App\Http\Controllers;

use App\Models\QurbanAnimal;
use Illuminate\Http\Request;
use App\Traits\ApiResponse;

class QurbanAnimalController extends Controller
{
    use ApiResponse;

    public function index()
    {
        $data = QurbanAnimal::with('owners')->get();

        return $this->successResponse(
            $data,
            'Qurban animals retrieved successfully'
        );
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'type' => 'required|in:sapi,kambing',
            'name' => 'nullable|string'
        ]);

        $animal = QurbanAnimal::create($validated);

        return $this->successResponse(
            $animal,
            'Qurban animal added',
            201
        );
    }
}
