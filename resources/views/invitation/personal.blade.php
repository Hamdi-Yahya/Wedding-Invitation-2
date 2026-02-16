@extends('layouts.invitation')

@section('content')
    <!-- Hero wrapper dengan navbar dan hero section -->
    <div class="hero-wrapper">
        <nav class="inv-navbar" id="navbar">
            <div class="nav-container">
                <a href="{{ route('home') }}" class="nav-logo">
                    <span class="logo-a">{{ substr($event->partner_1_name ?? 'A', 0, 1) }}</span>
                    <span class="logo-s">{{ substr($event->partner_2_name ?? 'S', 0, 1) }}</span>
                    <span class="logo-text">wedding</span>
                </a>
                <div class="nav-links" id="navLinks">
                    <a href="#mempelai">MEMPELAI</a>
                    <a href="#acara">ACARA</a>
                    <a href="#rsvp" class="nav-btn">RSVP</a>
                </div>
                <button class="hamburger" onclick="toggleMobileNav()">
                    <span></span>
                    <span></span>
                    <span></span>
                </button>
            </div>
        </nav>

        <!-- Hero section personal dengan nama tamu -->
        <section class="hero-section">
            <div class="hero-container">
                <div class="hero-left">
                    <h1 class="hero-title">
                        Remember<br>the special moment
                    </h1>
                    <p class="hero-guest-name">Dear, <strong>{{ $guest->name }}</strong></p>
                    <p class="hero-subtitle">
                        {{ $event->tagline ?? 'Kami mengundang Anda untuk merayakan hari bahagia kami.' }}
                    </p>
                    <a href="#rsvp" class="btn-hero">BUKA UNDANGAN</a>
                </div>
                <div class="hero-right">
                    @if ($gallery->isNotEmpty())
                        <img src="{{ asset($gallery->first()->image_url) }}"
                            alt="{{ $gallery->first()->alt_text ?? 'Wedding Photo' }}" class="hero-image"
                            style="object-position: {{ $gallery->first()->object_position ?? 'center' }};">
                    @else
                        <div class="hero-image-placeholder">
                            <span>📷</span>
                            <p>No photo yet</p>
                        </div>
                    @endif
                </div>
            </div>
        </section>
    </div>

    <!-- Countdown menuju hari pernikahan -->
    <section class="countdown-section">
        <div class="section-container">
            <p class="countdown-label">Menghitung Hari</p>
            <div class="countdown-timer" id="countdown">
                <div class="countdown-item">
                    <span class="countdown-value" id="days">0</span>
                    <span class="countdown-unit">HARI</span>
                </div>
                <div class="countdown-item">
                    <span class="countdown-value" id="hours">0</span>
                    <span class="countdown-unit">JAM</span>
                </div>
                <div class="countdown-item">
                    <span class="countdown-value" id="minutes">0</span>
                    <span class="countdown-unit">MENIT</span>
                </div>
                <div class="countdown-item">
                    <span class="countdown-value" id="seconds">0</span>
                    <span class="countdown-unit">DETIK</span>
                </div>
            </div>
            <p class="countdown-tagline">
                {{ $event->tagline ?? 'Setiap detik membawa kami lebih dekat ke hari yang kami impikan.' }}
            </p>
        </div>
    </section>

    <!-- Section profil mempelai -->
    <section class="mempelai-section" id="mempelai">
        <div class="section-container">
            <p class="section-pretitle">Insya Allah</p>
            <h2 class="section-title-bold">MEMPELAI</h2>
            <p class="section-desc">Dengan memohon rahmat dan ridho Allah SWT, kami bermaksud menyelenggarakan acara
                pernikahan kami.</p>

            <div class="mempelai-grid">
                <div class="mempelai-item">
                    <div class="mempelai-photo">
                        @if ($gallery->where('label', 'partner1')->first())
                            <img src="{{ asset($gallery->where('label', 'partner1')->first()->image_url) }}"
                                alt="{{ $event->partner_1_name ?? '' }}">
                        @else
                            <div class="mempelai-photo-placeholder">👰</div>
                        @endif
                    </div>
                    <h3 class="mempelai-name">{{ $event->partner_1_name ?? 'Nama Partner 1' }}</h3>
                    <p class="mempelai-parent">Putri dari Bapak {{ $event->partner_1_father ?? '...' }} & Ibu
                        {{ $event->partner_1_mother ?? '...' }}</p>
                </div>

                <span class="mempelai-separator">&</span>

                <div class="mempelai-item">
                    <div class="mempelai-photo">
                        @if ($gallery->where('label', 'partner2')->first())
                            <img src="{{ asset($gallery->where('label', 'partner2')->first()->image_url) }}"
                                alt="{{ $event->partner_2_name ?? '' }}">
                        @else
                            <div class="mempelai-photo-placeholder">🤵</div>
                        @endif
                    </div>
                    <h3 class="mempelai-name">{{ $event->partner_2_name ?? 'Nama Partner 2' }}</h3>
                    <p class="mempelai-parent">Putra dari Bapak {{ $event->partner_2_father ?? '...' }} & Ibu
                        {{ $event->partner_2_mother ?? '...' }}</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Section detail acara (akad & resepsi) -->
    <section class="acara-section" id="acara">
        <div class="section-container">
            <p class="section-pretitle">Waktu & Tempat</p>
            <h2 class="section-title-bold">ACARA</h2>
            <div class="acara-ornament">✦ ✦ ✦</div>

            @php
                $eventDay = $event->event_date ? $event->event_date->format('d') : '--';
                $eventMonth = $event->event_date
                    ? $event->event_date->locale('id')->translatedFormat('F')
                    : '---';
                $eventYear = $event->event_date ? $event->event_date->format('Y') : '----';
            @endphp

            <div class="acara-date-circle">
                <span class="acara-date-day">{{ $eventDay }}</span>
                <span class="acara-date-month">{{ $eventMonth }}</span>
                <span class="acara-date-year">{{ $eventYear }}</span>
            </div>

            <div class="acara-grid">
                <div class="acara-card">
                    <h3 class="acara-card-title">{{ $details->ceremony_title ?? 'Akad Nikah' }}</h3>
                    <p class="acara-card-time">{{ $details->ceremony_time ?? $event->start_time ?? '08:00' }}</p>
                    <p class="acara-card-venue">{{ $details->ceremony_venue ?? $event->venue_name ?? '-' }}</p>
                </div>
                <div class="acara-card">
                    <h3 class="acara-card-title">{{ $details->reception_title ?? 'Resepsi' }}</h3>
                    <p class="acara-card-time">{{ $details->reception_time ?? $event->end_time ?? '11:00' }}</p>
                    <p class="acara-card-venue">{{ $details->reception_venue ?? $event->venue_name ?? '-' }}</p>
                    @if ($details->reception_note ?? false)
                        <p class="acara-card-venue">{{ $details->reception_note }}</p>
                    @endif
                </div>

                @if ($details && ($details->dress_code_title || $details->dress_code_note))
                    <div class="acara-card">
                        <h3 class="acara-card-title">{{ $details->dress_code_title ?? 'Dress Code' }}</h3>
                        <p class="acara-card-venue">{{ $details->dress_code_note ?? '' }}</p>
                        @if ($details->dress_code_style1 || $details->dress_code_style2)
                            <div class="dresscode-styles">
                                @if ($details->dress_code_style1)
                                    <span class="dresscode-label">{{ $details->dress_code_style1 }}</span>
                                @endif
                                @if ($details->dress_code_style2)
                                    <span class="dresscode-label">{{ $details->dress_code_style2 }}</span>
                                @endif
                            </div>
                        @endif
                    </div>
                @endif
            </div>

            <div class="acara-location">
                <h4 class="acara-location-name">📍 {{ $event->venue_name ?? 'Nama Venue' }}</h4>
                <p class="acara-location-address">{{ $event->venue_address ?? 'Alamat Venue' }}</p>
                @if ($event->map_link_url)
                    <a href="{{ $event->map_link_url }}" target="_blank" class="btn-map">BUKA PETA</a>
                @endif
            </div>
        </div>
    </section>

    <!-- Section RSVP personal (nama sudah terisi otomatis) -->
    <section class="rsvp-section" id="rsvp">
        <div class="section-container">
            <div class="rsvp-card">
                <h2 class="rsvp-title">RSVP</h2>
                <p class="rsvp-subtitle">Mohon konfirmasi kehadiran Anda</p>
                <div class="rsvp-form">
                    <div class="form-group">
                        <label>NAMA</label>
                        <input type="text" id="rsvpName" value="{{ $guest->name }}" readonly>
                    </div>
                    <div class="form-group">
                        <label>NOMOR HP</label>
                        <input type="tel" id="rsvpPhone" placeholder="Contoh: 08123456789"
                            value="{{ $guest->phone_number ?? '' }}" inputmode="numeric"
                            oninput="this.value = this.value.replace(/[^0-9]/g, '')">
                    </div>
                    <div class="form-group">
                        <label>KONFIRMASI</label>
                        <select id="rsvpStatus">
                            <option value="Coming" {{ $guest->rsvp_status === 'Coming' ? 'selected' : '' }}>Hadir</option>
                            <option value="Not Coming" {{ $guest->rsvp_status === 'Not Coming' ? 'selected' : '' }}>Tidak
                                Hadir</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>UCAPAN (OPSIONAL)</label>
                        <textarea id="rsvpComment" rows="3" placeholder="Tulis ucapan untuk mempelai..."></textarea>
                    </div>
                    <button class="btn-submit" id="rsvpBtn" onclick="submitRSVP()">✉️ KIRIM KONFIRMASI</button>
                </div>
            </div>
        </div>
    </section>

    <!-- Section QR Code untuk verifikasi kehadiran -->
    <section class="qrcode-section">
        <div class="section-container">
            <h2 class="qr-title">QR Code Anda</h2>
            <p class="qr-subtitle">Tunjukkan QR code ini saat hadir di acara</p>
            <div class="qr-display">
                <img src="https://api.qrserver.com/v1/create-qr-code/?size=180x180&data={{ $guest->qr_code_string }}"
                    alt="QR Code {{ $guest->name }}">
                <span class="qr-code-text">{{ $guest->qr_code_string }}</span>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="inv-footer">
        <span class="footer-initials">
            {{ substr($event->partner_1_name ?? 'A', 0, 1) }} &
            {{ substr($event->partner_2_name ?? 'S', 0, 1) }}
        </span>
    </footer>

    @push('scripts')
        <script>
            /* Menjalankan countdown timer menuju hari pernikahan */
            function startCountdown() {
                const eventDate = new Date(
                    '{{ $event->event_date ? $event->event_date->format('Y-m-d') : '' }}T00:00:00').getTime();
                if (isNaN(eventDate)) return;

                setInterval(function () {
                    const now = new Date().getTime();
                    const diff = eventDate - now;
                    if (diff <= 0) return;

                    document.getElementById('days').textContent = Math.floor(diff / (1000 * 60 * 60 * 24));
                    document.getElementById('hours').textContent = Math.floor((diff % (1000 * 60 * 60 * 24)) / (
                        1000 * 60 * 60));
                    document.getElementById('minutes').textContent = Math.floor((diff % (1000 * 60 * 60)) / (1000 *
                        60));
                    document.getElementById('seconds').textContent = Math.floor((diff % (1000 * 60)) / 1000);
                }, 1000);
            }

            /* Mengirim RSVP personal via slug dan optional ucapan */
            function submitRSVP() {
                const btn = document.getElementById('rsvpBtn');
                const status = document.getElementById('rsvpStatus').value;
                const comment = document.getElementById('rsvpComment').value.trim();

                if (btn.disabled) return;
                btn.disabled = true;
                btn.textContent = '⏳ MENGIRIM...';

                fetch('/api/rsvp', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': CSRF_TOKEN,
                    },
                    body: JSON.stringify({
                        slug: '{{ $guest->slug }}',
                        rsvpStatus: status,
                        phoneNumber: document.getElementById('rsvpPhone').value.trim(),
                    }),
                })
                    .then(function (r) {
                        if (!r.ok) throw new Error('Server error: ' + r.status);
                        return r.json();
                    })
                    .then(function (res) {
                        if (comment) {
                            return fetch('/api/wishes', {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'Accept': 'application/json',
                                    'X-CSRF-TOKEN': CSRF_TOKEN,
                                },
                                body: JSON.stringify({
                                    name: '{{ $guest->name }}',
                                    message: comment,
                                }),
                            }).then(function () {
                                return res;
                            });
                        }
                        return res;
                    })
                    .then(function () {
                        alert('Terima kasih! Konfirmasi Anda telah kami terima.');
                        btn.textContent = '✅ TERKIRIM';
                    })
                    .catch(function (err) {
                        alert('Gagal mengirim: ' + err.message);
                        btn.disabled = false;
                        btn.textContent = '✉️ KIRIM KONFIRMASI';
                    });
            }

            document.addEventListener('DOMContentLoaded', startCountdown);
        </script>
    @endpush
@endsection