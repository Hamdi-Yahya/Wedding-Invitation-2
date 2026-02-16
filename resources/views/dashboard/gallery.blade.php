@extends('layouts.app')
@section('page-title', 'Gallery')

@section('content')
    @push('styles')
        <style>
            /* Section kategori gambar */
            .gallery-section {
                background: #fff;
                border-radius: 12px;
                padding: 24px;
                margin-bottom: 20px;
                box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
            }

            .gallery-section-header {
                display: flex;
                justify-content: space-between;
                align-items: center;
                margin-bottom: 16px;
            }

            .gallery-section-header h3 {
                font-size: 16px;
                font-weight: 600;
            }

            .gallery-section-header .section-badge {
                font-size: 11px;
                color: #888;
                background: #FAFAF5;
                padding: 4px 10px;
                border-radius: 20px;
            }

            /* Slot foto tunggal (partner/background) */
            .single-photo-slot {
                width: 240px;
                height: 240px;
                border-radius: 12px;
                overflow: hidden;
                position: relative;
                border: 2px dashed #e0e0e0;
                display: flex;
                align-items: center;
                justify-content: center;
                background: #FAFAF5;
            }

            .single-photo-slot.has-photo {
                border-style: solid;
                border-color: transparent;
            }

            .single-photo-slot img {
                width: 100%;
                height: 100%;
                object-fit: cover;
            }

            /* Placeholder saat belum ada foto */
            .slot-placeholder {
                text-align: center;
                color: #bbb;
            }

            .slot-placeholder span {
                font-size: 36px;
                display: block;
                margin-bottom: 8px;
            }

            .slot-placeholder p {
                font-size: 12px;
            }

            /* Overlay tombol aksi pada foto */
            .slot-overlay {
                position: absolute;
                bottom: 0;
                left: 0;
                right: 0;
                padding: 10px;
                background: rgba(0, 0, 0, 0.5);
                display: flex;
                justify-content: center;
                gap: 8px;
                opacity: 0;
                transition: opacity 0.3s;
            }

            .single-photo-slot:hover .slot-overlay {
                opacity: 1;
            }

            .slot-overlay button {
                background: none;
                border: none;
                color: #fff;
                cursor: pointer;
                font-size: 16px;
                padding: 4px 8px;
                border-radius: 4px;
                transition: background 0.2s;
            }

            .slot-overlay button:hover {
                background: rgba(255, 255, 255, 0.2);
            }

            /* Layout 2 slot berdampingan untuk partner photos */
            .partner-photos-grid {
                display: flex;
                justify-content: center;
                gap: 24px;
                flex-wrap: wrap;
            }

            /* Wrapper baris foto mempelai bersebelahan */
            .partner-row {
                display: flex;
                gap: 20px;
                flex-wrap: wrap;
                margin-bottom: 20px;
            }

            .partner-row .gallery-section {
                flex: 1;
                min-width: 280px;
                max-width: 420px;
                margin-bottom: 0;
            }

            /* Slot background lebih lebar */
            .bg-photo-slot {
                width: 380px;
                height: 220px;
            }
        </style>
    @endpush

    <!-- Section: Foto Mempelai Pria & Wanita bersebelahan -->
    <div class="partner-row">
        <div class="gallery-section">
            <div class="gallery-section-header">
                <h3>🤵 Foto Mempelai Pria</h3>
            </div>
            <div class="partner-photos-grid">
                <div class="single-photo-slot" id="slotPartner1">
                    <div class="slot-placeholder" id="placeholderPartner1">
                        <span>🤵</span>
                        <p>Belum ada foto</p>
                        <button class="btn btn-primary" style="margin-top: 8px; font-size: 12px;"
                            onclick="openUploadModal('partner1')">📷 Upload</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="gallery-section">
            <div class="gallery-section-header">
                <h3>👰 Foto Mempelai Wanita</h3>
            </div>
            <div class="partner-photos-grid">
                <div class="single-photo-slot" id="slotPartner2">
                    <div class="slot-placeholder" id="placeholderPartner2">
                        <span>👰</span>
                        <p>Belum ada foto</p>
                        <button class="btn btn-primary" style="margin-top: 8px; font-size: 12px;"
                            onclick="openUploadModal('partner2')">📷 Upload</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Section: Background Halaman Utama -->
    <div class="gallery-section">
        <div class="gallery-section-header">
            <h3>🖼️ Background Halaman Utama</h3>
            <span class="section-badge">Hero Image</span>
        </div>
        <div class="partner-photos-grid">
            <div class="single-photo-slot bg-photo-slot" id="slotHero">
                <div class="slot-placeholder" id="placeholderHero">
                    <span>🖼️</span>
                    <p>Belum ada background</p>
                    <button class="btn btn-primary" style="margin-top: 8px; font-size: 12px;"
                        onclick="openUploadModal('hero')">📷 Upload</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal upload foto -->
    <div class="modal-overlay" id="galleryModal" style="display:none;">
        <div class="modal">
            <div class="modal-header">
                <h3 id="modalTitle">Tambah Gambar</h3>
                <button class="modal-close" onclick="closeModal()">&times;</button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="uploadLabel">
                <div class="form-group">
                    <label>Upload Gambar</label>
                    <input type="file" id="imageFile" accept="image/*" class="upload-btn">
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-outline" onclick="closeModal()">Batal</button>
                <button class="btn btn-primary" onclick="saveImage()">Simpan</button>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            const CSRF = document.querySelector('meta[name="csrf-token"]').content;
            let allImages = [];
            let currentUploadLabel = 'gallery';

            /* Memuat semua gambar dari API dan distribusikan ke slot yang sesuai */
            function loadGallery() {
                fetch('/api/gallery', {
                    headers: { 'Accept': 'application/json' }
                })
                    .then(function (r) { return r.json(); })
                    .then(function (data) {
                        allImages = data.images || [];
                        renderCategorySlots();
                    });
            }

            /* Render foto ke slot partner1, partner2, dan hero */
            function renderCategorySlots() {
                var categories = ['partner1', 'partner2', 'hero'];
                categories.forEach(function (label) {
                    var img = allImages.find(function (i) { return i.label === label; });
                    var slot = document.getElementById('slot' + label.charAt(0).toUpperCase() + label.slice(1));
                    var placeholder = document.getElementById('placeholder' + label.charAt(0).toUpperCase() + label.slice(1));

                    if (img) {
                        slot.classList.add('has-photo');
                        slot.innerHTML = '<img src="' + img.image_url + '" alt="' + (img.alt_text || label) +
                            '" style="object-position: ' + (img.object_position || 'center') + ';">' +
                            '<div class="slot-overlay">' +
                            '<button onclick="openUploadModal(\'' + label + '\')" title="Ganti">🔄</button>' +
                            '<button onclick="deleteImage(' + img.id + ')" title="Hapus">🗑️</button>' +
                            '</div>';
                    } else {
                        slot.classList.remove('has-photo');
                        var emoji = label === 'partner1' ? '🤵' : (label === 'partner2' ? '👰' : '🖼️');
                        slot.innerHTML = '<div class="slot-placeholder">' +
                            '<span>' + emoji + '</span>' +
                            '<p>Belum ada foto</p>' +
                            '<button class="btn btn-primary" style="margin-top: 8px; font-size: 12px;" ' +
                            'onclick="openUploadModal(\'' + label + '\')">📷 Upload</button>' +
                            '</div>';
                    }
                });
            }

            /* Membuka modal upload dengan label kategori yang sesuai */
            function openUploadModal(label) {
                currentUploadLabel = label;
                var titles = {
                    'partner1': 'Upload Foto Mempelai Pria',
                    'partner2': 'Upload Foto Mempelai Wanita',
                    'hero': 'Upload Background Utama',
                };
                document.getElementById('modalTitle').textContent = titles[label] || 'Tambah Gambar';
                document.getElementById('uploadLabel').value = label;
                document.getElementById('imageFile').value = '';
                document.getElementById('galleryModal').style.display = 'flex';
            }

            /* Menutup modal */
            function closeModal() {
                document.getElementById('galleryModal').style.display = 'none';
            }

            /* Menyimpan gambar: upload file ke server lalu store ke API gallery */
            function saveImage() {
                var fileVal = document.getElementById('imageFile').files[0];
                var label = currentUploadLabel;

                if (!fileVal) {
                    alert('Pilih file gambar terlebih dahulu.');
                    return;
                }

                var formData = new FormData();
                formData.append('file', fileVal);
                formData.append('type', 'gallery');

                fetch('/api/upload', {
                    method: 'POST',
                    headers: { 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json' },
                    body: formData,
                })
                    .then(function (r) { return r.json(); })
                    .then(function (data) { storeImage(data.url, null, label); })
                    .catch(function (err) { alert('Upload gagal: ' + err.message); });
            }

            /* Simpan data gambar ke API /api/gallery, hapus foto lama jika label sudah ada */
            function storeImage(url, alt, label) {
                var existingImg = allImages.find(function (i) { return i.label === label; });
                var isSpecial = (label === 'partner1' || label === 'partner2' || label === 'hero');

                function doStore() {
                    fetch('/api/gallery', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': CSRF,
                        },
                        body: JSON.stringify({
                            image_url: url,
                            alt_text: alt || null,
                            label: label || null,
                        }),
                    })
                        .then(function (r) { return r.json(); })
                        .then(function () {
                            closeModal();
                            loadGallery();
                        })
                        .catch(function (err) { alert('Error: ' + err.message); });
                }

                if (isSpecial && existingImg) {
                    fetch('/api/gallery/' + existingImg.id, {
                        method: 'DELETE',
                        headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': CSRF },
                    }).then(function () { doStore(); });
                } else {
                    doStore();
                }
            }

            /* Menghapus gambar via API DELETE /api/gallery/{id} */
            function deleteImage(id) {
                if (!confirm('Hapus gambar ini?')) return;
                fetch('/api/gallery/' + id, {
                    method: 'DELETE',
                    headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': CSRF },
                })
                    .then(function () { loadGallery(); });
            }

            /* Load galeri saat halaman dimuat */
            loadGallery();
        </script>
    @endpush
@endsection