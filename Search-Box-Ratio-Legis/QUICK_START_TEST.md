# 🚀 Quick Start - Test OCR Integration

## Setup Sudah Selesai? ✅

Jika belum, jalankan:
```powershell
# 1. Install Python dependencies
cd "D:\Search Box\OCR"
pip install -r requirements.txt

# 2. Test setup
python test_ocr_setup.py
```

## Testing Upload PDF

### 1. Start Laravel Server
```powershell
cd "D:\Search Box\Search-Box-Ratio-Legis"
php artisan serve
```

### 2. Buka Browser
```
http://localhost:8000/search-box
```

### 3. Test Upload PDF Teks

**File**: `OCR/samples/sample_text_pdf.pdf`

1. Klik **"Input Dokumen"**
2. Isi form:
   - Title: "Test PDF Teks"
   - Type: pilih salah satu
   - Status: pilih salah satu
   - Category: pilih salah satu
   - File: Upload `sample_text_pdf.pdf`
3. Klik **"Simpan Dokumen"**
4. ✅ **Expected**: Langsung tersimpan tanpa alert
5. ✅ **Expected**: Bisa dicari dengan keyword "SAMPLE" atau "testing"

### 4. Test Upload PDF Gambar (OCR)

**File**: `OCR/samples/sample_image_pdf.pdf`

1. Klik **"Input Dokumen"**
2. Isi form:
   - Title: "Test PDF Gambar"
   - Type: pilih salah satu
   - Status: pilih salah satu
   - Category: pilih salah satu
   - File: Upload `sample_image_pdf.pdf`
3. Klik **"Simpan Dokumen"**
4. 🔔 **Expected**: Alert muncul dengan pesan:
   ```
   PDF yang Anda upload adalah PDF gambar (bukan teks).
   
   Apakah ingin convert PDF terlebih dahulu dengan OCR?
   
   Proses OCR akan memakan waktu beberapa menit.
   ```
5. Klik **OK** untuk lanjut OCR
6. ⏳ **Expected**: Button berubah jadi "Menjalankan OCR..."
7. ⏳ Tunggu 1-2 menit (untuk 1 halaman)
8. ✅ **Expected**: Alert sukses "PDF berhasil di-OCR dan disimpan!"
9. ✅ **Expected**: Dokumen muncul di list dan bisa dicari

### 5. Test Cancel OCR

1. Upload PDF gambar lagi
2. Ketika alert muncul, klik **Cancel**
3. ✅ **Expected**: Upload dibatalkan, kembali ke form

## Monitoring

### Laravel Log
```powershell
# Monitor log realtime
cd "D:\Search Box\Search-Box-Ratio-Legis"
Get-Content storage/logs/laravel.log -Wait -Tail 50
```

### Browser Console
1. Buka DevTools (F12)
2. Tab Console
3. Monitor untuk error atau log

## Troubleshooting Tests

### Test Gagal: "Gagal memeriksa PDF"
```powershell
# Check route
cd "D:\Search Box\Search-Box-Ratio-Legis"
php artisan route:list | Select-String "check-pdf"

# Should show:
# POST search-box/check-pdf ... SearchBoxController@checkPdf
```

### Test Gagal: OCR Timeout
```php
// Edit SearchBoxController.php method ocrPdf()
// Kurangi DPI untuk testing:
dpi=100  // was: 150
```

### Test Gagal: Python Error
```powershell
# Test Python manual
cd "D:\Search Box\OCR"
python -c "from ocr_pdf import pdf_file_to_searchable_pdf_file; print('OK')"

# Should print: OK
```

## Expected Results Summary

| Test Case | Expected Behavior |
|-----------|------------------|
| Upload PDF Teks | ✅ Langsung tersimpan |
| Upload PDF Gambar + OK | ✅ OCR berjalan → Tersimpan |
| Upload PDF Gambar + Cancel | ✅ Upload dibatalkan |
| Search OCR'd PDF | ✅ Bisa dicari dengan keyword |

## Next Steps

Setelah semua test PASS:
1. ✅ System ready untuk production use
2. 📝 Train user cara menggunakan
3. 🔧 Tune OCR settings sesuai kebutuhan (DPI, quality, lang)
4. 📊 Monitor performance dan error log

---

**Selamat! Integrasi OCR berhasil! 🎉**
