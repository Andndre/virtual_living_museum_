<?php

return [
    'app_guide' => 'Panduan Aplikasi',
    'learn_how_to_use' => 'Pelajari cara menggunakan seluruh fitur aplikasi Ganesha AR',

    'progressive_learning' => [
        'id' => 'progressive_learning',
        'title' => 'Alur Pembelajaran E-Learning',
        'description' => 'Panduan menyelesaikan 4 tahapan belajar bertingkat secara terstruktur',
        'icon' => 'fa-graduation-cap',
        'steps' => [
            [
                'title' => 'Buka Menu Kunjungi Peninggalan',
                'description' => 'Pilih menu Kunjungi Peninggalan (E-Learning) di halaman utama, lalu pilih era atau materi sejarah yang ingin dipelajari.',
            ],
            [
                'title' => 'Tahap 1: Kerjakan Pre-Test',
                'description' => 'Kerjakan kuis awal untuk menguji pemahaman awal Anda. Menyelesaikan pre-test akan membuka akses modul materi berikutnya.',
            ],
            [
                'title' => 'Tahap 2: Pelajari E-Book Interaktif',
                'description' => 'Baca dan pelajari e-book digital materi sejarah hingga tuntas untuk membuka gerbang Virtual Living Museum.',
            ],
            [
                'title' => 'Tahap 3: Eksplorasi Virtual Living Museum',
                'description' => 'Jelajahi rekonstruksi lingkungan situs 360° secara imersif dan pelajari objek peninggalan yang tersimpan di dalamnya.',
            ],
            [
                'title' => 'Tahap 4: Kerjakan Post-Test',
                'description' => 'Selesaikan kuis evaluasi akhir untuk mengukur peningkatan nilai Anda, mencatatkan skor ke rapor, dan membuka materi berikutnya.',
            ],
        ],
    ],

    'virtual_living_museum' => [
        'id' => 'virtual_living_museum',
        'title' => 'Virtual Living Museum (WebXR 360°)',
        'description' => 'Panduan menempatkan dan menjelajahi lingkungan situs di ruang nyata',
        'icon' => 'fa-vr-cardboard',
        'steps' => [
            [
                'title' => 'Buka AR di Perangkat Mobile',
                'description' => 'Buka situs pada smartphone Android/iOS yang mendukung WebXR. Jika membuka dari laptop/PC, gunakan fitur QR Token untuk melanjutkan di ponsel.',
            ],
            [
                'title' => 'Izinkan Kamera & Pindai Permukaan Lantai',
                'description' => 'Berikan izin akses kamera, lalu gerakkan ponsel perlahan ke area lantai datar yang lapang dan berpenerangan cukup.',
            ],
            [
                'title' => 'Kunci Posisi Reticle & Letakkan Situs',
                'description' => 'Arahkan lingkaran putih (reticle) ke posisi lantai yang diinginkan, lalu ketuk layar sekali untuk menempatkan situs 360° secara permanen.',
            ],
            [
                'title' => 'Berkeliling & Buka Objek Peninggalan',
                'description' => 'Anda dapat berjalan secara fisik untuk berkeliling di dalam situs. Ketuk tombol "Lihat Objek Peninggalan" untuk membuka katalog artefak yang berada di situs tersebut.',
            ],
        ],
    ],

    'ar_marker' => [
        'id' => 'ar_marker',
        'title' => 'Pindai QR Marker & 3D AR',
        'description' => 'Panduan memindai QR katalog dan menampilkan objek artefak di ruang nyata',
        'icon' => 'fa-qrcode',
        'steps' => [
            [
                'title' => 'Buka Menu AR Marker',
                'description' => 'Pilih menu AR Marker pada halaman utama. Anda dapat mengunduh katalog PDF jika belum memilikinya.',
            ],
            [
                'title' => 'Pindai QR Code di Katalog',
                'description' => 'Arahkan kamera scanner ke QR Code marker. QR dapat dipindai langsung dari layar monitor, layar ponsel lain, maupun katalog cetak.',
            ],
            [
                'title' => 'Eksplorasi Model 3D & Audio Narasi',
                'description' => 'Model 3D artefak akan dimuat secara interaktif. Anda dapat memutar model 360°, mendengarkan narasi audio sejarah, membaca deskripsi detail, atau memilih artefak lain melalui thumbnail bar.',
            ],
            [
                'title' => 'Tampilkan di Ruang Nyata (AR)',
                'description' => 'Tekan tombol "Tampilkan di Ruang Nyata (AR)". Model akan otomatis dibuka menggunakan Google Scene Viewer (Android) atau Apple Quick Look (iOS) untuk diletakkan di atas meja/lantai nyata Anda secara stabil.',
            ],
        ],
    ],

    'maps_heritage' => [
        'id' => 'maps_heritage',
        'title' => 'Peta Persebaran Situs (Maps)',
        'description' => 'Panduan menjelajahi peta peninggalan sejarah dan navigasi lokasi',
        'icon' => 'fa-map-marked-alt',
        'steps' => [
            [
                'title' => 'Buka Menu Maps',
                'description' => 'Pilih menu Maps dari halaman utama untuk melihat peta sebaran seluruh situs cagar budaya.',
            ],
            [
                'title' => 'Cari & Filter Berdasarkan Era',
                'description' => 'Gunakan kolom pencarian atau filter era sejarah untuk mempermudah penemuan situs arkeologi tertentu.',
            ],
            [
                'title' => 'Lihat Detail & Petunjuk Arah',
                'description' => 'Ketuk pin penanda situs pada peta untuk melihat foto, deskripsi singkat peninggalan, atau membuka rute navigasi menuju lokasi sebenarnya.',
            ],
        ],
    ],

    'laporan_peninggalan' => [
        'id' => 'laporan_peninggalan',
        'title' => 'Laporan Peninggalan Budaya',
        'description' => 'Panduan melaporkan temuan objek atau cagar budaya di sekitar Anda',
        'icon' => 'fa-flag',
        'steps' => [
            [
                'title' => 'Buka Menu Lapor Peninggalan',
                'description' => 'Pilih menu Lapor Peninggalan dari beranda dan klik tombol "Tambah Laporan".',
            ],
            [
                'title' => 'Lengkapi Data Temuan',
                'description' => 'Masukkan nama objek peninggalan, alamat, dan deskripsi kondisi peninggalan yang Anda temukan.',
            ],
            [
                'title' => 'Tandai Titik Lokasi di Peta',
                'description' => 'Gunakan peta interaktif untuk menandai titik koordinat penemuan peninggalan secara presisi.',
            ],
            [
                'title' => 'Unggah Foto & Kirim Laporan',
                'description' => 'Unggah foto dokumentasi artefak/situs, lalu kirimkan laporan. Laporan Anda dapat dilihat, disukai, dan didiskusikan oleh komunitas pengguna.',
            ],
        ],
    ],

    'heritage_video' => [
        'id' => 'heritage_video',
        'title' => 'Video Peninggalan',
        'description' => 'Panduan menonton dokumenter audio-visual cagar budaya',
        'icon' => 'fa-video',
        'steps' => [
            [
                'title' => 'Buka Menu Video Peninggalan',
                'description' => 'Pilih menu Video Peninggalan dari halaman utama aplikasi.',
            ],
            [
                'title' => 'Pilih Dokumenter Sejarah',
                'description' => 'Pilih materi video dokumenter yang ingin Anda tonton dari daftar kurasi yang tersedia.',
            ],
            [
                'title' => 'Tonton & Simak Penjelasan',
                'description' => 'Tonton video dengan penjelasan informatif mengenai sejarah dan konteks peninggalan untuk memperdalam pemahaman materi.',
            ],
        ],
    ],

    'statistik_rapor' => [
        'id' => 'statistik_rapor',
        'title' => 'Statistik & Papan Peringkat',
        'description' => 'Panduan memantau capaian skor evaluasi dan posisi leaderboard',
        'icon' => 'fa-chart-line',
        'steps' => [
            [
                'title' => 'Buka Menu Statistik',
                'description' => 'Pilih menu Statistik dari halaman utama aplikasi.',
            ],
            [
                'title' => 'Pantau Skor & Progres Belajar',
                'description' => 'Lihat rekapitulasi nilai post-test dari setiap materi yang telah Anda selesaikan serta perkembangan level belajar Anda.',
            ],
            [
                'title' => 'Lihat Peringkat Belajar',
                'description' => 'Bandingkan rata-rata skor evaluasi Anda dengan pengguna lain di papan peringkat (leaderboard).',
            ],
        ],
    ],
];
