'use client';

import Link from 'next/link';
import {
    LayoutDashboard,
    CalendarDays,
    Megaphone,
    Wallet,
    User,
    } from 'lucide-react';

    export default function Sidebar() {
    const menus = [
        {
        title: 'Dashboard',
        href: '/dashboard',
        icon: LayoutDashboard,
        },
        {
        title: 'Event',
        href: '/events',
        icon: CalendarDays,
        },
        {
        title: 'Pengumuman',
        href: '/announcements',
        icon: Megaphone,
        },
        {
        title: 'Infaq',
        href: '/infaq',
        icon: Wallet,
        },
        {
        title: 'Profil',
        href: '/profile',
        icon: User,
        },
    ];

    return (
        <aside className="w-64 bg-white shadow-lg min-h-screen">
        <div className="p-6">
            <h2 className="font-bold text-xl text-[#32CD32]">
            Masjid Digital
            </h2>
        </div>

        <nav className="space-y-2 px-4">
            {menus.map((menu) => {
            const Icon = menu.icon;

            return (
                <Link
                key={menu.title}
                href={menu.href}
                className="
                    flex items-center gap-3
                    p-3 rounded-lg
                    hover:bg-green-50
                    hover:text-[#32CD32]
                    transition
                "
                >
                <Icon size={20} />
                <span>{menu.title}</span>
                </Link>
            );
            })}
        </nav>
        </aside>
    );
}