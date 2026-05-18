<?php

use Illuminate\Support\Facades\File;

$convertedFiles = [
    'elearning/era-materi.blade.php',
    'elearning/materi.blade.php',
    'elearning/pretest.blade.php',
    'elearning/posttest.blade.php',
    'elearning/ebook.blade.php',
    'elearning/tugas.blade.php',
    'maps/peninggalan.blade.php',
    'maps/view.blade.php',
    'situs/detail.blade.php',
    'video-peninggalan/index.blade.php',
    'video-peninggalan/show.blade.php',
    'ar/museum.blade.php',
];

test('converted guest views contain no hardcoded Indonesian strings outside translation calls', function () use ($convertedFiles): void {
    $guestViewsPath = resource_path('views/guest');

    $violations = [];

    foreach ($convertedFiles as $relativePath) {
        $filePath = $guestViewsPath.'/'.$relativePath;
        $content = File::get($filePath);

        $lines = explode("\n", $content);
        foreach ($lines as $lineNo => $line) {
            if (preg_match('/^\s*{{\--.*--\}}$/', $line) || preg_match('/^\s*\<!--/', $line)) {
                continue;
            }

            if (preg_match('/^\s*\/\//', $line)) {
                continue;
            }

            $lineTrimmed = trim($line);

            if (preg_match('/^\s*@?(if|elseif|else|foreach|for|while|switch|case|endswitch|endforeach|endfor|endwhile|endif|php|endphp)/i', $lineTrimmed)) {
                continue;
            }

            if (preg_match('/\-\>(?:where|orderBy|select|join|has|with|count|first|get|find|create|update|delete|exists|pluck|value|count|avg|sum|max|min)\s*\(/i', $lineTrimmed)) {
                continue;
            }

            $stripped = preg_replace('/\{\{\s*__\([\'"](?:app\.)[^\'"]+[\'"]\)\s*\}\}/', '', $line);
            $stripped = preg_replace('/\{\{\s*(?:auth|config|route|session|csrf|old)\s*\([^)]*\)\s*\}\}/', '', $stripped);
            $stripped = preg_replace('/\{\{\s*\$[a-zA-Z_][a-zA-Z0-9_]*(?:\->[a-zA-Z_][a-zA-Z0-9_]*)*(?:\[[^\]]+\])*\s*\}\}/', '', $stripped);
            $stripped = preg_replace('/\{\{\s*(?:\w+\s*\?\?\s*)?(?:[a-zA-Z_][a-zA-Z0-9_]*\s*\|\s*)*[a-zA-Z_][a-zA-Z0-9_]*(?:\s*\|\s*(?:esc|html|date|number|format|json|trim|strtolower|strtoupper|capitalize|ucfirst))*\s*\}\}/', '', $stripped);
            $stripped = preg_replace('/\{\{\s*[^}]+\}\}/', '', $stripped);
            $stripped = preg_replace('/@json\([^)]*\)/', '', $stripped);
            $stripped = preg_replace('/@vite\[[^\]]*\]/', '', $stripped);

            $stripped = trim($stripped);

            if ($stripped === '') {
                continue;
            }

            if (preg_match('/^<\/?(?:div|span|p|a|button|input|img|table|tr|td|th|thead|tbody|form|label|select|option|h[1-6]|ul|li|br|hr)[^>]*>$/i', $stripped)) {
                continue;
            }

            if (preg_match('/^\<\/?[a-zA-Z][^\>]*\/?\s*$/', $stripped)) {
                continue;
            }

            if (preg_match('/^(?:alt|src|href|class|style|id|data-[a-z]+)=/', $stripped)) {
                continue;
            }

            $indonesianPatterns = [
                '/\b(Kembali|Tutup|Benar|Salah|Terbuka|Terkunci|Selesai|Mulai)\b/u',
                '/[À-ÿ]/u',
                '/\b(Pre-test|Post-test|E-Book)\b/',
            ];

            foreach ($indonesianPatterns as $pattern) {
                if (preg_match($pattern, $stripped)) {
                    $violations[] = "{$relativePath}:{$lineNo}: {$stripped}";
                    break;
                }
            }
        }
    }

    if ($violations !== []) {
        $report = "Hardcoded Indonesian strings found:\n".implode("\n", array_map(fn ($v) => "  - {$v}", $violations));
        throw new Exception($report);
    }

    expect(true)->toBeTrue();
});

test('ar-camera.blade.php has no user-facing UI strings outside translation calls', function (): void {
    $filePath = resource_path('views/guest/ar-camera.blade.php');

    expect(File::exists($filePath))->toBeTrue();

    $content = File::get($filePath);
    $lines = explode("\n", $content);

    $violations = [];
    $inBlockComment = false;
    $inConsoleBlock = false;
    $inDebugBranch = false;

    foreach ($lines as $lineNo => $line) {
        $lineNum = $lineNo + 1;

        if (preg_match('/\/\*/', $line)) {
            $inBlockComment = true;
        }
        if (preg_match('/\*\//', $line)) {
            $inBlockComment = false;

            continue;
        }

        if ($inBlockComment) {
            continue;
        }

        if (preg_match('/^\s*\/\/\s*/', $line) || preg_match('/^\s*\<!--/', $line)) {
            continue;
        }

        if (preg_match('/console\.(log|debug|info|warn|error)\s*\(/', $line)) {
            $inConsoleBlock = true;
        }
        if ($inConsoleBlock && preg_match('/\);\s*$/', $line)) {
            $inConsoleBlock = false;
        }

        if (preg_match('/if\s*\(\s*(isDebugMode|debug|debugPanel)/i', $line)) {
            $inDebugBranch = true;
        }
        if ($inDebugBranch && preg_match('/^\s*\}/', $line)) {
            $inDebugBranch = false;
        }

        if (preg_match('/pushDebugLog\s*\(/', $line)) {
            $inDebugBranch = true;
        }

        if ($inConsoleBlock || $inDebugBranch) {
            continue;
        }

        if (preg_match('/^\s*@?(if|elseif|else|foreach|for|while|switch|case|endswitch|endforeach|endfor|endwhile|endif)\b/', $line)) {
            continue;
        }

        if (preg_match('/^\s*\<\?php/', $line) && ! preg_match('/\{\{\s*__\(/', $line)) {
            continue;
        }

        $stripped = preg_replace('/\{\{\s*__\([\'"](?:app\.)[^\'"]+[\'"]\)\s*\}\}/', '', $line);

        $stripped = trim($stripped);
        if ($stripped === '' || preg_match('/^\s*@?(if|else|endif|end|foreach|endforeach|for|endfor|while|endwhile|php)/i', $stripped)) {
            continue;
        }

        if (preg_match('/^\s*<\/?(?:div|span|p|a|button|input|img|table|tr|td|th|thead|tbody|form|label|select|option|h[1-6]|ul|li|br|hr)[^>]*>\s*$/i', $stripped)) {
            continue;
        }

        $indonesianPatterns = [
            '/\b(Memuat|Objek AR|Gagal memuat|Tidak ada deskripsi)\b/u',
            '/\b(Kembali|Tutup|Benar|Salah|Terbuka|Terkunci|Selesai|Mulai)\b/u',
            '/[À-ÿ]/u',
        ];

        foreach ($indonesianPatterns as $pattern) {
            if (preg_match($pattern, $stripped)) {
                $violations[] = "{$lineNum}: {$line}";
            }
        }
    }

    if ($violations !== []) {
        $report = "ar-camera.blade.php user-facing violations:\n".implode("\n", array_map(fn ($v) => "  - {$v}", $violations));
        throw new Exception($report);
    }

    expect(true)->toBeTrue();
});
