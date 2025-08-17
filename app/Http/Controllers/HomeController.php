<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\Materi;
use App\Models\Ebook;
use App\Models\JawabanUser;
use App\Models\LogAktivitas;
use App\Models\ProgressMateri;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

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
        return view('guest.home', compact('greeting'));
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

    /**
     * E-Learning Index Page
     */
    public function elearning(Request $request)
    {
        $user = Auth::user();
        
        // Get all materis ordered by urutan
        $allMateris = Materi::with(['pretest', 'posttest', 'ebook', 'situsPeninggalan'])
            ->orderBy('urutan')
            ->get();
        
        $materis = $allMateris->map(function ($materi, $index) use ($user, $allMateris) {
            // Check if previous materi is completed (for sequential access)
            $previousMateriCompleted = $index === 0 ? true : $this->isMateriCompleted($user->id, $allMateris[$index - 1]->materi_id ?? null);
            
            // Calculate materi progress
            $materiProgress = $this->calculateMateriProgress($user->id, $materi->materi_id);
            
            $materi->is_available = $previousMateriCompleted;
            $materi->is_completed = $materiProgress['is_completed'];
            $materi->is_started = $materiProgress['is_started'];
            $materi->total_pretest = $materi->pretest->count();
            $materi->total_posttest = $materi->posttest->count();
            $materi->total_ebook = $materi->ebook->count();
            $materi->total_situs = $materi->situsPeninggalan->count();
            
            return $materi;
        });
        
        // Calculate overall progress
        $totalMateri = $materis->count();
        $completedMateri = $materis->where('is_completed', true)->count();
        $progressPercentage = $totalMateri > 0 ? round(($completedMateri / $totalMateri) * 100) : 0;
        
        // Get next materi to continue (first incomplete available materi)
        $nextMateri = $materis->where('is_available', true)
            ->where('is_completed', false)
            ->first();
        
        // Get riwayat aktivitas grouped by date
        $riwayatAktivitas = LogAktivitas::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->take(50) // Limit to last 50 activities
            ->get()
            ->groupBy(function($item) {
                return $item->created_at->format('Y-m-d');
            });
        
        return view('guest.elearning', compact(
            'materis', 
            'totalMateri', 
            'completedMateri', 
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
        $materi = Materi::with(['pretest', 'posttest', 'ebook', 'situsPeninggalan'])
            ->findOrFail($materi_id);
        
        // Check if materi is available (sequential learning)
        $previousMateri = Materi::where('urutan', '<', $materi->urutan)->orderBy('urutan', 'desc')->first();
        if ($previousMateri && !$this->isMateriCompleted($user->id, $previousMateri->materi_id)) {
            return redirect()->route('guest.elearning')->with('error', 'Selesaikan materi sebelumnya terlebih dahulu.');
        }
        
        // Calculate progress
        $materiProgress = $this->calculateMateriProgress($user->id, $materi->materi_id);
        $progressPercentage = $materiProgress['progress_percentage'];
        $completedSteps = $materiProgress['completed_steps'];
        $totalSteps = $materiProgress['total_steps'];
        
        // Check test completion status
        $pretest_completed = $this->isPretestCompleted($user->id, $materi->materi_id);
        $posttest_completed = $this->isPosttestCompleted($user->id, $materi->materi_id);
        
        // Check detailed progress for sequential access
        $ebook_available = $pretest_completed;
        $all_ebooks_read = $this->areAllEbooksRead($user->id, $materi->materi_id);
        $museum_available = $ebook_available && $all_ebooks_read;
        $all_museums_visited = $this->areAllMuseumsVisited($user->id, $materi->materi_id);
        $posttest_available = $museum_available && $all_museums_visited;
        
        // Log activity
        $this->logActivity($user->id, "Mengakses materi: {$materi->judul}");
        
        return view('guest.elearning.materi', compact(
            'materi', 
            'progressPercentage', 
            'completedSteps', 
            'totalSteps',
            'pretest_completed',
            'posttest_completed',
            'ebook_available',
            'all_ebooks_read',
            'museum_available',
            'all_museums_visited',
            'posttest_available'
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
        $user = Auth::user();
        $materi = Materi::with('pretest')->findOrFail($materi_id);
        
        // Validate the request
        $request->validate([
            'answers' => 'required|array',
            'answers.*' => 'required|in:A,B,C,D'
        ], [
            'answers.required' => 'Mohon jawab semua pertanyaan.',
            'answers.*.required' => 'Setiap pertanyaan harus dijawab.',
            'answers.*.in' => 'Jawaban harus berupa A, B, C, atau D.'
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
                'answered_at' => now()
            ]);
        }
        
        // Update progress materi to pretest_selesai
        $progress = ProgressMateri::getOrCreate($user->id, $materi_id);
        if ($progress->status === ProgressMateri::STATUS_BELUM_MULAI) {
            $progress->updateStatus(ProgressMateri::STATUS_PRETEST_SELESAI);
        }
        
        // Log activity
        $score = $totalQuestions > 0 ? round(($correctCount / $totalQuestions) * 100) : 0;
        $this->logActivity($user->id, "Menyelesaikan pretest {$materi->judul} dengan nilai {$score}%");
        
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
        if (!$this->isPretestCompleted($user->id, $materi_id)) {
            return redirect()->route('guest.elearning.materi', $materi_id)
                ->with('error', 'Selesaikan pretest terlebih dahulu.');
        }
        
        // Check if posttest is already completed
        $isCompleted = $this->isPosttestCompleted($user->id, $materi_id);
        
        $questions = $materi->posttest;
        $totalQuestions = $questions->count();
        
        // Get results if completed
        $score = 0;
        $correctAnswers = 0;
        
        if ($isCompleted) {
            $userAnswers = JawabanUser::where('user_id', $user->id)
                ->where('materi_id', $materi_id)
                ->where('jenis', 'posttest')
                ->get();
            
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
        $user = Auth::user();
        $materi = Materi::with('posttest')->findOrFail($materi_id);
        
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
                'answered_at' => now()
            ]);
        }
        
        // Log activity
        $score = $totalQuestions > 0 ? round(($correctCount / $totalQuestions) * 100) : 0;
        $this->logActivity($user->id, "Menyelesaikan posttest {$materi->judul} dengan nilai {$score}%");
        
        return redirect()->route('guest.elearning.posttest', $materi_id)
            ->with('success', 'Posttest berhasil diselesaikan!');
    }

    /**
     * Situs Detail Page
     */
    public function situsDetail(Request $request, $situs_id)
    {
        $user = Auth::user();
        $situs = \App\Models\SitusPeninggalan::with(['virtualMuseum', 'materi'])->findOrFail($situs_id);
        
        // Log activity with situs_id for tracking
        $this->logActivity($user->id, 'Telah mengunjungi Virtual Museum: ' . $situs->nama . ' [situs_id:' . $situs->situs_id . ']');
        
        // Update progress to museum_selesai if all museums in this materi have been visited
        if ($situs->materi_id) {
            $progress = ProgressMateri::getOrCreate($user->id, $situs->materi_id);
            if ($progress->status === ProgressMateri::STATUS_EBOOK_SELESAI) {
                // Get all situs IDs for this materi
                $allSitusIds = \App\Models\SitusPeninggalan::where('materi_id', $situs->materi_id)->pluck('situs_id')->toArray();
                
                // Check how many unique situs have been visited by this user for this materi
                $visitedSitusIds = [];
                foreach ($allSitusIds as $situsId) {
                    $hasVisited = LogAktivitas::where('user_id', $user->id)
                        ->where('aktivitas', 'LIKE', '%[situs_id:' . $situsId . ']%')
                        ->exists();
                    if ($hasVisited) {
                        $visitedSitusIds[] = $situsId;
                    }
                }
                    
                // If all situs have been visited, update progress
                if (count($visitedSitusIds) >= count($allSitusIds)) {
                    $progress->updateStatus(ProgressMateri::STATUS_MUSEUM_SELESAI);
                }
            }
        }
        
        return view('guest.situs.detail', compact('situs'));
    }

    /**
     * Check if materi is completed
     */
    private function isMateriCompleted($user_id, $materi_id)
    {
        if (!$materi_id) return true;
        
        $materi = Materi::with(['pretest', 'posttest'])->find($materi_id);
        if (!$materi) return false;
        
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
        $pretest = Materi::find($materi_id)->pretest ?? collect();
        if ($pretest->isEmpty()) return true; // No pretest means completed
        
        return JawabanUser::where('user_id', $user_id)
            ->where('materi_id', $materi_id)
            ->where('jenis', 'pretest')
            ->exists();
    }

    /**
     * Check if posttest is completed
     */
    private function isPosttestCompleted($user_id, $materi_id)
    {
        $posttest = Materi::find($materi_id)->posttest ?? collect();
        if ($posttest->isEmpty()) return true; // No posttest means completed
        
        return JawabanUser::where('user_id', $user_id)
            ->where('materi_id', $materi_id)
            ->where('jenis', 'posttest')
            ->exists();
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
            'is_started' => $isStarted
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
            'created_at' => now()
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
        
        if (!$pretestCompleted) {
            return redirect()->route('guest.elearning.materi', $ebook->materi_id)
                ->with('error', 'Anda harus menyelesaikan pre-test terlebih dahulu untuk mengakses e-book ini.');
        }
        
        // Check if file exists
        if (!$ebook->path_file || !file_exists(storage_path('app/public/' . $ebook->path_file))) {
            return redirect()->route('guest.elearning.materi', $ebook->materi_id)
                ->with('error', 'File e-book tidak ditemukan.');
        }
        
        // Log activity with ebook_id for tracking
        $this->logActivity($user->id, 'Telah membaca E-Book: ' . $ebook->judul . ' [ebook_id:' . $ebook->ebook_id . ']');
        
        // Update progress to ebook_selesai if all ebooks in this materi have been read
        $progress = ProgressMateri::getOrCreate($user->id, $ebook->materi_id);
        if ($progress->status === ProgressMateri::STATUS_PRETEST_SELESAI) {
            // Get all ebook IDs for this materi
            $allEbookIds = Ebook::where('materi_id', $ebook->materi_id)->pluck('ebook_id')->toArray();
            
            // Check how many unique ebooks have been read by this user for this materi
            $readEbookIds = [];
            foreach ($allEbookIds as $ebookId) {
                $hasRead = LogAktivitas::where('user_id', $user->id)
                    ->where('aktivitas', 'LIKE', '%[ebook_id:' . $ebookId . ']%')
                    ->exists();
                if ($hasRead) {
                    $readEbookIds[] = $ebookId;
                }
            }
                
            // If all ebooks have been read, update progress
            if (count($readEbookIds) >= count($allEbookIds)) {
                $progress->updateStatus(ProgressMateri::STATUS_EBOOK_SELESAI);
            }
        }
        
        return view('guest.elearning.ebook', compact('ebook'));
    }

    /**
     * Check if all e-books in a materi have been read by user
     */
    private function areAllEbooksRead($user_id, $materi_id)
    {
        $progress = ProgressMateri::where('user_id', $user_id)
            ->where('materi_id', $materi_id)
            ->first();
            
        if (!$progress) {
            return false;
        }
        
        // Check if ebook status is completed
        return in_array($progress->status, [
            ProgressMateri::STATUS_EBOOK_SELESAI,
            ProgressMateri::STATUS_MUSEUM_SELESAI,
            ProgressMateri::STATUS_POSTTEST_SELESAI
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
            
        if (!$progress) {
            return false;
        }
        
        // Check if museum status is completed
        return in_array($progress->status, [
            ProgressMateri::STATUS_MUSEUM_SELESAI,
            ProgressMateri::STATUS_POSTTEST_SELESAI
        ]);
    }
}
