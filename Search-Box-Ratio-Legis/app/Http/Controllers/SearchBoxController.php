<?php

namespace App\Http\Controllers;

use App\Models\Document;
use App\Models\DocumentType;
use App\Models\DocumentStatus;
use App\Models\DocumentCategory;
use App\Models\SearchLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Cache;
use Smalot\PdfParser\Parser;

class SearchBoxController extends Controller
{
    public function data(Request $request)
    {
        $keyword = trim((string) $request->query('keyword', ''));
        $type = trim((string) $request->query('type', ''));
        $status = trim((string) $request->query('status', ''));
        $year = trim((string) $request->query('year', ''));
        $chip = trim((string) $request->query('chip', 'perdagangan'));
        $sort = trim((string) $request->query('sort', 'date-desc'));

        $query = Document::query()->with(['documentType', 'documentStatus', 'documentCategory']);

        if ($chip !== '' && $chip !== 'all') {
            $query->whereHas('documentCategory', function ($q) use ($chip) {
                $q->where('name', $chip);
            });
        }

        if ($type !== '') {
            $query->whereHas('documentType', function ($q) use ($type) {
                $q->where('name', $type);
            });
        }

        if ($status !== '') {
            $query->whereHas('documentStatus', function ($q) use ($status) {
                $q->where('name', $status);
            });
        }

        if ($year !== '') {
            // Since the schema doesn't store a dedicated document date, use created_at year.
            $query->whereYear('created_at', $year);
        }

        if ($keyword !== '' && strtolower($keyword) !== 'all') {
            $query->where(function ($q) use ($keyword) {
                $q->where('title', 'like', '%'.$keyword.'%')
                    ->orWhere('description', 'like', '%'.$keyword.'%')
                    ->orWhere('extracted_content', 'like', '%'.$keyword.'%')
                    ->orWhereHas('documentType', function ($q) use ($keyword) {
                        $q->where('name', 'like', '%'.$keyword.'%');
                    })
                    ->orWhereHas('documentStatus', function ($q) use ($keyword) {
                        $q->where('name', 'like', '%'.$keyword.'%');
                    })
                    ->orWhereHas('documentCategory', function ($q) use ($keyword) {
                        $q->where('name', 'like', '%'.$keyword.'%');
                    });
            });
        }

        switch ($sort) {
            case 'date-asc':
                $query->orderBy('created_at', 'asc');
                break;
            case 'date-desc':
                $query->orderBy('created_at', 'desc');
                break;
            case 'title-asc':
                $query->orderBy('title', 'asc');
                break;
            case 'title-desc':
                $query->orderBy('title', 'desc');
                break;
            default:
                $query->orderBy('created_at', 'desc');
                break;
        }

        $documents = $query
            ->limit(200)
            ->get();

        $payloadDocuments = $documents->map(function (Document $doc) use ($keyword) {
            $openUrl = null;
            $downloadUrl = null;
            if ($doc->file_path) {
                $openUrl = Storage::disk('public')->url($doc->file_path);
                $downloadUrl = route('documents.download', $doc);
            }

            // Extract snippet from content if keyword exists
            $snippet = '';
            if ($keyword && $doc->extracted_content) {
                $snippet = $this->extractSnippets($doc->extracted_content, $keyword);
            }

            return [
                'id' => $doc->id,
                'date' => optional($doc->created_at)->toDateString(),
                'type' => $doc->type,
                'title' => $doc->title,
                'description' => $doc->description,
                'status' => $doc->status,
                'category' => $doc->category,
                'file_name' => $doc->file_name,
                'open_url' => $openUrl,
                'download_url' => $downloadUrl,
                'snippet' => $snippet,
            ];
        })->values();

        $stats = [
            'total' => $documents->count(),
            'active' => $documents->where('status', 'Aktif')->count(),
            'types' => $documents->pluck('type')->filter()->unique()->count(),
            'open' => $payloadDocuments->where(fn ($d) => !empty($d['open_url']))->count(),
        ];

        SearchLog::query()->create([
            'keyword' => $keyword !== '' ? $keyword : null,
            'type' => $type !== '' ? $type : null,
            'status' => $status !== '' ? $status : null,
            'chip' => $chip !== '' ? $chip : null,
            'sort' => $sort !== '' ? $sort : null,
            'results_count' => $stats['total'],
            'ip' => $request->ip(),
            'user_agent' => substr((string) $request->userAgent(), 0, 1000),
        ]);

        return response()->json([
            'documents' => $payloadDocuments,
            'stats' => $stats,
        ]);
    }

