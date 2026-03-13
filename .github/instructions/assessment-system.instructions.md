---
description: "Use when creating or modifying pre-tests, post-tests, quizzes, assignments (tugas), or user answer tracking. Covers question validation, scoring logic, and progress tracking integration."
---

# Assessment System Guidelines

## Data Model Hierarchy

```
Materi (Learning Material)
├── Pretest (many)
├── Posttest (many)
└── Tugas (many assignments)

User
└── JawabanUser (pivot table)
    ├── Linked to: Materi
    ├── Type: 'pretest' | 'posttest'
    └── Results: benar (correct), poin (points)
```

## Creating Assessment Questions

### Pretest/Posttest Structure

**Models**: [Pretest.php](app/Models/Pretest.php), [Posttest.php](app/Models/Posttest.php)

**Required Fields**:

```php
Schema::create('pretest', function (Blueprint $table) {
    $table->id('pretest_id');
    $table->foreignId('materi_id')->constrained('materi', 'materi_id')->onDelete('cascade');
    $table->text('pertanyaan');           // Question text
    $table->string('pilihan_a');          // Option A
    $table->string('pilihan_b');          // Option B
    $table->string('pilihan_c');          // Option C
    $table->string('pilihan_d');          // Option D
    $table->enum('jawaban_benar', ['A', 'B', 'C', 'D']);  // Correct answer
    $table->timestamps();
});
```

**Validation Rules**:

```php
$request->validate([
    'pertanyaan' => 'required|string|max:1000',
    'pilihan_a' => 'required|string|max:255',
    'pilihan_b' => 'required|string|max:255',
    'pilihan_c' => 'required|string|max:255',
    'pilihan_d' => 'required|string|max:255',
    'jawaban_benar' => 'required|in:A,B,C,D',
]);
```

### Question Design Best Practices

**Quality Guidelines**:

- ✅ Questions in **Indonesian language** (consistent with codebase convention)
- ✅ Clear, unambiguous wording
- ✅ Options similar length to avoid pattern recognition
- ✅ Distractors (wrong answers) plausible but distinctly incorrect
- ✅ No "all/none of the above" unless necessary
- ✅ Related to heritage content in associated `materi`

**Example**:

```
Pertanyaan: "Candi Borobudur dibangun pada masa kerajaan?"
A: Majapahit
B: Mataram Kuno (correct)
C: Sriwijaya
D: Singhasari
```

## Answer Tracking & Scoring

### Recording User Answers

**Model**: [JawabanUser.php](app/Models/JawabanUser.php)

```php
// When user submits test
$jawaban = JawabanUser::create([
    'user_id' => auth()->id(),
    'materi_id' => $materiId,
    'jenis' => 'pretest',  // or 'posttest'
    'benar' => $correctCount,
    'poin' => $totalPoints,
]);

// Check if user should progress
if ($materi->shouldIncrementProgress($user, 'pretest')) {
    $user->incrementProgressLevel();
}
```

### Scoring Logic

**Standard Points**:

- 1 point per correct answer
- 0 points for incorrect
- Calculate percentage for display: `($benar / $totalQuestions) * 100`

**Progress Gate**:
Users typically need 60%+ to unlock next material (verify with client requirements)

## Progress Tracking Integration

### Progress Constants (User Model)

```php
// app/Models/User.php
const PRE_TEST = 1;
const EBOOK = 2;
const VIRTUAL_LIVING_MUSEUM = 3;
const POST_TEST = 4;
```

### Unlocking Materials Flow

1. **Complete Pretest** → Progress to EBOOK
2. **Finish Reading Ebook** → Progress to VIRTUAL_LIVING_MUSEUM
3. **Visit Virtual Museum** → Progress to POST_TEST
4. **Complete Posttest** → Unlock next Materi level

```php
// Check eligibility before showing content
if (!$materi->shouldIncrementProgress($user, 'ebook')) {
    return redirect()->back()->with('error', 'Selesaikan pretest terlebih dahulu');
}
```

## Assignment (Tugas) System

**Model**: [Tugas.php](app/Models/Tugas.php)

**Difference from Tests**:

- Assignments are **open-ended** (text/file submission)
- Tests are **multiple-choice** (auto-graded)
- Assignments may require **manual admin review**

**Fields**:

```php
Schema::create('tugas', function (Blueprint $table) {
    $table->id('tugas_id');
    $table->foreignId('materi_id')->constrained('materi', 'materi_id')->onDelete('cascade');
    $table->text('deskripsi');       // Assignment description
    $table->timestamp('batas_waktu')->nullable();  // Deadline
    // User submissions tracked separately
});
```

## Admin Interface Patterns

### Creating Questions (Bulk or Single)

**Controller**: [AdminController.php](app/Http/Controllers/Admin/AdminController.php)

```php
// Nested resource routes
Route::post('/admin/materi/{id}/pretest', [AdminController::class, 'storePretestSoal']);
Route::put('/admin/materi/{materi_id}/pretest/{pretest_id}', [AdminController::class, 'updatePretestSoal']);
Route::delete('/admin/materi/{materi_id}/pretest/{pretest_id}', [AdminController::class, 'destroyPretestSoal']);
```

**Cascade Deletion**: Questions auto-delete when parent `materi` is deleted (via foreign key constraint)

## Reporting & Analytics

### User Statistics

**Display in "Rapor" (Report Card)**:

```php
$user->load(['materi' => function($query) {
    $query->withPivot('jenis', 'benar', 'poin', 'created_at');
}]);

// Calculate per-material stats:
$pretestScore = $user->materi->where('pivot.jenis', 'pretest')->first()->pivot->benar;
$posttestScore = $user->materi->where('pivot.jenis', 'posttest')->first()->pivot->benar;
$improvement = $posttestScore - $pretestScore;
```

### Leaderboard Queries

```php
// Top performers (highest combined scores)
$leaderboard = User::withCount(['materi as total_poin' => function($query) {
    $query->select(DB::raw('SUM(poin)'));
}])->orderByDesc('total_poin')->limit(10)->get();
```

## Testing Assessment Features

**Scenarios to Test**:

- ✅ Submit pre-test with all correct answers → Progress unlocked
- ✅ Submit pre-test with failing score → Stuck at current level
- ✅ Complete full material sequence → Next materi unlocked
- ✅ Delete materi → Cascade deletes questions and user answers
- ✅ Retake test → Update existing `jawaban_user` record or create new?

**Edge Cases**:

- User navigates directly to posttest URL before completing pretest
- Materi has no questions yet (show friendly message)
- Timer expiration (if timed tests implemented)

## Common Pitfalls

❌ **Forgetting cascade deletes** — Questions orphaned when materi deleted  
❌ **Not validating progress** — Users skip to posttest without pretest  
❌ **Hardcoded point values** — Use consistent scoring logic  
❌ **Missing Indonesian translations** — All text must be in Indonesian  
❌ **No admin preview** — Allow admins to test questions before publishing

## Future Enhancements

Potential features to propose:

- **Question Bank**: Reusable questions across multiple materi
- **Randomization**: Shuffle question and option order
- **Timed Tests**: Countdown timer with auto-submit
- **Explanation**: Show correct answer rationale after completion
- **Adaptive Testing**: Adjust difficulty based on performance
