<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Event;
use App\Models\InfaqReport;
use App\Models\ZakatReport;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class DashboardController extends Controller
{
    use AuthorizesRequests;

    /**
     * DASHBOARD PUBLIK (Tanpa Auth)
     */
    public function publicSummary(): JsonResponse
    {
        return response()->json([
            'status'  => 'success',
            'message' => 'Public dashboard summary retrieved successfully',
            'data'    => $this->buildDashboardData(),
        ]);
    }

    /**
     * DASHBOARD ADMIN (Pengurus)
     */
    public function adminSummary(): JsonResponse
    {
        $this->authorize('viewDashboard', User::class);

        return response()->json([
            'status'  => 'success',
            'message' => 'Admin dashboard summary retrieved successfully',
            'data'    => $this->buildDashboardData(),
        ]);
    }

    /**
     * CORE LOGIC + CACHE (5 MENIT)
     */
    private function buildDashboardData(): array
    {
        return Cache::remember(
            'dashboard.summary',
            now()->addMinutes(5),
            function () {

                // === JADWAL SHOLAT + HIJRI ===
                $response = Http::get('https://api.aladhan.com/v1/timingsByCity', [
                    'city'    => 'Bandung',
                    'country' => 'Indonesia',
                    'method'  => 20,
                ]);

                $timings = $response->json('data.timings');
                $hijri   = $response->json('data.date.hijri');

                // === DETEKSI RAMADHAN ===
                $isRamadhan = ((int) $hijri['month']['number'] === 9);

                // === TANGGAL ===
                $now = Carbon::now()->locale('id');

                $dateInfo = [
                    'gregorian' => $now->translatedFormat('l, d F Y'),
                    'hijriah' => $hijri['day'] . ' ' . $hijri['month']['en'] . ' ' . $hijri['year'] . ' H',
                ];

                // === JADWAL SHOLAT ===
                $jadwalSholat = [
                    'subuh'   => $timings['Fajr']    ?? '-',
                    'dzuhur'  => $timings['Dhuhr']   ?? '-',
                    'ashar'   => $timings['Asr']     ?? '-',
                    'maghrib' => $timings['Maghrib'] ?? '-',
                    'isya'    => $timings['Isha']    ?? '-',
                ];

                // === TAMBAHAN KHUSUS RAMADHAN ===
                if ($isRamadhan) {
                    $jadwalSholat['imsak']      = $timings['Imsak']   ?? '-';
                    $jadwalSholat['sahur']      = $timings['Fajr']    ?? '-';
                    $jadwalSholat['buka_puasa'] = $timings['Maghrib'] ?? '-';
                }

                // === DATA DASHBOARD ===
                return [
                    'today' => $dateInfo,

                    'is_ramadhan' => $isRamadhan,

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
                ];
            }
        );
    }
}