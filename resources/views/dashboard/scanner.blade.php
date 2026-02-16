@extends('layouts.app')
@section('page-title', 'QR Scanner')

@section('content')
    @push('styles')
        <style>
            /* Layout 2 kolom: kamera kiri, hasil kanan */
            .scanner-layout {
                display: grid;
                grid-template-columns: 1fr 1fr;
                gap: 24px;
                align-items: start;
            }

            /* Card scanner kamera */
            .scanner-card {
                background: #fff;
                border-radius: 12px;
                padding: 24px;
                box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
            }

            .scanner-card h3 {
                font-size: 16px;
                font-weight: 600;
                margin-bottom: 16px;
            }

            /* Container video kamera */
            .camera-container {
                position: relative;
                width: 100%;
                aspect-ratio: 4/3;
                background: #1a1a1a;
                border-radius: 10px;
                overflow: hidden;
                margin-bottom: 16px;
            }

            .camera-container video {
                width: 100%;
                height: 100%;
                object-fit: cover;
            }

            /* Overlay garis pemindai di atas video */
            .scan-overlay {
                position: absolute;
                top: 50%;
                left: 50%;
                transform: translate(-50%, -50%);
                width: 200px;
                height: 200px;
                border: 2px solid #F5D547;
                border-radius: 12px;
                pointer-events: none;
            }

            .scan-overlay::before {
                content: '';
                position: absolute;
                top: 0;
                left: 0;
                right: 0;
                height: 2px;
                background: #F5D547;
                animation: scanLine 2s ease-in-out infinite;
            }

            @keyframes scanLine {

                0%,
                100% {
                    top: 0;
                }

                50% {
                    top: calc(100% - 2px);
                }
            }

            /* Tombol kontrol kamera */
            .camera-controls {
                display: flex;
                gap: 8px;
                margin-bottom: 16px;
            }

            .camera-controls .btn {
                flex: 1;
                justify-content: center;
            }

            /* Status kamera */
            .camera-status {
                text-align: center;
                font-size: 13px;
                color: #999;
                padding: 8px 0;
            }

            /* Input manual QR code */
            .manual-input {
                display: flex;
                gap: 8px;
                margin-top: 16px;
                padding-top: 16px;
                border-top: 1px solid #f0f0f0;
            }

            .manual-input input {
                flex: 1;
                padding: 10px 14px;
                border: 1px solid #e0e0e0;
                border-radius: 8px;
                font-size: 14px;
                font-family: inherit;
                background: #FAFAF5;
            }

            .manual-input input:focus {
                outline: none;
                border-color: #F5D547;
            }

            /* Card hasil pencarian QR */
            .result-card {
                background: #FAFAF5;
                border: 1px solid #e8e0d0;
                border-radius: 12px;
                padding: 24px;
                display: none;
            }

            .result-card.show {
                display: block;
            }

            .result-card h3 {
                font-size: 16px;
                font-weight: 600;
                margin-bottom: 16px;
            }

            /* Baris detail tamu */
            .result-row {
                display: flex;
                justify-content: space-between;
                padding: 10px 0;
                border-bottom: 1px solid #f0f0f0;
                font-size: 14px;
            }

            .result-row:last-of-type {
                border-bottom: none;
            }

            .result-label {
                color: #888;
                font-weight: 500;
            }

            .result-value {
                font-weight: 600;
                color: #333;
            }



            /* Alert feedback */
            .alert-box {
                padding: 12px 16px;
                border-radius: 8px;
                font-size: 14px;
                margin-bottom: 16px;
                display: none;
            }

            .alert-success {
                background: #D4EDDA;
                color: #155724;
            }

            .alert-error {
                background: #FDE8E8;
                color: #721c24;
            }

            /* Placeholder saat belum ada hasil */
            .empty-result {
                text-align: center;
                color: #bbb;
                padding: 40px 20px;
            }

            .empty-result span {
                font-size: 48px;
                display: block;
                margin-bottom: 12px;
            }

            /* Responsive: stack vertikal di mobile */
            @media (max-width: 768px) {
                .scanner-layout {
                    grid-template-columns: 1fr;
                }
            }
        </style>
    @endpush

    <div class="scanner-layout">
        <!-- Kolom kiri: Kamera dan input manual -->
        <div>
            <div class="scanner-card">
                <h3>📷 Kamera Scanner</h3>

                <div class="camera-container" id="cameraContainer">
                    <video id="cameraVideo" autoplay playsinline muted></video>
                    <div class="scan-overlay" id="scanOverlay" style="display:none;"></div>
                </div>

                <div class="camera-controls">
                    <button class="btn btn-primary" id="startCameraBtn" onclick="startCamera()">📷 Nyalakan Kamera</button>
                    <button class="btn btn-outline" id="stopCameraBtn" onclick="stopCamera()" style="display:none;">⏹️
                        Matikan</button>
                </div>

                <p class="camera-status" id="cameraStatus">Klik tombol di atas untuk menyalakan kamera</p>

                <div class="manual-input">
                    <input type="text" id="qrInput" placeholder="Atau ketik kode QR manual..." autofocus>
                    <button class="btn btn-primary" onclick="validateQR()">Konfirmasi</button>
                </div>
            </div>
        </div>

        <!-- Kolom kanan: Hasil validasi dan check-in -->
        <div>
            <div class="scanner-card">
                <h3>📋 Hasil Scan</h3>

                <div class="alert-box alert-success" id="alertSuccess"></div>
                <div class="alert-box alert-error" id="alertError"></div>

                <!-- Card detail tamu dari QR code -->
                <div class="result-card" id="resultCard">
                    <div class="result-row">
                        <span class="result-label">Nama</span>
                        <span class="result-value" id="resName">-</span>
                    </div>
                    <div class="result-row">
                        <span class="result-label">Kategori</span>
                        <span class="result-value" id="resCategory">-</span>
                    </div>
                    <div class="result-row">
                        <span class="result-label">RSVP Status</span>
                        <span class="result-value" id="resRSVP">-</span>
                    </div>
                    <div class="result-row">
                        <span class="result-label">Jumlah Tamu</span>
                        <span class="result-value" id="resCount">-</span>
                    </div>
                    <div class="result-row">
                        <span class="result-label">Check-in</span>
                        <span class="result-value" id="resCheckIn">-</span>
                    </div>


                </div>

                <!-- Placeholder saat belum ada scan -->
                <div class="empty-result" id="emptyResult">
                    <span>📱</span>
                    <p>Scan QR code atau ketik kode manual untuk melihat detail tamu</p>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            const CSRF = document.querySelector('meta[name="csrf-token"]').content;
            let currentGuestId = null;
            let cameraStream = null;
            let scanInterval = null;

            /* Submit QR code saat tekan Enter di input manual */
            document.getElementById('qrInput').addEventListener('keydown', function (e) {
                if (e.key === 'Enter') validateQR();
            });

            /* Menampilkan alert feedback sementara (4 detik) */
            function showAlert(type, msg) {
                const el = document.getElementById(type === 'success' ? 'alertSuccess' : 'alertError');
                el.textContent = msg;
                el.style.display = 'block';
                setTimeout(function () { el.style.display = 'none'; }, 4000);
            }

            /* Menyalakan kamera device dan mulai streaming video */
            function startCamera() {
                const video = document.getElementById('cameraVideo');
                const status = document.getElementById('cameraStatus');

                if (!navigator.mediaDevices || !navigator.mediaDevices.getUserMedia) {
                    status.textContent = '❌ Browser tidak mendukung kamera';
                    return;
                }

                status.textContent = '⏳ Membuka kamera...';

                navigator.mediaDevices.getUserMedia({
                    video: { facingMode: 'environment', width: { ideal: 640 }, height: { ideal: 480 } }
                })
                    .then(function (stream) {
                        cameraStream = stream;
                        video.srcObject = stream;
                        document.getElementById('scanOverlay').style.display = 'block';
                        document.getElementById('startCameraBtn').style.display = 'none';
                        document.getElementById('stopCameraBtn').style.display = 'inline-flex';
                        status.textContent = '🟢 Kamera aktif — arahkan ke QR code';

                        startQRScanning();
                    })
                    .catch(function (err) {
                        status.textContent = '❌ Gagal akses kamera: ' + err.message;
                    });
            }

            /* Mematikan kamera dan menghentikan semua track */
            function stopCamera() {
                if (cameraStream) {
                    cameraStream.getTracks().forEach(function (t) { t.stop(); });
                    cameraStream = null;
                }
                if (scanInterval) {
                    clearInterval(scanInterval);
                    scanInterval = null;
                }
                document.getElementById('cameraVideo').srcObject = null;
                document.getElementById('scanOverlay').style.display = 'none';
                document.getElementById('startCameraBtn').style.display = 'inline-flex';
                document.getElementById('stopCameraBtn').style.display = 'none';
                document.getElementById('cameraStatus').textContent = 'Kamera dimatikan';
            }

            /* Mulai proses scanning QR dari frame kamera menggunakan canvas */
            function startQRScanning() {
                const video = document.getElementById('cameraVideo');
                const canvas = document.createElement('canvas');
                const ctx = canvas.getContext('2d');

                scanInterval = setInterval(function () {
                    if (!cameraStream || video.readyState !== video.HAVE_ENOUGH_DATA) return;

                    canvas.width = video.videoWidth;
                    canvas.height = video.videoHeight;
                    ctx.drawImage(video, 0, 0, canvas.width, canvas.height);

                    try {
                        const imageData = ctx.getImageData(0, 0, canvas.width, canvas.height);
                        if (typeof jsQR !== 'undefined') {
                            const code = jsQR(imageData.data, imageData.width, imageData.height);
                            if (code && code.data) {
                                document.getElementById('qrInput').value = code.data;
                                validateQR();
                                clearInterval(scanInterval);
                                setTimeout(function () { startQRScanning(); }, 3000);
                            }
                        }
                    } catch (e) { /* jsQR belum dimuat, scanning tidak berjalan */ }
                }, 500);
            }

            /* Validasi QR code via API POST /api/check-in dengan action=validate */
            function validateQR() {
                const qr = document.getElementById('qrInput').value.trim();
                if (!qr) return;

                document.getElementById('resultCard').classList.remove('show');
                document.getElementById('emptyResult').style.display = 'none';

                fetch('/api/check-in', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': CSRF,
                    },
                    body: JSON.stringify({ action: 'validate', qrCodeString: qr }),
                })
                    .then(function (r) {
                        if (!r.ok) throw new Error('QR Code tidak ditemukan.');
                        return r.json();
                    })
                    .then(function (data) {
                        const g = data.guest;
                        currentGuestId = g.id;
                        document.getElementById('resName').textContent = g.name;
                        document.getElementById('resCategory').textContent = g.category;
                        document.getElementById('resRSVP').textContent = g.rsvp_status;
                        document.getElementById('resCount').textContent = g.guest_count || 1;
                        document.getElementById('resultCard').classList.add('show');

                        /* Auto check-in jika tamu belum check-in */
                        if (!g.check_in_time) {
                            document.getElementById('resCheckIn').textContent = '⏳ Memproses...';
                            confirmCheckIn();
                        } else {
                            document.getElementById('resCheckIn').textContent = '✅ Sudah Check-in';
                        }
                    })
                    .catch(function (err) {
                        showAlert('error', err.message);
                        document.getElementById('emptyResult').style.display = 'block';
                    });
            }

            /* Proses check-in otomatis via API POST /api/check-in */
            function confirmCheckIn() {
                if (!currentGuestId) return;

                fetch('/api/check-in', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': CSRF,
                    },
                    body: JSON.stringify({
                        action: 'checkin',
                        guestId: currentGuestId,
                    }),
                })
                    .then(function (r) { return r.json(); })
                    .then(function () {
                        showAlert('success', 'Check-in berhasil! 🎉');
                        document.getElementById('resCheckIn').textContent = '✅ Sudah Check-in';
                        document.getElementById('qrInput').value = '';
                        document.getElementById('qrInput').focus();
                    })
                    .catch(function (err) {
                        showAlert('error', 'Gagal check-in: ' + err.message);
                        document.getElementById('resCheckIn').textContent = 'Belum';
                    });
            }
        </script>
        <script src="https://cdn.jsdelivr.net/npm/jsqr@1.4.0/dist/jsQR.min.js"></script>
    @endpush
@endsection