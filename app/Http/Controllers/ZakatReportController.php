<?php

namespace App\Http\Controllers;

use App\Models\ZakatReport;
use Illuminate\Http\Request;
use App\Traits\ApiResponse;

class ZakatReportController extends Controller
{
    use ApiResponse;

    public function index()
    {
        return $this->successResponse(
            ZakatReport::latest()->get(),
            'Zakat reports retrieved successfully'
        );
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'zakat_type' => 'required|in:fitrah,mal',
            'payer_name' => 'required|string',
            'amount'     => 'required|numeric'
        ]);

        $zakat = ZakatReport::create($validated);

        return $this->successResponse(
            $zakat,
            'Zakat recorded successfully',
            201
        );
    }
}
