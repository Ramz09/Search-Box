<?php

namespace App\Http\Controllers;

use App\Models\Document;
use App\Models\DocumentType;
use App\Models\DocumentStatus;
use App\Models\DocumentCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $documents = Document::query()
            ->with(['documentType', 'documentStatus', 'documentCategory'])
            ->orderByDesc('created_at')
            ->get()
            ->map(function (Document $doc) {
                $openUrl = null;
                $downloadUrl = null;

                if ($doc->file_path) {
                    $openUrl = Storage::disk('public')->url($doc->file_path);
                    $downloadUrl = route('documents.download', $doc);
                }

                return [
                    'id' => $doc->id,
                    'date' => optional($doc->created_at)->toDateString(),
                    'type' => $doc->type,
                    'title' => $doc->title,
                    'description' => $doc->description,
                    'status' => $doc->status,
                    'category' => $doc->category,
                    'open_url' => $openUrl,
                    'download_url' => $downloadUrl,
                ];
            });

        $stats = [
            'total' => $documents->count(),
            'active' => $documents->where('status', 'Aktif')->count(),
            'types' => $documents->pluck('type')->filter()->unique()->count(),
            'open' => $documents->where(fn ($d) => !empty($d['open_url']))->count(),
        ];

        return view('admin.dashboard', [
            'documents' => $documents,
            'stats' => $stats,
        ]);
    }

    public function options()
    {
        // Return ALL options (not filtered) for admin dashboard
        $types = DocumentType::pluck('name')->sort()->values()->toArray();
        $statuses = DocumentStatus::pluck('name')->sort()->values()->toArray();
        $categories = DocumentCategory::pluck('name')->sort()->values()->toArray();

        return response()->json([
            'types' => $types,
            'statuses' => $statuses,
            'categories' => $categories,
        ]);
    }

    public function addType(Request $request)
    {
        $validated = $request->validate([
            'type' => 'required|string|max:100|unique:document_types,name',
        ]);

        try {
            DocumentType::create([
                'name' => $validated['type'],
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Jenis dokumen berhasil ditambahkan',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function addStatus(Request $request)
    {
        $validated = $request->validate([
            'status' => 'required|string|max:100|unique:document_statuses,name',
        ]);

        try {
            DocumentStatus::create([
                'name' => $validated['status'],
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Status berhasil ditambahkan',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function addCategory(Request $request)
    {
        $validated = $request->validate([
            'category' => 'required|string|max:100|unique:document_categories,name',
        ]);

        try {
            DocumentCategory::create([
                'name' => $validated['category'],
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Kategori berhasil ditambahkan',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function deleteType(Request $request)
    {
        try {
            DocumentType::where('name', $request->type)->delete();

            return response()->json([
                'success' => true,
                'message' => 'Jenis dokumen berhasil dihapus',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function deleteStatus(Request $request)
    {
        try {
            DocumentStatus::where('name', $request->status)->delete();

            return response()->json([
                'success' => true,
                'message' => 'Status berhasil dihapus',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function deleteCategory(Request $request)
    {
        try {
            DocumentCategory::where('name', $request->category)->delete();

            return response()->json([
                'success' => true,
                'message' => 'Kategori berhasil dihapus',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }
}
