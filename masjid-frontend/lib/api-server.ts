// lib/api-server.ts
export async function apiServer(path: string) {
    const res = await fetch(`http://localhost:8000/api${path}`, {
    cache: 'no-store',
    });

    if (!res.ok) {
    throw new Error('Failed to fetch');
    }

    return res.json();
}
