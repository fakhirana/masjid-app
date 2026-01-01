<?php

namespace App\Http\Controllers;

use App\Models\QurbanOwner;
use Illuminate\Http\Request;
use App\Traits\ApiResponse;

class QurbanOwnerController extends Controller
{
    use ApiResponse;

    public function store(Request $request)
    {
        $validated = $request->validate([
            'qurban_animal_id' => 'required|exists:qurban_animals,id',
            'owner_name'       => 'required|string'
        ]);

        $owner = QurbanOwner::create($validated);

        return $this->successResponse(
            $owner,
            'Qurban owner added',
            201
        );
    }
}
