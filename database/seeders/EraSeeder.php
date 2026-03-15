<?php

namespace Database\Seeders;

use App\Models\Era;
use Illuminate\Database\Seeder;

class EraSeeder extends Seeder
{
  /**
   * Run the database seeds.
   */
  public function run(): void
  {
    $eras = [
      ['kode' => 'A', 'nama' => 'Zaman Prasejarah', 'rentang_waktu' => '+/-2000 SM - 800 M', 'urutan' => 1],
      ['kode' => 'B', 'nama' => 'Masa Bali Kuno & Hindu-Buddha', 'rentang_waktu' => 'Abad ke-8 - Abad ke-14', 'urutan' => 2],
      ['kode' => 'C', 'nama' => 'Periode Majapahit', 'rentang_waktu' => '1343 M - Abad ke-15', 'urutan' => 3],
      ['kode' => 'D', 'nama' => 'Era Gelgel dan Sembilan Kerajaan', 'rentang_waktu' => 'Abad ke-16 - Abad ke-19', 'urutan' => 4],
      ['kode' => 'E', 'nama' => 'Masa Kolonial Belanda', 'rentang_waktu' => '1846 - 1942', 'urutan' => 5],
      ['kode' => 'F', 'nama' => 'Masa Pasca-Kemerdekaan', 'rentang_waktu' => '1945 - Sekarang', 'urutan' => 6],
    ];

    foreach ($eras as $era) {
      Era::updateOrCreate(['kode' => $era['kode']], $era);
    }
  }
}
