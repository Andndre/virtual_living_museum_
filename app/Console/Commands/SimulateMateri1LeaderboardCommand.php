<?php

namespace App\Console\Commands;

use App\Models\Ebook;
use App\Models\JawabanUser;
use App\Models\LogAktivitas;
use App\Models\Materi;
use App\Models\MuseumUserVisit;
use App\Models\Posttest;
use App\Models\Pretest;
use App\Models\SitusPeninggalan;
use App\Models\User;
use App\Models\VirtualMuseum;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class SimulateMateri1LeaderboardCommand extends Command
{
    protected $signature = 'simulation:materi1-leaderboard
                            {--clean : Hapus seluruh data user simulasi sebelumnya sebelum membuat baru}
                            {--source=database/data/students_absen_kls7.json : Path sumber data siswa (JSON)}
                            {--export=storage/simulated_credentials.csv : Path file CSV tujuan export}';

    protected $description = 'Simulasikan akun baru dari seluruh nama siswa di ABSEN KLS 7 NEW.xls, selesaikan Materi 1, dan isi Leaderboard';

    private const SIMULATION_TAG = 'Simulasi Peserta';

    public function handle(): int
    {
        $this->info('=====================================================');
        $this->info('  SIMULASI 516 SISWA (ABSEN KLS 7) & LEADERBOARD VLM ');
        $this->info('=====================================================');

        // 1. Opsi Pembersihan (jika --clean)
        if ($this->option('clean')) {
            $this->warn('Membersihkan user simulasi sebelumnya...');
            $existingSimUsers = User::where('pekerjaan', self::SIMULATION_TAG)->get();
            $deletedCount = $existingSimUsers->count();

            foreach ($existingSimUsers as $simUser) {
                $simUser->delete();
            }

            $this->info("✓ Berhasil menghapus {$deletedCount} akun simulasi lama.");
        }

        // 2. Baca seluruh data siswa dari file sumber
        $sourcePath = base_path($this->option('source'));
        if (! file_exists($sourcePath)) {
            $this->error("File sumber data siswa tidak ditemukan: {$sourcePath}");

            return 1;
        }

        $students = json_decode(file_get_contents($sourcePath), true);
        if (! is_array($students) || empty($students)) {
            $this->error('Data siswa kosong atau format JSON tidak valid!');

            return 1;
        }

        $totalStudents = count($students);
        $this->info("Berhasil membaca {$totalStudents} nama siswa dari seluruh sheet 'ABSEN KLS 7 NEW.xls'.");

        // 3. Ambil data Materi 1 (Punden Berundak)
        $materiId = 5;
        $materi = Materi::find($materiId);
        if (! $materi) {
            $this->error("Materi ID {$materiId} tidak ditemukan!");

            return 1;
        }

        $pretests = Pretest::where('materi_id', $materiId)->get();
        $posttests = Posttest::where('materi_id', $materiId)->get();
        $ebook = Ebook::where('materi_id', $materiId)->first();
        $situsList = SitusPeninggalan::whereIn('situs_id', [2, 3])->get();
        $museums = VirtualMuseum::whereIn('situs_id', [2, 3])->get();

        $this->line("• Materi: <comment>{$materi->judul}</comment> (ID: {$materiId})");
        $this->line("• Soal Pretest: <comment>{$pretests->count()}</comment> | Soal Posttest: <comment>{$posttests->count()}</comment>");
        $this->line('• E-Book: <comment>'.($ebook ? $ebook->judul : 'None').'</comment>');
        $this->line("• Spot Virtual Museum: <comment>{$museums->count()}</comment>");

        // 4. Bangun distribusi target posttest (60% - 100%) untuk seluruh 516 siswa
        $targetsPool = $this->buildScoreDistribution($totalStudents);

        $credentials = [];
        $words = [
            'Kintamani', 'Singaraja', 'Bedugul', 'Batur', 'Denpasar', 'Gianyar',
            'Sanur', 'Ubud', 'Pangkung', 'Selulung', 'Tabanan', 'Bangli',
            'Klungkung', 'Karangasem', 'Jembrana', 'Semeton', 'Ganesha', 'Punden',
            'Sarkofagus', 'Megalitikum', 'Bintang', 'Nusantara', 'Dewata', 'Merdeka', 'Garuda',
        ];

        $bar = $this->output->createProgressBar($totalStudents);
        $bar->start();

        foreach ($students as $index => $item) {
            // Jaga nama PERSIS seperti di Excel (jangan ada yang berbeda / dikurangi)
            $name = trim($item['name']);
            $sheet = $item['source_sheet'] ?? '';
            $kelas = $item['kelas'] ?? '';
            $noAbsen = $item['no_absen'] ?? '';
            $nis = $item['nis'] ?? '';
            $gender = $item['gender'] ?? '';

            // Generate email unik realistis khusus domain @gmail.com
            $email = $this->generateUniqueGmail($name, $index + 1);

            // Generate password unik realistis
            $plainPassword = $words[array_rand($words)].rand(10, 99).['!', '@', '#', '*'][array_rand(['!', '@', '#', '*'])].rand(20, 26);

            // Timeline waktu berjenjang
            $startTime = Carbon::now()->subDays(rand(2, 6))->subHours(rand(1, 10))->subMinutes(rand(1, 45));

            // Simpan akun user simulasi baru
            $simUser = User::create([
                'name' => $name,
                'email' => $email,
                'password' => Hash::make($plainPassword),
                'phone_number' => '08'.rand(11, 99).rand(1000000, 9999999),
                'address' => 'Singaraja, Buleleng, Bali',
                'date_of_birth' => Carbon::now()->subYears(13)->subDays(rand(1, 365)),
                'pekerjaan' => self::SIMULATION_TAG,
                'role' => 'user',
                'email_verified_at' => $startTime,
                'level_sekarang' => 1,
                'progress_level_sekarang' => 0,
                'created_at' => $startTime,
                'updated_at' => $startTime->copy()->addMinutes(45),
            ]);

            // A. Pretest (skor 40% - 73%)
            $tPretest = $startTime->copy()->addMinutes(rand(3, 7));
            $pretestCorrectTarget = rand(6, 11);
            $pretestCorrectCount = 0;

            if ($pretests->isNotEmpty()) {
                $shuffledPre = $pretests->shuffle();
                $preIndices = $shuffledPre->take($pretestCorrectTarget)->pluck('pretest_id')->toArray();

                foreach ($pretests as $q) {
                    $isCorrect = in_array($q->pretest_id, $preIndices);
                    if ($isCorrect) {
                        $answer = $q->jawaban_benar;
                        $pretestCorrectCount++;
                    } else {
                        $options = array_diff(['A', 'B', 'C', 'D'], [$q->jawaban_benar]);
                        $answer = $options[array_rand($options)];
                    }

                    JawabanUser::create([
                        'user_id' => $simUser->id,
                        'materi_id' => $materiId,
                        'jenis' => 'pretest',
                        'soal_id' => $q->pretest_id,
                        'jawaban_user' => $answer,
                        'benar' => $isCorrect,
                        'poin' => $isCorrect ? 10 : 0,
                        'answered_at' => $tPretest,
                    ]);
                }

                $pretestPct = round(($pretestCorrectCount / $pretests->count()) * 100);
                LogAktivitas::create([
                    'user_id' => $simUser->id,
                    'aktivitas' => "Menyelesaikan pretest {$materi->judul} dengan nilai {$pretestPct}%",
                    'created_at' => $tPretest,
                ]);
            }

            // B. E-Book
            $tEbook = $tPretest->copy()->addMinutes(rand(5, 12));
            if ($ebook) {
                LogAktivitas::create([
                    'user_id' => $simUser->id,
                    'aktivitas' => "Telah membaca E-Book: {$ebook->judul}",
                    'created_at' => $tEbook,
                ]);
                LogAktivitas::create([
                    'user_id' => $simUser->id,
                    'aktivitas' => "Menuntaskan semua halaman E-Book: {$ebook->judul}",
                    'created_at' => $tEbook->copy()->addMinutes(rand(4, 8)),
                ]);
            }

            // C. VR 360 & AR Spots
            $tMuseum = $tEbook->copy()->addMinutes(rand(6, 15));
            foreach ($situsList as $situs) {
                LogAktivitas::create([
                    'user_id' => $simUser->id,
                    'aktivitas' => "Memulai pengalaman VR 360 Panorama untuk situs: {$situs->nama}",
                    'created_at' => $tMuseum,
                ]);
            }

            foreach ($museums as $mIndex => $museum) {
                $tSpot = $tMuseum->copy()->addMinutes(($mIndex + 1) * 3);
                MuseumUserVisit::create([
                    'user_id' => $simUser->id,
                    'museum_id' => $museum->museum_id,
                    'visited_at' => $tSpot,
                    'created_at' => $tSpot,
                    'updated_at' => $tSpot,
                ]);

                $situsNama = $museum->situs_id == 2 ? 'Pura Candi' : 'Pura Mihu';
                LogAktivitas::create([
                    'user_id' => $simUser->id,
                    'aktivitas' => "Memulai pengalaman AR untuk spot: {$museum->nama} di {$situsNama}",
                    'created_at' => $tSpot,
                ]);
            }

            LogAktivitas::create([
                'user_id' => $simUser->id,
                'aktivitas' => "Menuntaskan semua spot Virtual Living Museum pada materi ID: {$materiId}",
                'created_at' => $tMuseum->copy()->addMinutes(12),
            ]);

            // D. Posttest (skor 60% - 100%)
            $tPosttest = $tMuseum->copy()->addMinutes(rand(14, 25));
            $targetCorrect = $targetsPool[$index] ?? 12;
            $posttestCorrectCount = 0;

            if ($posttests->isNotEmpty()) {
                $shuffledPost = $posttests->shuffle();
                $postIndices = $shuffledPost->take($targetCorrect)->pluck('posttest_id')->toArray();

                foreach ($posttests as $q) {
                    $isCorrect = in_array($q->posttest_id, $postIndices);
                    if ($isCorrect) {
                        $answer = $q->jawaban_benar;
                        $posttestCorrectCount++;
                    } else {
                        $options = array_diff(['A', 'B', 'C', 'D'], [$q->jawaban_benar]);
                        $answer = $options[array_rand($options)];
                    }

                    JawabanUser::create([
                        'user_id' => $simUser->id,
                        'materi_id' => $materiId,
                        'jenis' => 'posttest',
                        'soal_id' => $q->posttest_id,
                        'jawaban_user' => $answer,
                        'benar' => $isCorrect,
                        'poin' => $isCorrect ? 10 : 0,
                        'answered_at' => $tPosttest,
                    ]);
                }

                LogAktivitas::create([
                    'user_id' => $simUser->id,
                    'aktivitas' => "Menyelesaikan posttest {$materi->judul}",
                    'created_at' => $tPosttest,
                ]);
            }

            $scorePct = $posttests->isNotEmpty() ? round(($posttestCorrectCount / $posttests->count()) * 100) : 0;

            $credentials[] = [
                'id' => $simUser->id,
                'sheet' => $sheet,
                'kelas' => $kelas,
                'no_absen' => $noAbsen,
                'nis' => $nis,
                'gender' => $gender,
                'name' => $simUser->name,
                'email' => $simUser->email,
                'password' => $plainPassword,
                'posttest_score' => $scorePct,
                'correct_answers' => "{$posttestCorrectCount}/{$posttests->count()}",
                'completed_at' => $tPosttest->format('Y-m-d H:i:s'),
            ];

            $bar->advance();
        }

        $bar->finish();
        $this->newLine(2);

        // 5. Urutkan berdasarkan skor tertinggi untuk pemberian peringkat
        usort($credentials, fn ($a, $b) => $b['posttest_score'] <=> $a['posttest_score']);

        foreach ($credentials as $rankIndex => &$cred) {
            $cred['rank'] = $rankIndex + 1;
        }
        unset($cred);

        // 6. Export ke CSV
        $exportPath = base_path($this->option('export'));
        $dir = dirname($exportPath);
        if (! is_dir($dir)) {
            mkdir($dir, 0755, true);
        }

        $fp = fopen($exportPath, 'w');
        fputcsv($fp, ['Peringkat', 'ID', 'Sheet', 'Kelas', 'No_Absen', 'NIS', 'Gender', 'Nama Siswa', 'Email Fake (Gmail)', 'Password Plaintext', 'Skor Posttest (%)', 'Jawaban Benar', 'Waktu Selesai']);
        foreach ($credentials as $row) {
            fputcsv($fp, [
                $row['rank'],
                $row['id'],
                $row['sheet'],
                $row['kelas'],
                $row['no_absen'],
                $row['nis'],
                $row['gender'],
                $row['name'],
                $row['email'],
                $row['password'],
                $row['posttest_score'],
                $row['correct_answers'],
                $row['completed_at'],
            ]);
        }
        fclose($fp);

        $this->info("✓ Berhasil mengekspor 516 kredensial siswa ke: <comment>{$exportPath}</comment>");

        // 7. Tampilkan Preview Top 15 Leaderboard
        $this->newLine();
        $this->info('PREVIEW TOP 15 LEADERBOARD DARI 516 SISWA (ABSEN KLS 7):');
        $headers = ['Rank', 'ID', 'Kelas', 'Nama Siswa', 'Email (Gmail)', 'Password', 'Skor (%)', 'Benar'];
        $tableRows = [];

        foreach (array_slice($credentials, 0, 15) as $c) {
            $tableRows[] = [
                $c['rank'],
                $c['id'],
                $c['kelas'],
                $c['name'],
                $c['email'],
                $c['password'],
                $c['posttest_score'].'%',
                $c['correct_answers'],
            ];
        }

        $this->table($headers, $tableRows);
        $this->info('✓ Seluruh simulasi 516 siswa selesai dengan sempurna!');

        return 0;
    }

    /**
     * Membentuk kurva sebaran skor natural untuk sejumlah total siswa (60% - 100%)
     *
     * @return array<int, int>
     */
    private function buildScoreDistribution(int $totalUsers): array
    {
        $distributionProfile = [
            15 => 0.08, // ~8% Juara 100%
            14 => 0.16, // ~16% Sangat baik 93%
            13 => 0.16, // ~16% Sangat baik 87%
            12 => 0.24, // ~24% Menengah atas 80%
            11 => 0.16, // ~16% Menengah 73%
            10 => 0.12, // ~12% Cukup 67%
            9 => 0.08,  // ~8% Batas bawah 60%
        ];

        $pool = [];
        foreach ($distributionProfile as $correctCount => $ratio) {
            $count = (int) round($totalUsers * $ratio);
            for ($i = 0; $i < $count; $i++) {
                $pool[] = $correctCount;
            }
        }

        while (count($pool) < $totalUsers) {
            $pool[] = rand(11, 13);
        }
        while (count($pool) > $totalUsers) {
            array_pop($pool);
        }

        shuffle($pool);

        return $pool;
    }

    /**
     * Generate alamat email realistis berbasis nama dan domain @gmail.com
     */
    private function generateUniqueGmail(string $name, int $index): string
    {
        $cleanName = preg_replace('/[^a-zA-Z0-9\s]/', '', strtolower($name));
        $parts = array_values(array_filter(explode(' ', $cleanName)));

        if (empty($parts)) {
            $base = 'siswa.'.$index;
        } elseif (count($parts) === 1) {
            $base = $parts[0].$index;
        } else {
            $base = $parts[0].'.'.end($parts).$index;
        }

        $email = $base.'@gmail.com';
        $attempt = 1;

        while (User::where('email', $email)->exists()) {
            $email = $base.rand(10, 999).'@gmail.com';
            $attempt++;
            if ($attempt > 50) {
                $email = Str::slug($cleanName, '.').'.'.Str::random(5).'@gmail.com';
                break;
            }
        }

        return $email;
    }
}
