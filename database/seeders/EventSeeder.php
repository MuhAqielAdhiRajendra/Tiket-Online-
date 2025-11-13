<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Event; 

class EventSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Event 1
        Event::create([
            'name' => 'Konser Sheila on 7 - Tunggu Aku',
            'description' => 'Konser nostalgia bareng Duta dan kawan-kawan.',
            'event_date' => '2025-12-20 19:00:00',
            'venue' => 'Stadion Utama GBK, Jakarta',
            'price' => 250000,
            'quota' => 5000,
        ]);

        // Event 2
        Event::create([
            'name' => 'Music Festival 2025',
            'description' => 'Festival musik indie terbesar tahun ini.',
            'event_date' => '2026-01-15 15:00:00',
            'venue' => 'Lapangan Gasibu, Bandung',
            'price' => 100000,
            'quota' => 2000,
        ]);

        // Event 3
        Event::create([
            'name' => 'Seminar Teknologi AI',
            'description' => 'Belajar AI bareng pakar Google & Microsoft.',
            'event_date' => '2025-11-30 09:00:00',
            'venue' => 'Zoom Meeting (Online)',
            'price' => 50000,
            'quota' => 500,
        ]);
    }
}