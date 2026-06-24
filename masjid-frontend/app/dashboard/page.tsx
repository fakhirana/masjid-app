'use client';

import { useEffect, useState } from 'react';
import { useRouter } from 'next/navigation';

import apiClient from '@/lib/api-client';
import Navbar from '@/components/Navbar';
import PrayerCountdown from '@/components/PrayerCountdown';
import Sidebar from '@/components/Sidebar';
import AnnouncementList from '@/components/AnnouncementList';
import DashboardStats from '@/components/DashboardStats';
import PrayerTimes from '@/components/PrayerTimes';

export default function Dashboard() {
    const [data, setData] = useState<any>(null);
    const router = useRouter();

    useEffect(() => {
        Promise.all([
        apiClient.get('/me'),
        apiClient.get('/public/dashboard/summary'),
        apiClient.get('/public/announcements'),
        apiClient.get('/public/infaq'),
        ])
        .then(([me, dashboard, announcements, infaq]) => {
            setData({
            user: me.data.data.user,
            verification: me.data.data.warga_verification,
            dashboard: dashboard.data.data,
            announcements: announcements.data.data,
            infaq: infaq.data.data,
            });
        })
        .catch(() => {
            router.replace('/login');
        });
    }, [router]);

    if (!data) {
        return (
        <div className="min-h-screen flex items-center justify-center">
            Loading...
        </div>
        );
    }

    const jadwal = data.dashboard.jadwal_sholat;

    return (
        <div className="min-h-screen bg-gray-100">
        <Navbar name={data.user.name} />

        <div className="flex">
            <Sidebar />

            <main className="flex-1 p-6 space-y-6">

            {/* Greeting */}
            <div className="bg-white rounded-xl shadow p-5">
                <h2 className="text-xl font-bold">
                Assalamu'alaikum, {data.user.name}
                </h2>

                <p className="text-gray-500">
                Role: {data.user.role}
                </p>

                {data.verification && (
                <p className="text-sm text-green-600 mt-2">
                    Status Verifikasi: {data.verification.status}
                </p>
                )}
            </div>

            {/* Tanggal */}
            <div className="grid md:grid-cols-2 gap-4">
                <div className="bg-white rounded-xl shadow p-5">
                <h3 className="font-semibold text-[#32CD32]">
                    Tanggal Masehi
                </h3>

                <p className="text-lg font-bold">
                    {data.dashboard.today.gregorian}
                </p>
                </div>

                <div className="bg-white rounded-xl shadow p-5">
                <h3 className="font-semibold text-[#32CD32]">
                    Tanggal Hijriah
                </h3>

                <p className="text-lg font-bold">
                    {data.dashboard.today.hijriah}
                </p>
                </div>
            </div>

            {/* Jadwal Sholat */}
            <div className="bg-white rounded-xl shadow p-5">
                <h3 className="font-bold text-[#32CD32] mb-4">
                Jadwal Sholat Hari Ini
                </h3>

                <div className="grid grid-cols-2 md:grid-cols-6 gap-3">
                <PrayerCard title="Imsak" value={jadwal.imsak} />
                <PrayerCard title="Subuh" value={jadwal.subuh} />
                <PrayerCard title="Dzuhur" value={jadwal.dzuhur} />
                <PrayerCard title="Ashar" value={jadwal.ashar} />
                <PrayerCard title="Maghrib" value={jadwal.maghrib} />
                <PrayerCard title="Isya" value={jadwal.isya} />
                </div>
            </div>

            {/* Statistik */}
            <div className="grid md:grid-cols-2 gap-4">
                <div className="bg-white rounded-xl shadow p-5">
                <h3 className="font-semibold text-[#32CD32]">
                    Total Event
                </h3>

                <p className="text-3xl font-bold">
                    {data.dashboard.events.total}
                </p>
                </div>

                <div className="bg-white rounded-xl shadow p-5">
                <h3 className="font-semibold text-[#32CD32]">
                    Total Infaq
                </h3>

                <p className="text-3xl font-bold">
                    Rp{' '}
                    {Number(
                    data.dashboard.finance.grand_total
                    ).toLocaleString('id-ID')}
                </p>
                </div>
            </div>

            {/* Pengumuman */}
            <div className="bg-white rounded-xl shadow p-5">
                <h3 className="font-bold text-[#32CD32] mb-4">
                Pengumuman Terbaru
                </h3>

                <div className="space-y-3">
                {data.announcements?.slice(0, 5).map((item: any) => (
                    <div
                    key={item.id}
                    className="border-l-4 border-[#32CD32] pl-4"
                    >
                    <h4 className="font-semibold">
                        {item.title}
                    </h4>

                    <p className="text-sm text-gray-600">
                        {item.content}
                    </p>
                    </div>
                ))}
                </div>
            </div>

            </main>
        </div>
        </div>
    );
    }

    function PrayerCard({
    title,
    value,
    }: {
    title: string;
    value: string;
    }) {
    return (
        <div className="bg-green-50 rounded-lg p-3 text-center">
        <p className="text-sm text-gray-500">
            {title}
        </p>

        <p className="font-bold text-[#32CD32]">
            {value}
        </p>
        </div>
    );
}