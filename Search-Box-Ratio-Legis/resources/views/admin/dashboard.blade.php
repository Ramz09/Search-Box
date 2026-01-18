<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Admin Dashboard - Peraturan</title>

    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@400;500;600;700&family=Playfair+Display:wght@600;700&display=swap" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-gradient-to-br from-sky-50 via-white to-emerald-50 text-slate-900" style="font-family: 'Space Grotesk', 'Instrument Sans', ui-sans-serif, system-ui, sans-serif;">
    <div class="absolute inset-0 pointer-events-none">
        <div class="absolute -left-14 top-10 h-56 w-56 rounded-full bg-sky-200 blur-3xl opacity-70"></div>
        <div class="absolute right-8 top-24 h-64 w-64 rounded-full bg-emerald-200 blur-3xl opacity-70"></div>
        <div class="absolute left-40 bottom-12 h-56 w-56 rounded-full bg-indigo-200 blur-3xl opacity-60"></div>
    </div>

    <div class="relative z-10 px-4 py-8 md:px-10 lg:px-16 xl:px-24">
        <header class="flex flex-col gap-6 md:flex-row md:items-center md:justify-between">
            <div>
                <p class="text-xs uppercase tracking-[0.35em] text-sky-600">Kementerian Perdagangan</p>
                <h1 class="mt-2 text-4xl font-bold text-slate-900 md:text-5xl" style="font-family: 'Playfair Display', serif;">Dashboard Admin</h1>
                <p class="mt-3 max-w-2xl text-sm text-slate-600 md:text-base">Pantau seluruh peraturan yang tersimpan</p>
            </div>
            <div class="flex flex-wrap gap-2 text-xs text-slate-700">
                <span class="rounded-full border border-emerald-200 bg-emerald-50 px-3 py-1 font-semibold text-emerald-700 shadow-sm">Mode Admin</span>
                <span class="rounded-full border border-sky-200 bg-white px-3 py-1 font-semibold text-sky-800 shadow-sm">Peraturan</span>
            </div>
        </header>

        <section class="mt-8 grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
            <article class="rounded-2xl border border-white/80 bg-white/90 p-4 shadow-xl shadow-sky-100/80">
                <p class="text-xs uppercase tracking-wide text-slate-500">Total Dokumen</p>
                <div class="mt-2 flex items-baseline gap-2">
                    <span class="text-3xl font-semibold text-slate-900">{{ $stats['total'] }}</span>
                    <span class="text-xs text-emerald-600">• live</span>
                </div>
                <p class="mt-1 text-xs text-slate-500">Semua peraturan yang dimuat</p>
            </article>
            <article class="rounded-2xl border border-sky-100 bg-gradient-to-br from-sky-100 via-white to-indigo-50 p-4 shadow-xl shadow-sky-100">
                <p class="text-xs uppercase tracking-wide text-slate-600">Kebijakan Aktif</p>
                <div class="mt-2 flex items-baseline gap-2">
                    <span class="text-3xl font-semibold text-slate-900">{{ $stats['active'] }}</span>
                    <span class="text-xs text-sky-700">aktif</span>
                </div>
                <p class="mt-1 text-xs text-slate-500">Status: aktif</p>
            </article>
            <article class="rounded-2xl border border-amber-100 bg-gradient-to-br from-amber-50 via-white to-orange-50 p-4 shadow-xl shadow-amber-100">
                <p class="text-xs uppercase tracking-wide text-slate-600">Jenis Regulasi</p>
                <div class="mt-2 text-2xl font-semibold text-slate-900">{{ $stats['types'] }} jenis</div>
                <p class="mt-1 text-xs text-slate-500">Ragam format dokumen</p>
            </article>
            <article class="rounded-2xl border border-emerald-100 bg-gradient-to-br from-emerald-50 via-white to-teal-50 p-4 shadow-xl shadow-emerald-100">
                <p class="text-xs uppercase tracking-wide text-slate-600">Tautan Publik</p>
                <div class="mt-2 flex items-baseline gap-2">
                    <span class="text-3xl font-semibold text-slate-900">{{ $stats['open'] }}</span>
                    <span class="text-xs text-emerald-700">bisa dibuka</span>
                </div>
                <p class="mt-1 text-xs text-slate-500">Tersedia link eksternal</p>
            </article>
        </section>

        <section class="mt-10">
            <div class="rounded-3xl border border-white/80 bg-white/95 p-6 shadow-2xl shadow-slate-100">
                <h2 class="text-2xl font-bold text-slate-900 mb-4" style="font-family: 'Playfair Display', serif;">Kelola Dropdown</h2>
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8">
                    <button onclick="openAddTypeModal()" class="rounded-lg bg-blue-500 px-6 py-3 text-sm font-semibold text-white hover:bg-blue-600 transition">+ Tambah Jenis Dokumen</button>
                    <button onclick="openAddStatusModal()" class="rounded-lg bg-emerald-500 px-6 py-3 text-sm font-semibold text-white hover:bg-emerald-600 transition">+ Tambah Status</button>
                    <button onclick="openAddCategoryModal()" class="rounded-lg bg-purple-500 px-6 py-3 text-sm font-semibold text-white hover:bg-purple-600 transition">+ Tambah Kategori</button>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <!-- Jenis Dokumen -->
                    <div class="rounded-lg border border-slate-200 p-4">
                        <h3 class="text-sm font-semibold text-slate-900 mb-3">Jenis Dokumen</h3>
                        <div id="types-list" class="space-y-2 max-h-64 overflow-y-auto">
                            <p class="text-xs text-slate-500">Loading...</p>
                        </div>
                    </div>

                    <!-- Status -->
                    <div class="rounded-lg border border-slate-200 p-4">
                        <h3 class="text-sm font-semibold text-slate-900 mb-3">Status</h3>
                        <div id="statuses-list" class="space-y-2 max-h-64 overflow-y-auto">
                            <p class="text-xs text-slate-500">Loading...</p>
                        </div>
                    </div>

                    <!-- Kategori -->
                    <div class="rounded-lg border border-slate-200 p-4">
                        <h3 class="text-sm font-semibold text-slate-900 mb-3">Kategori</h3>
                        <div id="categories-list" class="space-y-2 max-h-64 overflow-y-auto">
                            <p class="text-xs text-slate-500">Loading...</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="mt-10">
            <div class="rounded-3xl border border-white/80 bg-white/95 p-6 shadow-2xl shadow-slate-100">
                <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
                    <div class="flex items-center gap-2 text-xs text-slate-600">
                        <span class="h-2 w-2 rounded-full bg-emerald-500"></span>
                        <span>Data realtime</span>
                    </div>
                </div>

                <div class="mt-6 overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-2xl shadow-slate-100">
                    <div class="hidden md:grid grid-cols-[0.4fr_1fr_1.4fr_1.1fr_1fr_2fr_1.2fr] gap-4 bg-slate-50 px-4 py-3 text-xs font-semibold uppercase tracking-wide text-slate-700">
                        <span class="text-center">No</span>
                        <span class="text-left">Tanggal</span>
                        <span class="text-left">Judul</span>
                        <span class="text-left">Jenis Dokumen</span>
                        <span class="text-left">Kategori</span>
                        <span class="text-left">Deskripsi</span>
                        <span class="text-center">Aksi</span>
                    </div>
                    <div class="divide-y divide-slate-200" id="admin-table-body">
                        @forelse ($documents as $index => $doc)
                            <div class="grid grid-cols-1 gap-4 px-4 py-4 md:grid-cols-[0.4fr_1fr_1.4fr_1.1fr_1fr_2fr_1.2fr] md:items-center hover:bg-slate-50 transition">
                                <div class="text-sm font-semibold text-slate-800 text-center">{{ $index + 1 }}</div>
                                <div class="flex items-center gap-2 text-sm text-slate-700">
                                    <span class="rounded-full bg-emerald-100 px-2 py-1 text-[11px] uppercase tracking-wide text-emerald-700">{{ $doc['status'] ?? '-' }}</span>
                                    <span>{{ $doc['date'] ?? '-' }}</span>
                                </div>
                                <div class="text-sm font-semibold text-slate-900">{{ $doc['title'] }}</div>
                                <div class="text-sm text-slate-800">{{ $doc['type'] ?? '-' }}</div>
                                <div class="text-sm text-slate-800">{{ $doc['category'] ?? '-' }}</div>
                                <div class="text-sm text-slate-700">{{ $doc['description'] ?? '-' }}</div>
                                <div class="flex flex-wrap items-center justify-center gap-1">
                                    <button onclick="openEditModal({{ $doc['id'] }}, '{{ addslashes($doc['title']) }}', '{{ addslashes($doc['description']) }}', '{{ $doc['type'] }}', '{{ $doc['status'] }}', '{{ $doc['category'] }}')" class="inline-flex items-center rounded-lg bg-blue-500 px-2 py-1 text-xs font-semibold text-white hover:bg-blue-600">Edit</button>
                                    <button onclick="confirmDelete({{ $doc['id'] }}, '{{ addslashes($doc['title']) }}')" class="inline-flex items-center rounded-lg bg-red-500 px-2 py-1 text-xs font-semibold text-white hover:bg-red-600">Hapus</button>
                                </div>
                            </div>
                        @empty
                            <div class="px-4 py-6 text-center text-sm text-slate-500">Belum ada peraturan tersimpan.</div>
                        @endforelse
                    </div>
                </div>
            </div>
        </section>
    </div>

    <!-- Modal Edit Dokumen -->
    <div id="modal-edit-dokumen" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm p-4">
        <div class="relative w-full max-w-2xl rounded-3xl border border-white/80 bg-white/95 shadow-2xl shadow-slate-200">
            <div class="flex items-center justify-between border-b border-slate-200 px-6 py-4">
                <div>
                    <h2 class="text-xl font-bold text-slate-900" style="font-family: 'Playfair Display', serif;">Edit Dokumen</h2>
                    <p class="mt-1 text-xs text-slate-600">Ubah informasi dokumen peraturan</p>
                </div>
                <button onclick="closeEditModal()" class="text-2xl text-slate-400 hover:text-slate-600">&times;</button>
            </div>
            
            <form id="edit-form" class="space-y-4 p-6">
                <input type="hidden" id="edit-doc-id" name="id">
                
                <div>
                    <label class="block text-sm font-semibold text-slate-700">Judul <span class="text-red-500">*</span></label>
                    <input type="text" id="edit-title" name="title" required class="mt-1 w-full rounded-lg border border-slate-300 px-4 py-2 text-sm focus:border-blue-500 focus:outline-none">
                </div>

                <div>
                    <label class="block text-sm font-semibold text-slate-700">Deskripsi</label>
                    <textarea id="edit-description" name="description" rows="3" class="mt-1 w-full rounded-lg border border-slate-300 px-4 py-2 text-sm focus:border-blue-500 focus:outline-none"></textarea>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-slate-700">Jenis Dokumen <span class="text-red-500">*</span></label>
                        <select id="edit-type" name="type" required class="mt-1 w-full rounded-lg border border-slate-300 px-4 py-2 text-sm focus:border-blue-500 focus:outline-none">
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-slate-700">Status <span class="text-red-500">*</span></label>
                        <select id="edit-status" name="status" required class="mt-1 w-full rounded-lg border border-slate-300 px-4 py-2 text-sm focus:border-blue-500 focus:outline-none">
                        </select>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-slate-700">Kategori <span class="text-red-500">*</span></label>
                    <select id="edit-category" name="category" required class="mt-1 w-full rounded-lg border border-slate-300 px-4 py-2 text-sm focus:border-blue-500 focus:outline-none">
                    </select>
                </div>

                <div class="flex gap-3 pt-4">
                    <button type="submit" class="flex-1 rounded-lg bg-blue-500 px-4 py-2 text-sm font-semibold text-white hover:bg-blue-600">Simpan Perubahan</button>
                    <button type="button" onclick="closeEditModal()" class="flex-1 rounded-lg border border-slate-300 px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50">Batal</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Konfirmasi Delete -->
    <div id="modal-confirm-delete" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm p-4">
        <div class="relative w-full max-w-sm rounded-3xl border border-white/80 bg-white/95 shadow-2xl shadow-slate-200 p-6">
            <h2 class="text-lg font-bold text-slate-900">Hapus Dokumen?</h2>
            <p id="delete-title" class="mt-2 text-sm text-slate-600"></p>
            <p class="mt-1 text-xs text-slate-500">Tindakan ini tidak dapat dibatalkan.</p>
            
            <div class="mt-6 flex gap-3">
                <button id="btn-confirm-delete" class="flex-1 rounded-lg bg-red-500 px-4 py-2 text-sm font-semibold text-white hover:bg-red-600">Hapus</button>
                <button onclick="closeDeleteModal()" class="flex-1 rounded-lg border border-slate-300 px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50">Batal</button>
            </div>
        </div>
    </div>

    <!-- Modal Konfirmasi Delete Tag -->
    <div id="modal-confirm-delete-tag" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm p-4">
        <div class="relative w-full max-w-md rounded-3xl border border-white/80 bg-white shadow-2xl shadow-slate-200 animate-scale-in">
            <!-- Modal Header -->
            <div class="px-6 py-5 text-center border-b border-slate-100">
                <div class="mx-auto mb-3 flex h-14 w-14 items-center justify-center rounded-full bg-amber-100">
                    <svg class="h-8 w-8 text-amber-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z"></path>
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-slate-900" style="font-family: 'Playfair Display', serif;">Konfirmasi Hapus</h3>
            </div>

            <!-- Modal Body -->
            <div class="px-6 py-5">
                <p id="delete-tag-message" class="text-center text-slate-600"></p>
                <div class="mt-4 rounded-xl border border-amber-100 bg-amber-50 p-3">
                    <p class="text-xs text-amber-800">
                        <span class="font-semibold">⚠️ Peringatan:</span> Tindakan ini tidak dapat dibatalkan.
                    </p>
                </div>
            </div>

            <!-- Modal Footer -->
            <div class="flex items-center justify-end gap-3 border-t border-slate-200 px-6 py-4 bg-slate-50">
                <button id="btn-cancel-delete-tag" class="rounded-xl border border-slate-300 bg-white px-5 py-2.5 text-sm font-semibold text-slate-700 hover:bg-slate-100 transition">
                    Batal
                </button>
                <button id="btn-confirm-delete-tag" class="rounded-xl bg-red-500 px-5 py-2.5 text-sm font-semibold text-white shadow-lg shadow-red-200 hover:bg-red-600 transition">
                    Ya, Hapus
                </button>
            </div>
        </div>
    </div>

    <!-- Modal Tambah Jenis Dokumen -->
    <div id="modal-add-type" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm p-4">
        <div class="relative w-full max-w-sm rounded-3xl border border-white/80 bg-white/95 shadow-2xl shadow-slate-200">
            <div class="flex items-center justify-between border-b border-slate-200 px-6 py-4">
                <h2 class="text-lg font-bold text-slate-900">Tambah Jenis Dokumen</h2>
                <button onclick="closeAddTypeModal()" class="text-2xl text-slate-400 hover:text-slate-600">&times;</button>
            </div>
            <form id="add-type-form" class="space-y-4 p-6">
                <input type="text" id="add-type-value" placeholder="Nama Jenis Dokumen" required class="w-full rounded-lg border border-slate-300 px-4 py-2 text-sm focus:border-blue-500 focus:outline-none">
                <div class="flex gap-3">
                    <button type="submit" class="flex-1 rounded-lg bg-blue-500 px-4 py-2 text-sm font-semibold text-white hover:bg-blue-600">Tambah</button>
                    <button type="button" onclick="closeAddTypeModal()" class="flex-1 rounded-lg border border-slate-300 px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50">Batal</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Tambah Status -->
    <div id="modal-add-status" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm p-4">
        <div class="relative w-full max-w-sm rounded-3xl border border-white/80 bg-white/95 shadow-2xl shadow-slate-200">
            <div class="flex items-center justify-between border-b border-slate-200 px-6 py-4">
                <h2 class="text-lg font-bold text-slate-900">Tambah Status</h2>
                <button onclick="closeAddStatusModal()" class="text-2xl text-slate-400 hover:text-slate-600">&times;</button>
            </div>
            <form id="add-status-form" class="space-y-4 p-6">
                <input type="text" id="add-status-value" placeholder="Nama Status" required class="w-full rounded-lg border border-slate-300 px-4 py-2 text-sm focus:border-blue-500 focus:outline-none">
                <div class="flex gap-3">
                    <button type="submit" class="flex-1 rounded-lg bg-blue-500 px-4 py-2 text-sm font-semibold text-white hover:bg-blue-600">Tambah</button>
                    <button type="button" onclick="closeAddStatusModal()" class="flex-1 rounded-lg border border-slate-300 px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50">Batal</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Tambah Kategori -->
    <div id="modal-add-category" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm p-4">
        <div class="relative w-full max-w-sm rounded-3xl border border-white/80 bg-white/95 shadow-2xl shadow-slate-200">
            <div class="flex items-center justify-between border-b border-slate-200 px-6 py-4">
                <h2 class="text-lg font-bold text-slate-900">Tambah Kategori</h2>
                <button onclick="closeAddCategoryModal()" class="text-2xl text-slate-400 hover:text-slate-600">&times;</button>
            </div>
            <form id="add-category-form" class="space-y-4 p-6">
                <input type="text" id="add-category-value" placeholder="Nama Kategori" required class="w-full rounded-lg border border-slate-300 px-4 py-2 text-sm focus:border-blue-500 focus:outline-none">
                <div class="flex gap-3">
                    <button type="submit" class="flex-1 rounded-lg bg-blue-500 px-4 py-2 text-sm font-semibold text-white hover:bg-blue-600">Tambah</button>
                    <button type="button" onclick="closeAddCategoryModal()" class="flex-1 rounded-lg border border-slate-300 px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50">Batal</button>
                </div>
            </form>
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
        @keyframes scale-in {
            0% { transform: scale(0.95); opacity: 0; }
            100% { transform: scale(1); opacity: 1; }
        }
        .animate-scale-in { animation: scale-in 0.2s ease-out; }
    </style>

    <script>
        let currentDeleteId = null;
        let filterOptions = { types: [], statuses: [], categories: [] };

        // Show Notification Modal
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

        // Render lists
        function renderLists() {
            const typesList = document.getElementById('types-list');
            const statusesList = document.getElementById('statuses-list');
            const categoriesList = document.getElementById('categories-list');

            typesList.innerHTML = filterOptions.types.length > 0 
                ? filterOptions.types.map(t => `<div class="flex justify-between items-center text-sm bg-blue-50 px-3 py-2 rounded"><span>${t}</span><button onclick="deleteItem('type', '${t}')" class="text-red-500 hover:text-red-700 text-xs">✕</button></div>`).join('')
                : '<p class="text-xs text-slate-500">Belum ada</p>';

            statusesList.innerHTML = filterOptions.statuses.length > 0
                ? filterOptions.statuses.map(s => `<div class="flex justify-between items-center text-sm bg-emerald-50 px-3 py-2 rounded"><span>${s}</span><button onclick="deleteItem('status', '${s}')" class="text-red-500 hover:text-red-700 text-xs">✕</button></div>`).join('')
                : '<p class="text-xs text-slate-500">Belum ada</p>';

            categoriesList.innerHTML = filterOptions.categories.length > 0
                ? filterOptions.categories.map(c => `<div class="flex justify-between items-center text-sm bg-purple-50 px-3 py-2 rounded"><span>${c}</span><button onclick="deleteItem('category', '${c}')" class="text-red-500 hover:text-red-700 text-xs">✕</button></div>`).join('')
                : '<p class="text-xs text-slate-500">Belum ada</p>';
        }

        function openAddTypeModal() {
            document.getElementById('modal-add-type').classList.remove('hidden');
            document.getElementById('add-type-value').focus();
        }

        function closeAddTypeModal() {
            document.getElementById('modal-add-type').classList.add('hidden');
            document.getElementById('add-type-value').value = '';
        }

        function openAddStatusModal() {
            document.getElementById('modal-add-status').classList.remove('hidden');
            document.getElementById('add-status-value').focus();
        }

        function closeAddStatusModal() {
            document.getElementById('modal-add-status').classList.add('hidden');
            document.getElementById('add-status-value').value = '';
        }

        function openAddCategoryModal() {
            document.getElementById('modal-add-category').classList.remove('hidden');
            document.getElementById('add-category-value').focus();
        }

        function closeAddCategoryModal() {
            document.getElementById('modal-add-category').classList.add('hidden');
            document.getElementById('add-category-value').value = '';
        }

        // Load filter options
        async function loadFilterOptions() {
            try {
                const res = await fetch('/admin/options');
                const data = await res.json();
                filterOptions = data;
                
                const typeSelect = document.getElementById('edit-type');
                const statusSelect = document.getElementById('edit-status');
                const categorySelect = document.getElementById('edit-category');
                
                typeSelect.innerHTML = '';
                statusSelect.innerHTML = '';
                categorySelect.innerHTML = '';
                
                data.types.forEach(type => {
                    typeSelect.innerHTML += `<option value="${type}">${type}</option>`;
                });
                
                data.statuses.forEach(status => {
                    statusSelect.innerHTML += `<option value="${status}">${status}</option>`;
                });

                data.categories.forEach(category => {
                    categorySelect.innerHTML += `<option value="${category}">${category}</option>`;
                });

                renderLists();
            } catch (err) {
                console.error('Error loading options:', err);
            }
        }

        function openEditModal(id, title, description, type, status, category) {
            document.getElementById('edit-doc-id').value = id;
            document.getElementById('edit-title').value = title;
            document.getElementById('edit-description').value = description;
            document.getElementById('edit-type').value = type;
            document.getElementById('edit-status').value = status;
            document.getElementById('edit-category').value = category;
            document.getElementById('modal-edit-dokumen').classList.remove('hidden');
        }

        function closeEditModal() {
            document.getElementById('modal-edit-dokumen').classList.add('hidden');
        }

        function confirmDelete(id, title) {
            currentDeleteId = id;
            document.getElementById('delete-title').textContent = `Anda akan menghapus: "${title}"`;
            document.getElementById('modal-confirm-delete').classList.remove('hidden');
        }

        function closeDeleteModal() {
            document.getElementById('modal-confirm-delete').classList.add('hidden');
            currentDeleteId = null;
        }

        // Handle Edit Form Submit
        document.getElementById('edit-form').addEventListener('submit', async (e) => {
            e.preventDefault();
            const docId = document.getElementById('edit-doc-id').value;
            const submitBtn = document.querySelector('#edit-form button[type="submit"]');
            const originalText = submitBtn.textContent;
            
            submitBtn.disabled = true;
            submitBtn.textContent = 'Menyimpan...';
            
            // Get form values
            const formData = {
                title: document.getElementById('edit-title').value,
                description: document.getElementById('edit-description').value,
                type: document.getElementById('edit-type').value,
                status: document.getElementById('edit-status').value,
                category: document.getElementById('edit-category').value,
            };

            try {
                const res = await fetch(`/documents/${docId}`, {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify(formData)
                });

                const data = await res.json();
                if (data.success) {
                    closeEditModal();
                    showNotification('Dokumen berhasil diubah!', 'success');
                    setTimeout(() => location.reload(), 1000);
                } else {
                    submitBtn.disabled = false;
                    submitBtn.textContent = originalText;
                    showNotification('Error: ' + (data.message || 'Gagal mengubah dokumen'), 'error');
                }
            } catch (err) {
                console.error('Error:', err);
                submitBtn.disabled = false;
                submitBtn.textContent = originalText;
                showNotification('Gagal mengubah dokumen: ' + err.message, 'error');
            }
        });

        // Handle Delete Confirm
        document.getElementById('btn-confirm-delete').addEventListener('click', async () => {
            const btn = document.getElementById('btn-confirm-delete');
            btn.disabled = true;
            btn.textContent = 'Menghapus...';
            
            try {
                const res = await fetch(`/documents/${currentDeleteId}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
                        'Accept': 'application/json',
                    }
                });

                const data = await res.json();
                if (data.success) {
                    closeDeleteModal();
                    showNotification('Dokumen berhasil dihapus!', 'success');
                    setTimeout(() => location.reload(), 1000);
                } else {
                    btn.disabled = false;
                    btn.textContent = 'Hapus';
                    showNotification('Error: ' + (data.message || 'Gagal menghapus dokumen'), 'error');
                }
            } catch (err) {
                console.error('Error:', err);
                btn.disabled = false;
                btn.textContent = 'Hapus';
                showNotification('Gagal menghapus dokumen: ' + err.message, 'error');
            }
        });

        // Handle Add Type
        document.getElementById('add-type-form').addEventListener('submit', async (e) => {
            e.preventDefault();
            const value = document.getElementById('add-type-value').value.trim();
            if (!value) return;
            
            try {
                const res = await fetch('/admin/add-type', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
                    },
                    body: JSON.stringify({ type: value })
                });
                
                const data = await res.json();
                if (data.success) {
                    closeAddTypeModal();
                    showNotification('Tipe dokumen berhasil ditambahkan!', 'success');
                    loadFilterOptions();
                } else {
                    showNotification('Error: ' + data.message, 'error');
                }
            } catch (err) {
                showNotification('Error: ' + err.message, 'error');
            }
        });

        // Handle Add Status
        document.getElementById('add-status-form').addEventListener('submit', async (e) => {
            e.preventDefault();
            const value = document.getElementById('add-status-value').value.trim();
            if (!value) return;
            
            try {
                const res = await fetch('/admin/add-status', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
                    },
                    body: JSON.stringify({ status: value })
                });
                
                const data = await res.json();
                if (data.success) {
                    closeAddStatusModal();
                    showNotification('Status dokumen berhasil ditambahkan!', 'success');
                    loadFilterOptions();
                } else {
                    showNotification('Error: ' + data.message, 'error');
                }
            } catch (err) {
                showNotification('Error: ' + err.message, 'error');
            }
        });

        // Handle Add Category
        document.getElementById('add-category-form').addEventListener('submit', async (e) => {
            e.preventDefault();
            const value = document.getElementById('add-category-value').value.trim();
            if (!value) return;
            
            try {
                const res = await fetch('/admin/add-category', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ category: value })
                });
                
                const text = await res.text();
                let data;
                try {
                    data = JSON.parse(text);
                } catch (parseErr) {
                    console.error('Response is not JSON:', text);
                    showNotification('Server error: Invalid response format. Check browser console for details.', 'error');
                    return;
                }
                
                if (data.success) {
                    closeAddCategoryModal();
                    showNotification('Kategori dokumen berhasil ditambahkan!', 'success');
                    loadFilterOptions();
                } else {
                    showNotification('Error: ' + data.message, 'error');
                }
            } catch (err) {
                console.error('Fetch error:', err);
                showNotification('Error: ' + err.message, 'error');
            }
        });

        // Delete Item - Show confirmation modal
        function deleteItem(type, value) {
            const modal = document.getElementById('modal-confirm-delete-tag');
            const message = document.getElementById('delete-tag-message');
            const btnConfirm = document.getElementById('btn-confirm-delete-tag');
            const btnCancel = document.getElementById('btn-cancel-delete-tag');
            
            const typeName = type === 'type' ? 'Tipe Dokumen' : type === 'status' ? 'Status Dokumen' : 'Kategori Dokumen';
            message.textContent = `Apakah Anda yakin ingin menghapus ${typeName}: "${value}"?`;
            
            // Show modal
            modal.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
            
            // Handle confirm
            const handleConfirm = async () => {
                btnConfirm.disabled = true;
                btnConfirm.textContent = 'Menghapus...';
                
                try {
                    const endpoint = type === 'type' ? '/admin/delete-type' : 
                                    type === 'status' ? '/admin/delete-status' : 
                                    '/admin/delete-category';
                    
                    const res = await fetch(endpoint, {
                        method: 'DELETE',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
                        },
                        body: JSON.stringify({ [type]: value })
                    });
                    
                    const data = await res.json();
                    
                    // Close modal
                    modal.classList.add('hidden');
                    document.body.style.overflow = '';
                    
                    if (data.success) {
                        const itemName = type === 'type' ? 'Tipe' : type === 'status' ? 'Status' : 'Kategori';
                        showNotification(`${itemName} berhasil dihapus!`, 'success');
                        loadFilterOptions();
                    } else {
                        showNotification('Error: ' + data.message, 'error');
                    }
                } catch (err) {
                    modal.classList.add('hidden');
                    document.body.style.overflow = '';
                    showNotification('Error: ' + err.message, 'error');
                } finally {
                    btnConfirm.disabled = false;
                    btnConfirm.textContent = 'Ya, Hapus';
                    cleanup();
                }
            };
            
            // Handle cancel
            const handleCancel = () => {
                modal.classList.add('hidden');
                document.body.style.overflow = '';
                cleanup();
            };
            
            // Cleanup listeners
            const cleanup = () => {
                btnConfirm.removeEventListener('click', handleConfirm);
                btnCancel.removeEventListener('click', handleCancel);
            };
            
            // Add listeners
            btnConfirm.addEventListener('click', handleConfirm);
            btnCancel.addEventListener('click', handleCancel);
        }

        // Initialize
        loadFilterOptions();
    </script>
</body>
</html>
