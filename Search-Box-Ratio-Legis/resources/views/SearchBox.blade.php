<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <title>Search Box Ratio Legis</title>

    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@400;500;600;700&family=Playfair+Display:wght@600;700&display=swap" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-gradient-to-br from-sky-50 via-white to-emerald-50 text-slate-900 font-sans">
    <div class="absolute inset-0 pointer-events-none">
        <div class="absolute -left-14 top-10 h-56 w-56 rounded-full bg-sky-200 blur-3xl opacity-70"></div>
        <div class="absolute right-8 top-24 h-64 w-64 rounded-full bg-emerald-200 blur-3xl opacity-70"></div>
        <div class="absolute left-40 bottom-12 h-56 w-56 rounded-full bg-indigo-200 blur-3xl opacity-60"></div>
    </div>

    <div class="relative z-10 px-4 py-8 md:px-10 lg:px-16 xl:px-24">
        <header class="flex flex-col gap-6 md:flex-row md:items-center md:justify-between">
            <div>
                <p class="text-xs uppercase tracking-[0.35em] text-sky-600">Kementerian Perdagangan</p>
                <h1 class="mt-2 text-4xl font-bold text-slate-900 md:text-5xl" style="font-family: 'Playfair Display', serif;">
                    Search Box Ratio Legis
                </h1>
                <p class="mt-3 max-w-2xl text-sm text-slate-600 md:text-base">
                    Cari dan jelajahi dokumen hukum, kebijakan, dan nota dinas perdagangan dalam negeri dengan pencarian interaktif berbasis front end.
                </p>
            </div>
            <div class="flex flex-wrap gap-3">
                <button class="inline-flex items-center gap-2 rounded-full border border-sky-200 bg-white px-4 py-2 text-sm font-semibold text-sky-800 shadow-sm transition hover:-translate-y-0.5 hover:shadow-md">
                    <span class="h-2.5 w-2.5 rounded-full bg-emerald-400 shadow-[0_0_0_6px_rgba(16,185,129,0.2)]"></span>
                    Real-time Filter
                </button>
                <button class="inline-flex items-center gap-2 rounded-full border border-emerald-200 bg-emerald-50 px-4 py-2 text-sm font-semibold text-emerald-800 shadow-sm transition hover:-translate-y-0.5 hover:shadow-md">
                    <span class="h-2 w-2 rounded-full bg-emerald-400"></span>
                    Dummy Data (Front End)
                </button>
            </div>
        </header>

        <section class="mt-8 grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
            <article class="rounded-2xl border border-white/80 bg-white/90 p-4 shadow-xl shadow-sky-100/80">
                <p class="text-xs uppercase tracking-wide text-slate-500">Total Dokumen</p>
                <div class="mt-2 flex items-baseline gap-2">
                    <span id="stat-total" class="text-3xl font-semibold text-slate-900">0</span>
                    <span class="text-xs text-emerald-600">• live</span>
                </div>
                <p class="mt-1 text-xs text-slate-500">Semua sumber yang dimuat</p>
            </article>
            <article class="rounded-2xl border border-sky-100 bg-gradient-to-br from-sky-100 via-white to-indigo-50 p-4 shadow-xl shadow-sky-100">
                <p class="text-xs uppercase tracking-wide text-slate-600">Kebijakan Aktif</p>
                <div class="mt-2 flex items-baseline gap-2">
                    <span id="stat-active" class="text-3xl font-semibold text-slate-900">0</span>
                    <span class="text-xs text-sky-700">aktif</span>
                </div>
                <p class="mt-1 text-xs text-slate-500">Filter status: aktif</p>
            </article>
            <article class="rounded-2xl border border-amber-100 bg-gradient-to-br from-amber-50 via-white to-orange-50 p-4 shadow-xl shadow-amber-100">
                <p class="text-xs uppercase tracking-wide text-slate-600">Jenis Regulasi</p>
                <div class="mt-2 text-2xl font-semibold text-slate-900" id="stat-types">-</div>
                <p class="mt-1 text-xs text-slate-500">Ragam format dokumen</p>
            </article>
            <article class="rounded-2xl border border-emerald-100 bg-gradient-to-br from-emerald-50 via-white to-teal-50 p-4 shadow-xl shadow-emerald-100">
                <p class="text-xs uppercase tracking-wide text-slate-600">Tautan Publik</p>
                <div class="mt-2 flex items-baseline gap-2">
                    <span id="stat-open" class="text-3xl font-semibold text-slate-900">0</span>
                    <span class="text-xs text-emerald-700">bisa dibuka</span>
                </div>
                <p class="mt-1 text-xs text-slate-500">Tersedia link eksternal</p>
            </article>
        </section>

        <section class="mt-10 grid gap-6 lg:grid-cols-3">
            <div class="lg:col-span-2 rounded-3xl border border-white/80 bg-white/95 p-6 shadow-2xl shadow-slate-100">
                <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
                    <div class="flex flex-wrap gap-2 text-xs text-slate-600">
                        <span class="rounded-full border border-slate-200 bg-slate-50 px-3 py-1">Cari semua</span>
                        <span class="rounded-full border border-slate-200 bg-slate-50 px-3 py-1">Filter multi kolom</span>
                        <span class="rounded-full border border-slate-200 bg-slate-50 px-3 py-1">Sorting kolom</span>
                    </div>
                    <div class="flex items-center gap-2 text-xs text-slate-600">
                        <span class="h-2 w-2 rounded-full bg-emerald-500"></span>
                        Sinkronisasi hasil instan
                    </div>
                </div>

                <div class="mt-4 grid gap-3 md:grid-cols-4">
                    <div class="md:col-span-2">
                        <label class="text-xs text-slate-600">Pencarian kata kunci</label>
                        <div class="mt-1 flex items-center gap-2 rounded-xl border border-slate-200 bg-white px-3 py-2 shadow-inner shadow-slate-100">
                            <svg class="h-4 w-4 text-slate-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35m0 0A7.5 7.5 0 104.5 4.5a7.5 7.5 0 0012.15 12.15z" />
                            </svg>
                            <input id="search-input" type="text" placeholder="Cari judul, deskripsi, kata kunci" class="w-full bg-transparent text-sm text-slate-900 placeholder:text-slate-400 focus:outline-none" />
                        </div>
                    </div>
                    <div>
                        <label class="text-xs text-slate-600">Jenis dokumen</label>
                        <div class="mt-1">
                            <select id="filter-type" class="w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm text-slate-900 focus:border-sky-400 focus:outline-none">
                                <option value="">Semua jenis</option>
                            </select>
                        </div>
                    </div>
                    <div>
                        <label class="text-xs text-slate-600">Status</label>
                        <div class="mt-1">
                            <select id="filter-status" class="w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm text-slate-900 focus:border-emerald-400 focus:outline-none">
                                <option value="">Semua status</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="mt-3 grid gap-3 md:grid-cols-3">
                    <div>
                        <label class="text-xs text-slate-600">Tahun</label>
                        <input id="filter-year" type="number" min="2000" max="2100" placeholder="2025" class="mt-1 w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm text-slate-900 placeholder:text-slate-400 focus:border-amber-400 focus:outline-none" />
                    </div>
                    <div>
                        <label class="text-xs text-slate-600">Urutkan</label>
                        <select id="sort-select" class="mt-1 w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm text-slate-900 focus:border-indigo-400 focus:outline-none">
                            <option value="date-desc">Tanggal terbaru</option>
                            <option value="date-asc">Tanggal terlama</option>
                            <option value="title-asc">Judul A-Z</option>
                            <option value="title-desc">Judul Z-A</option>
                        </select>
                    </div>
                    <div class="flex items-end gap-2">
                        <button id="clear-filters" class="w-full rounded-xl border border-slate-200 bg-slate-50 px-3 py-2 text-sm font-semibold text-slate-800 transition hover:-translate-y-0.5 hover:border-slate-300 hover:bg-white">
                            Reset
                        </button>
                    </div>
                </div>

                <div id="chips-container" class="mt-4 flex flex-wrap gap-2 text-xs text-slate-800">
                    <!-- Chips generated by JavaScript -->
                </div>

                <div class="mt-6 overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-2xl shadow-slate-100">
                    <div class="hidden md:grid grid-cols-[0.4fr_1fr_1.4fr_1.1fr_1.2fr_1.2fr_0.9fr] gap-4 bg-slate-50 px-4 py-1 text-xs font-semibold uppercase tracking-wide text-slate-700 items-start">
                        <span class="text-center self-start">No</span>
                        <span class="text-left self-start">Tanggal</span>
                        <span class="text-left self-start">Judul</span>
                        <span class="text-left self-start">Jenis Dokumen</span>
                        <span class="text-left self-start">Deskripsi</span>
                        <span class="text-left self-start">Snippet</span>
                        <span class="text-center self-start">File</span>
                    </div>
                    <div id="table-body" class="divide-y divide-slate-200">
                        <!-- rows injected by JS -->
                    </div>
                </div>
            </div>

            <aside class="rounded-3xl border border-white/80 bg-white/95 p-5 shadow-2xl shadow-slate-100">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs uppercase tracking-wide text-slate-500">Ringkasan cepat</p>
                        <h3 class="text-lg font-semibold text-slate-900">Snapshot</h3>
                    </div>
                    <span class="rounded-full bg-emerald-100 px-3 py-1 text-xs font-semibold text-emerald-700">Realtime</span>
                </div>

                <ul class="mt-4 space-y-3 text-sm text-slate-800">
                    <li class="flex items-start gap-3 rounded-2xl border border-slate-200 bg-slate-50 p-3 shadow-inner shadow-slate-100">
                        <div class="mt-1 h-2.5 w-2.5 rounded-full bg-sky-400"></div>
                        <div>
                            <p class="font-semibold">Filtering multi-kata</p>
                            <p class="text-xs text-slate-500">Cari di judul, deskripsi, kategori.</p>
                        </div>
                    </li>
                    <li class="flex items-start gap-3 rounded-2xl border border-slate-200 bg-slate-50 p-3 shadow-inner shadow-slate-100">
                        <div class="mt-1 h-2.5 w-2.5 rounded-full bg-amber-400"></div>
                        <div>
                            <p class="font-semibold">Dummy dataset</p>
                            <p class="text-xs text-slate-500">Data tersimpan di front end untuk uji coba cepat.</p>
                        </div>
                    </li>
                    <li class="flex items-start gap-3 rounded-2xl border border-slate-200 bg-slate-50 p-3 shadow-inner shadow-slate-100">
                        <div class="mt-1 h-2.5 w-2.5 rounded-full bg-emerald-400"></div>
                        <div>
                            <p class="font-semibold">Status insight</p>
                            <p class="text-xs text-slate-500">Hitung aktif, revisi, dan draft secara dinamis.</p>
                        </div>
                    </li>
                </ul>

                <div class="mt-6 rounded-2xl border border-slate-200 bg-gradient-to-br from-white via-slate-50 to-emerald-50 p-4 shadow-inner shadow-slate-100">
                    <p class="text-xs uppercase tracking-wide text-slate-500">Simulasi Input Dokumen</p>
                    <p class="mt-1 text-sm text-slate-700">Tombol ini hanya ilustrasi front end.</p>
                    <button id="btn-input-dokumen" class="mt-3 w-full rounded-xl bg-emerald-500 px-4 py-2 text-sm font-semibold text-white shadow-lg shadow-emerald-200 transition hover:-translate-y-0.5 hover:bg-emerald-400">
                        + Input Dokumen Baru
                    </button>
                </div>

                <div class="mt-6 text-xs text-slate-600">
                    <p>Ketik "all" untuk menampilkan seluruh data.</p>
                    <p class="mt-1">Gunakan sort untuk urutan tanggal atau judul.</p>
                </div>
            </aside>
        </section>
    </div>

    <!-- Modal Input Dokumen Baru -->
    <div id="modal-input-dokumen" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm p-4">
        <div class="relative w-full max-w-2xl rounded-3xl border border-white/80 bg-white/95 shadow-2xl shadow-slate-200">
            <!-- Modal Header -->
            <div class="flex items-center justify-between border-b border-slate-200 px-6 py-4">
                <div>
                    <h2 class="text-xl font-bold text-slate-900" style="font-family: 'Playfair Display', serif;">Input Dokumen Baru</h2>
                    <p class="mt-1 text-xs text-slate-600">Tambahkan dokumen peraturan ke dalam sistem</p>
                </div>
                <button id="modal-close" class="rounded-lg p-2 hover:bg-slate-100 transition">
                    <svg class="h-5 w-5 text-slate-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>

            <!-- Modal Body -->
            <div class="max-h-[70vh] overflow-y-auto px-6 py-5">
                <form id="form-input-dokumen" class="space-y-4">
                    <!-- Title -->
                    <div>
                        <label class="block text-sm font-semibold text-slate-900 mb-2">Judul Dokumen *</label>
                        <input type="text" name="title" placeholder="Masukkan judul dokumen" required class="w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-900 placeholder:text-slate-400 focus:border-sky-400 focus:outline-none shadow-inner shadow-slate-50" />
                    </div>

                    <!-- Description -->
                    <div>
                        <label class="block text-sm font-semibold text-slate-900 mb-2">Deskripsi</label>
                        <textarea name="description" placeholder="Masukkan deskripsi dokumen" rows="3" class="w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-900 placeholder:text-slate-400 focus:border-sky-400 focus:outline-none shadow-inner shadow-slate-50 resize-none"></textarea>
                    </div>

                    <!-- Type -->
                    <div>
                        <label class="block text-sm font-semibold text-slate-900 mb-2">Jenis Dokumen *</label>
                        <select name="type" required class="w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-900 focus:border-sky-400 focus:outline-none shadow-inner shadow-slate-50">
                            <option value="">-- Pilih jenis dokumen --</option>
                        </select>
                    </div>

                    <!-- Status -->
                    <div>
                        <label class="block text-sm font-semibold text-slate-900 mb-2">Status *</label>
                        <select name="status" required class="w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-900 focus:border-sky-400 focus:outline-none shadow-inner shadow-slate-50">
                            <option value="">-- Pilih status --</option>
                        </select>
                    </div>

                    <!-- Category -->
                    <div>
                        <label class="block text-sm font-semibold text-slate-900 mb-2">Kategori *</label>
                        <select name="category" required class="w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-900 focus:border-sky-400 focus:outline-none shadow-inner shadow-slate-50">
                            <option value="">-- Pilih kategori --</option>
                        </select>
                    </div>

                    <!-- File Upload -->
                    <div>
                        <label class="block text-sm font-semibold text-slate-900 mb-2">Upload File</label>
                        <div class="relative">
                            <input type="file" name="file" id="modal-file-input" class="hidden" accept=".pdf,.doc,.docx,.xls,.xlsx" />
                            <label for="modal-file-input" class="block w-full rounded-xl border-2 border-dashed border-slate-300 bg-slate-50 px-4 py-6 text-center cursor-pointer hover:border-sky-400 hover:bg-sky-50 transition">
                                <svg class="mx-auto h-8 w-8 text-slate-400 mb-2" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 16.5V9.75m0 0l3 3m-3-3l-3 3M6.75 19.5a4.5 4.5 0 01-1.41-8.775 5.25 5.25 0 0110.233-2.33A3 3 0 0116.5 19.5H6.75z"></path>
                                </svg>
                                <p class="text-sm font-semibold text-slate-700">Klik untuk upload atau drag file</p>
                                <p class="mt-1 text-xs text-slate-500">PDF</p>
                            </label>
                            <p id="modal-file-name" class="mt-2 text-xs text-slate-600 hidden">File: <span id="modal-file-text"></span></p>
                        </div>
                    </div>

                </form>
            </div>

            <!-- Modal Footer -->
            <div class="flex items-center justify-end gap-3 border-t border-slate-200 px-6 py-4 bg-slate-50">
                <button id="modal-cancel" class="rounded-xl border border-slate-200 bg-white px-5 py-2 text-sm font-semibold text-slate-800 hover:bg-slate-100 transition">
                    Batal
                </button>
                <button id="modal-submit" class="rounded-xl bg-emerald-500 px-5 py-2 text-sm font-semibold text-white shadow-lg shadow-emerald-200 hover:bg-emerald-400 transition">
                    Simpan Dokumen
                </button>
            </div>
        </div>
    </div>

    <!-- Modal Konfirmasi OCR -->
    <div id="modal-ocr-confirmation" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm p-4">
        <div class="relative w-full max-w-md rounded-3xl border border-white/80 bg-white shadow-2xl shadow-slate-200 animate-scale-in">
            <!-- Modal Header -->
            <div class="px-6 py-5 text-center border-b border-slate-100">
                <div class="mx-auto mb-3 flex h-14 w-14 items-center justify-center rounded-full bg-amber-100">
                    <svg class="h-8 w-8 text-amber-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z"></path>
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-slate-900" style="font-family: 'Playfair Display', serif;">PDF Gambar Terdeteksi</h3>
                <p class="mt-2 text-sm text-slate-600">PDF yang Anda upload adalah PDF gambar (bukan teks).</p>
            </div>

            <!-- Modal Body -->
            <div class="px-6 py-5">
                <div class="rounded-xl border border-sky-100 bg-sky-50 p-4">
                    <div class="flex gap-3">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-sky-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M11.25 11.25l.041-.02a.75.75 0 011.063.852l-.708 2.836a.75.75 0 001.063.853l.041-.021M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9-3.75h.008v.008H12V8.25z"></path>
                            </svg>
                        </div>
                        <div class="flex-1 text-sm text-sky-900">
                            <p class="font-semibold">Apakah ingin convert PDF terlebih dahulu?</p>
                            <p class="mt-1 text-xs text-sky-700">Proses OCR (Optical Character Recognition) akan mengubah gambar menjadi teks yang dapat dicari. Waktu proses: 3-5 menit per 10 halaman.</p>
                        </div>
                    </div>
                </div>

                <div class="mt-4 rounded-xl border border-slate-200 bg-slate-50 p-4">
                    <h4 class="text-xs font-semibold uppercase tracking-wide text-slate-700 mb-2">Info OCR:</h4>
                    <ul class="space-y-1 text-xs text-slate-600">
                        <li class="flex items-start gap-2">
                            <svg class="h-4 w-4 text-emerald-500 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.809a.75.75 0 00-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 10-1.06 1.061l2.5 2.5a.75.75 0 001.137-.089l4-5.5z" clip-rule="evenodd"></path>
                            </svg>
                            <span>Dokumen hasil OCR dapat dicari dengan kata kunci</span>
                        </li>
                        <li class="flex items-start gap-2">
                            <svg class="h-4 w-4 text-emerald-500 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.809a.75.75 0 00-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 10-1.06 1.061l2.5 2.5a.75.75 0 001.137-.089l4-5.5z" clip-rule="evenodd"></path>
                            </svg>
                            <span>Mendukung bahasa Indonesia dan Inggris</span>
                        </li>
                        <li class="flex items-start gap-2">
                            <svg class="h-4 w-4 text-amber-500 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.28 7.22a.75.75 0 00-1.06 1.06L8.94 10l-1.72 1.72a.75.75 0 101.06 1.06L10 11.06l1.72 1.72a.75.75 0 101.06-1.06L11.06 10l1.72-1.72a.75.75 0 00-1.06-1.06L10 8.94 8.28 7.22z" clip-rule="evenodd"></path>
                            </svg>
                            <span>Proses tidak bisa dibatalkan setelah dimulai</span>
                        </li>
                    </ul>
                </div>
            </div>

            <!-- Modal Footer -->
            <div class="flex items-center justify-end gap-3 border-t border-slate-200 px-6 py-4 bg-slate-50">
                <button id="ocr-btn-cancel" class="rounded-xl border border-slate-300 bg-white px-5 py-2.5 text-sm font-semibold text-slate-700 hover:bg-slate-100 transition">
                    Tidak, kembali
                </button>
                <button id="ocr-btn-confirm" class="rounded-xl bg-emerald-500 px-5 py-2.5 text-sm font-semibold text-white shadow-lg shadow-emerald-200 hover:bg-emerald-600 transition flex items-center gap-2">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <span>Ya, konversi</span>
                </button>
            </div>
        </div>
    </div>

    <!-- Modal Loading OCR Progress -->
    <div id="modal-ocr-progress" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm p-4">
        <div class="relative w-full max-w-md rounded-3xl border border-white/80 bg-white shadow-2xl shadow-slate-200 animate-scale-in">
            <!-- Modal Header -->
            <div class="px-6 py-5 text-center border-b border-slate-100">
                <h3 class="text-xl font-bold text-slate-900" style="font-family: 'Playfair Display', serif;">Proses OCR Berlangsung</h3>
                <p class="mt-2 text-sm text-slate-600">Harap tunggu, dokumen sedang diproses...</p>
            </div>

            <!-- Modal Body -->
            <div class="px-6 py-8 flex flex-col items-center">
                <!-- Circular Progress -->
                <div class="relative w-40 h-40">
                    <svg class="transform -rotate-90 w-40 h-40">
                        <circle
                            cx="80"
                            cy="80"
                            r="70"
                            stroke="#e2e8f0"
                            stroke-width="12"
                            fill="transparent"
                        />
                        <circle
                            id="progress-circle"
                            cx="80"
                            cy="80"
                            r="70"
                            stroke="#10b981"
                            stroke-width="12"
                            fill="transparent"
                            stroke-dasharray="439.8"
                            stroke-dashoffset="439.8"
                            stroke-linecap="round"
                            class="transition-all duration-500 ease-out"
                        />
                    </svg>
                    <div class="absolute inset-0 flex items-center justify-center">
                        <div class="text-center">
                            <div id="progress-percentage" class="text-4xl font-bold text-slate-900">0%</div>
                            <div class="text-xs text-slate-500 mt-1">Diproses</div>
                        </div>
                    </div>
                </div>

                <!-- Progress Status Text -->
                <div class="mt-6 w-full">
                    <div class="rounded-xl border border-slate-200 bg-slate-50 p-4">
                        <div class="flex items-center gap-3">
                            <div class="flex-shrink-0">
                                <div class="animate-spin h-5 w-5 border-3 border-emerald-500 border-t-transparent rounded-full"></div>
                            </div>
                            <div class="flex-1">
                                <p id="progress-status" class="text-sm font-medium text-slate-700">Memulai proses OCR...</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Info -->
                <div class="mt-4 text-center">
                    <p class="text-xs text-slate-500">Proses ini mungkin memakan waktu beberapa menit</p>
                    <p class="text-xs text-slate-400 mt-1">Jangan tutup atau refresh halaman ini</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Notifikasi -->
    <div id="modal-notification" class="hidden fixed inset-0 z-[60] flex items-center justify-center bg-black/50 backdrop-blur-sm p-4">
        <div class="relative w-full max-w-md rounded-3xl border border-white/80 bg-white shadow-2xl shadow-slate-200 animate-scale-in">
            <!-- Modal Header -->
            <div id="notification-header" class="px-6 py-5 text-center border-b border-slate-100">
                <div id="notification-icon" class="mx-auto mb-3 flex h-14 w-14 items-center justify-center rounded-full">
                    <!-- Icon will be inserted here -->
                </div>
                <h3 id="notification-title" class="text-xl font-bold text-slate-900" style="font-family: 'Playfair Display', serif;"></h3>
            </div>

            <!-- Modal Body -->
            <div class="px-6 py-5">
                <p id="notification-message" class="text-center text-slate-600"></p>
            </div>

            <!-- Modal Footer -->
            <div class="flex items-center justify-center border-t border-slate-200 px-6 py-4 bg-slate-50">
                <button id="notification-btn-ok" class="rounded-xl bg-slate-700 px-6 py-2.5 text-sm font-semibold text-white hover:bg-slate-800 transition">
                    OK
                </button>
            </div>
        </div>
    </div>

    <style>
        body { font-family: 'Space Grotesk', 'Instrument Sans', ui-sans-serif, system-ui, sans-serif; }
        .chip {
            padding: 0.35rem 0.9rem;
            border-radius: 9999px;
            border: 1px solid rgba(148, 163, 184, 0.5);
            background: #f8fafc;
            color: #0f172a;
            transition: all 0.2s ease;
        }
        .chip:hover { transform: translateY(-2px); border-color: rgba(59, 130, 246, 0.4); background: #e2e8f0; }
        .chip.active { border-color: rgba(34, 197, 94, 0.8); background: #dcfce7; color: #065f46; }
        @keyframes scale-in {
            0% { transform: scale(0.95); opacity: 0; }
            100% { transform: scale(1); opacity: 1; }
        }
        .animate-scale-in { animation: scale-in 0.2s ease-out; }
    </style>

    <script>
        const state = {
            keyword: '',
            type: '',
            status: '',
            year: '',
            sort: 'date-desc',
            chip: 'all',
        };

        const dom = {
            tableBody: document.getElementById('table-body'),
            search: document.getElementById('search-input'),
            type: document.getElementById('filter-type'),
            status: document.getElementById('filter-status'),
            year: document.getElementById('filter-year'),
            sort: document.getElementById('sort-select'),
            clear: document.getElementById('clear-filters'),
            statTotal: document.getElementById('stat-total'),
            statActive: document.getElementById('stat-active'),
            statTypes: document.getElementById('stat-types'),
            statOpen: document.getElementById('stat-open'),
        };
        let lastRequestId = 0;

        function debounce(fn, waitMs) {
            let t;
            return (...args) => {
                clearTimeout(t);
                t = setTimeout(() => fn(...args), waitMs);
            };
        }

        async function loadFilterOptions() {
            try {
                const res = await fetch('/search-box/options');
                const data = await res.json();

                // Populate type options
                data.types.forEach(type => {
                    const option = document.createElement('option');
                    option.value = type;
                    option.textContent = type;
                    dom.type.appendChild(option);
                });

                // Populate status options
                data.statuses.forEach(status => {
                    const option = document.createElement('option');
                    option.value = status;
                    option.textContent = status;
                    dom.status.appendChild(option);
                });

                // Render category chips
                renderChips(data.categories);
            } catch (e) {
                console.error('Failed to load filter options:', e);
            }
        }

        function renderChips(categories) {
            const chipsContainer = document.getElementById('chips-container');
            chipsContainer.innerHTML = '';

            // Add "All" chip
            const allChip = document.createElement('button');
            allChip.type = 'button';
            allChip.className = 'chip active';
            allChip.dataset.chip = 'all';
            allChip.textContent = 'All';
            chipsContainer.appendChild(allChip);

            // Add category chips
            categories.forEach(category => {
                const chip = document.createElement('button');
                chip.type = 'button';
                chip.className = 'chip';
                chip.dataset.chip = category;
                chip.textContent = category.charAt(0).toUpperCase() + category.slice(1);
                chipsContainer.appendChild(chip);
            });

            // Re-attach chip listeners
            attachChipListeners();
        }

        function renderRows(rows) {
            dom.tableBody.innerHTML = '';
            if (!rows.length) {
                dom.tableBody.innerHTML = `<div class="px-4 py-6 text-center text-sm text-slate-500">Tidak ada hasil yang cocok.</div>`;
                return;
            }

            rows.forEach((item, idx) => {
                const dateObj = new Date(item.date);
                const dateLabel = dateObj.toLocaleDateString('id-ID', { day: '2-digit', month: 'long', year: 'numeric' });
                const row = document.createElement('div');

                const openUrl = item.open_url || '#';
                const openDisabled = !item.open_url;
                const openClass = openDisabled
                    ? 'inline-flex items-center gap-1 rounded-lg bg-slate-200 px-3 py-1 text-xs font-semibold text-slate-500 cursor-not-allowed'
                    : 'inline-flex items-center gap-1 rounded-lg bg-emerald-500 px-3 py-1 text-xs font-semibold text-white shadow hover:bg-emerald-400';

                const downloadUrl = item.download_url || '';
                const downloadDisabled = !downloadUrl;
                const downloadClass = downloadDisabled
                    ? 'inline-flex items-center gap-1 rounded-lg border border-slate-200 px-3 py-1 text-xs font-semibold text-slate-400 cursor-not-allowed'
                    : 'inline-flex items-center gap-1 rounded-lg border border-slate-200 px-3 py-1 text-xs font-semibold text-slate-700 hover:border-amber-400';

                // Escape HTML for safe rendering except for snippet which may contain highlighting
                const escapeHtml = (str) => {
                    const div = document.createElement('div');
                    div.textContent = str || '-';
                    return div.innerHTML;
                };

                row.className = 'grid grid-cols-1 gap-4 px-4 py-3 md:grid-cols-[0.4fr_1fr_1.4fr_1.1fr_1.2fr_1.2fr_0.9fr] md:items-start hover:bg-slate-50 transition';
                row.innerHTML = `
                    <div class="text-sm font-semibold text-slate-800 text-center self-start">${idx + 1}</div>
                    <div class="flex items-center gap-2 text-sm text-slate-700 self-start">
                        <span class="rounded-full bg-emerald-100 px-2 py-1 text-[11px] uppercase tracking-wide text-emerald-700">${escapeHtml(item.status)}</span>
                        <span>${dateLabel}</span>
                    </div>
                    <div class="text-sm font-semibold text-slate-900 text-left self-start -ml-1">${escapeHtml(item.title)}</div>
                    <div class="text-sm text-slate-800 text-left self-start -ml-1">${escapeHtml(item.type)}</div>
                    <div class="text-sm text-slate-700 line-clamp-2 text-left self-start -ml-1">${escapeHtml(item.description)}</div>
                    <div class="text-xs text-slate-600 line-clamp-3 text-left self-start -ml-1">${item.snippet || '-'}</div>
                    <div class="flex flex-wrap items-center justify-center gap-2">
                        <a href="${openUrl}" target="_blank" rel="noopener noreferrer" class="${openClass}" ${openDisabled ? 'tabindex="-1" aria-disabled="true"' : ''}>Open</a>
                        <a href="${downloadUrl}" class="${downloadClass}" ${downloadDisabled ? 'tabindex="-1" aria-disabled="true"' : 'download'}>Download</a>
                    </div>
                `;
                dom.tableBody.appendChild(row);
            });
        }

        function updateStats(stats) {
            dom.statTotal.textContent = stats?.total ?? 0;
            dom.statActive.textContent = stats?.active ?? 0;
            dom.statTypes.textContent = `${stats?.types ?? 0} jenis`;
            dom.statOpen.textContent = stats?.open ?? 0;
        }

        async function fetchData() {
            const requestId = ++lastRequestId;
            const params = new URLSearchParams({
                keyword: state.keyword,
                type: state.type,
                status: state.status,
                year: state.year,
                sort: state.sort,
                chip: state.chip,
            });

            dom.tableBody.innerHTML = `<div class="px-4 py-6 text-center text-sm text-slate-500">Memuat data...</div>`;

            try {
                const res = await fetch(`/search-box/data?${params.toString()}`, {
                    headers: { 'Accept': 'application/json' },
                });

                if (!res.ok) {
                    throw new Error(`HTTP ${res.status}`);
                }

                const json = await res.json();
                if (requestId !== lastRequestId) return;

                renderRows(json.documents ?? []);
                updateStats(json.stats ?? {});
            } catch (e) {
                if (requestId !== lastRequestId) return;
                dom.tableBody.innerHTML = `<div class="px-4 py-6 text-center text-sm text-rose-600">Gagal memuat data.</div>`;
                updateStats({ total: 0, active: 0, types: 0, open: 0 });
            }
        }

        function attachListeners() {
            const debouncedFetch = debounce(fetchData, 300);

            dom.search.addEventListener('input', (e) => {
                state.keyword = e.target.value;
                debouncedFetch();
            });
            dom.type.addEventListener('change', (e) => {
                state.type = e.target.value;
                fetchData();
            });
            dom.status.addEventListener('change', (e) => {
                state.status = e.target.value;
                fetchData();
            });
            dom.year.addEventListener('input', (e) => {
                state.year = e.target.value;
                debouncedFetch();
            });
            dom.sort.addEventListener('change', (e) => {
                state.sort = e.target.value;
                fetchData();
            });
            dom.clear.addEventListener('click', () => {
                state.keyword = '';
                state.type = '';
                state.status = '';
                state.year = '';
                state.sort = 'date-desc';
                state.chip = 'all';
                dom.search.value = '';
                dom.type.value = '';
                dom.status.value = '';
                dom.year.value = '';
                dom.sort.value = 'date-desc';
                const chips = Array.from(document.querySelectorAll('.chip'));
                chips.forEach((c) => c.classList.toggle('active', c.dataset.chip === 'all'));
                fetchData();
            });
            attachChipListeners();
        }

        function attachChipListeners() {
            const chips = Array.from(document.querySelectorAll('.chip'));
            chips.forEach((chip) => {
                chip.addEventListener('click', () => {
                    chips.forEach((c) => c.classList.remove('active'));
                    chip.classList.add('active');
                    state.chip = chip.dataset.chip;
                    fetchData();
                });
            });
        }

        function setupModal() {
            const modal = document.getElementById('modal-input-dokumen');
            const btnOpen = document.getElementById('btn-input-dokumen');
            const btnClose = document.getElementById('modal-close');
            const btnCancel = document.getElementById('modal-cancel');
            const btnSubmit = document.getElementById('modal-submit');
            const fileInput = document.getElementById('modal-file-input');
            const fileName = document.getElementById('modal-file-name');
            const fileText = document.getElementById('modal-file-text');
            const form = document.getElementById('form-input-dokumen');

            // Open modal
            btnOpen.addEventListener('click', () => {
                modal.classList.remove('hidden');
                document.body.style.overflow = 'hidden';
                // Populate dropdowns with dynamic options
                populateModalDropdowns();
            });

            // Close modal
            const closeModal = () => {
                modal.classList.add('hidden');
                document.body.style.overflow = '';
                form.reset();
                fileName.classList.add('hidden');
            };

            btnClose.addEventListener('click', closeModal);
            btnCancel.addEventListener('click', closeModal);
            modal.addEventListener('click', (e) => {
                if (e.target === modal) closeModal();
            });

            // File input handler
            fileInput.addEventListener('change', (e) => {
                if (e.target.files.length > 0) {
                    fileText.textContent = e.target.files[0].name;
                    fileName.classList.remove('hidden');
                } else {
                    fileName.classList.add('hidden');
                }
            });

            // Form submit
            btnSubmit.addEventListener('click', async (e) => {
                e.preventDefault();

                // Validate form
                if (!form.title.value || !form.type.value || !form.status.value || !form.category.value || !fileInput.files.length) {
                    showNotification('Harap lengkapi semua field yang wajib diisi', 'error');
                    return;
                }

                // Check file is PDF
                if (fileInput.files[0].type !== 'application/pdf') {
                    showNotification('File harus berformat PDF', 'error');
                    return;
                }

                try {
                    btnSubmit.disabled = true;
                    btnSubmit.textContent = 'Memeriksa PDF...';

                    // First, check if PDF is image-based
                    const checkFormData = new FormData();
                    checkFormData.append('file', fileInput.files[0]);

                    const checkRes = await fetch('/search-box/check-pdf', {
                        method: 'POST',
                        body: checkFormData,
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
                        },
                    });

                    const checkData = await checkRes.json();

                    if (!checkData.success) {
                        showNotification('Gagal memeriksa PDF: ' + checkData.message, 'error');
                        btnSubmit.disabled = false;
                        btnSubmit.textContent = 'Simpan Dokumen';
                        return;
                    }

                    // If PDF is image-based, show OCR confirmation modal
                    if (checkData.is_image_pdf) {
                        btnSubmit.disabled = false;
                        btnSubmit.textContent = 'Simpan Dokumen';
                        
                        // Show OCR confirmation modal
                        showOcrConfirmationModal(async (confirmed) => {
                            if (confirmed) {
                                // User confirmed, run OCR
                                btnSubmit.disabled = true;
                                btnSubmit.textContent = 'Menjalankan OCR...';
                                
                                // Show progress modal
                                showOcrProgressModal();
                                updateOcrProgress(0, 'Memulai proses OCR...');
                                
                                try {
                                    const ocrFormData = new FormData();
                                    ocrFormData.append('file', fileInput.files[0]);
                                    ocrFormData.append('title', form.title.value);
                                    ocrFormData.append('description', form.description.value);
                                    ocrFormData.append('type', form.type.value);
                                    ocrFormData.append('status', form.status.value);
                                    ocrFormData.append('category', form.category.value);

                                    const ocrRes = await fetch('/search-box/ocr-pdf', {
                                        method: 'POST',
                                        body: ocrFormData,
                                        headers: {
                                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
                                        },
                                    });

                                    const ocrData = await ocrRes.json();

                                    if (ocrData.success && ocrData.session_id) {
                                        // Start polling for progress
                                        await pollOcrProgress(ocrData.session_id);
                                        
                                        // OCR completed successfully
                                        hideOcrProgressModal();
                                        showNotification('PDF berhasil di-OCR dan disimpan! Sekarang dokumen dapat dicari dengan kata kunci.', 'success');
                                        closeModal();
                                        fetchData();
                                    } else {
                                        hideOcrProgressModal();
                                        showNotification('Error OCR: ' + ocrData.message, 'error');
                                    }
                                } catch (error) {
                                    hideOcrProgressModal();
                                    showNotification('Error OCR: ' + error.message, 'error');
                                }
                                
                                btnSubmit.disabled = false;
                                btnSubmit.textContent = 'Simpan Dokumen';
                            } else {
                                // User cancelled, do nothing
                                showNotification('Upload dibatalkan. Silakan pilih PDF yang berisi teks.', 'info');
                            }
                        });
                    } else {
                        // PDF has text, upload normally
                        btnSubmit.textContent = 'Menyimpan...';
                        
                        const formData = new FormData();
                        formData.append('title', form.title.value);
                        formData.append('description', form.description.value);
                        formData.append('type', form.type.value);
                        formData.append('status', form.status.value);
                        formData.append('category', form.category.value);
                        formData.append('file', fileInput.files[0]);

                        const res = await fetch('/search-box/upload', {
                            method: 'POST',
                            body: formData,
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
                            },
                        });

                        const data = await res.json();

                        if (data.success) {
                            showNotification('Dokumen berhasil disimpan!', 'success');
                            closeModal();
                            fetchData();
                        } else {
                            showNotification('Error: ' + data.message, 'error');
                        }
                        
                        btnSubmit.disabled = false;
                        btnSubmit.textContent = 'Simpan Dokumen';
                    }
                } catch (e) {
                    showNotification('Gagal memproses dokumen: ' + e.message, 'error');
                    btnSubmit.disabled = false;
                    btnSubmit.textContent = 'Simpan Dokumen';
                }
            });
        }

        function showOcrConfirmationModal(callback) {
            const modal = document.getElementById('modal-ocr-confirmation');
            const btnConfirm = document.getElementById('ocr-btn-confirm');
            const btnCancel = document.getElementById('ocr-btn-cancel');
            
            const closeOcrModal = () => {
                modal.classList.add('hidden');
                document.body.style.overflow = '';
            };
            
            const handleConfirm = () => {
                closeOcrModal();
                callback(true);
            };
            
            const handleCancel = () => {
                closeOcrModal();
                callback(false);
            };
            
            // Remove old listeners
            btnConfirm.replaceWith(btnConfirm.cloneNode(true));
            btnCancel.replaceWith(btnCancel.cloneNode(true));
            
            // Get new references after cloning
            const newBtnConfirm = document.getElementById('ocr-btn-confirm');
            const newBtnCancel = document.getElementById('ocr-btn-cancel');
            
            // Add new listeners
            newBtnConfirm.addEventListener('click', handleConfirm);
            newBtnCancel.addEventListener('click', handleCancel);
            
            // Show modal
            modal.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        function showOcrProgressModal() {
            const modal = document.getElementById('modal-ocr-progress');
            modal.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        function hideOcrProgressModal() {
            const modal = document.getElementById('modal-ocr-progress');
            modal.classList.add('hidden');
            document.body.style.overflow = '';
        }

        function updateOcrProgress(progress, status) {
            const progressCircle = document.getElementById('progress-circle');
            const progressPercentage = document.getElementById('progress-percentage');
            const progressStatus = document.getElementById('progress-status');
            
            // Update percentage text
            progressPercentage.textContent = progress + '%';
            
            // Update status text
            progressStatus.textContent = status;
            
            // Update circle progress
            // Circle circumference = 2 * PI * r = 2 * 3.14159 * 70 = 439.8
            const circumference = 439.8;
            const offset = circumference - (progress / 100) * circumference;
            progressCircle.style.strokeDashoffset = offset;
        }

        async function pollOcrProgress(sessionId) {
            return new Promise((resolve, reject) => {
                const pollInterval = setInterval(async () => {
                    try {
                        const res = await fetch(`/search-box/ocr-progress/${sessionId}`);
                        const data = await res.json();
                        
                        if (data.success) {
                            updateOcrProgress(data.progress, data.status);
                            
                            if (data.complete) {
                                clearInterval(pollInterval);
                                resolve(data);
                            } else if (data.error) {
                                clearInterval(pollInterval);
                                reject(new Error(data.status));
                            }
                        } else {
                            clearInterval(pollInterval);
                            reject(new Error('Failed to get progress'));
                        }
                    } catch (error) {
                        clearInterval(pollInterval);
                        reject(error);
                    }
                }, 1000); // Poll every 1 second
            });
        }

        function showNotification(message, type = 'info') {
            const modal = document.getElementById('modal-notification');
            const icon = document.getElementById('notification-icon');
            const title = document.getElementById('notification-title');
            const messageEl = document.getElementById('notification-message');
            const btnOk = document.getElementById('notification-btn-ok');
            
            // Configure based on type
            if (type === 'success') {
                icon.className = 'mx-auto mb-3 flex h-14 w-14 items-center justify-center rounded-full bg-emerald-100';
                icon.innerHTML = `
                    <svg class="h-8 w-8 text-emerald-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                `;
                title.textContent = 'Berhasil!';
                btnOk.className = 'rounded-xl bg-emerald-500 px-6 py-2.5 text-sm font-semibold text-white shadow-lg shadow-emerald-200 hover:bg-emerald-600 transition';
            } else if (type === 'error') {
                icon.className = 'mx-auto mb-3 flex h-14 w-14 items-center justify-center rounded-full bg-rose-100';
                icon.innerHTML = `
                    <svg class="h-8 w-8 text-rose-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z"></path>
                    </svg>
                `;
                title.textContent = 'Terjadi Kesalahan';
                btnOk.className = 'rounded-xl bg-rose-500 px-6 py-2.5 text-sm font-semibold text-white shadow-lg shadow-rose-200 hover:bg-rose-600 transition';
            } else {
                icon.className = 'mx-auto mb-3 flex h-14 w-14 items-center justify-center rounded-full bg-sky-100';
                icon.innerHTML = `
                    <svg class="h-8 w-8 text-sky-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M11.25 11.25l.041-.02a.75.75 0 011.063.852l-.708 2.836a.75.75 0 001.063.853l.041-.021M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9-3.75h.008v.008H12V8.25z"></path>
                    </svg>
                `;
                title.textContent = 'Informasi';
                btnOk.className = 'rounded-xl bg-sky-500 px-6 py-2.5 text-sm font-semibold text-white shadow-lg shadow-sky-200 hover:bg-sky-600 transition';
            }
            
            messageEl.textContent = message;
            
            // Show modal
            modal.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
            
            // Handle OK button
            const handleOk = () => {
                modal.classList.add('hidden');
                document.body.style.overflow = '';
                btnOk.removeEventListener('click', handleOk);
            };
            
            btnOk.addEventListener('click', handleOk);
        }

        function populateModalDropdowns() {
            const selectType = document.querySelector('#form-input-dokumen select[name="type"]');
            const selectStatus = document.querySelector('#form-input-dokumen select[name="status"]');
            const selectCategory = document.querySelector('#form-input-dokumen select[name="category"]');

            // Fetch ALL options (including empty ones) for input form
            fetch('/search-box/all-options')
                .then(res => res.json())
                .then(data => {
                    // Populate type dropdown
                    selectType.innerHTML = '<option value="">-- Pilih jenis dokumen --</option>';
                    data.types.forEach(type => {
                        const option = document.createElement('option');
                        option.value = type;
                        option.textContent = type;
                        selectType.appendChild(option);
                    });

                    // Populate status dropdown
                    selectStatus.innerHTML = '<option value="">-- Pilih status --</option>';
                    data.statuses.forEach(status => {
                        const option = document.createElement('option');
                        option.value = status;
                        option.textContent = status;
                        selectStatus.appendChild(option);
                    });

                    // Populate category dropdown
                    selectCategory.innerHTML = '<option value="">-- Pilih kategori --</option>';
                    data.categories.forEach(category => {
                        const option = document.createElement('option');
                        option.value = category;
                        option.textContent = category;
                        selectCategory.appendChild(option);
                    });
                })
                .catch(err => console.error('Error loading modal options:', err));
        }

        attachListeners();
        setupModal();
        loadFilterOptions();
        fetchData();
    </script>
</body>
</html>
