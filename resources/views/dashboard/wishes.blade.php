@extends('layouts.app')
@section('page-title', 'Ucapan & RSVP')

@section('content')
    <!-- Container tabel ucapan -->
    <div class="table-container">
        <div class="table-header">
            <h2>Daftar Ucapan</h2>
        </div>
        <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th>Nama Tamu</th>
                        <th>Pesan</th>
                        <th>Tanggal</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody id="wishesTableBody">
                    <tr>
                        <td colspan="4" class="loading-text">Memuat data...</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    @push('scripts')
        <script>
            const CSRF = document.querySelector('meta[name="csrf-token"]').content;

            /* Memuat semua ucapan dari API /api/wishes?all=1 */
            function loadWishes() {
                fetch('/api/wishes?all=1', {
                    headers: { 'Accept': 'application/json' }
                })
                    .then(function (r) { return r.json(); })
                    .then(function (data) {
                        const wishes = data.wishes || [];
                        const tbody = document.getElementById('wishesTableBody');
                        if (wishes.length === 0) {
                            tbody.innerHTML = '<tr><td colspan="4" class="loading-text">Belum ada ucapan.</td></tr>';
                            return;
                        }
                        tbody.innerHTML = wishes.map(function (w) {
                            const guestName = w.guest ? w.guest.name : '-';
                            const dateStr = new Date(w.created_at).toLocaleDateString('id-ID');

                            return '<tr>' +
                                '<td>' + guestName + '</td>' +
                                '<td class="message-cell">' + w.message + '</td>' +
                                '<td>' + dateStr + '</td>' +
                                '<td class="actions-cell">' +
                                '<button class="btn-icon btn-danger" onclick="deleteWish(' + w.id + ')" title="Hapus">🗑️</button>' +
                                '</td>' +
                                '</tr>';
                        }).join('');
                    });
            }

            /* Hapus ucapan via API DELETE /api/wishes/{id} */
            function deleteWish(id) {
                if (!confirm('Hapus ucapan ini?')) return;
                fetch('/api/wishes/' + id, {
                    method: 'DELETE',
                    headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': CSRF },
                }).then(function () { loadWishes(); });
            }

            /* Load data ucapan saat halaman dimuat */
            loadWishes();
        </script>
    @endpush
@endsection