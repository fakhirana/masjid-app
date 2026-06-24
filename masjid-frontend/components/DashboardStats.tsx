interface DashboardStatsProps {
    totalEvents: number;
    totalInfaq: number;
    }

    export default function DashboardStats({
    totalEvents,
    totalInfaq,
    }: DashboardStatsProps) {
    return (
        <div className="grid md:grid-cols-2 gap-4">

        <div className="bg-white rounded-xl shadow p-5">
            <h3 className="font-semibold text-[#32CD32]">
            Total Event
            </h3>

            <p className="text-3xl font-bold">
            {totalEvents}
            </p>
        </div>

        <div className="bg-white rounded-xl shadow p-5">
            <h3 className="font-semibold text-[#32CD32]">
            Total Infaq
            </h3>

            <p className="text-3xl font-bold">
            Rp {Number(totalInfaq).toLocaleString('id-ID')}
            </p>
        </div>

        </div>
    );
}