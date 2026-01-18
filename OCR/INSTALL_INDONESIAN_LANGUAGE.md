# Instalasi Bahasa Indonesia untuk Tesseract OCR

## Cara Install (Manual)

### Opsi 1: Download Manual
1. Download file `ind.traineddata` dari:
   https://github.com/tesseract-ocr/tessdata/raw/main/ind.traineddata

2. Copy file ke folder Tesseract:
   - Windows: `C:\Program Files\Tesseract-OCR\tessdata\`
   - Atau: `C:\Program Files (x86)\Tesseract-OCR\tessdata\`

3. Restart terminal/aplikasi

### Opsi 2: PowerShell dengan Admin
```powershell
# Jalankan PowerShell sebagai Administrator
# Kemudian jalankan:
cd "D:\Search Box\OCR"
.\download_indonesian_lang.ps1
```

## Verifikasi Instalasi

Setelah install, cek apakah bahasa Indonesia tersedia:

```powershell
tesseract --list-langs
```

Harusnya ada output:
```
List of available languages (3):
eng
ind
osd
```

## Testing OCR dengan Bahasa Indonesia

```powershell
cd "D:\Search Box\OCR"
python test_ocr_setup.py
```

Jika berhasil, harusnya terlihat:
```
✓ Bahasa Indonesia (ind) tersedia
```

## Catatan

- Bahasa Indonesia (`ind`) **OPTIONAL** tapi direkomendasikan untuk dokumen Indonesia
- Tanpa `ind`, OCR tetap bisa berjalan menggunakan bahasa Inggris (`eng`)
- Untuk hasil terbaik gunakan: `ind+eng` (campuran Indonesia dan Inggris)
