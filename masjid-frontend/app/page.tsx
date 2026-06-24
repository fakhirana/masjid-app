// app/page.tsx
import { apiServer } from '@/lib/api-server';

export default async function HomePage() {
  const res = await apiServer('/public/dashboard/summary');
  const data = res.data;

  return (
    <main className="p-6 space-y-6">
      <h1 className="text-2xl font-bold">Dashboard Masjid</h1>

      <div className="bg-white rounded shadow p-4">
        <p className="font-semibold">{data.today.gregorian}</p>
        <p className="text-sm text-gray-600">{data.today.hijriah}</p>
      </div>

      <div className="bg-white rounded shadow p-4">
        <h2 className="font-semibold mb-2">Jadwal Sholat</h2>
        <ul className="grid grid-cols-2 gap-2 text-sm">
          {Object.entries(data.jadwal_sholat).map(([key, value]) => (
            <li key={key} className="flex justify-between">
              <span className="capitalize">{key.replace('_', ' ')}</span>
              <span>{value as string}</span>
            </li>
          ))}
        </ul>
      </div>
    </main>
  );
}
