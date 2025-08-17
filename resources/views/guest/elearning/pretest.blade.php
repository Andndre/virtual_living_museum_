<x-elearning-layout>
    {{-- Header Section --}}
    <div class="px-6 py-6 bg-primary text-white">
        <div class="flex justify-between items-center">
            <div class="flex items-center space-x-4">
                <a href="{{ route('guest.elearning.materi', $materi->materi_id) }}" class="p-2 hover:bg-white/10 rounded-full transition-colors">
                    <i class="fas fa-arrow-left text-xl"></i>
                </a>
                <div class="flex-1">
                    <h1 class="text-lg font-bold">Pre-test</h1>
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
                    <h3 class="text-xl font-bold text-gray-900 mb-2">Pre-test Selesai!</h3>
                    <p class="text-gray-600 mb-6">Anda telah menyelesaikan pre-test untuk materi ini.</p>
                    
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

                    <a href="{{ route('guest.elearning.materi', $materi->materi_id) }}" 
                       class="inline-flex items-center px-6 py-3 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 transition-colors">
                        <i class="fas fa-arrow-right mr-2"></i>
                        Lanjut ke Materi
                    </a>
                </div>
            </div>
        @else
            {{-- Quiz Instructions --}}
            <div class="bg-white rounded-2xl shadow-sm p-6 mb-6">
                {{-- Show validation errors --}}
                @if ($errors->any())
                    <div class="bg-red-50 border border-red-200 rounded-lg p-4 mb-4">
                        <div class="flex items-center space-x-2 mb-2">
                            <i class="fas fa-exclamation-triangle text-red-600"></i>
                            <h4 class="font-medium text-red-800">Terjadi kesalahan:</h4>
                        </div>
                        <ul class="list-disc list-inside text-red-700 text-sm space-y-1">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                
                <h3 class="text-xl font-bold text-gray-900 mb-4">Instruksi Pre-test</h3>
                <div class="space-y-3 text-gray-700">
                    <div class="flex items-start space-x-3">
                        <div class="flex-shrink-0 w-6 h-6 bg-blue-100 rounded-full flex items-center justify-center mt-0.5">
                            <span class="text-xs font-medium text-blue-600">1</span>
                        </div>
                        <p>Pre-test terdiri dari {{ $totalQuestions }} soal pilihan ganda</p>
                    </div>
                    <div class="flex items-start space-x-3">
                        <div class="flex-shrink-0 w-6 h-6 bg-blue-100 rounded-full flex items-center justify-center mt-0.5">
                            <span class="text-xs font-medium text-blue-600">2</span>
                        </div>
                        <p>Pilih satu jawaban yang paling tepat untuk setiap soal</p>
                    </div>
                    <div class="flex items-start space-x-3">
                        <div class="flex-shrink-0 w-6 h-6 bg-blue-100 rounded-full flex items-center justify-center mt-0.5">
                            <span class="text-xs font-medium text-blue-600">3</span>
                        </div>
                        <p>Setelah selesai, Anda dapat melanjutkan ke materi pembelajaran</p>
                    </div>
                </div>
                
                <div class="mt-6 pt-6 border-t border-gray-200">
                    <button onclick="startQuiz()" class="w-full bg-blue-600 text-white font-medium py-3 rounded-lg hover:bg-blue-700 transition-colors">
                        <i class="fas fa-play mr-2"></i>
                        Mulai Pre-test
                    </button>
                </div>
            </div>

            {{-- Quiz Container (Hidden Initially) --}}
            <div id="quiz-container" class="hidden">
                <form id="quiz-form" method="POST" action="{{ route('guest.elearning.pretest.submit', $materi->materi_id) }}">
                    @csrf
                    
                    {{-- Progress Bar --}}
                    <div class="bg-white rounded-2xl shadow-sm p-6 mb-6">
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-sm font-medium text-gray-600">Progres</span>
                            <span class="text-sm font-medium text-blue-600" id="progress-text">1 dari {{ $totalQuestions }}</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2">
                            <div class="bg-blue-600 h-2 rounded-full transition-all duration-300" id="progress-bar" style="width: {{ 100/$totalQuestions }}%"></div>
                        </div>
                    </div>

                    {{-- Questions --}}
                    @foreach($questions as $index => $question)
                        <div class="quiz-question bg-white rounded-2xl shadow-sm p-6 mb-6 {{ $index === 0 ? '' : 'hidden' }}" data-question="{{ $index }}">
                            <div class="mb-6">
                                <div class="flex items-center space-x-3 mb-4">
                                    <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                                        <span class="text-sm font-medium text-blue-600">{{ $index + 1 }}</span>
                                    </div>
                                    <h3 class="text-lg font-semibold text-gray-900">Soal {{ $index + 1 }}</h3>
                                </div>
                                <p class="text-gray-800 leading-relaxed">{{ $question->pertanyaan }}</p>
                            </div>
                            
                            <div class="space-y-3">
                                @foreach(['A' => $question->pilihan_a, 'B' => $question->pilihan_b, 'C' => $question->pilihan_c, 'D' => $question->pilihan_d] as $key => $option)
                                    <label class="flex items-start space-x-3 p-4 border border-gray-200 rounded-lg hover:bg-gray-50 cursor-pointer transition-all duration-300 answer-option" data-answer="{{ $key }}" data-correct="{{ $question->jawaban_benar }}">
                                        <input type="radio" name="answers[{{ $question->pretest_id }}]" value="{{ $key }}" class="mt-1 text-blue-600 focus:ring-blue-500" required>
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
                                <button type="button" id="next-btn" onclick="nextQuestion()" class="px-6 py-3 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 transition-colors">
                                    Selanjutnya
                                    <i class="fas fa-arrow-right ml-2"></i>
                                </button>
                                
                                <button type="button" id="submit-btn" onclick="submitQuiz()" class="px-6 py-3 bg-green-600 text-white font-medium rounded-lg hover:bg-green-700 transition-colors hidden">
                                    <i class="fas fa-check mr-2"></i>
                                    Selesai
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
            
            // Hide notification
            hideNotification();
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
            hideNotification();
            if (currentQuestion < totalQuestions - 1) {
                setTimeout(() => {
                    nextQuestion();
                }, 300);
            } else {
                // Last question - show submit button
                document.getElementById('submit-btn').classList.remove('hidden');
                document.getElementById('next-btn').classList.add('hidden');
            }
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
        
        function showNotification(isCorrect, correctAnswer) {
            const notification = document.getElementById('answer-notification');
            const content = document.getElementById('notification-content');
            const icon = document.getElementById('notification-icon');
            const title = document.getElementById('notification-title');
            const subtitle = document.getElementById('notification-subtitle');
            const continueBtn = document.getElementById('continue-btn');
            
            if (isCorrect) {
                content.className = 'mx-6 mb-6 p-4 rounded-xl shadow-lg bg-green-500';
                icon.className = 'w-8 h-8 rounded-full flex items-center justify-center bg-green-600';
                icon.innerHTML = '<i class="fas fa-check text-white"></i>';
                title.textContent = 'Benar!';
                subtitle.textContent = 'Jawaban Anda tepat';
            } else {
                content.className = 'mx-6 mb-6 p-4 rounded-xl shadow-lg bg-red-500';
                icon.className = 'w-8 h-8 rounded-full flex items-center justify-center bg-red-600';
                icon.innerHTML = '<i class="fas fa-times text-white"></i>';
                title.textContent = 'Salah!';
                subtitle.textContent = `Jawaban yang benar adalah opsi ${correctAnswer}`;
            }
            
            if (currentQuestion === totalQuestions - 1) {
                continueBtn.textContent = 'Lihat Hasil';
            } else {
                continueBtn.textContent = 'Selanjutnya';
            }
            
            // Show notification
            notification.classList.remove('translate-y-full');
            notification.classList.add('translate-y-0');
        }
        
        function hideNotification() {
            const notification = document.getElementById('answer-notification');
            notification.classList.remove('translate-y-0');
            notification.classList.add('translate-y-full');
        }
        
        // Handle answer selection with immediate feedback
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('input[type="radio"]').forEach(radio => {
                radio.addEventListener('change', function() {
                    if (isAnswered) return; // Prevent multiple selections
                    isAnswered = true;
                    
                    const selectedOption = this.closest('.answer-option');
                    const selectedAnswer = selectedOption.dataset.answer;
                    const correctAnswer = selectedOption.dataset.correct;
                    const isCorrect = selectedAnswer === correctAnswer;
                    
                    // Get all options for this question
                    const allOptions = selectedOption.closest('.quiz-question').querySelectorAll('.answer-option');
                    
                    // Style all options based on correct/incorrect
                    allOptions.forEach(option => {
                        const optionAnswer = option.dataset.answer;
                        const icon = option.querySelector('.answer-icon');
                        const letter = option.querySelector('.option-letter');
                        const radio = option.querySelector('input[type="radio"]');
                        
                        // Don't disable radio buttons as it prevents form submission
                        // radio.disabled = true;
                        
                        if (optionAnswer === correctAnswer) {
                            // Correct answer styling
                            option.classList.remove('border-gray-200');
                            option.classList.add('bg-green-100', 'border-green-500');
                            letter.classList.remove('bg-gray-100', 'text-gray-600');
                            letter.classList.add('bg-green-500', 'text-white');
                            icon.innerHTML = '<i class="fas fa-check text-green-600"></i>';
                            icon.style.opacity = '1';
                        } else if (optionAnswer === selectedAnswer && !isCorrect) {
                            // Selected wrong answer styling
                            option.classList.remove('border-gray-200');
                            option.classList.add('bg-red-100', 'border-red-500');
                            letter.classList.remove('bg-gray-100', 'text-gray-600');
                            letter.classList.add('bg-red-500', 'text-white');
                            icon.innerHTML = '<i class="fas fa-times text-red-600"></i>';
                            icon.style.opacity = '1';
                        }
                    });
                    
                    // Show notification after a short delay
                    setTimeout(() => {
                        showNotification(isCorrect, correctAnswer);
                    }, 500);
                });
            });
        });
    </script>
    @endif
</x-elearning-layout>
