<?php

use Carbon\Carbon;
use Illuminate\Support\Str;

function generateSlug(string $name): string
{
    $base = Str::slug($name);
    $uuid = substr(Str::uuid()->toString(), 0, 8);

    return $base . '-' . $uuid;
}

function generateQRString(): string
{
    $characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
    $result = '';

    for ($i = 0; $i < 5; $i++) {
        $result .= $characters[random_int(0, strlen($characters) - 1)];
    }

    return $result;
}

function formatDateIndonesia(Carbon $date): string
{
    $days = [
        'Sunday' => 'Minggu',
        'Monday' => 'Senin',
        'Tuesday' => 'Selasa',
        'Wednesday' => 'Rabu',
        'Thursday' => 'Kamis',
        'Friday' => 'Jumat',
        'Saturday' => 'Sabtu',
    ];

    $months = [
        1 => 'Januari',
        2 => 'Februari',
        3 => 'Maret',
        4 => 'April',
        5 => 'Mei',
        6 => 'Juni',
        7 => 'Juli',
        8 => 'Agustus',
        9 => 'September',
        10 => 'Oktober',
        11 => 'November',
        12 => 'Desember',
    ];

    $dayName = $days[$date->format('l')];
    $day = $date->format('j');
    $monthName = $months[(int) $date->format('n')];
    $year = $date->format('Y');

    return "{$dayName}, {$day} {$monthName} {$year}";
}

function isValidPhoneNumber(string $phone): bool
{
    $pattern = '/^(\+62|62|0)8[1-9][0-9]{6,10}$/';

    return (bool) preg_match($pattern, $phone);
}

function formatPhoneForWhatsApp(string $phone): string
{
    $phone = preg_replace('/[^\d+]/', '', $phone);

    if (str_starts_with($phone, '0')) {
        $phone = '62' . substr($phone, 1);
    }

    if (str_starts_with($phone, '+')) {
        $phone = substr($phone, 1);
    }

    return $phone;
}

function sanitizeString(string $str): string
{
    return htmlspecialchars(strip_tags(trim($str)), ENT_QUOTES, 'UTF-8');
}
