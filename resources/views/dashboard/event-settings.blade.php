@extends('layouts.app')
@section('page-title', 'Event Settings')

@section('content')
    <!-- Form container untuk pengaturan acara -->
    <div class="form-container">
        <form id="eventForm" onsubmit="return saveEventSettings(event)">
            <!-- Section nama mempelai -->
            <div class="form-section">
                <div class="section-header">
                    <span class="section-icon">💑</span>
                    <h2>Nama Mempelai</h2>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label>Partner 1</label>
                        <input type="text" id="partner1Name" value="{{ $event->partner_1_name ?? '' }}" required>
                    </div>
                    <div class="form-group">
                        <label>Partner 2</label>
                        <input type="text" id="partner2Name" value="{{ $event->partner_2_name ?? '' }}" required>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label>Ayah Partner 1</label>
                        <input type="text" id="partner1Father" value="{{ $event->partner_1_father ?? '' }}"
                            placeholder="Nama ayah partner 1">
                    </div>
                    <div class="form-group">
                        <label>Ibu Partner 1</label>
                        <input type="text" id="partner1Mother" value="{{ $event->partner_1_mother ?? '' }}"
                            placeholder="Nama ibu partner 1">
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label>Ayah Partner 2</label>
                        <input type="text" id="partner2Father" value="{{ $event->partner_2_father ?? '' }}"
                            placeholder="Nama ayah partner 2">
                    </div>
                    <div class="form-group">
                        <label>Ibu Partner 2</label>
                        <input type="text" id="partner2Mother" value="{{ $event->partner_2_mother ?? '' }}"
                            placeholder="Nama ibu partner 2">
                    </div>
                </div>
                <div class="form-group">
                    <label>Tagline (opsional)</label>
                    <input type="text" id="tagline" value="{{ $event->tagline ?? '' }}"
                        placeholder="Kalimat pendek untuk hero section">
                </div>
            </div>

            <!-- Section tanggal dan waktu acara -->
            <div class="form-section">
                <div class="section-header">
                    <span class="section-icon">📅</span>
                    <h2>Tanggal & Waktu</h2>
                </div>
                <div class="form-group">
                    <label>Tanggal Acara</label>
                    <input type="date" id="eventDate"
                        value="{{ $event->event_date ? $event->event_date->format('Y-m-d') : '' }}" required>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label>Mulai</label>
                        <input type="time" id="startTime" value="{{ $event->start_time ?? '' }}" required>
                    </div>
                    <div class="form-group">
                        <label>Selesai</label>
                        <input type="time" id="endTime" value="{{ $event->end_time ?? '' }}" required>
                    </div>
                </div>
            </div>

            <!-- Section tempat acara -->
            <div class="form-section">
                <div class="section-header">
                    <span class="section-icon">📍</span>
                    <h2>Tempat Acara</h2>
                </div>
                <div class="form-group">
                    <label>Nama Venue</label>
                    <input type="text" id="venueName" value="{{ $event->venue_name ?? '' }}" required>
                </div>
                <div class="form-group">
                    <label>Alamat</label>
                    <textarea id="venueAddress" rows="2" required>{{ $event->venue_address ?? '' }}</textarea>
                </div>
                <div class="form-group">
                    <label>Link Google Maps (opsional)</label>
                    <input type="url" id="mapUrl" value="{{ $event->map_link_url ?? '' }}"
                        placeholder="https://maps.google.com/...">
                </div>
            </div>

            <!-- Tombol simpan -->
            <div class="form-actions">
                <button type="submit" class="btn btn-primary">💾 Simpan Perubahan</button>
            </div>
        </form>
    </div>

    @push('scripts')
        <script>
            const CSRF = document.querySelector('meta[name="csrf-token"]').content;

            /* Mengirim data event settings ke API PUT /api/event-settings */
            function saveEventSettings(e) {
                e.preventDefault();
                const data = {
                    partner_1_name: document.getElementById('partner1Name').value,
                    partner_1_father: document.getElementById('partner1Father').value || null,
                    partner_1_mother: document.getElementById('partner1Mother').value || null,
                    partner_2_name: document.getElementById('partner2Name').value,
                    partner_2_father: document.getElementById('partner2Father').value || null,
                    partner_2_mother: document.getElementById('partner2Mother').value || null,
                    tagline: document.getElementById('tagline').value || null,
                    event_date: document.getElementById('eventDate').value,
                    start_time: document.getElementById('startTime').value,
                    end_time: document.getElementById('endTime').value,
                    venue_name: document.getElementById('venueName').value,
                    venue_address: document.getElementById('venueAddress').value,
                    map_link_url: document.getElementById('mapUrl').value || null,
                };

                fetch('/api/event-settings', {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': CSRF,
                    },
                    body: JSON.stringify(data),
                })
                    .then(function (r) { return r.json(); })
                    .then(function () { alert('✅ Pengaturan acara berhasil disimpan!'); })
                    .catch(function (err) { alert('❌ Gagal menyimpan: ' + err.message); });

                return false;
            }
        </script>
    @endpush
@endsection