    public function checkPdf(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:pdf|max:10240',
        ]);

        try {
            $extractedContent = $this->extractPdfContent($request->file('file'));
            $textLength = strlen(trim($extractedContent));
            
            return response()->json([
                'success' => true,
                'is_image_pdf' => $textLength < 50,
                'text_length' => $textLength,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal memeriksa PDF: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function ocrPdf(Request $request)
    {
        $validated = $request->validate([
            'file' => 'required|file|mimes:pdf|max:10240',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'type' => 'required|string|max:100',
            'status' => 'required|string|max:100',
            'category' => 'required|string|max:100',
        ]);

        try {
            // Generate unique session ID for this OCR process
            $sessionId = uniqid('ocr_', true);
            
            // Initialize progress
            Cache::put("ocr_progress_{$sessionId}", [
                'progress' => 5,
                'status' => 'Memulai proses OCR...',
            ], now()->addHours(2));
            
            // Get the uploaded file
            $file = $request->file('file');
            $originalFilename = $file->getClientOriginalName();
            
            // Save temporary file
            $tempInputPath = storage_path('app/temp_ocr_input_' . time() . '.pdf');
            $tempOutputPath = storage_path('app/temp_ocr_output_' . time() . '.pdf');
            
            $file->move(dirname($tempInputPath), basename($tempInputPath));
            
            // Save data for background process
            Cache::put("ocr_data_{$sessionId}", [
                'input_path' => $tempInputPath,
                'output_path' => $tempOutputPath,
                'original_filename' => $originalFilename,
                'title' => $validated['title'],
                'description' => $validated['description'],
                'type' => $validated['type'],
                'status' => $validated['status'],
                'category' => $validated['category'],
            ], now()->addHours(2));
            
            // Run background OCR process using artisan command
            $artisanPath = base_path('artisan');
            $phpPath = 'php';
            
            // Windows background command using start /B
            if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
                $command = sprintf(
                    'start /B %s "%s" ocr:process %s > NUL 2>&1',
                    $phpPath,
                    $artisanPath,
                    $sessionId
                );
                pclose(popen($command, 'r'));
            } else {
                // Unix/Linux background command
                $command = sprintf(
                    '%s "%s" ocr:process %s > /dev/null 2>&1 &',
                    $phpPath,
                    $artisanPath,
                    $sessionId
                );
                exec($command);
            }
            
            // Return session_id immediately
            return response()->json([
                'success' => true,
                'message' => 'Proses OCR dimulai',
                'session_id' => $sessionId,
            ]);

        } catch (\Exception $e) {
            $sessionId = $sessionId ?? uniqid('ocr_', true);
            Cache::put("ocr_progress_{$sessionId}", [
                'progress' => 0,
                'status' => 'Error: ' . $e->getMessage(),
                'error' => true,
            ], now()->addMinutes(30));
            
            return response()->json([
                'success' => false,
                'message' => 'Gagal melakukan OCR: ' . $e->getMessage(),
                'session_id' => $sessionId,
            ], 500);
        }
    }

    public function ocrProgress($sessionId)
    {
        $progress = Cache::get("ocr_progress_{$sessionId}");
        
        if (!$progress) {
            return response()->json([
                'success' => false,
                'message' => 'Session not found',
            ], 404);
        }
        
        return response()->json([
            'success' => true,
            'progress' => $progress['progress'] ?? 0,
            'status' => $progress['status'] ?? 'Unknown',
            'complete' => $progress['complete'] ?? false,
            'error' => $progress['error'] ?? false,
        ]);
    }

    public function upload(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'type' => 'required|string|max:100',
            'status' => 'required|string|max:100',
            'category' => 'required|string|max:100',
            'file' => 'required|file|mimes:pdf|max:10240',
        ]);

        try {
            // Extract PDF content
            $extractedContent = $this->extractPdfContent($request->file('file'));

            // Store file
            $filePath = $request->file('file')->store('documents', 'public');
            $fileName = $request->file('file')->getClientOriginalName();

            // Map type, status, and category to their IDs
            $docType = DocumentType::where('name', $validated['type'])->first();
            $docStatus = DocumentStatus::where('name', $validated['status'])->first();
            $docCategory = DocumentCategory::where('name', $validated['category'])->first();

            // Create document record
            $document = Document::create([
                'title' => $validated['title'],
                'description' => $validated['description'],
                'file_path' => $filePath,
                'file_name' => $fileName,
                'extracted_content' => $extractedContent,
                'document_type_id' => $docType?->id,
                'document_status_id' => $docStatus?->id,
                'document_category_id' => $docCategory?->id,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Dokumen berhasil disimpan',
                'document' => $document,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menyimpan dokumen: ' . $e->getMessage(),
            ], 400);
        }
    }

    private function extractPdfContent($file)
    {
        try {
            $parser = new Parser();
            $pdf = $parser->parseFile($file->getRealPath());
            $text = $pdf->getText();
            
            // Fix UTF-8 encoding issues
            // Remove malformed UTF-8 sequences
            $text = mb_convert_encoding($text, 'UTF-8', 'UTF-8');
            
            // Remove invalid UTF-8 characters
            $text = preg_replace('/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F]/u', '', $text);
            
            // Replace common encoding issues
            $text = str_replace(["\r\n", "\r", "\0"], "\n", $text);
            
            // Remove extra whitespace but preserve structure
            $text = preg_replace('/\n\s+\n/', "\n", $text);
            
            return trim($text);
        } catch (\Exception $e) {
            return '';
        }
    }

    private function extractSnippets($text, $keyword, $contextLength = 300)
    {
        if (!$keyword || strlen($text) === 0) {
            return '';
        }

        $originalText = $text;
        $keyword = strtolower($keyword);
        $textLower = strtolower($text);

        // Try to find whole word match first (with word boundaries)
        $pattern = '/\b' . preg_quote($keyword, '/') . '\b/i';
        if (preg_match($pattern, $textLower, $matches, PREG_OFFSET_CAPTURE)) {
            $position = $matches[0][1];
        } else {
            // Fallback to partial match
            $position = strpos($textLower, $keyword);
            if ($position === false) {
                return substr($originalText, 0, $contextLength) . '...';
            }
        }

        // Start snippet from the keyword position (show keyword first)
        // Only show context after the keyword
        $start = $position;
        $end = min(strlen($originalText), $position + strlen($keyword) + ($contextLength * 2));
        $snippet = substr($originalText, $start, $end - $start);

        // Highlight the keyword with yellow background
        $highlightedSnippet = preg_replace(
            '/\b' . preg_quote($keyword, '/') . '\b/i',
            '<span class="bg-yellow-200 font-semibold">$0</span>',
            $snippet
        );

        if ($end < strlen($originalText)) {
            $highlightedSnippet .= '...';
        }

        return trim($highlightedSnippet);
    }

    public function download(Document $document)
    {
        if (!$document->file_path) {
            abort(404);
        }

        $filePath = $document->file_path;
        $downloadName = $document->file_name ?: basename($filePath);

        if (!Storage::disk('public')->exists($filePath)) {
            abort(404);
        }

        // Force Content-Disposition attachment so the browser downloads instead of previewing inline.
        return Storage::disk('public')->download($filePath, $downloadName);
    }

    public function options()
    {
        // Only get types, statuses, and categories that have documents
        $types = DocumentType::whereHas('documents')->pluck('name')->sort()->values()->toArray();
        $statuses = DocumentStatus::whereHas('documents')->pluck('name')->sort()->values()->toArray();
        $categories = DocumentCategory::whereHas('documents')->pluck('name')->sort()->values()->toArray();

        return response()->json([
            'types' => $types,
            'statuses' => $statuses,
            'categories' => $categories,
        ]);
    }

    public function allOptions()
    {
        // Get ALL types, statuses, and categories (including empty ones) for input forms
        $types = DocumentType::pluck('name')->sort()->values()->toArray();
        $statuses = DocumentStatus::pluck('name')->sort()->values()->toArray();
        $categories = DocumentCategory::pluck('name')->sort()->values()->toArray();

        return response()->json([
            'types' => $types,
            'statuses' => $statuses,
            'categories' => $categories,
        ]);
    }

    public function update(Request $request, Document $document)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'type' => 'required|string|max:100',
            'status' => 'required|string|max:100',
            'category' => 'required|string|max:100',
        ]);

        try {
            // Map type, status, and category to their IDs
            $docType = DocumentType::where('name', $validated['type'])->first();
            $docStatus = DocumentStatus::where('name', $validated['status'])->first();
            $docCategory = DocumentCategory::where('name', $validated['category'])->first();

            // Update document with foreign key IDs only
            $document->update([
                'title' => $validated['title'],
                'description' => $validated['description'],
                'document_type_id' => $docType?->id,
                'document_status_id' => $docStatus?->id,
                'document_category_id' => $docCategory?->id,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Dokumen berhasil diperbarui',
                'data' => $document,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal memperbarui dokumen: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function destroy(Document $document)
    {
        try {
            // Delete file from storage
            if ($document->file_path && Storage::disk('public')->exists($document->file_path)) {
                Storage::disk('public')->delete($document->file_path);
            }

            // Delete document record
            $document->delete();

            return response()->json([
                'success' => true,
                'message' => 'Dokumen berhasil dihapus',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus dokumen: ' . $e->getMessage(),
            ], 500);
        }
    }
}
