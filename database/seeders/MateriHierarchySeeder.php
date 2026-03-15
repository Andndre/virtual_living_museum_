<?php

namespace Database\Seeders;

use App\Models\Era;
use App\Models\Materi;
use Illuminate\Database\Seeder;

class MateriHierarchySeeder extends Seeder
{
  /**
   * Run the database seeds.
   */
  public function run(): void
  {
    $materiByEra = [
      'A' => [
        ['bab' => 1, 'judul' => 'Punden Berundak', 'deskripsi' => 'Materi peninggalan punden berundak pada periode prasejarah.'],
        ['bab' => 2, 'judul' => 'Sarkofagus', 'deskripsi' => 'Materi peninggalan sarkofagus pada periode prasejarah.'],
        ['bab' => 3, 'judul' => 'Arca Megalitik', 'deskripsi' => 'Materi arca megalitik sebagai peninggalan periode prasejarah.'],
        ['bab' => 4, 'judul' => 'Menhir', 'deskripsi' => 'Materi peninggalan menhir pada periode prasejarah.'],
        ['bab' => 5, 'judul' => 'Dolmen', 'deskripsi' => 'Materi peninggalan dolmen pada periode prasejarah.'],
      ],
      'B' => [
        ['bab' => 1, 'judul' => 'Arca Hindu-Buddha', 'deskripsi' => 'Materi arca pada masa Bali Kuno dan Hindu-Buddha.'],
        ['bab' => 2, 'judul' => 'Candi', 'deskripsi' => 'Materi candi pada masa Bali Kuno dan Hindu-Buddha.'],
        ['bab' => 3, 'judul' => 'Prasasti', 'deskripsi' => 'Materi prasasti sebagai sumber sejarah masa Bali Kuno dan Hindu-Buddha.'],
      ],
      'C' => [
        ['bab' => 1, 'judul' => 'Periode Majapahit', 'deskripsi' => 'Materi peninggalan periode Majapahit di Bali.'],
      ],
      'D' => [
        ['bab' => 1, 'judul' => 'Wayang Kamasan', 'deskripsi' => 'Materi Wayang Kamasan pada era Gelgel dan Sembilan Kerajaan.'],
      ],
      'E' => [
        ['bab' => 1, 'judul' => 'Masa Kolonial Belanda', 'deskripsi' => 'Materi peninggalan pada masa kolonial Belanda.'],
      ],
      'F' => [
        ['bab' => 1, 'judul' => 'Masa Pasca-Kemerdekaan', 'deskripsi' => 'Materi peninggalan pada masa pasca-kemerdekaan hingga saat ini.'],
      ],
    ];

    $globalOrder = 1;

    foreach ($materiByEra as $kodeEra => $items) {
      $era = Era::where('kode', $kodeEra)->first();
      if (!$era) {
        continue;
      }

      foreach ($items as $item) {
        $materi = Materi::where('judul', $item['judul'])->first();

        if ($materi) {
          $materi->update([
            'era_id' => $era->era_id,
            'bab' => $item['bab'],
            'deskripsi' => $materi->deskripsi ?: $item['deskripsi'],
            'urutan' => $globalOrder,
          ]);
        } else {
          Materi::create([
            'era_id' => $era->era_id,
            'bab' => $item['bab'],
            'judul' => $item['judul'],
            'deskripsi' => $item['deskripsi'],
            'urutan' => $globalOrder,
          ]);
        }

        $globalOrder++;
      }
    }
  }
}
