# Setup Integrasi OCR dengan Laravel

## Prerequisites

### 1. Python dari Python.org
Pastikan Python sudah terinstal dari python.org (bukan dari Microsoft Store):
```powershell
python --version
```

### 2. Install Dependencies Python
Masuk ke folder OCR dan install dependencies:
```powershell
cd ..\OCR
pip install -r requirements.txt
```

### 3. Install Tesseract OCR
Download dan install Tesseract OCR:
- Download dari: https://github.com/UB-Mannheim/tesseract/wiki
- Install ke `C:\Program Files\Tesseract-OCR\`
- Tesseract akan otomatis terdeteksi oleh script Python

### 4. Download Tesseract Language Data (Optional)
Untuk bahasa Indonesia:
```powershell
# Download ind.traineddata dari:
# https://github.com/tesseract-ocr/tessdata/raw/main/ind.traineddata
# Letakkan di: C:\Program Files\Tesseract-OCR\tessdata\
```

## Cara Kerja Sistem

### 1. Upload PDF
Ketika user mengupload PDF, sistem akan:
- **Langkah 1**: Memeriksa apakah PDF berisi teks atau gambar
- **Langkah 2**: Jika PDF gambar (text < 50 karakter), tampilkan alert konfirmasi OCR
- **Langkah 3**: Jika user setuju, jalankan OCR Python
- **Langkah 4**: Simpan PDF hasil OCR ke database

### 2. Deteksi PDF Gambar
Route: `POST /search-box/check-pdf`
```php
// Mengembalikan:
{
    "success": true,
    "is_image_pdf": true/false,
    "text_length": 45
}
```

### 3. Proses OCR
Route: `POST /search-box/ocr-pdf`
- Menerima file PDF dan metadata dokumen
- Menjalankan Python OCR script
- Menyimpan hasil OCR ke database
- File hasil OCR dapat dicari dengan kata kunci

## Testing

### 1. Test dengan PDF Teks
Upload PDF yang berisi teks normal → Langsung tersimpan

### 2. Test dengan PDF Gambar (Scan)
Upload PDF hasil scan/gambar → Alert muncul → Pilih "OK" untuk OCR

### 3. Test Manual Python Script
```powershell
cd ..\OCR
python -c "from ocr_pdf import pdf_file_to_searchable_pdf_file; pdf_file_to_searchable_pdf_file('input.pdf', 'output.pdf', dpi=150, lang='ind+eng', quality=85)"
```

## Troubleshooting

### Error: Python not found
Pastikan Python dari python.org sudah terinstal:
```powershell
# Cek versi Python
python --version

# Jika tidak ada, download dari python.org
```

### Error: Tesseract not found
```powershell
# Cek instalasi Tesseract
tesseract --version

# Jika error, install dari:
# https://github.com/UB-Mannheim/tesseract/wiki
```

### Error: Module not found (PyMuPDF, pytesseract, dll)
```powershell
cd ..\OCR
pip install -r requirements.txt
```

### OCR Lambat atau Error
1. **Kurangi DPI**: Edit controller, ubah `dpi=150` menjadi `dpi=100`
2. **Kurangi Kualitas**: Edit controller, ubah `quality=85` menjadi `quality=75`
3. **Cek Memory**: OCR membutuhkan RAM yang cukup untuk PDF besar

### OCR Tidak Akurat
1. **Tingkatkan DPI**: Ubah `dpi=150` menjadi `dpi=300` (lebih lambat tapi lebih akurat)
2. **Tambah Language**: Ubah `lang='ind+eng'` sesuai bahasa dokumen
3. **Cek Kualitas Scan**: Pastikan PDF scan asli cukup jelas

## File yang Dimodifikasi

1. **routes/web.php**
   - Tambah route `check-pdf` dan `ocr-pdf`

2. **app/Http/Controllers/SearchBoxController.php**
   - Method `checkPdf()`: Deteksi PDF gambar
   - Method `ocrPdf()`: Proses OCR dengan Python

3. **resources/views/SearchBox.blade.php**
   - Update form submit untuk deteksi dan konfirmasi OCR

## Konfigurasi OCR

Edit di `SearchBoxController.php` method `ocrPdf()`:

```php
// Bahasa OCR (default: Indonesia + English)
lang='ind+eng'

// DPI (resolusi rendering)
dpi=150  // 100=cepat, 150=normal, 300=detail tinggi

// Kualitas JPEG kompresi
quality=85  // 50-100 (lebih tinggi = lebih besar file)
```

## Performance Tips

1. **Untuk PDF Besar (>10 halaman)**:
   - DPI: 100-150
   - Quality: 75-85

2. **Untuk Akurasi Maksimal**:
   - DPI: 300
   - Quality: 90-95
   - Waktu proses lebih lama

3. **Untuk Speed**:
   - DPI: 100
   - Quality: 70
   - Akurasi mungkin berkurang
