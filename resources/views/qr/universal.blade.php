<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Google Review Terdekat</title>
    <style>
        *{box-sizing:border-box}body{margin:0;min-height:100vh;display:grid;place-items:center;padding:24px;background:#f3f6f4;color:#17221b;font:16px/1.5 system-ui,-apple-system,Segoe UI,sans-serif}.card{width:min(100%,440px);padding:30px;border-radius:22px;background:#fff;box-shadow:0 16px 50px #173a2014;text-align:center}.pin{width:58px;height:58px;margin:0 auto 18px;display:grid;place-items:center;border-radius:18px;background:#e8f5ec;color:#15803d;font-size:28px}h1{margin:0;font-size:23px}.desc{margin:10px 0 22px;color:#65716a}.status{min-height:48px;color:#49564e}.retry{display:none;margin-top:12px;padding:12px 20px;border:0;border-radius:10px;background:#15803d;color:#fff;font-weight:700;font-size:15px}
    </style>
</head>
<body>
<main class="card">
    <div class="pin" aria-hidden="true">⌖</div>
    <h1>Mencari Google Review</h1>
    <p class="desc">Izinkan akses lokasi agar kami menemukan tempat Google terdekat dan membuka halaman ulasannya.</p>
    <div class="status" id="status" role="status" aria-live="polite">Meminta izin lokasi…</div>
    <button class="retry" id="retry" type="button">Coba lagi</button>
</main>
<script>
const statusBox = document.getElementById('status');
const retryButton = document.getElementById('retry');
function fail(message) { statusBox.textContent = message; retryButton.style.display = 'inline-block'; }
function locate() {
    retryButton.style.display = 'none';
    statusBox.textContent = 'Meminta izin lokasi…';
    if (!('geolocation' in navigator)) { fail('Browser ini tidak mendukung akses lokasi.'); return; }
    navigator.geolocation.getCurrentPosition(async ({coords}) => {
        statusBox.textContent = 'Mencari tempat Google terdekat…';
        try {
            const response = await fetch(@json(route('qr.universal.locate')), {
                method: 'POST',
                headers: {'Content-Type':'application/json','Accept':'application/json','X-CSRF-TOKEN':document.querySelector('meta[name="csrf-token"]').content},
                body: JSON.stringify({latitude:coords.latitude,longitude:coords.longitude})
            });
            const data = await response.json();
            if (!response.ok) throw new Error(data.message || 'Tempat tidak ditemukan.');
            statusBox.textContent = `Membuka ulasan ${data.place_name}…`;
            window.location.replace(data.review_url);
        } catch (error) { fail(error.message || 'Pencarian gagal. Periksa koneksi lalu coba lagi.'); }
    }, error => {
        const messages = {1:'Izin lokasi ditolak. Aktifkan izin lokasi untuk halaman ini, lalu coba lagi.',2:'Lokasi belum tersedia. Pastikan GPS aktif, lalu coba lagi.',3:'Permintaan lokasi terlalu lama. Coba lagi.'};
        fail(messages[error.code] || 'Lokasi tidak dapat diakses. Coba lagi.');
    }, {enableHighAccuracy:true,timeout:20000,maximumAge:30000});
}
retryButton.addEventListener('click', locate);
locate();
</script>
</body>
</html>
