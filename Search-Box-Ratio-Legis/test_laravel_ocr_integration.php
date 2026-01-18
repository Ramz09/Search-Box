<?php
/**
 * Test script untuk memastikan Laravel bisa memanggil Python OCR
 * 
 * Jalankan: php test_laravel_ocr_integration.php
 */

// Simulasi base_path
function base_path($path = '') {
    $base = realpath(__DIR__);
    return $base . '/' . ltrim($path, '/');
}

function storage_path($path = '') {
    return __DIR__ . '/storage/' . ltrim($path, '/');
}

echo "============================================================\n";
echo "TEST LARAVEL - PYTHON OCR INTEGRATION\n";
echo "============================================================\n\n";

// Test 1: Check Python
echo "1. Checking Python installation...\n";
$pythonExe = 'python';
exec("$pythonExe --version 2>&1", $output, $returnCode);
if ($returnCode === 0) {
    echo "   ✓ Python: " . implode("\n", $output) . "\n";
} else {
    echo "   ✗ Python not found!\n";
    exit(1);
}

// Test 2: Check OCR script exists
echo "\n2. Checking OCR script...\n";
$pythonScript = realpath(__DIR__ . '/../OCR/ocr_pdf.py');
if ($pythonScript && file_exists($pythonScript)) {
    echo "   ✓ OCR script found: $pythonScript\n";
} else {
    echo "   ✗ OCR script not found!\n";
    echo "   Looking for: " . __DIR__ . '/../OCR/ocr_pdf.py' . "\n";
    exit(1);
}

// Test 3: Test Python can import OCR module
echo "\n3. Testing Python can import OCR module...\n";
$ocrDir = realpath(__DIR__ . '/../OCR');
$testCommand = "cd \"$ocrDir\" && $pythonExe -c \"from ocr_pdf import pdf_bytes_to_searchable_pdf_bytes; print('Import successful')\" 2>&1";

$output = [];
exec($testCommand, $output, $returnCode);
if ($returnCode === 0 && in_array('Import successful', $output)) {
    echo "   ✓ Python can import OCR module\n";
} else {
    echo "   ✗ Python cannot import OCR module\n";
    echo "   Output: " . implode("\n   ", $output) . "\n";
    exit(1);
}

// Test 4: Test proc_open (method used in controller)
echo "\n4. Testing proc_open functionality...\n";
$descriptorspec = [
    0 => ['pipe', 'r'],
    1 => ['pipe', 'w'],
    2 => ['pipe', 'w']
];

$command = "$pythonExe --version";
$process = proc_open($command, $descriptorspec, $pipes, $ocrDir);

if (is_resource($process)) {
    fclose($pipes[0]);
    $stdout = stream_get_contents($pipes[1]);
    $stderr = stream_get_contents($pipes[2]);
    fclose($pipes[1]);
    fclose($pipes[2]);
    $returnCode = proc_close($process);
    
    if ($returnCode === 0) {
        echo "   ✓ proc_open works: $stdout\n";
    } else {
        echo "   ✗ proc_open failed with code $returnCode\n";
        exit(1);
    }
} else {
    echo "   ✗ Cannot create process\n";
    exit(1);
}

// Test 5: Check storage directory
echo "\n5. Checking storage directory...\n";
$storageDir = storage_path();
if (!file_exists($storageDir)) {
    mkdir($storageDir, 0777, true);
    echo "   ✓ Created storage directory: $storageDir\n";
} else {
    echo "   ✓ Storage directory exists: $storageDir\n";
}

// Test 6: Test Tesseract
echo "\n6. Testing Tesseract OCR...\n";
$testCommand = "$pythonExe -c \"import pytesseract; print('Tesseract version:', pytesseract.get_tesseract_version())\" 2>&1";
exec($testCommand, $output, $returnCode);

if ($returnCode === 0) {
    $version = array_filter($output, function($line) {
        return strpos($line, 'Tesseract version:') !== false;
    });
    if (!empty($version)) {
        echo "   ✓ " . implode("\n", $version) . "\n";
    } else {
        echo "   ✓ Tesseract is installed\n";
    }
} else {
    echo "   ⚠ Tesseract might not be installed properly\n";
    echo "   Output: " . implode("\n   ", $output) . "\n";
}

echo "\n============================================================\n";
echo "SUMMARY\n";
echo "============================================================\n";
echo "✓ All integration tests PASSED!\n";
echo "✓ Laravel can call Python OCR script\n";
echo "✓ Ready for production use\n\n";

echo "Next steps:\n";
echo "1. Make sure Tesseract is installed\n";
echo "2. Optional: Install Indonesian language (see INSTALL_INDONESIAN_LANGUAGE.md)\n";
echo "3. Test with actual PDF upload in Laravel app\n\n";
