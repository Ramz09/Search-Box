# OCR PDF (Image PDF → Searchable Text PDF)

This project converts scanned/image-only PDFs into searchable PDFs using Tesseract OCR.
- Rendering: PyMuPDF (no Poppler needed on Windows)
- OCR: Tesseract via pytesseract
- Output: Searchable PDF with an invisible text layer
- Interfaces: CLI and a minimal Flask web app

## Prerequisites
- Windows with Python 3.9+
- Tesseract OCR installed and on PATH
  - Get Windows installer: https://github.com/UB-Mannheim/tesseract/wiki
  - After install, restart terminal so `tesseract --version` works
- Recommended languages installed in Tesseract (e.g., `eng`, `spa`, etc.)

If Tesseract is not on PATH, set the full path:
- Example: `C:\Program Files\Tesseract-OCR\tesseract.exe`
- You can either add it to PATH or set env var `TESSERACT_CMD` to that full path.

## Setup
```powershell
# From the project root (d:\OCR)
python -m venv .venv
.\.venv\Scripts\activate
pip install -r requirements.txt
```

Optional: if you need to point pytesseract directly to tesseract.exe:
```powershell
$env:TESSERACT_CMD = "C:\\Program Files\\Tesseract-OCR\\tesseract.exe"
```

## CLI Usage
Convert a PDF to a searchable PDF:
```powershell
.\.venv\Scripts\activate
python cli.py -i sample.pdf -o output_searchable.pdf -l eng -d 300
```
- `-i/--input`: input PDF path
- `-o/--output`: output PDF path
- `-l/--lang`: Tesseract language code(s), default `eng`. Multiple languages supported like `eng+spa` if installed.
- `-d/--dpi`: rendering DPI (higher → better OCR but slower/larger), default `300`.

## Web App Usage (Flask)
Run the server:
```powershell
.\.venv\Scripts\activate
set FLASK_ENV=development
python app.py
```
Open http://127.0.0.1:5000/ and upload a PDF.

cURL example:
```powershell
curl -X POST "http://127.0.0.1:5000/ocr?lang=eng&dpi=300" `
  -H "Content-Type: multipart/form-data" `
  -F "file=@sample.pdf" `
  -o output_searchable.pdf
```

## Notes
- PyMuPDF renders each PDF page to an image, then Tesseract creates a page-level searchable PDF (image + invisible text layer). All pages are merged into a single PDF.
- Memory use scales with DPI and page size. If you hit limits, try lowering DPI (e.g., 200) or processing in batches.

## Troubleshooting
- If OCR fails with language not found, install the language in Tesseract and confirm it exists under `tessdata`.
- Verify Tesseract is callable:
```powershell
tesseract --version
```
- If not on PATH, set `TESSERACT_CMD` (see above) and try again.

## License
This is a sample project provided for demonstration/testing.
