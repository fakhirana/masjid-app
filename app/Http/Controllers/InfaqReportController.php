<?php

namespace App\Http\Controllers;

use App\Models\InfaqReport;
use Illuminate\Http\Request;
use App\Traits\ApiResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class InfaqReportController extends Controller
{
    use ApiResponse;

    public function index()
    {
        $data = InfaqReport::orderBy('created_at', 'desc')->get();

        return $this->successResponse(
            $data,
            'Infaq reports retrieved successfully'
        );
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'source' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0',
        ]);

        $report = InfaqReport::create([
            'source'     => $validated['source'],
            'amount'     => $validated['amount'],
            'created_by' => Auth::id(), // FIX FK
        ]);

        // 🔥 CLEAR DASHBOARD CACHE
        Cache::forget('dashboard.summary');

        return $this->successResponse(
            $report,
            'Infaq report created successfully',
            201
        );
    }
}
