<x-elearning-layout>
    {{-- Header Section --}}
    <div class="px-6 py-6 bg-primary text-white">
        <div class="flex justify-between items-center">
            <div class="flex items-center space-x-4">
                <a href="{{ route('guest.elearning.materi', $materi->materi_id) }}" class="p-2 hover:bg-white/10 rounded-full transition-colors">
                    <i class="fas fa-arrow-left text-xl"></i>
                </a>
                <div class="flex-1">
                    <h1 class="text-lg font-bold">Post-test</h1>
                    <p class="text-sm opacity-90">{{ $materi->judul }}</p>
                </div>
            </div>
        </div>
    </div>

    {{-- Content Section --}}
    <div class="px-6 py-6 bg-gray-50 min-h-screen">
        @if($isCompleted)
            {{-- Completed State --}}
            <div class="bg-white rounded-2xl shadow-sm p-6 mb-6">
                <div class="text-center">
                    <div class="w-16 h-16 mx-auto mb-4 bg-green-100 rounded-full flex items-center justify-center">
                        <i class="fas fa-check text-green-600 text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-2">Post-test Selesai!</h3>
                    <p class="text-gray-600 mb-6">Selamat! Anda telah menyelesaikan seluruh materi pembelajaran ini.</p>
                    <div class="grid grid-cols-2 gap-4 mb-6">
                        <div class="bg-blue-50 rounded-lg p-4">
                            <div class="text-2xl font-bold text-blue-600">{{ $score }}%</div>
                            <div class="text-sm text-blue-600">Nilai</div>
                        </div>
                        <div class="bg-green-50 rounded-lg p-4">
                            <div class="text-2xl font-bold text-green-600">{{ $correctAnswers }}/{{ $totalQuestions }}</div>
                            <div class="text-sm text-green-600">Benar</div>
                        </div>
                    </div>
                </div>
                {{-- Evaluasi Soal --}}
                <div class="mt-8">
                    <h4 class="text-lg font-semibold mb-4 text-gray-800">Evaluasi Jawaban Anda</h4>
                    <div class="space-y-6">
                        @php
                            $userAnswers = \App\Models\JawabanUser::where('user_id', Auth::id())
                                ->where('materi_id', $materi->materi_id)
                                ->where('jenis', 'posttest')
                                ->get()
                                ->keyBy('soal_id');
                        @endphp
                        @foreach($questions as $idx => $question)
                            @php
                                $jawabanUser = $userAnswers[$question->posttest_id] ?? null;
                                $userChoice = $jawabanUser->jawaban_user ?? null;
                                $isCorrect = $jawabanUser ? $jawabanUser->benar : false;
                            @endphp
                            <div class="p-4 rounded-xl border @if($isCorrect) border-green-300 bg-green-50 @else border-red-300 bg-red-50 @endif">
                                <div class="flex items-center mb-2">
                                    <span class="font-bold mr-2">Soal {{ $idx+1 }}:</span>
                                    <span class="flex-1 text-gray-800">{{ $question->pertanyaan }}</span>
                                    @if($isCorrect)
                                        <span class="ml-2 px-2 py-1 rounded bg-green-500 text-white text-xs font-semibold">Benar</span>
                                    @else
                                        <span class="ml-2 px-2 py-1 rounded bg-red-500 text-white text-xs font-semibold">Salah</span>
                                    @endif
                                </div>
                                <div class="space-y-2 mt-2">
                                    @foreach(['A' => $question->pilihan_a, 'B' => $question->pilihan_b, 'C' => $question->pilihan_c, 'D' => $question->pilihan_d] as $key => $option)
                                        @php
                                            $isUser = $userChoice === $key;
                                            $isBenar = $question->jawaban_benar === $key;
                                        @endphp
                                        <div class="flex items-center p-3 rounded-lg border transition-all duration-200
                                            @if($isBenar) border-green-500 bg-green-50 @elseif($isUser) border-red-500 bg-red-50 @else border-gray-200 @endif">
                                            <span class="option-letter w-8 h-8 flex items-center justify-center rounded-full font-bold mr-4
                                                @if($isBenar) bg-green-500 text-white @elseif($isUser) bg-red-500 text-white @else bg-gray-100 text-gray-600 @endif">
                                                {{ $key }}
                                            </span>
                                            <span class="flex-1">{{ $option }}</span>
                                            {{-- Icon logic --}}
                                            @if($isCorrect)
                                                @if($isUser && $isBenar)
                                                    <span class="ml-2"><i class="fas fa-check text-green-600 text-lg"></i></span>
                                                @endif
                                            @else
                                                @if($isUser && !$isBenar)
                                                    <span class="ml-2"><i class="fas fa-times text-red-600 text-lg"></i></span>
                                                @endif
                                                @if($isBenar)
                                                    <span class="ml-2"><i class="fas fa-check text-green-600 text-lg"></i></span>
                                                @endif
                                            @endif
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
                <div class="text-center mt-8">
                    <a href="{{ route('guest.elearning') }}" 
                       class="inline-flex items-center justify-center px-6 py-3 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 transition-colors">
                        <i class="fas fa-list mr-2"></i>
                        Kembali ke Daftar Materi
                    </a>
                    <a href="{{ route('guest.elearning.materi', $materi->materi_id) }}" 
                       class="inline-flex items-center justify-center px-6 py-3 bg-gray-100 text-gray-700 font-medium rounded-lg hover:bg-gray-200 transition-colors ml-2">
                        <i class="fas fa-eye mr-2"></i>
                        Lihat Materi
                    </a>
                </div>
            </div>
        @else
            {{-- Quiz Instructions --}}
            <div class="bg-white rounded-2xl shadow-sm p-6 mb-6">
                <h3 class="text-xl font-bold text-gray-900 mb-4">Instruksi Post-test</h3>
                <div class="space-y-3 text-gray-700">
                    <div class="flex items-start space-x-3">
                        <div class="flex-shrink-0 w-6 h-6 bg-green-100 rounded-full flex items-center justify-center mt-0.5">
                            <span class="text-xs font-medium text-green-600">1</span>
                        </div>
                        <p>Post-test terdiri dari {{ $totalQuestions }} soal pilihan ganda</p>
                    </div>
                    <div class="flex items-start space-x-3">
                        <div class="flex-shrink-0 w-6 h-6 bg-green-100 rounded-full flex items-center justify-center mt-0.5">
                            <span class="text-xs font-medium text-green-600">2</span>
                        </div>
                        <p>Soal ini menguji pemahaman Anda setelah mempelajari materi</p>
                    </div>
                    <div class="flex items-start space-x-3">
                        <div class="flex-shrink-0 w-6 h-6 bg-green-100 rounded-full flex items-center justify-center mt-0.5">
                            <span class="text-xs font-medium text-green-600">3</span>
                        </div>
                        <p>Setelah selesai, materi ini akan dianggap completed</p>
                    </div>
                </div>
                <div class="mt-6 pt-6 border-t border-gray-200">
                    <button onclick="startQuiz()" class="w-full bg-green-600 text-white font-medium py-3 rounded-lg hover:bg-green-700 transition-colors">
                        <i class="fas fa-play mr-2"></i>
                        Mulai Post-test
                    </button>
                </div>
            </div>
            {{-- Quiz Container (Hidden Initially) --}}
            <div id="quiz-container" class="hidden">
                <form id="quiz-form" method="POST" action="{{ route('guest.elearning.posttest.submit', $materi->materi_id) }}">
                    @csrf
                    {{-- Progress Bar --}}
                    <div class="bg-white rounded-2xl shadow-sm p-6 mb-6">
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-sm font-medium text-gray-600">Progres</span>
                            <span class="text-sm font-medium text-green-600" id="progress-text">1 dari {{ $totalQuestions }}</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2">
                            <div class="bg-green-600 h-2 rounded-full transition-all duration-300" id="progress-bar" style="width: {{ 100/$totalQuestions }}%"></div>
                        </div>
                    </div>
                    {{-- Questions --}}
                    @foreach($questions as $index => $question)
                        <div class="quiz-question bg-white rounded-2xl shadow-sm p-6 mb-6 {{ $index === 0 ? '' : 'hidden' }}" data-question="{{ $index }}">
                            <div class="mb-6">
                                <div class="flex items-center space-x-3 mb-4"></div>
                                <p class="text-gray-800 leading-relaxed">{{ $question->pertanyaan }}</p>
                            </div>
                            <div class="space-y-3">
                                @foreach(['A' => $question->pilihan_a, 'B' => $question->pilihan_b, 'C' => $question->pilihan_c, 'D' => $question->pilihan_d] as $key => $option)
                                    <label class="flex items-start space-x-3 p-4 border border-gray-200 rounded-lg hover:bg-gray-50 cursor-pointer transition-all duration-300 answer-option" data-answer="{{ $key }}" data-correct="{{ $question->jawaban_benar }}">
                                        <input type="radio" name="answers[{{ $question->posttest_id }}]" value="{{ $key }}" class="mt-1 text-green-600 focus:ring-green-500" required>
                                        <div class="flex-1">
                                            <div class="flex items-center space-x-2">
                                                <span class="w-6 h-6 bg-gray-100 rounded-full flex items-center justify-center text-sm font-medium text-gray-600 option-letter">{{ $key }}</span>
                                                <span class="text-gray-800">{{ $option }}</span>
                                            </div>
                                        </div>
                                        <div class="answer-icon opacity-0 transition-opacity duration-300">
                                            <i class="fas fa-check text-green-600"></i>
                                        </div>
                                    </label>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                    {{-- Navigation --}}
                    <div class="bg-white rounded-2xl shadow-sm p-6">
                        <div class="flex justify-between">
                            <button type="button" id="prev-btn" onclick="previousQuestion()" class="px-6 py-3 bg-gray-100 text-gray-600 font-medium rounded-lg hover:bg-gray-200 transition-colors hidden">
                                <i class="fas fa-arrow-left mr-2"></i>
                                Sebelumnya
                            </button>
                            <div class="flex space-x-3">
                                @if($totalQuestions > 1)
                                    <button type="button" id="next-btn" onclick="nextQuestion()" class="px-6 py-3 bg-green-600 text-white font-medium rounded-lg hover:bg-green-700 transition-colors">
                                        Selanjutnya <i class="fas fa-arrow-right ml-2"></i>
                                    </button>
                                @endif
                                <button type="button" id="submit-btn" onclick="submitQuiz()" class="px-6 py-3 bg-green-600 text-white font-medium rounded-lg hover:bg-green-700 transition-colors @if($totalQuestions > 1) hidden @endif">
                                    Selesai <i class="fas fa-check ml-2"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        @endif
    </div>

    {{-- Bottom Notification --}}
    <div id="answer-notification" class="fixed bottom-0 left-0 right-0 transform translate-y-full transition-transform duration-300 z-50">
        <div class="w-full lg:max-w-xl mx-auto">
            <div id="notification-content" class="mx-6 mb-6 p-4 rounded-xl shadow-lg">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-3">
                        <div id="notification-icon" class="w-8 h-8 rounded-full flex items-center justify-center">
                            <i class="fas fa-check text-white"></i>
                        </div>
                        <div>
                            <div id="notification-title" class="font-semibold text-white"></div>
                            <div id="notification-subtitle" class="text-sm text-white/80"></div>
                        </div>
                    </div>
                    <button onclick="continueToNext()" id="continue-btn" class="px-4 py-2 bg-white/20 text-white rounded-lg hover:bg-white/30 transition-colors text-sm font-medium">
                        Selanjutnya
                    </button>
                </div>
            </div>
        </div>
    </div>

    @if(!$isCompleted)
    <script>
        let currentQuestion = 0;
        let isAnswered = false;
        const totalQuestions = {{ $totalQuestions }};
        const questions = document.querySelectorAll('.quiz-question');

        function startQuiz() {
            document.querySelector('.bg-white.rounded-2xl.shadow-sm.p-6.mb-6').style.display = 'none';
            document.getElementById('quiz-container').classList.remove('hidden');
        }

        function showQuestion(index) {
            isAnswered = false;
            questions.forEach((q, i) => {
                q.classList.toggle('hidden', i !== index);
            });
            // Reset question state
            const currentQuestionEl = questions[index];
            const options = currentQuestionEl.querySelectorAll('.answer-option');
            options.forEach(option => {
                option.classList.remove('bg-green-100', 'bg-red-100', 'border-green-500', 'border-red-500');
                option.classList.add('border-gray-200');
                const icon = option.querySelector('.answer-icon');
                const letter = option.querySelector('.option-letter');
                icon.style.opacity = '0';
                letter.classList.remove('bg-green-500', 'bg-red-500', 'text-white');
                letter.classList.add('bg-gray-100', 'text-gray-600');
            });
            // Update progress
            const progress = ((index + 1) / totalQuestions) * 100;
            document.getElementById('progress-bar').style.width = progress + '%';
            document.getElementById('progress-text').textContent = `${index + 1} dari ${totalQuestions}`;
            // Update navigation buttons
            const prevBtn = document.getElementById('prev-btn');
            const nextBtn = document.getElementById('next-btn');
            const submitBtn = document.getElementById('submit-btn');
            prevBtn.classList.toggle('hidden', index === 0);
            nextBtn.classList.toggle('hidden', index === totalQuestions - 1);
            submitBtn.classList.toggle('hidden', index !== totalQuestions - 1);
            // Disable next/submit until answered
            if (totalQuestions === 1) {
                nextBtn.disabled = true;
                submitBtn.disabled = true;
            } else {
                nextBtn.disabled = true;
                submitBtn.disabled = true;
            }
        }

        function nextQuestion() {
            if (currentQuestion < totalQuestions - 1) {
                currentQuestion++;
                showQuestion(currentQuestion);
            }
        }

        function previousQuestion() {
            if (currentQuestion > 0) {
                currentQuestion--;
                showQuestion(currentQuestion);
            }
        }

        function continueToNext() {
            // Not used in this version
        }

        function submitQuiz() {
            // Check if all questions are answered
            const answeredQuestions = document.querySelectorAll('input[type="radio"]:checked').length;
            if (answeredQuestions < totalQuestions) {
                alert(`Mohon jawab semua pertanyaan. Anda baru menjawab ${answeredQuestions} dari ${totalQuestions} soal.`);
                return;
            }
            // Submit the form
            document.getElementById('quiz-form').submit();
        }

        // Hide notification (not used)
        function hideNotification() {
            const notification = document.getElementById('answer-notification');
            notification.classList.remove('translate-y-0');
            notification.classList.add('translate-y-full');
        }

        // Handle answer selection: enable next/submit, no feedback
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('input[type="radio"]').forEach(radio => {
                radio.addEventListener('change', function() {
                    if (isAnswered) return;
                    isAnswered = true;

                    const currentQuestionEl = questions[currentQuestion];
                    const nextBtn = document.getElementById('next-btn');
                    const submitBtn = document.getElementById('submit-btn');

                    if (totalQuestions === 1) {
                        // Only one question: enable submitBtn
                        submitBtn.disabled = false;
                    } else {
                        // Enable next or submit button
                        if (currentQuestion === totalQuestions - 1) {
                            submitBtn.disabled = false;
                        } else {
                            nextBtn.disabled = false;
                        }
                    }
                });
            });
        });
    </script>
    @endif
</x-elearning-layout>
