interface PrayerTimesProps {
    jadwal: {
        imsak: string;
        subuh: string;
        dzuhur: string;
        ashar: string;
        maghrib: string;
        isya: string;
    };
}

export default function PrayerTimes({
    jadwal,
    }: PrayerTimesProps) {
    const prayers = [
        { title: 'Imsak', value: jadwal.imsak },
        { title: 'Subuh', value: jadwal.subuh },
        { title: 'Dzuhur', value: jadwal.dzuhur },
        { title: 'Ashar', value: jadwal.ashar },
        { title: 'Maghrib', value: jadwal.maghrib },
        { title: 'Isya', value: jadwal.isya },
    ];

    return (
        <div className="bg-white rounded-xl shadow p-5">
        <h3 className="font-bold text-[#32CD32] mb-4">
            Jadwal Sholat Hari Ini
        </h3>

        <div className="grid grid-cols-2 md:grid-cols-6 gap-3">
            {prayers.map((prayer) => (
            <div
                key={prayer.title}
                className="bg-green-50 rounded-lg p-3 text-center"
            >
                <p className="text-sm text-gray-500">
                {prayer.title}
                </p>

                <p className="font-bold text-[#32CD32]">
                {prayer.value}
                </p>
            </div>
            ))}
        </div>
        </div>
    );
}