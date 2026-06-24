'use client';

import { Bell, UserCircle } from 'lucide-react';

interface NavbarProps {
    name?: string;
}

export default function Navbar({
    name = 'User',
}: NavbarProps) {
    return (
    <nav className="bg-[#32CD32] text-white shadow-md">
        <div className="flex items-center justify-between px-6 py-4">

        {/* Logo */}
        <div>
            <h1 className="text-xl font-bold">
            Masjid Al-Amin
            </h1>
        </div>

        {/* Right Menu */}
        <div className="flex items-center gap-4">

          {/* Notifikasi */}
            <button className="relative hover:opacity-80">
            <Bell size={22} />

            <span className="
                absolute
                -top-1
                -right-1
                w-4
                h-4
                rounded-full
                bg-red-500
                text-[10px]
                flex
                items-center
                justify-center
            ">
                3
            </span>
            </button>

          {/* Profile */}
            <div className="flex items-center gap-2">
            <UserCircle size={32} />

            <div className="hidden md:block">
                <p className="text-sm">Assalamu'alaikum</p>
                <p className="font-semibold">
                {name}
                </p>
            </div>
        </div>

        </div>
        </div>
    </nav>
    );
}