interface Announcement {
    id: number;
    title: string;
    content: string;
    }

    interface Props {
    announcements: Announcement[];
    }

    export default function AnnouncementList({
    announcements,
    }: Props) {
    return (
        <div className="bg-white rounded-xl shadow p-5">
        <h3 className="font-bold text-[#32CD32] mb-4">
            Pengumuman Terbaru
        </h3>

        <div className="space-y-3">
            {announcements?.slice(0, 5).map((item) => (
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
    );
}