'use client';

import { useEffect, useState } from 'react';

interface Props {
    nextPrayer: string;
    prayerTime: string;
    }

    export default function PrayerCountdown({
    nextPrayer,
    prayerTime,
    }: Props) {
    const [countdown, setCountdown] = useState('');

    useEffect(() => {
        const timer = setInterval(() => {
        const now = new Date();

        const [hour, minute] = prayerTime
            .split(':')
            .map(Number);

        const target = new Date();

        target.setHours(hour);
        target.setMinutes(minute);
        target.setSeconds(0);

        const diff = target.getTime() - now.getTime();

        if (diff <= 0) {
            setCountdown('Sudah masuk waktu');
            return;
        }

        const h = Math.floor(diff / 1000 / 60 / 60);
        const m = Math.floor((diff / 1000 / 60) % 60);
        const s = Math.floor((diff / 1000) % 60);

        setCountdown(
            `${h.toString().padStart(2, '0')}:${m
            .toString()
            .padStart(2, '0')}:${s
            .toString()
            .padStart(2, '0')}`
        );
        }, 1000);

        return () => clearInterval(timer);
    }, [prayerTime]);

    return (
        <div className="bg-white rounded-xl shadow p-5">
        <h3 className="text-lg font-semibold text-[#32CD32]">
            Waktu Sholat Berikutnya
        </h3>

        <p className="mt-2">
            {nextPrayer} ({prayerTime})
        </p>

        <p className="text-3xl font-bold mt-4">
            {countdown}
        </p>
        </div>
    );
}