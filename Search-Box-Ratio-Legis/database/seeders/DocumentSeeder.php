<?php

namespace Database\Seeders;

use App\Models\Document;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Smalot\PdfParser\Parser;

class DocumentSeeder extends Seeder
{
    public function run(): void
    {
        $sourcePath = base_path('resources/views/Kesesuaian_Pelayanan_Laporan.pdf');

        if (!file_exists($sourcePath)) {
            // If the file isn't present, don't fail the entire seeding process.
            return;
        }

        $targetFileName = 'kesesuaian_pelayanan_pelaporan.pdf';
        $targetPath = 'documents/'.$targetFileName;

        // Copy the PDF into the public disk so it can be opened from the UI.
        $pdfBytes = file_get_contents($sourcePath);
        Storage::disk('public')->put($targetPath, $pdfBytes);

        $extractedText = null;
        try {
            $parser = new Parser();
            $pdf = $parser->parseContent($pdfBytes);
            $text = $pdf->getText();
            $text = preg_replace('/\s+/', ' ', $text ?? '');
            $extractedText = trim($text);
            if ($extractedText === '') {
                $extractedText = null;
            }
        } catch (\Throwable $e) {
            $extractedText = null;
        }

        Document::query()->updateOrCreate(
            ['file_name' => $targetFileName],
            [
                'title' => 'Kesesuaian Pelayanan Pelaporan',
                'description' => 'Dokumen PDF: Kesesuaian Pelayanan Pelaporan.',
                'type' => 'Peraturan',
                'status' => 'Aktif',
                'category' => 'perdagangan',
                'file_path' => $targetPath,
                'file_name' => $targetFileName,
                'extracted_content' => $extractedText,
            ]
        );
    }
}
