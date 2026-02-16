@extends('layouts.app')
@section('page-title', 'Dashboard')

@section('content')
    <!-- Grid statistik utama: total tamu, hadir, check-in, ucapan pending -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon" style="background-color: #E8F4FD;">👥</div>
            <div>
                <p class="stat-label">Total Tamu</p>
                <p class="stat-value">{{ $totalGuests }}</p>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon" style="background-color: #D4EDDA;">✅</div>
            <div>
                <p class="stat-label">Hadir (RSVP)</p>
                <p class="stat-value">{{ $totalComing }}</p>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon" style="background-color: #FFF3CD;">📋</div>
            <div>
                <p class="stat-label">Check-in</p>
                <p class="stat-value">{{ $totalCheckedIn }}</p>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon" style="background-color: #FDE8E8;">💬</div>
            <div>
                <p class="stat-label">Ucapan Pending</p>
                <p class="stat-value">{{ $pendingWishes }}</p>
            </div>
        </div>
    </div>

    @push('styles')
        <style>
            /* Layout grid 2 kolom untuk widget dashboard */
            .dashboard-row {
                display: grid;
                grid-template-columns: 1fr 1fr;
                gap: 20px;
                margin-top: 24px;
            }

            /* Card widget untuk chart dan progress */
            .widget-card {
                background: #fff;
                border-radius: 12px;
                padding: 24px;
                box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
            }

            .widget-card h3 {
                font-size: 16px;
                font-weight: 600;
                margin-bottom: 16px;
                color: #1a1a1a;
            }

            /* Container chart donut dan legend */
            .chart-container {
                display: flex;
                align-items: center;
                gap: 24px;
            }

            .chart-legend {
                display: flex;
                flex-direction: column;
                gap: 8px;
            }

            .legend-item {
                display: flex;
                align-items: center;
                gap: 8px;
                font-size: 13px;
                color: #666;
            }

            .legend-dot {
                width: 12px;
                height: 12px;
                border-radius: 50%;
                flex-shrink: 0;
            }

            /* Progress bar untuk rsvp dan check-in */
            .progress-list {
                display: flex;
                flex-direction: column;
                gap: 16px;
            }

            .progress-item label {
                display: block;
                font-size: 13px;
                font-weight: 500;
                color: #666;
                margin-bottom: 6px;
            }

            .progress-bar-bg {
                height: 8px;
                background: #f0f0f0;
                border-radius: 4px;
                overflow: hidden;
            }

            .progress-bar-fill {
                height: 100%;
                border-radius: 4px;
                transition: width 0.5s ease;
            }

            .progress-value {
                font-size: 12px;
                color: #999;
                margin-top: 4px;
            }

            /* Tabel recent check-in */
            .checkin-table {
                width: 100%;
                margin-top: 8px;
            }

            .checkin-table th {
                font-size: 12px;
                font-weight: 600;
                color: #888;
                text-transform: uppercase;
                letter-spacing: 0.5px;
                padding: 8px 12px;
                text-align: left;
                background: #FAFAF5;
                border-bottom: 1px solid #eee;
            }

            .checkin-table td {
                font-size: 13px;
                padding: 10px 12px;
                border-bottom: 1px solid #f5f5f5;
                color: #555;
            }

            /* Responsive: stack kolom di mobile */
            @media (max-width: 768px) {
                .dashboard-row {
                    grid-template-columns: 1fr;
                }
            }
        </style>
    @endpush

    <!-- Row widget: RSVP donut chart & progress bars -->
    <div class="dashboard-row">
        <div class="widget-card">
            <h3>📊 RSVP Overview</h3>
            <div class="chart-container">
                <canvas id="rsvpChart" width="150" height="150"></canvas>
                <div class="chart-legend">
                    <div class="legend-item">
                        <span class="legend-dot" style="background: #27ae60;"></span>
                        <span>Hadir: {{ $totalComing }}</span>
                    </div>
                    <div class="legend-item">
                        <span class="legend-dot" style="background: #e74c3c;"></span>
                        <span>Tidak Hadir: {{ $totalNotComing }}</span>
                    </div>
                    <div class="legend-item">
                        <span class="legend-dot" style="background: #f39c12;"></span>
                        <span>Pending: {{ $totalPending }}</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="widget-card">
            <h3>📈 Progress</h3>
            <div class="progress-list">
                @php
                    $rsvpPct = $totalGuests > 0 ? round(($totalComing / $totalGuests) * 100) : 0;
                    $checkinPct = $totalGuests > 0 ? round(($totalCheckedIn / $totalGuests) * 100) : 0;
                    $notComingPct = $totalGuests > 0 ? round(($totalNotComing / $totalGuests) * 100) : 0;
                @endphp
                <div class="progress-item">
                    <label>RSVP Confirmed</label>
                    <div class="progress-bar-bg">
                        <div class="progress-bar-fill" style="width: {{ $rsvpPct }}%; background: #27ae60;"></div>
                    </div>
                    <span class="progress-value">{{ $rsvpPct }}% ({{ $totalComing }}/{{ $totalGuests }})</span>
                </div>
                <div class="progress-item">
                    <label>Check-in Progress</label>
                    <div class="progress-bar-bg">
                        <div class="progress-bar-fill" style="width: {{ $checkinPct }}%; background: #3498db;"></div>
                    </div>
                    <span class="progress-value">{{ $checkinPct }}% ({{ $totalCheckedIn }}/{{ $totalGuests }})</span>
                </div>
                <div class="progress-item">
                    <label>Not Coming</label>
                    <div class="progress-bar-bg">
                        <div class="progress-bar-fill" style="width: {{ $notComingPct }}%; background: #e74c3c;"></div>
                    </div>
                    <span class="progress-value">{{ $notComingPct }}%
                        ({{ $totalNotComing }}/{{ $totalGuests }})</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Widget recent check-in -->
    <div style="margin-top: 24px;">
        <div class="widget-card">
            <h3>🕐 Recent Check-ins</h3>
            @if ($recentCheckIns->isEmpty())
                <p class="loading-text">Belum ada check-in.</p>
            @else
                <table class="checkin-table">
                    <thead>
                        <tr>
                            <th>Nama</th>
                            <th>Kategori</th>
                            <th>Waktu Check-in</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($recentCheckIns as $ci)
                            <tr>
                                <td>{{ $ci->name }}</td>
                                <td>
                                    <span class="badge {{ $ci->category === 'VIP' ? 'badge-vip' : 'badge-regular' }}">
                                        {{ $ci->category }}
                                    </span>
                                </td>
                                <td>{{ $ci->check_in_time->format('d M Y, H:i') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>
    </div>

    @push('scripts')
        <script>
            /* Menggambar donut chart RSVP overview pada canvas */
            document.addEventListener('DOMContentLoaded', function () {
                const canvas = document.getElementById('rsvpChart');
                const ctx = canvas.getContext('2d');
                const centerX = canvas.width / 2;
                const centerY = canvas.height / 2;
                const radius = 60;
                const lineWidth = 20;

                const data = [
                    { value: {{ $totalComing }}, color: '#27ae60' },
                    { value: {{ $totalNotComing }}, color: '#e74c3c' },
                    { value: {{ $totalPending }}, color: '#f39c12' },
                ];

                const total = data.reduce(function (sum, d) { return sum + d.value; }, 0);

                if (total === 0) {
                    ctx.beginPath();
                    ctx.arc(centerX, centerY, radius, 0, Math.PI * 2);
                    ctx.strokeStyle = '#eee';
                    ctx.lineWidth = lineWidth;
                    ctx.stroke();
                } else {
                    let startAngle = -Math.PI / 2;
                    data.forEach(function (d) {
                        const sliceAngle = (d.value / total) * Math.PI * 2;
                        ctx.beginPath();
                        ctx.arc(centerX, centerY, radius, startAngle, startAngle + sliceAngle);
                        ctx.strokeStyle = d.color;
                        ctx.lineWidth = lineWidth;
                        ctx.stroke();
                        startAngle += sliceAngle;
                    });
                }

                ctx.fillStyle = '#1a1a1a';
                ctx.font = 'bold 24px Inter';
                ctx.textAlign = 'center';
                ctx.textBaseline = 'middle';
                ctx.fillText(total, centerX, centerY - 6);

                ctx.fillStyle = '#999';
                ctx.font = '11px Inter';
                ctx.fillText('TOTAL', centerX, centerY + 14);
            });
        </script>
    @endpush
@endsection