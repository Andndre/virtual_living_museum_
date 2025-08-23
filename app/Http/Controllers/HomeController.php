<?php

namespace App\Http\Controllers;

use App\Models\Ebook;
use App\Models\JawabanUser;
use App\Models\LogAktivitas;
use App\Models\Materi;
use App\Models\MuseumUserVisit;
use App\Models\ProgressMateri;
use App\Models\User;
use App\Models\VirtualMuseum;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    /**
     * Get dynamic greeting based on current time
     */
    private function getGreeting()
    {
        $hour = Carbon::now()->hour;

        if ($hour >= 5 && $hour < 12) {
            return __('app.good_morning');
        } elseif ($hour >= 12 && $hour < 17) {
            return __('app.good_afternoon');
        } elseif ($hour >= 17 && $hour < 21) {
            return __('app.good_evening');
        } else {
            return __('app.good_night');
        }
    }

    public function index(Request $request)
    {
        $greeting = $this->getGreeting();
        $user = Auth::user();
        
        // Check if profile is complete
        $profileComplete = !empty($user->phone_number) && !empty($user->address) && !empty($user->date_of_birth);

        return view('guest.home', compact('greeting', 'profileComplete'));
    }

    public function panduan(Request $request)
    {
        return view('guest.panduan');
    }

    public function pengaturan(Request $request)
    {
        return view('guest.pengaturan');
    }

    public function pengembang(Request $request)
    {
        return view('guest.pengembang');
    }

    public function arMarker(Request $request)
    {
        return view('guest.ar-marker');
    }

    public function maps(Request $request)
    {
        return view('guest.maps');
    }

    /**
     * E-Learning Index Page
     */
    public function elearning(Request $request)
    {
        $user = Auth::user();

        // Get all materis ordered by urutan
        $materis = Materi::with(['pretest', 'posttest', 'ebook', 'situsPeninggalan'])
            ->orderBy('urutan')
            ->get();

        // Tandai status tiap materi (completed, available, locked) berdasarkan level user
        $materis = $materis->map(function ($materi) use ($user) {
            $materiLevel = $materi->urutan;
            if ($materiLevel < $user->level_sekarang + 1) {
                $materi->is_completed = true;
                $materi->is_available = true;
            } elseif ($materiLevel == $user->level_sekarang + 1) {
                $materi->is_completed = false;
                $materi->is_available = true;
            } else {
                $materi->is_completed = false;
                $materi->is_available = false;
            }

            return $materi;
        });

        $totalMateri = $materis->count();
        $completedMateri = $materis->where('is_completed', true)->count();
        $progressPercentage = $totalMateri > 0 ? round(($completedMateri / $totalMateri) * 100) : 0;

        $nextMateri = $materis->first(function ($materi) {
            return $materi->is_available && ! $materi->is_completed;
        });

        $riwayatAktivitas = LogAktivitas::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->take(50)
            ->get()
            ->groupBy(function ($item) {
                return $item->created_at->format('Y-m-d');
            });

        return view('guest.elearning', compact(
            'materis',
            'progressPercentage',
            'nextMateri',
            'riwayatAktivitas'
        ));
    }

    /**
     * E-Learning Materi Detail Page
     */
    public function elearningMateri(Request $request, $materi_id)
    {
        $user = Auth::user();
        $materi = Materi::with(['pretest', 'posttest', 'ebook', 'situsPeninggalan'])->findOrFail($materi_id);

        // Cek status progress user pada materi ini
        $materiLevel = $materi->urutan;
        $isAvailable = ($materiLevel == $user->level_sekarang + 1);
        $isCompleted = ($materiLevel < $user->level_sekarang + 1);

        // Progress step: 1=pretest, 2=ebook, 3=museum, 4=posttest
        $progress = $user->progress_level_sekarang;

        $pretest_completed = $isCompleted || $progress >= User::PRE_TEST;
        $ebook_available = $pretest_completed;
        $all_ebooks_read = $isCompleted || $progress >= User::EBOOK;
        $museum_available = $all_ebooks_read;
        $all_museums_visited = $isCompleted || $progress >= User::VIRTUAL_LIVING_MUSEUM;
        $posttest_available = $all_museums_visited;
        $posttest_completed = $isCompleted || $progress >= User::POST_TEST;

        return view('guest.elearning.materi', compact(
            'materi',
            'pretest_completed',
            'ebook_available',
            'all_ebooks_read',
            'museum_available',
            'all_museums_visited',
            'posttest_available',
            'posttest_completed'
        ));
    }

    /**
     * E-Learning Pretest Page
     */
    public function elearningPretest(Request $request, $materi_id)
    {
        $user = Auth::user();
        $materi = Materi::with('pretest')->findOrFail($materi_id);

        // Check if pretest is already completed
        $isCompleted = $this->isPretestCompleted($user->id, $materi_id);

        $questions = $materi->pretest;
        $totalQuestions = $questions->count();

        // Get results if completed
        $score = 0;
        $correctAnswers = 0;

        if ($isCompleted) {
            $userAnswers = JawabanUser::where('user_id', $user->id)
                ->where('materi_id', $materi_id)
                ->where('jenis', 'pretest')
                ->get();

            $correctAnswers = $userAnswers->where('benar', true)->count();
            $score = $totalQuestions > 0 ? round(($correctAnswers / $totalQuestions) * 100) : 0;
        }

        return view('guest.elearning.pretest', compact(
            'materi',
            'questions',
            'totalQuestions',
            'isCompleted',
            'score',
            'correctAnswers'
        ));
    }

    /**
     * Submit Pretest Answers
     */
    public function submitPretest(Request $request, $materi_id)
    {
        $userAuth = Auth::user();
        $user = User::findOrFail($userAuth->id);
        $materi = Materi::with('pretest')->findOrFail($materi_id);

        // Validate the request
        $request->validate([
            'answers' => 'required|array',
            'answers.*' => 'required|in:A,B,C,D',
        ], [
            'answers.required' => 'Mohon jawab semua pertanyaan.',
            'answers.*.required' => 'Setiap pertanyaan harus dijawab.',
            'answers.*.in' => 'Jawaban harus berupa A, B, C, atau D.',
        ]);

        $answers = $request->input('answers', []);
        $correctCount = 0;
        $totalQuestions = $materi->pretest->count();

        foreach ($materi->pretest as $question) {
            $userAnswer = $answers[$question->pretest_id] ?? null;

            // Skip saving if no answer provided
            if ($userAnswer === null) {
                continue;
            }

            $isCorrect = $userAnswer === $question->jawaban_benar;

            if ($isCorrect) {
                $correctCount++;
            }

            // Save answer
            JawabanUser::create([
                'user_id' => $user->id,
                'materi_id' => $materi_id,
                'jenis' => 'pretest',
                'soal_id' => $question->pretest_id,
                'jawaban_user' => $userAnswer,
                'benar' => $isCorrect,
                'poin' => $isCorrect ? 10 : 0,
                'answered_at' => now(),
            ]);
        }

        // Update progress materi
        // Seharusnya ini akan selalu True untuk sekarang
        // karena pretest tidak bisa diulang
        if ($materi->shouldIncrementProgress($user, 1)) {
            $user->incrementProgressLevel();
            $this->logActivity($user->id, "Menyelesaikan pretest {$materi->judul}");
        }

        return redirect()->route('guest.elearning.pretest', $materi_id)
            ->with('success', 'Pretest berhasil diselesaikan!');
    }

    /**
     * E-Learning Posttest Page
     */
    public function elearningPosttest(Request $request, $materi_id)
    {
        $user = Auth::user();
        $materi = Materi::with('posttest')->findOrFail($materi_id);

        // Check if pretest is completed (requirement for posttest)
        if (! $this->isPretestCompleted($user->id, $materi_id)) {
            return redirect()->route('guest.elearning.materi', $materi_id)
                ->with('error', 'Selesaikan pretest terlebih dahulu.');
        }

        // Check if posttest is already completed (hanya true jika sudah ada jawaban user di DB)
        $userAnswers = JawabanUser::where('user_id', $user->id)
            ->where('materi_id', $materi_id)
            ->where('jenis', 'posttest')
            ->get();
        $isCompleted = $userAnswers->count() > 0;

        $questions = $materi->posttest;
        $totalQuestions = $questions->count();

        // Get results if completed
        $score = 0;
        $correctAnswers = 0;

        if ($isCompleted) {
            $correctAnswers = $userAnswers->where('benar', true)->count();
            $score = $totalQuestions > 0 ? round(($correctAnswers / $totalQuestions) * 100) : 0;
        }

        return view('guest.elearning.posttest', compact(
            'materi',
            'questions',
            'totalQuestions',
            'isCompleted',
            'score',
            'correctAnswers'
        ));
    }

    /**
     * Submit Posttest Answers
     */
    public function submitPosttest(Request $request, $materi_id)
    {
        $userAuth = Auth::user();
        $user = User::findOrFail($userAuth->id);
        $materi = Materi::with('posttest')->findOrFail($materi_id);

        // Validate the request
        $request->validate([
            'answers' => 'required|array',
            'answers.*' => 'required|in:A,B,C,D',
        ], [
            'answers.required' => 'Mohon jawab semua pertanyaan.',
            'answers.*.required' => 'Setiap pertanyaan harus dijawab.',
            'answers.*.in' => 'Jawaban harus berupa A, B, C, atau D.',
        ]);

        $answers = $request->input('answers', []);
        $correctCount = 0;
        $totalQuestions = $materi->posttest->count();

        foreach ($materi->posttest as $question) {
            $userAnswer = $answers[$question->posttest_id] ?? null;

            // Skip saving if no answer provided
            if ($userAnswer === null) {
                continue;
            }

            $isCorrect = $userAnswer === $question->jawaban_benar;

            if ($isCorrect) {
                $correctCount++;
            }

            // Save answer
            JawabanUser::create([
                'user_id' => $user->id,
                'materi_id' => $materi_id,
                'jenis' => 'posttest',
                'soal_id' => $question->posttest_id,
                'jawaban_user' => $userAnswer,
                'benar' => $isCorrect,
                'poin' => $isCorrect ? 10 : 0,
                'answered_at' => now(),
            ]);
        }

        // Increment user progress if needed
        if ($materi->shouldIncrementProgress($user, 4)) {
            $user->incrementProgressLevel();
            $this->logActivity($user->id, "Menyelesaikan posttest {$materi->judul}");
        }

        // Calculate score and correct answers for result display
        $score = $totalQuestions > 0 ? round(($correctCount / $totalQuestions) * 100) : 0;

        return redirect()->route('guest.elearning.posttest', $materi_id)
            ->with([
                'success' => 'Posttest berhasil diselesaikan!',
                'score' => $score,
                'correctAnswers' => $correctCount,
            ]);
    }

    /**
     * Situs Detail Page
     */
    public function situsDetail(Request $request, $situs_id)
    {
        $user = Auth::user();
        $situs = \App\Models\SitusPeninggalan::with(['virtualMuseum', 'materi'])->findOrFail($situs_id);

        return view('guest.situs.detail', compact('situs'));
    }

    public function arMuseum(Request $request, $situs_id, $museum_id)
    {
        $userAuth = Auth::user();
        $user = User::findOrFail($userAuth->id);
        $situs = \App\Models\SitusPeninggalan::findOrFail($situs_id);
        $museum = VirtualMuseum::with('virtualMuseumObjects')->findOrFail($museum_id);
        // Verify that this museum belongs to the situs
        if ($museum->situs_id != $situs_id) {
            abort(404, 'Museum tidak ditemukan di situs ini.');
        }

        // Log AR activity
        $this->logActivity($user->id, 'Memulai pengalaman AR untuk spot: '.$museum->nama.' di '.$situs->nama);

        // --- Museum Visit Tracking Logic ---
        // 1. Catat kunjungan user ke museum ini (jika belum ada)
        MuseumUserVisit::firstOrCreate([
            'user_id' => $user->id,
            'museum_id' => $museum->museum_id,
        ], [
            'visited_at' => now(),
        ]);

        // 2. Jika materi terkait ada, cek apakah semua museum pada materi ini sudah dikunjungi user
        if ($situs->materi_id) {
            $materiId = $situs->materi_id;
            $materi = Materi::findOrFail($materiId);
            // Ambil semua museum_id pada materi ini
            $allMuseumIds = VirtualMuseum::whereIn('situs_id',
                \App\Models\SitusPeninggalan::where('materi_id', $materiId)->pluck('situs_id')
            )->pluck('museum_id')->toArray();

            // dd($allMuseumIds);

            // Ambil semua museum_id yang sudah dikunjungi user
            $visitedMuseumIds = MuseumUserVisit::where('user_id', $user->id)
                ->whereIn('museum_id', $allMuseumIds)
                ->pluck('museum_id')->unique()->toArray();

            // Jika semua museum sudah dikunjungi dan user progress di step museum, increment progress
            if (count($allMuseumIds) > 0 && count($visitedMuseumIds) === count($allMuseumIds)) {
                if ($user->progress_level_sekarang == User::EBOOK && $user->level_sekarang + 1 == $materi->urutan) {
                    $user->incrementProgressLevel();
                    $this->logActivity($user->id, 'Menuntaskan semua spot Virtual Museum pada materi ID: '.$materiId);
                }
            }
        }

        // dd($museum);

        return view('guest.ar.museum', compact('situs', 'museum'));
    }

    /**
     * Check if materi is completed
     */
    private function isMateriCompleted($user_id, $materi_id)
    {
        if (! $materi_id) {
            return true;
        }

        $materi = Materi::with(['pretest', 'posttest'])->find($materi_id);
        if (! $materi) {
            return false;
        }

        // Check if both pretest and posttest are completed
        $pretestCompleted = $this->isPretestCompleted($user_id, $materi_id);
        $posttestCompleted = $this->isPosttestCompleted($user_id, $materi_id);

        return $pretestCompleted && $posttestCompleted;
    }

    /**
     * Check if pretest is completed
     */
    private function isPretestCompleted($user_id, $materi_id)
    {
        $materi = Materi::find($materi_id);
        if (! $materi) {
            return false;
        }
        $user = User::find($user_id);
        if (! $user) {
            return false;
        }

        // Jika materi sudah lewat level user, pasti sudah selesai
        if ($materi->urutan < $user->level_sekarang + 1) {
            return true;
        }

        // Jika progress user pada materi ini sudah mencapai atau melewati step pretest
        return $user->progress_level_sekarang >= User::PRE_TEST;
    }

    /**
     * Check if posttest is completed
     */
    private function isPosttestCompleted($user_id, $materi_id)
    {
        $materi = Materi::find($materi_id);
        if (! $materi) {
            return false;
        }
        $user = User::find($user_id);
        if (! $user) {
            return false;
        }

        // Jika materi sudah lewat level user, pasti sudah selesai
        if ($materi->urutan < $user->level_sekarang + 1) {
            return true;
        }

        // Jika progress user pada materi ini sudah mencapai atau melewati step posttest
        return $user->progress_level_sekarang >= User::POST_TEST;
    }

    /**
     * Calculate materi progress
     */
    private function calculateMateriProgress($user_id, $materi_id)
    {
        $materi = Materi::with(['pretest', 'posttest', 'ebook'])->find($materi_id);

        $totalSteps = 0;
        $completedSteps = 0;

        // Count pretest
        if ($materi->pretest->count() > 0) {
            $totalSteps++;
            if ($this->isPretestCompleted($user_id, $materi_id)) {
                $completedSteps++;
            }
        }

        // Count ebooks (assume read if pretest completed)
        if ($materi->ebook->count() > 0) {
            $totalSteps++;
            if ($this->isPretestCompleted($user_id, $materi_id)) {
                $completedSteps++;
            }
        }

        // Count posttest
        if ($materi->posttest->count() > 0) {
            $totalSteps++;
            if ($this->isPosttestCompleted($user_id, $materi_id)) {
                $completedSteps++;
            }
        }

        $progressPercentage = $totalSteps > 0 ? round(($completedSteps / $totalSteps) * 100) : 100;
        $isCompleted = $this->isMateriCompleted($user_id, $materi_id);
        $isStarted = $this->isPretestCompleted($user_id, $materi_id) || $this->isPosttestCompleted($user_id, $materi_id);

        return [
            'progress_percentage' => $progressPercentage,
            'completed_steps' => $completedSteps,
            'total_steps' => $totalSteps,
            'is_completed' => $isCompleted,
            'is_started' => $isStarted,
        ];
    }

    /**
     * Log user activity
     */
    private function logActivity($user_id, $aktivitas)
    {
        LogAktivitas::create([
            'user_id' => $user_id,
            'aktivitas' => $aktivitas,
            'created_at' => now(),
        ]);
    }

    /**
     * E-Learning E-book Page
     */
    public function elearningEbook($ebook_id)
    {
        $user = Auth::user();
        $ebook = Ebook::with('materi')->findOrFail($ebook_id);

        // Check if user has completed pretest for this materi
        $pretestCompleted = $this->isPretestCompleted($user->id, $ebook->materi_id);
        if (! $pretestCompleted) {
            return redirect()->route('guest.elearning.materi', $ebook->materi_id)
                ->with('error', 'Anda harus menyelesaikan pre-test terlebih dahulu untuk mengakses e-book ini.');
        }

        // Check if file exists
        if (! $ebook->path_file || ! file_exists(storage_path('app/public/'.$ebook->path_file))) {
            return redirect()->route('guest.elearning.materi', $ebook->materi_id)
                ->with('error', 'File e-book tidak ditemukan.');
        }

        // Log activity with ebook_id for tracking
        $this->logActivity($user->id, 'Telah membaca E-Book: '.$ebook->judul.' [ebook_id:'.$ebook->ebook_id.']');

        // Catatan: Progress level akan diupdate via AJAX saat user membaca halaman terakhir (lihat endpoint di bawah)

        return view('guest.elearning.ebook', compact('ebook'));
    }

    /**
     * Endpoint untuk AJAX: update progress_level_sekarang user jika sudah membaca semua halaman e-book
     */
    public function markEbookRead(Request $request, $ebook_id)
    {
        $userAuth = Auth::user();
        $user = User::findOrFail($userAuth->id);
        $ebook = Ebook::with('materi')->findOrFail($ebook_id);

        // Cek apakah user sudah pada step EBOOK dan materi yang benar
        $materi = $ebook->materi;
        if ($materi && $materi->urutan == $user->level_sekarang + 1 && $user->progress_level_sekarang == \App\Models\User::PRE_TEST) {
            // Increment progress ke EBOOK
            $user->incrementProgressLevel();
        }

        // Log aktivitas jika perlu
        $this->logActivity($user->id, 'Menuntaskan semua halaman E-Book: '.$ebook->judul.' [ebook_id:'.$ebook->ebook_id.']');

        return response()->json(['success' => true]);
    }

    /**
     * Check if all e-books in a materi have been read by user
     */
    private function areAllEbooksRead($user_id, $materi_id)
    {
        $progress = ProgressMateri::where('user_id', $user_id)
            ->where('materi_id', $materi_id)
            ->first();

        if (! $progress) {
            return false;
        }

        // Check if ebook status is completed
        return in_array($progress->status, [
            ProgressMateri::STATUS_EBOOK_SELESAI,
            ProgressMateri::STATUS_MUSEUM_SELESAI,
            ProgressMateri::STATUS_POSTTEST_SELESAI,
        ]);
    }

    /**
     * Check if all virtual museums in a materi have been visited by user
     */
    private function areAllMuseumsVisited($user_id, $materi_id)
    {
        $progress = ProgressMateri::where('user_id', $user_id)
            ->where('materi_id', $materi_id)
            ->first();

        if (! $progress) {
            return false;
        }

        // Check if museum status is completed
        return in_array($progress->status, [
            ProgressMateri::STATUS_MUSEUM_SELESAI,
            ProgressMateri::STATUS_POSTTEST_SELESAI,
        ]);
    }
}
