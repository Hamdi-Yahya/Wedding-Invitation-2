@extends('layouts.app')
@section('page-title', 'Export Undangan')

@section('content')
    <div class="export-container">
        <div class="table-header">
            <h2>Kartu Undangan</h2>
            <button class="btn btn-primary" onclick="window.print()">🖨️ Print Semua</button>
        </div>

        <div class="cards-grid" id="cardsGrid">
            @forelse($guests as $guest)
                <div class="invitation-card">
                    <div class="card-header-accent" style="background-color: {{ $theme->primary_color ?? '#E91E8C' }};"></div>
                    <div class="card-body">
                        <p class="card-couple" style="color: {{ $theme->primary_color ?? '#E91E8C' }};">
                            {{ $event->partner_1_name ?? 'Partner 1' }} & {{ $event->partner_2_name ?? 'Partner 2' }}
                        </p>
                        <p class="card-invite-text">Mengundang</p>
                        <h3 class="card-guest-name">{{ $guest->name }}</h3>
                        <div class="card-divider"></div>
                        <p class="card-detail">📅 {{ $event->event_date ? formatDateIndonesia($event->event_date) : '-' }}</p>
                        <p class="card-detail">⏰ {{ $event->start_time ?? '' }} - {{ $event->end_time ?? '' }}</p>
                        <p class="card-detail">📍 {{ $event->venue_name ?? '-' }}</p>
                        <div class="card-qr">
                            <img src="https://api.qrserver.com/v1/create-qr-code/?size=120x120&data={{ $guest->qr_code_string }}"
                                alt="QR Code {{ $guest->name }}">
                            <span class="qr-label">{{ $guest->qr_code_string }}</span>
                        </div>
                    </div>
                </div>
            @empty
                <p class="loading-text">Belum ada tamu untuk di-export.</p>
            @endforelse
        </div>
    </div>

    @push('styles')
        <style>
            .cards-grid {
                display: grid;
                grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
                gap: 24px;
                margin-top: 20px;
            }

            .invitation-card {
                width: 400px;
                max-width: 100%;
                height: 560px;
                background: #FFFAF5;
                border-radius: 12px;
                overflow: hidden;
                box-shadow: 0 2px 12px rgba(0, 0, 0, 0.08);
                display: flex;
                flex-direction: column;
            }

            .card-header-accent {
                height: 6px;
                width: 100%;
            }

            .card-body {
                padding: 32px 24px;
                text-align: center;
                flex: 1;
                display: flex;
                flex-direction: column;
                justify-content: center;
                align-items: center;
            }

            .card-couple {
                font-family: 'Parisienne', cursive;
                font-size: 24px;
                margin-bottom: 8px;
            }

            .card-invite-text {
                font-size: 12px;
                letter-spacing: 2px;
                text-transform: uppercase;
                color: #999;
                margin-bottom: 8px;
            }

            .card-guest-name {
                font-size: 22px;
                font-weight: 700;
                margin-bottom: 16px;
            }

            .card-divider {
                width: 60px;
                height: 2px;
                background: #ddd;
                margin: 12px auto;
            }

            .card-detail {
                font-size: 13px;
                color: #666;
                margin-bottom: 4px;
            }

            .card-qr {
                margin-top: 20px;
                text-align: center;
            }

            .card-qr img {
                border-radius: 8px;
            }

            .qr-label {
                display: block;
                font-size: 12px;
                color: #999;
                margin-top: 6px;
                letter-spacing: 2px;
            }

            @media print {

                .sidebar,
                .hamburger-btn,
                .content-header,
                .table-header {
                    display: none !important;
                }

                .main-content {
                    margin-left: 0 !important;
                    padding: 0 !important;
                }

                .invitation-card {
                    break-inside: avoid;
                    page-break-inside: avoid;
                }

                .cards-grid {
                    grid-template-columns: 1fr 1fr;
                }
            }
        </style>
    @endpush
@endsection