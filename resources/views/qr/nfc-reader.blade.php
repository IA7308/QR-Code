<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1">
    <title>QR Universal NFC</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-gray-50 text-gray-900">
    <main class="mx-auto flex min-h-screen max-w-xl items-center px-4 py-10">
        <article class="w-full rounded-2xl bg-white p-6 shadow-sm sm:p-10">
            <p class="text-xs font-bold uppercase tracking-wide text-green-700">QR Universal</p>
            <h1 class="mt-2 text-2xl font-bold sm:text-3xl">Tempelkan NFC untuk membuka Google Review</h1>
            <p class="mt-3 leading-7 text-gray-600">QR ini adalah pintu universal. Tekan tombol, lalu dekatkan HP ke kartu NFC yang ingin digunakan. Sistem membaca URL unik NFC tersebut: jika belum aktif, Anda diminta mengisi tempat; jika sudah aktif, langsung menuju Google Review.</p>
            <button id="read-nfc" type="button" style="background-color:#15803d;color:#ffffff;border:1px solid #166534;" class="mt-6 min-h-12 w-full rounded-lg px-5 py-3 text-base font-bold shadow-sm hover:brightness-90">Baca kartu NFC</button>
            <p id="nfc-status" role="status" aria-live="polite" class="mt-4 rounded-lg bg-gray-50 p-3 text-sm text-gray-700">Gunakan Chrome di Android dengan NFC aktif. Halaman harus dibuka melalui HTTPS.</p>
            <p class="mt-4 text-xs leading-5 text-gray-500">Jika HP tidak mendukung pembacaan NFC dari browser, tempelkan kartu NFC secara langsung ke HP. URL unik pada kartu akan membuka alur aktivasi atau Google Review yang sama.</p>
        </article>
    </main>
    <script>
        (() => {
            const button = document.getElementById('read-nfc');
            const status = document.getElementById('nfc-status');
            const setStatus = (message, error = false) => {
                status.textContent = message;
                status.className = `mt-4 rounded-lg p-3 text-sm ${error ? 'bg-red-50 text-red-700' : 'bg-gray-50 text-gray-700'}`;
            };

            button.addEventListener('click', async () => {
                if (!('NDEFReader' in window)) {
                    setStatus('Browser ini belum mendukung pembacaan NFC. Gunakan Chrome Android, atau tempelkan NFC langsung ke HP.', true);
                    return;
                }

                button.disabled = true;
                button.textContent = 'Menunggu kartu NFC…';
                setStatus('Dekatkan kartu NFC ke bagian belakang HP. Izinkan akses NFC jika diminta.');
                try {
                    const reader = new NDEFReader();
                    await reader.scan();
                    reader.addEventListener('reading', event => {
                        const record = [...event.message.records].find(item => item.recordType === 'url');
                        if (!record) {
                            setStatus('Data URL tidak ditemukan di kartu NFC ini.', true);
                            return;
                        }

                        let rawUrl = typeof record.data === 'string'
                            ? record.data
                            : new TextDecoder().decode(record.data);
                        try {
                            const parsed = new URL(rawUrl, window.location.origin);
                            const match = parsed.pathname.match(/^\/n\/([a-f0-9]{48})\/?$/i);
                            if (!match) throw new Error('invalid NFC URL');
                            window.location.assign(`/n/${match[1].toLowerCase()}`);
                        } catch (error) {
                            setStatus('Kartu NFC tidak berisi URL unit Dynamic QR yang valid.', true);
                            button.disabled = false;
                            button.textContent = 'Coba baca lagi';
                        }
                    }, { once: true });
                    reader.addEventListener('readingerror', () => setStatus('Kartu tidak dapat dibaca. Coba dekatkan kembali atau gunakan NFC langsung.', true));
                } catch (error) {
                    setStatus(error.name === 'NotAllowedError'
                        ? 'Izin NFC ditolak. Izinkan NFC pada browser lalu coba lagi.'
                        : 'NFC gagal dimulai. Pastikan HP mendukung NFC, NFC aktif, dan halaman dibuka melalui HTTPS.', true);
                    button.disabled = false;
                    button.textContent = 'Coba baca lagi';
                }
            });
        })();
    </script>
</body>
</html>
