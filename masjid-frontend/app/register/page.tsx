'use client';

import { useState } from 'react';
import api from '@/lib/api-client';

export default function RegisterPage() {
  const [name, setName] = useState('');
  const [username, setUsername] = useState('');
  const [password, setPassword] = useState('');
  const [passwordConfirmation, setPasswordConfirmation] = useState('');

  const submit = async () => {
    try {
      await api.post('/register', {
        name,
        username,
        password,
        password_confirmation: passwordConfirmation,
      });

      alert('Registrasi berhasil');
      window.location.href = '/login';
    } catch (err: any) {
      console.error(err);
      alert('Registrasi gagal');
    }
  };

  return (
    <div className="min-h-screen flex items-center justify-center">
      <div className="w-96 space-y-3">
        <h1 className="text-2xl font-bold">Register</h1>

        <input
          className="w-full border p-2 rounded"
          placeholder="Nama Lengkap"
          value={name}
          onChange={(e) => setName(e.target.value)}
        />

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

        <input
          type="password"
          className="w-full border p-2 rounded"
          placeholder="Konfirmasi Password"
          value={passwordConfirmation}
          onChange={(e) => setPasswordConfirmation(e.target.value)}
        />

        <button
          onClick={submit}
          className="w-full bg-green-600 text-white p-2 rounded"
        >
          Register
        </button>
      </div>
    </div>
  );
}