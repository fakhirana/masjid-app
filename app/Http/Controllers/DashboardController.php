<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Event;
use App\Models\InfaqReport;
use App\Models\ZakatReport;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Http;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class DashboardController extends Controller
{
    use AuthorizesRequests;

    /**
     * DASHBOARD PUBLIK (Tanpa Auth)
     */
    public function publicSummary(): JsonResponse
    {
        return $this->buildDashboardResponse();
    }

    /**
     * DASHBOARD ADMIN (Pengurus)
     */
    public function adminSummary(): JsonResponse
    {
        $this->authorize('viewDashboard', User::class);

        return $this->buildDashboardResponse();
    }

    /**
     * Logic dashboard dipusatkan di sini
     */
    private function buildDashboardResponse(): JsonResponse
    {
        $response = Http::get('https://api.aladhan.com/v1/timingsByCity', [
            'city'    => 'Bandung',
            'country' => 'Indonesia',
            'method'  => 20,
        ]);

        $timings = $response->json('data.timings');
        $hijri   = $response->json('data.date.hijri');

        // 3. Tanggal
        $now = Carbon::now()->locale('id');

        $dateInfo = [
            'gregorian' => $now->translatedFormat('l, d F Y'),
            'hijriah'   => $hijri['day'].' '.$hijri['month']['ar'].' '.$hijri['year'].' H',
        ];

        // 4. Jadwal Sholat + Ramadhan
        $jadwalSholat = [
            'imsak'      => $timings['Imsak'],
            'subuh'      => $timings['Fajr'],
            'dzuhur'     => $timings['Dhuhr'],
            'ashar'      => $timings['Asr'],
            'maghrib'    => $timings['Maghrib'],
            'isya'       => $timings['Isha'],
            'buka_puasa' => $timings['Maghrib'],
            // 'sahur'      => $timings['Fajr'],
        ];

        // 5. Statistik Dashboard
        return response()->json([
            'status'  => 'success',
            'message' => 'Dashboard summary retrieved successfully',
            'data' => [
                'today' => $dateInfo,
                'jadwal_sholat' => $jadwalSholat,

                'events' => [
                    'total'  => Event::count(),
                    'active' => Event::where('status', 'active')->count(),
                ],

                'attendance' => [
                    'total_joined' => (int) Event::sum('joined'),
                ],

                'finance' => [
                    'total_infaq' => (float) InfaqReport::sum('amount'),
                    'total_zakat' => (float) ZakatReport::sum('nominal'),
                    'grand_total' => (float) (
                        InfaqReport::sum('amount') +
                        ZakatReport::sum('nominal')
                    ),
                ],
            ],
        ]);
    }
}
