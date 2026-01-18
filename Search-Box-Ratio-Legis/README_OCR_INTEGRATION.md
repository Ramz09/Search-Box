# 🎉 Integrasi OCR Python dengan Laravel - BERHASIL!

## ✅ Status Setup

Semua test integration **PASSED**! Sistem siap digunakan.

### Yang Sudah Terinstall:
- ✓ Python 3.14.2 (dari python.org)
- ✓ Dependencies Python (PyMuPDF, pytesseract, Pillow, PyPDF2, Flask)
- ✓ Tesseract OCR 5.5.0
- ✓ Bahasa Inggris (eng) untuk OCR
- ⚠ Bahasa Indonesia (ind) - *belum terinstall (optional)*

---

## 🚀 Cara Menggunakan

### 1. Upload PDF Teks (Biasa)
1. Buka browser: http://localhost:8000/search-box
2. Klik tombol **"Input Dokumen"**
3. Isi form dan pilih PDF yang berisi teks
4. Klik **"Simpan Dokumen"**
5. ✓ PDF langsung disimpan dan bisa dicari

### 2. Upload PDF Gambar/Scan
1. Buka browser: http://localhost:8000/search-box
2. Klik tombol **"Input Dokumen"**
3. Isi form dan pilih PDF hasil scan/gambar
4. Klik **"Simpan Dokumen"**
5. 🔍 Sistem mendeteksi PDF adalah gambar
6. 💬 Alert muncul: **"Apakah ingin convert PDF terlebih dahulu?"**
   - **Klik OK** → OCR berjalan (3-5 menit per 10 halaman)
   - **Klik Cancel** → Kembali ke form (upload dibatalkan)
7. ✓ Setelah OCR selesai, PDF tersimpan dan bisa dicari!

---

## 📋 Fitur yang Ditambahkan

### Backend (Laravel)

#### 1. Route Baru
- `POST /search-box/check-pdf` - Cek apakah PDF adalah gambar/teks
- `POST /search-box/ocr-pdf` - Jalankan OCR pada PDF gambar

#### 2. Method Baru di SearchBoxController

**`checkPdf(Request $request)`**
```php
// Mendeteksi apakah PDF berisi teks atau gambar
// Return: { is_image_pdf: true/false, text_length: 45 }
```

**`ocrPdf(Request $request)`**
```php
// Menjalankan OCR Python pada PDF gambar
// Proses:
// 1. Simpan file temporary
// 2. Panggil Python OCR script
// 3. Extract content dari PDF hasil OCR
// 4. Simpan ke database
// 5. Cleanup temporary files
```

### Frontend (Blade)

#### Form Submit Handler
- **Step 1**: Validasi form input
- **Step 2**: Kirim PDF ke `/check-pdf` untuk deteksi
- **Step 3**: Jika image PDF → Tampilkan alert konfirmasi
  - User pilih **Yes** → Kirim ke `/ocr-pdf`
  - User pilih **No** → Cancel upload
- **Step 4**: Jika text PDF → Kirim ke `/upload` (normal)

---

## 🔧 Konfigurasi OCR

File: `app/Http/Controllers/SearchBoxController.php` method `ocrPdf()`

```php
// Bahasa OCR
lang='ind+eng'  // Indonesia + Inggris (butuh install ind.traineddata)
lang='eng'      // Hanya Inggris (default)

// DPI - Resolusi rendering PDF
dpi=100   // Cepat tapi kurang akurat
dpi=150   // RECOMMENDED - Balance speed & quality
dpi=300   // Lambat tapi sangat akurat

// Kualitas JPEG
quality=70   // File kecil tapi kurang jelas
quality=85   // RECOMMENDED
quality=95   // File besar tapi sangat jelas
```

### Rekomendasi Berdasarkan Kebutuhan

| Use Case | DPI | Quality | Lang | Est. Time (10 hal) |
|----------|-----|---------|------|---------------------|
| **Cepat** | 100 | 75 | eng | 2-3 menit |
| **Balance** ⭐ | 150 | 85 | ind+eng | 3-5 menit |
| **Akurat** | 300 | 90 | ind+eng | 8-12 menit |

