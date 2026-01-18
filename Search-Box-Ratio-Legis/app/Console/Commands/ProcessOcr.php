<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use App\Models\Document;
use App\Models\DocumentType;
use App\Models\DocumentStatus;
use App\Models\DocumentCategory;
use Smalot\PdfParser\Parser;

class ProcessOcr extends Command
{
    protected $signature = 'ocr:process {sessionId}';
    protected $description = 'Process OCR in background';

    public function handle()
    {
        $sessionId = $this->argument('sessionId');
        
        // Get OCR data from cache
        $data = Cache::get("ocr_data_{$sessionId}");
        
        if (!$data) {
            $this->error("Session data not found for {$sessionId}");
            return 1;
        }
        
        try {
            $tempInputPath = $data['input_path'];
            $tempOutputPath = $data['output_path'];
            $originalFilename = $data['original_filename'];
            
            // Update progress: File uploaded
            Cache::put("ocr_progress_{$sessionId}", [
                'progress' => 10,
                'status' => 'File berhasil diunggah, mempersiapkan OCR...',
            ], now()->addHours(2));
            
            // Path to Python script
            $pythonExe = 'python';
            
            // Normalize paths for Windows
            $tempInputPathNormalized = str_replace('\\', '/', $tempInputPath);
            $tempOutputPathNormalized = str_replace('\\', '/', $tempOutputPath);
            
            // Update progress: Starting OCR
            Cache::put("ocr_progress_{$sessionId}", [
                'progress' => 20,
                'status' => 'Menjalankan OCR pada dokumen...',
            ], now()->addHours(2));
            
            // Run OCR using Python script
            $command = sprintf(
                "%s -c \"from ocr_pdf import pdf_file_to_searchable_pdf_file; pdf_file_to_searchable_pdf_file(r'%s', r'%s', dpi=150, lang='ind+eng', quality=85)\"",
                $pythonExe,
                $tempInputPathNormalized,
                $tempOutputPathNormalized
            );
            
            // Set working directory to OCR folder
            $ocrDir = base_path('../OCR');
            $descriptorspec = [
                0 => ['pipe', 'r'],
                1 => ['pipe', 'w'],
                2 => ['pipe', 'w']
            ];
            
            // Update progress: OCR in progress
            Cache::put("ocr_progress_{$sessionId}", [
                'progress' => 30,
                'status' => 'Sedang memproses halaman...',
            ], now()->addHours(2));
            
            $process = proc_open($command, $descriptorspec, $pipes, $ocrDir);
            
            if (is_resource($process)) {
                fclose($pipes[0]);
                
                // Update progress while processing
                Cache::put("ocr_progress_{$sessionId}", [
                    'progress' => 40,
                    'status' => 'Memproses OCR (ini mungkin memakan waktu)...',
                ], now()->addHours(2));
                
                // Read output streams with timeout simulation
                $startTime = time();
                $stdout = '';
                $stderr = '';
                
                // Non-blocking read to allow progress updates
                stream_set_blocking($pipes[1], false);
                stream_set_blocking($pipes[2], false);
                
                while (!feof($pipes[1]) || !feof($pipes[2])) {
                    $stdout .= stream_get_contents($pipes[1]);
                    $stderr .= stream_get_contents($pipes[2]);
                    
                    // Update progress based on elapsed time
                    $elapsed = time() - $startTime;
                    $progress = min(55, 40 + ($elapsed * 2)); // Gradually increase from 40 to 55
                    
                    Cache::put("ocr_progress_{$sessionId}", [
                        'progress' => $progress,
                        'status' => 'Memproses OCR... (' . $elapsed . 's)',
                    ], now()->addHours(2));
                    
                    usleep(500000); // Sleep 0.5 seconds
                }
                
                fclose($pipes[1]);
                fclose($pipes[2]);
                
                Cache::put("ocr_progress_{$sessionId}", [
                    'progress' => 60,
                    'status' => 'Menyelesaikan OCR...',
                ], now()->addHours(2));
                
                $returnCode = proc_close($process);
                
                if ($returnCode !== 0 || !file_exists($tempOutputPath)) {
                    // Cleanup
                    if (file_exists($tempInputPath)) unlink($tempInputPath);
                    if (file_exists($tempOutputPath)) unlink($tempOutputPath);
                    
                    Cache::put("ocr_progress_{$sessionId}", [
                        'progress' => 0,
                        'status' => 'OCR gagal: ' . $stderr,
                        'error' => true,
                    ], now()->addHours(2));
                    
                    $this->error("OCR failed: {$stderr}");
                    return 1;
                }
                
                // Update progress: Extracting content
                Cache::put("ocr_progress_{$sessionId}", [
                    'progress' => 70,
                    'status' => 'Mengekstrak teks dari PDF...',
                ], now()->addHours(2));
                
                // Create UploadedFile from OCR output
                $ocrFile = new \Illuminate\Http\UploadedFile(
                    $tempOutputPath,
                    $originalFilename,
                    'application/pdf',
                    null,
                    true
                );
                
                // Extract content from OCR'd PDF
                $extractedContent = $this->extractPdfContent($ocrFile);
                
                // Update progress: Saving file
                Cache::put("ocr_progress_{$sessionId}", [
                    'progress' => 80,
                    'status' => 'Menyimpan dokumen...',
                ], now()->addHours(2));
                
                // Store the OCR'd file
                $filePath = $ocrFile->store('documents', 'public');
                
                // Map type, status, and category to their IDs
                $docType = DocumentType::where('name', $data['type'])->first();
                $docStatus = DocumentStatus::where('name', $data['status'])->first();
                $docCategory = DocumentCategory::where('name', $data['category'])->first();
                
                // Update progress: Creating database record
                Cache::put("ocr_progress_{$sessionId}", [
                    'progress' => 90,
                    'status' => 'Menyimpan data ke database...',
                ], now()->addHours(2));
                
                // Create document record
                $document = Document::create([
                    'title' => $data['title'],
                    'description' => $data['description'],
                    'file_path' => $filePath,
                    'file_name' => $originalFilename,
                    'extracted_content' => $extractedContent,
                    'document_type_id' => $docType?->id,
                    'document_status_id' => $docStatus?->id,
                    'document_category_id' => $docCategory?->id,
                ]);
                
                // Cleanup temp files
                if (file_exists($tempInputPath)) unlink($tempInputPath);
                if (file_exists($tempOutputPath)) unlink($tempOutputPath);
                
                // Update progress: Complete
                Cache::put("ocr_progress_{$sessionId}", [
                    'progress' => 100,
                    'status' => 'Proses OCR selesai!',
                    'complete' => true,
                    'document_id' => $document->id,
                ], now()->addHours(2));
                
                // Clear data cache
                Cache::forget("ocr_data_{$sessionId}");
                
                $this->info("OCR completed successfully for session {$sessionId}");
                return 0;
            } else {
                // Cleanup
                if (file_exists($tempInputPath)) unlink($tempInputPath);
                
                Cache::put("ocr_progress_{$sessionId}", [
                    'progress' => 0,
                    'status' => 'Gagal menjalankan proses OCR',
                    'error' => true,
                ], now()->addHours(2));
                
                $this->error("Failed to start OCR process");
                return 1;
            }
        } catch (\Exception $e) {
            Cache::put("ocr_progress_{$sessionId}", [
                'progress' => 0,
                'status' => 'Error: ' . $e->getMessage(),
                'error' => true,
            ], now()->addHours(2));
            
            $this->error("Exception: " . $e->getMessage());
            return 1;
        }
    }
    
    private function extractPdfContent($file)
    {
        try {
            $parser = new Parser();
            $pdf = $parser->parseFile($file->getRealPath());
            $text = $pdf->getText();
            
            // Fix UTF-8 encoding issues
            $text = mb_convert_encoding($text, 'UTF-8', 'UTF-8');
            
            // Remove invalid UTF-8 characters
            $text = preg_replace('/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F]/u', '', $text);
            
            // Normalize whitespace
            $text = preg_replace('/\s+/', ' ', $text);
            
            return trim($text);
        } catch (\Exception $e) {
            return '';
        }
    }
}
