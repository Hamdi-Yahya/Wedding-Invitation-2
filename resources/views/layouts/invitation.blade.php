<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description"
        content="Undangan Pernikahan {{ $event->partner_1_name ?? '' }} & {{ $event->partner_2_name ?? '' }}">
    <title>{{ $event->partner_1_name ?? 'Wedding' }} & {{ $event->partner_2_name ?? 'Invitation' }} - Undangan
        Pernikahan</title>

    @php
        $headingFont = $theme->font_family ?? 'Playfair Display';
        $headingFontUrl = str_replace(' ', '+', $headingFont);
    @endphp
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family={{ $headingFontUrl }}:ital,wght@0,400;0,600;0,700;1,400&family=Montserrat:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/invitation.css') }}">

    <style>
        :root {
            --primary-color:
                {{ $theme->primary_color ?? '#9B7B2C' }}
            ;
            --secondary-color:
                {{ $theme->secondary_color ?? '#FAF6EE' }}
            ;
            --heading-font: '{{ $headingFont }}', serif;
        }
    </style>
    @stack('styles')
</head>

<body>
    @yield('content')

    <script>
        const CSRF_TOKEN = document.querySelector('meta[name="csrf-token"]').content;

        function toggleMobileNav() {
            const navLinks = document.getElementById('navLinks');
            navLinks.classList.toggle('active');
        }

        document.addEventListener('DOMContentLoaded', function () {
            const links = document.querySelectorAll('.nav-links a');
            links.forEach(function (link) {
                link.addEventListener('click', function () {
                    const navLinks = document.getElementById('navLinks');
                    if (navLinks) navLinks.classList.remove('active');
                });
            });
        });
    </script>
    @stack('scripts')
</body>

</html>