---

## 🧪 Testing

### 1. Test Setup
```powershell
# Test Python OCR setup
cd "D:\Search Box\OCR"
python test_ocr_setup.py

# Test Laravel integration
cd "D:\Search Box\Search-Box-Ratio-Legis"
php test_laravel_ocr_integration.php
```

### 2. Test dengan PDF Real

**Buat sample PDF gambar untuk testing:**
```powershell
cd "D:\Search Box\OCR"
# Upload PDF scan/screenshot ke folder samples/
```

---

## 📦 Optional: Install Bahasa Indonesia

Untuk hasil OCR lebih baik pada dokumen Indonesia:

### Cara 1: Download Manual
1. Download: https://github.com/tesseract-ocr/tessdata/raw/main/ind.traineddata
2. Copy ke: `C:\Program Files\Tesseract-OCR\tessdata\`
3. Restart aplikasi

### Cara 2: PowerShell (butuh Admin)
```powershell
# Run as Administrator
cd "D:\Search Box\OCR"
.\download_indonesian_lang.ps1
```

### Verifikasi
```powershell
tesseract --list-langs
# Harus ada: eng, ind, osd
```

---

## 🐛 Troubleshooting

### PDF Upload Stuck di "Menjalankan OCR..."
**Penyebab**: OCR membutuhkan waktu lama untuk PDF besar

**Solusi**:
1. Tunggu sampai selesai (bisa 5-10 menit)
2. Atau kurangi DPI di controller (150 → 100)
3. Check browser console untuk error

### Error: "Gagal menjalankan proses OCR"
**Penyebab**: Python tidak bisa dipanggil dari Laravel

**Solusi**:
```powershell
# Test manual Python
python --version

# Test import module
cd "D:\Search Box\OCR"
python -c "from ocr_pdf import pdf_file_to_searchable_pdf_file; print('OK')"

# Jika error, reinstall dependencies
pip install -r requirements.txt
```

### PDF Hasil OCR Tidak Bisa Dicari
**Penyebab**: OCR gagal extract teks

**Solusi**:
1. Cek kualitas scan asli (harus cukup jelas)
2. Tingkatkan DPI (150 → 300)
3. Install bahasa Indonesia jika dokumen Indonesia

### Memory Error saat OCR
**Penyebab**: PDF terlalu besar

**Solusi**:
1. Kurangi DPI (150 → 100)
2. Kurangi quality (85 → 70)
3. Split PDF besar jadi beberapa file kecil

---

## 📁 File yang Ditambahkan/Dimodifikasi

### Modified:
1. `routes/web.php` - Tambah route check-pdf dan ocr-pdf
2. `app/Http/Controllers/SearchBoxController.php` - Tambah method checkPdf() dan ocrPdf()
3. `resources/views/SearchBox.blade.php` - Update form submit handler

### New Files:
1. `OCR_INTEGRATION_SETUP.md` - Dokumentasi setup
2. `test_laravel_ocr_integration.php` - Test integration script
3. `OCR/test_ocr_setup.py` - Test Python OCR setup
4. `OCR/download_indonesian_lang.ps1` - Helper download bahasa Indonesia
5. `OCR/INSTALL_INDONESIAN_LANGUAGE.md` - Panduan install bahasa

---

## 🎯 Next Steps

1. ✅ Test upload PDF teks → Harus langsung tersimpan
2. ✅ Test upload PDF gambar → Alert muncul → OCR berjalan
3. ⚠ Optional: Install bahasa Indonesia untuk akurasi lebih baik
4. 🚀 Deploy ke production (jika diperlukan)

---

## 📞 Support

Jika ada masalah:
1. Cek error di browser console (F12)
2. Cek Laravel log: `storage/logs/laravel.log`
3. Test Python OCR manual: `cd OCR && python test_ocr_setup.py`
4. Test Laravel integration: `php test_laravel_ocr_integration.php`

---

**Happy Coding! 🚀**
