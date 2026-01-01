<?php

namespace App\Http\Controllers;

use App\Models\InfaqReport;
use Illuminate\Http\Request;
use App\Traits\ApiResponse;
use Illuminate\Support\Facades\Auth; 

class InfaqReportController extends Controller
{
    use ApiResponse;

    public function index()
    {
        $data = InfaqReport::orderBy('date', 'desc')->get();

        return $this->successResponse(
            $data,
            'Infaq reports retrieved successfully'
        );
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'date'   => 'required|date',
            'amount' => 'required|numeric',
            'note'   => 'nullable|string'
        ]);

        $report = InfaqReport::create($validated);

        return $this->successResponse(
            $report,
            'Infaq report created',
            201
        );
    }
}
