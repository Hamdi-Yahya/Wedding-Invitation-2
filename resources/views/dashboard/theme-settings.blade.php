@extends('layouts.app')
@section('page-title', 'Theme Settings')

@section('content')
    @push('styles')
        <style>
            /* Layout 2 kolom: form di kiri, preview di kanan */
            .theme-layout {
                display: grid;
                grid-template-columns: 1fr 1fr;
                gap: 24px;
            }

            /* Preview card undangan */
            .preview-card {
                background: #fff;
                border-radius: 12px;
                padding: 32px;
                box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
                text-align: center;
                position: sticky;
                top: 24px;
            }

            .preview-card h3 {
                font-size: 16px;
                font-weight: 600;
                margin-bottom: 20px;
            }

            /* Preview frame menggunakan warna aktif */
            .preview-frame {
                border: 2px solid #e8e0d0;
                border-radius: 12px;
                padding: 32px 20px;
                min-height: 280px;
                display: flex;
                flex-direction: column;
                align-items: center;
                justify-content: center;
                gap: 12px;
                background-size: cover;
                background-position: center;
            }

            .preview-title {
                font-size: 28px;
                font-weight: 300;
            }

            .preview-subtitle {
                font-size: 13px;
                letter-spacing: 2px;
            }

            .preview-btn {
                padding: 10px 24px;
                border: none;
                border-radius: 4px;
                color: #fff;
                font-size: 11px;
                font-weight: 600;
                letter-spacing: 1px;
                cursor: pointer;
            }

            /* Color picker group */
            .color-group {
                display: flex;
                align-items: center;
                gap: 12px;
            }

            .color-group input[type="color"] {
                width: 48px;
                height: 38px;
                border: 1px solid #e0e0e0;
                border-radius: 8px;
                cursor: pointer;
                padding: 2px;
            }

            .color-group input[type="text"] {
                flex: 1;
            }

            /* Responsive: stack di mobile */
            @media (max-width: 768px) {
                .theme-layout {
                    grid-template-columns: 1fr;
                }
            }
        </style>
    @endpush

    <div class="theme-layout">
        <!-- Form kolom kiri: pengaturan warna, font, background -->
        <div>
            <form id="themeForm" onsubmit="return saveTheme(event)">
                <div class="form-section">
                    <div class="section-header">
                        <span class="section-icon">🎨</span>
                        <h2>Warna</h2>
                    </div>
                    <div class="form-group">
                        <label>Primary Color</label>
                        <div class="color-group">
                            <input type="color" id="colorPickerPrimary" value="{{ $theme->primary_color ?? '#9B7B2C' }}"
                                onchange="syncColor('primary')">
                            <input type="text" id="primaryColor" value="{{ $theme->primary_color ?? '#9B7B2C' }}"
                                onchange="syncPicker('primary')">
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Secondary Color</label>
                        <div class="color-group">
                            <input type="color" id="colorPickerSecondary" value="{{ $theme->secondary_color ?? '#FAF6EE' }}"
                                onchange="syncColor('secondary')">
                            <input type="text" id="secondaryColor" value="{{ $theme->secondary_color ?? '#FAF6EE' }}"
                                onchange="syncPicker('secondary')">
                        </div>
                    </div>
                </div>

                <div class="form-section">
                    <div class="section-header">
                        <span class="section-icon">🔤</span>
                        <h2>Font</h2>
                    </div>
                    <div class="form-group">
                        <label>Heading Font Family</label>
                        <select id="fontFamily">
                            @php
                                $fonts = [
                                    'Playfair Display',
                                    'Cormorant Garamond',
                                    'Great Vibes',
                                    'Cinzel',
                                    'Lora',
                                    'Merriweather',
                                    'EB Garamond',
                                    'Libre Baskerville',
                                    'Spectral',
                                    'Crimson Pro',
                                ];
                            @endphp
                            @foreach ($fonts as $font)
                                <option value="{{ $font }}" {{ ($theme->font_family ?? 'Playfair Display') === $font ? 'selected' : '' }}>
                                    {{ $font }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>



                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">💾 Simpan Theme</button>
                </div>
            </form>
        </div>

        <!-- Preview kolom kanan: live preview undangan -->
        <div>
            <div class="preview-card">
                <h3>Live Preview</h3>
                <div class="preview-frame" id="previewFrame"
                    style="background-color: {{ $theme->secondary_color ?? '#FAF6EE' }};
                                @if ($theme->background_image_url) background-image: url('{{ asset($theme->background_image_url) }}'); @endif">
                    <p class="preview-subtitle" id="previewSubtitle" style="color: #888;">THE WEDDING OF</p>
                    <h2 class="preview-title" id="previewTitle"
                        style="color: {{ $theme->primary_color ?? '#9B7B2C' }}; font-family: '{{ $theme->font_family ?? 'Playfair Display' }}', serif;">
                        Partner 1 & Partner 2
                    </h2>
                    <button class="preview-btn" id="previewBtn"
                        style="background-color: {{ $theme->primary_color ?? '#9B7B2C' }};">
                        RSVP
                    </button>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            const CSRF = document.querySelector('meta[name="csrf-token"]').content;

            /* Sinkronkan warna dari color picker ke input text dan update preview */
            function syncColor(type) {
                const picker = document.getElementById('colorPicker' + type.charAt(0).toUpperCase() + type.slice(1));
                const input = document.getElementById(type + 'Color');
                input.value = picker.value;
                updatePreview();
            }

            /* Sinkronkan warna dari input text ke color picker dan update preview */
            function syncPicker(type) {
                const picker = document.getElementById('colorPicker' + type.charAt(0).toUpperCase() + type.slice(1));
                const input = document.getElementById(type + 'Color');
                picker.value = input.value;
                updatePreview();
            }

            /* Load Google Font secara dinamis agar preview bisa menampilkan font yang dipilih */
            function loadGoogleFont(fontName) {
                var fontUrl = fontName.replace(/ /g, '+');
                var linkId = 'dynamic-font-' + fontUrl;
                if (document.getElementById(linkId)) return;

                var link = document.createElement('link');
                link.id = linkId;
                link.rel = 'stylesheet';
                link.href = 'https://fonts.googleapis.com/css2?family=' + fontUrl + ':wght@300;400;600;700&display=swap';
                document.head.appendChild(link);
            }

            /* Update live preview dengan warna dan font terbaru */
            function updatePreview() {
                const primary = document.getElementById('primaryColor').value;
                const secondary = document.getElementById('secondaryColor').value;
                const font = document.getElementById('fontFamily').value;

                loadGoogleFont(font);

                document.getElementById('previewFrame').style.backgroundColor = secondary;
                document.getElementById('previewTitle').style.color = primary;
                document.getElementById('previewTitle').style.fontFamily = "'" + font + "', serif";
                document.getElementById('previewBtn').style.backgroundColor = primary;
            }

            /* Listener perubahan font untuk update live preview */
            document.getElementById('fontFamily').addEventListener('change', updatePreview);

            /* Menyimpan pengaturan theme ke API PUT /api/theme-settings */
            function saveTheme(e) {
                e.preventDefault();

                const data = {
                    primary_color: document.getElementById('primaryColor').value,
                    secondary_color: document.getElementById('secondaryColor').value,
                    font_family: document.getElementById('fontFamily').value,
                };

                fetch('/api/theme-settings', {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': CSRF,
                    },
                    body: JSON.stringify(data),
                })
                    .then(function (r) { return r.json(); })
                    .then(function () { alert('✅ Theme berhasil disimpan!'); })
                    .catch(function (err) { alert('❌ Gagal menyimpan: ' + err.message); });

                return false;
            }
        </script>
    @endpush
@endsection