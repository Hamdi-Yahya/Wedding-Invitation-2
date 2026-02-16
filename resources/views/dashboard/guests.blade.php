@extends('layouts.app')
@section('page-title', 'Guest List')

@section('content')
    <!-- Container tabel tamu dengan search dan tombol tambah -->
    <div class="table-container">
        <div class="table-header">
            <h2>Daftar Tamu</h2>
            <button class="btn btn-primary" onclick="openAddModal()">➕ Tambah Tamu</button>
        </div>
        <div class="table-filters">
            <input type="text" id="searchInput" placeholder="🔍 Cari tamu..." oninput="filterGuests()">
        </div>
        <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th>Nama</th>
                        <th>Kategori</th>
                        <th>No. HP</th>
                        <th>RSVP</th>
                        <th>Check-in</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody id="guestTableBody">
                    <tr>
                        <td colspan="6" class="loading-text">Memuat data...</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal form untuk tambah/edit tamu -->
    <div class="modal-overlay" id="guestModal" style="display:none;">
        <div class="modal">
            <div class="modal-header">
                <h3 id="modalTitle">Tambah Tamu</h3>
                <button class="modal-close" onclick="closeModal()">&times;</button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="editGuestId">
                <div class="form-group">
                    <label>Nama</label>
                    <input type="text" id="guestName" placeholder="Nama tamu">
                </div>
                <div class="form-group">
                    <label>No. HP</label>
                    <input type="text" id="guestPhone" placeholder="08xxxxxxxxxx">
                </div>
                <div class="form-group">
                    <label>Kategori</label>
                    <select id="guestCategory">
                        <option value="Regular">Regular</option>
                        <option value="VIP">VIP</option>
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-outline" onclick="closeModal()">Batal</button>
                <button class="btn btn-primary" onclick="saveGuest()">Simpan</button>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            const CSRF = document.querySelector('meta[name="csrf-token"]').content;
            let allGuests = [];

            /* Memuat daftar tamu dari API /api/guests */
            function loadGuests() {
                fetch('/api/guests', {
                    headers: { 'Accept': 'application/json' }
                })
                    .then(function (r) { return r.json(); })
                    .then(function (data) {
                        allGuests = data.guests || [];
                        renderGuests(allGuests);
                    });
            }

            /* Render data tamu ke dalam tabel HTML */
            function renderGuests(guests) {
                const tbody = document.getElementById('guestTableBody');
                if (guests.length === 0) {
                    tbody.innerHTML = '<tr><td colspan="6" class="loading-text">Belum ada tamu.</td></tr>';
                    return;
                }
                tbody.innerHTML = guests.map(function (g) {
                    const rsvpClass = 'badge-' + (g.rsvp_status || 'Pending').toLowerCase().replace(' ', '-');
                    const checkinBadge = g.check_in_time
                        ? '<span class="badge badge-checkin-yes">✅ Checked</span>'
                        : '<span class="badge badge-checkin-no">—</span>';
                    const inviteLink = g.slug
                        ? '<a class="btn-icon" href="/invite/' + g.slug + '" target="_blank" title="Lihat Undangan">🔗</a>'
                        : '';

                    return '<tr>' +
                        '<td>' + g.name + '</td>' +
                        '<td><span class="badge ' + (g.category === 'VIP' ? 'badge-vip' : 'badge-regular') + '">' + g.category + '</span></td>' +
                        '<td>' + (g.phone_number || '-') + '</td>' +
                        '<td><span class="badge ' + rsvpClass + '">' + (g.rsvp_status || 'Pending') + '</span></td>' +
                        '<td>' + checkinBadge + '</td>' +
                        '<td class="actions-cell">' +
                        '<button class="btn-icon" onclick="editGuest(' + g.id + ')" title="Edit">✏️</button>' +
                        '<button class="btn-icon btn-danger" onclick="deleteGuest(' + g.id + ')" title="Hapus">🗑️</button>' +
                        inviteLink +
                        '</td>' +
                        '</tr>';
                }).join('');
            }

            /* Filter tamu berdasarkan input pencarian */
            function filterGuests() {
                const q = document.getElementById('searchInput').value.toLowerCase();
                const filtered = allGuests.filter(function (g) {
                    return g.name.toLowerCase().includes(q);
                });
                renderGuests(filtered);
            }

            /* Membuka modal untuk menambah tamu baru */
            function openAddModal() {
                document.getElementById('modalTitle').textContent = 'Tambah Tamu';
                document.getElementById('editGuestId').value = '';
                document.getElementById('guestName').value = '';
                document.getElementById('guestPhone').value = '';
                document.getElementById('guestCategory').value = 'Regular';
                document.getElementById('guestModal').style.display = 'flex';
            }

            /* Membuka modal untuk mengedit tamu yang sudah ada */
            function editGuest(id) {
                const g = allGuests.find(function (x) { return x.id === id; });
                if (!g) return;
                document.getElementById('modalTitle').textContent = 'Edit Tamu';
                document.getElementById('editGuestId').value = id;
                document.getElementById('guestName').value = g.name;
                document.getElementById('guestPhone').value = g.phone_number || '';
                document.getElementById('guestCategory').value = g.category || 'Regular';
                document.getElementById('guestModal').style.display = 'flex';
            }

            /* Menutup modal form */
            function closeModal() {
                document.getElementById('guestModal').style.display = 'none';
            }

            /* Menyimpan data tamu: POST untuk baru, PUT untuk update */
            function saveGuest() {
                const id = document.getElementById('editGuestId').value;
                const body = {
                    name: document.getElementById('guestName').value,
                    phone_number: document.getElementById('guestPhone').value,
                    category: document.getElementById('guestCategory').value,
                };
                const url = id ? '/api/guests/' + id : '/api/guests';
                const method = id ? 'PUT' : 'POST';

                fetch(url, {
                    method: method,
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': CSRF,
                    },
                    body: JSON.stringify(body),
                })
                    .then(function (r) { return r.json(); })
                    .then(function () {
                        closeModal();
                        loadGuests();
                    })
                    .catch(function (err) { alert('Error: ' + err.message); });
            }

            /* Menghapus tamu berdasarkan ID via API DELETE */
            function deleteGuest(id) {
                if (!confirm('Hapus tamu ini?')) return;
                fetch('/api/guests/' + id, {
                    method: 'DELETE',
                    headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': CSRF },
                })
                    .then(function () { loadGuests(); })
                    .catch(function (err) { alert('Error: ' + err.message); });
            }

            /* Load data tamu saat halaman dimuat */
            loadGuests();
        </script>
    @endpush
@endsection