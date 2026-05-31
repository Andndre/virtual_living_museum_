<?php

namespace Database\Seeders;

use App\Models\ArMarker;
use App\Models\Materi;
use App\Models\SitusPeninggalan;
use App\Models\User;
use App\Models\VirtualMuseum;
use App\Models\VirtualMuseumObject;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class SitusPeninggalanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Dapatkan atau buat user admin sebagai pemilik/pengunggah situs
        $admin = User::where('email', 'admin@gmail.com')->first();
        if (!$admin) {
            $admin = User::create([
                'name' => 'Admin User',
                'email' => 'admin@gmail.com',
                'password' => bcrypt('password'),
                'role' => 'admin',
                'level_sekarang' => 12, // Membuka semua bab materi
                'progress_level_sekarang' => 3,
            ]);
        } else {
            // Pastikan akun admin memiliki level tertinggi untuk membuka semua situs di peta
            $admin->update([
                'level_sekarang' => 12,
                'progress_level_sekarang' => 3,
            ]);
        }

        // 2. Data Situs Peninggalan dengan titik koordinat asli di Bali
        $situsData = [
            [
                'nama' => 'Pura Besakih',
                'alamat' => 'Desa Besakih, Kecamatan Rendang, Kabupaten Karangasem, Bali',
                'deskripsi' => 'Pura Besakih adalah kompleks candi Hindu terbesar, terpenting, dan tersuci di Bali yang terletak di lereng Gunung Agung. Kompleks ini terdiri dari Pura Penataran Agung dan puluhan pura pendamping lainnya, mencerminkan harmoni arsitektur suci bernuansa punden berundak megah.',
                'lat' => -8.373656,
                'lng' => 115.451368,
                'materi_judul' => 'Candi',
                'thumbnail' => 'thumbnails/besakih.jpg',
                'objects' => [
                    [
                        'nama' => 'Arca Pengapit Pura',
                        'deskripsi' => 'Arca batu pengapit gapura Candi Bentar Pura Penataran Agung Besakih.',
                        'path_obj' => 'virtual-museum/objects/models/arca_pengapit.glb',
                        'gambar_real' => 'images/objects/arca_pengapit.jpg',
                    ]
                ]
            ],
            [
                'nama' => 'Candi Gunung Kawi',
                'alamat' => 'Banjar Penaka, Desa Tampaksiring, Kecamatan Tampaksiring, Kabupaten Gianyar, Bali',
                'deskripsi' => 'Candi Gunung Kawi adalah situs arkeologi purbakala abad ke-11 berupa gugusan candi tebing yang dipahat langsung pada dinding tebing batu pasir di tepi Sungai Pakerisan. Situs ini merupakan monumen penghormatan bagi Raja Udayana Warmadewa dan keluarganya.',
                'lat' => -8.472288,
                'lng' => 115.279694,
                'materi_judul' => 'Candi',
                'thumbnail' => 'thumbnails/gunung_kawi.jpg',
                'objects' => [
                    [
                        'nama' => 'Miniatur Candi Tebing',
                        'deskripsi' => 'Representasi 3D detail struktur pahatan candi tebing Gunung Kawi Tampaksiring.',
                        'path_obj' => 'virtual-museum/objects/models/candi_tebing.glb',
                        'gambar_real' => 'images/objects/candi_tebing.jpg',
                    ]
                ]
            ],
            [
                'nama' => 'Goa Gajah Bedulu',
                'alamat' => 'Desa Bedulu, Kecamatan Blahbatuh, Kabupaten Gianyar, Bali',
                'deskripsi' => 'Situs Goa Gajah menyajikan kompleks gua pertapaan bercorak Hindu-Buddha yang bersejarah tinggi dari abad ke-11. Di dalamnya terdapat arca Dewa Ganesha dan arca Tiga Lingga, serta area petirtaan suci kuno dengan arca pancuran bidadari penjaga mata air.',
                'lat' => -8.523056,
                'lng' => 115.287222,
                'materi_judul' => 'Arca Hindu-Buddha',
                'thumbnail' => 'thumbnails/goa_gajah.jpg',
                'objects' => [
                    [
                        'nama' => 'Arca Ganesha Goa Gajah',
                        'deskripsi' => 'Arca Dewa Ganesha sebagai perlambang kebijaksanaan dan penghalau rintangan yang berada di dalam relung Goa Gajah.',
                        'path_obj' => 'virtual-museum/objects/models/ganesha_goagajah.glb',
                        'gambar_real' => 'images/objects/ganesha_goagajah.jpg',
                    ]
                ]
            ],
            [
                'nama' => 'Situs Sarkofagus Manuaba',
                'alamat' => 'Desa Kenderan, Kecamatan Tegallalang, Kabupaten Gianyar, Bali',
                'deskripsi' => 'Tinggalan sarkofagus prasejarah dari zaman logam/perundagian di Bali. Berfungsi sebagai peti kubur batu pelindung jasad tokoh adat terpandang, dilengkapi ukiran simbolik berwujud topeng gaib pelindung roh leluhur dari gangguan roh jahat.',
                'lat' => -8.455200,
                'lng' => 115.291300,
                'materi_judul' => 'Sarkofagus',
                'thumbnail' => 'thumbnails/sarkofagus_manuaba.jpg',
                'objects' => [
                    [
                        'nama' => 'Sarkofagus Batu Manuaba',
                        'deskripsi' => 'Peti mati batu megalitik lengkap dengan ukiran penutup kepala topeng pelindung jasad.',
                        'path_obj' => 'virtual-museum/objects/models/sarkofagus_manuaba.glb',
                        'gambar_real' => 'images/objects/sarkofagus_manuaba.jpg',
                    ]
                ]
            ],
            [
                'nama' => 'Situs Megalitik Punden Berundak Lempuyang',
                'alamat' => 'Desa Tri Samaya, Kecamatan Abang, Kabupaten Karangasem, Bali',
                'deskripsi' => 'Situs punden berundak megalitik asli di kawasan suci Lempuyang. Menampilkan struktur terasering batu bertingkat peninggalan zaman prasejarah Bali sebelum berkembangnya pengaruh arsitektur pura Hindu modern.',
                'lat' => -8.390800,
                'lng' => 115.629400,
                'materi_judul' => 'Punden Berundak',
                'thumbnail' => 'thumbnails/lempuyang_punden.jpg',
                'objects' => [
                    [
                        'nama' => 'Miniatur Punden Berundak',
                        'deskripsi' => 'Visualisasi arsitektur prasejarah teras batu bertingkat sebagai media pemujaan roh leluhur.',
                        'path_obj' => 'virtual-museum/objects/models/punden_berundak.glb',
                        'gambar_real' => 'images/objects/punden_berundak.jpg',
                    ]
                ]
            ],
            [
                'nama' => 'Kerta Gosa Semarapura',
                'alamat' => 'Semarapura Kelod, Kecamatan Klungkung, Kabupaten Klungkung, Bali',
                'deskripsi' => 'Kerta Gosa adalah bangunan bersejarah balai peradilan peninggalan masa kejayaan Kerajaan Klungkung. Langit-langit bale dihiasi lukisan klasik bercorak Wayang Kamasan yang menggambarkan hukum karma phala dan perjalanan spiritual ke akhirat.',
                'lat' => -8.535833,
                'lng' => 115.403889,
                'materi_judul' => 'Wayang Kamasan',
                'thumbnail' => 'thumbnails/kerta_gosa.jpg',
                'objects' => [
                    [
                        'nama' => 'Lukisan Wayang Kamasan Kerta Gosa',
                        'deskripsi' => 'Panel lukisan langit-langit Kerta Gosa yang legendaris, menceritakan kisah perjalanan Bima Swarga.',
                        'path_obj' => 'virtual-museum/objects/models/wayang_kamasan.glb',
                        'gambar_real' => 'images/objects/wayang_kamasan.jpg',
                    ]
                ]
            ],
            [
                'nama' => 'Situs Prasasti Blanjong Sanur',
                'alamat' => 'Banjar Blanjong, Sanur Kauh, Denpasar Selatan, Kota Denpasar, Bali',
                'deskripsi' => 'Prasasti Blanjong berbentuk tiang batu (ciung) peninggalan Raja Sri Kesari Warmadewa berangka tahun 913 Masehi. Ditulis dalam dua bahasa (Sansekerta dan Kawi) dengan dua aksara (Pre-Negari dan Bali Kuno), menjadikannya dokumen tertulis tertua tentang sejarah Bali.',
                'lat' => -8.705833,
                'lng' => 115.253889,
                'materi_judul' => 'Prasasti',
                'thumbnail' => 'thumbnails/prasasti_blanjong.jpg',
                'objects' => [
                    [
                        'nama' => 'Pilar Prasasti Blanjong',
                        'deskripsi' => 'Pilar batu silinder bersejarah tinggi bertuliskan silsilah keturunan raja dinasti Warmadewa.',
                        'path_obj' => 'virtual-museum/objects/models/prasasti_blanjong.glb',
                        'gambar_real' => 'images/objects/prasasti_blanjong.jpg',
                    ]
                ]
            ]
        ];

        foreach ($situsData as $data) {
            // Dapatkan Materi ID berdasarkan judul materi
            $materi = Materi::where('judul', $data['materi_judul'])->first();
            if (!$materi) {
                // Jika materi tidak ditemukan, ambil materi pertama yang ada
                $materi = Materi::first();
            }

            if (!$materi) {
                continue; // Skip jika tidak ada materi sama sekali di database
            }

            // Create atau update Situs Peninggalan
            $situs = SitusPeninggalan::updateOrCreate(
                ['nama' => $data['nama']],
                [
                    'alamat' => $data['alamat'],
                    'deskripsi' => $data['deskripsi'],
                    'lat' => $data['lat'],
                    'lng' => $data['lng'],
                    'materi_id' => $materi->materi_id,
                    'user_id' => $admin->id,
                    'thumbnail' => $data['thumbnail'],
                ]
            );

            // Create atau update Virtual Museum untuk situs ini
            $museum = VirtualMuseum::updateOrCreate(
                ['situs_id' => $situs->situs_id],
                [
                    'nama' => 'Museum Virtual ' . $situs->nama,
                    'path_obj' => 'virtual-museum/models/' . Str::slug($situs->nama) . '.glb',
                ]
            );

            // Create atau update Virtual Museum Objects dan AR Markers
            foreach ($data['objects'] as $obj) {
                // 1. Buat ArMarker terlebih dahulu
                $markerName = 'Marker ' . $obj['nama'];
                $slug = Str::slug($obj['nama']);
                
                $marker = ArMarker::updateOrCreate(
                    [
                        'situs_id' => $situs->situs_id,
                        'museum_id' => $museum->museum_id,
                        'nama' => $markerName,
                    ],
                    [
                        'path_gambar_marker' => 'markers/' . $slug . '.png',
                        'path_patt' => 'patterns/' . $slug . '.patt',
                    ]
                );

                // 2. Buat VirtualMuseumObject dan kaitkan dengan marker yang baru dibuat
                VirtualMuseumObject::updateOrCreate(
                    [
                        'museum_id' => $museum->museum_id,
                        'situs_id' => $situs->situs_id,
                        'nama' => $obj['nama'],
                    ],
                    [
                        'marker_id' => $marker->marker_id,
                        'deskripsi' => $obj['deskripsi'],
                        'path_obj' => $obj['path_obj'],
                        'gambar_real' => $obj['gambar_real'],
                        'path_gambar_marker' => 'markers/' . $slug . '.png',
                        'path_patt' => 'patterns/' . $slug . '.patt',
                        'path_audio' => null,
                    ]
                );
            }
        }
    }
}
