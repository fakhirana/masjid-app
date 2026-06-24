'use client';

import { useState } from 'react';
import api from '@/lib/api-client';

export default function LoginPage() {
    const [username, setUsername] = useState('');
    const [password, setPassword] = useState('');

    const submit = async () => {
    try {
    const res = await api.post('/login', {
        username,
        password,
        });

        localStorage.setItem('token', res.data.data.token);
        window.location.href = '/dashboard';
    } catch (err: any) {
        alert('Login gagal');
    }
};

    return (
    <div className="min-h-screen flex items-center justify-center">
        <div className="w-96 space-y-3">
        <h1 className="text-2xl font-bold">Login</h1>

        <input
            className="w-full border p-2 rounded"
            placeholder="Username"
            value={username}
            onChange={(e) => setUsername(e.target.value)}
        />

        <input
            type="password"
            className="w-full border p-2 rounded"
            placeholder="Password"
            value={password}
            onChange={(e) => setPassword(e.target.value)}
        />

        <button
            onClick={submit}
            className="w-full bg-blue-600 text-white p-2 rounded"
        >
            Login
        </button>
        </div>
    </div>
    );
}
