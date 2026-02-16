<?php

namespace Database\Seeders;

use App\Models\Admin;
use App\Models\EventSetting;
use App\Models\Guest;
use App\Models\ThemeSetting;
use App\Models\WeddingDetail;
use Illuminate\Database\Seeder;

/**
 * Seeder utama untuk mengisi data default wedding invitation.
 */
class DatabaseSeeder extends Seeder
{
    /**
     * Menjalankan semua seeder: admin, event settings, theme, wedding details, dan sample guest.
     */
    public function run(): void
    {
        /* Buat akun admin default */
        Admin::create([
            'username' => 'admin',
            'password' => bcrypt('admin123'),
        ]);

        /* Buat pengaturan acara default (singleton ID=1) */
        EventSetting::create([
            'partner_1_name' => 'Sarah',
            'partner_2_name' => 'Michael',
            'tagline' => 'Two souls, one heart, forever together',
            'event_date' => '2024-09-05',
            'start_time' => '10:00',
            'end_time' => '22:00',
            'venue_name' => 'Rosewood Estate Gardens',
            'venue_address' => '123 Blossom Lane, Beverly Hills, CA 90210',
            'map_link_url' => 'https://maps.google.com/?q=Rosewood+Estate+Gardens',
            'wa_template_msg' => 'Halo {nama}, kami mengundang Anda ke pernikahan kami. Info lengkap: {link}',
        ]);

        /* Buat pengaturan tema default (singleton ID=1) */
        ThemeSetting::create([
            'theme_name' => 'Elegant Gold',
            'primary_color' => '#9B7B2C',
            'secondary_color' => '#FAF6EE',
            'font_family' => 'Playfair Display',
        ]);

        /* Buat detail pernikahan default (singleton ID=1) */
        WeddingDetail::create([
            'ceremony_title' => 'The Ceremony',
            'reception_title' => 'The Reception',
            'dress_code_title' => 'Dress Code',
            'dress_code_style1' => 'Formal Attire',
            'dress_code_style2' => 'Smart Casual',
        ]);

        /* Buat sample tamu VIP */
        Guest::create([
            'name' => 'John Doe',
            'phone_number' => '081234567890',
            'category' => 'VIP',
            'slug' => 'john-doe',
            'qr_code_string' => 'ABC12',
            'rsvp_status' => 'Pending',
            'guest_count' => 2,
        ]);
    }
}
