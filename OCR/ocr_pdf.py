import io
import os
from typing import Optional

import fitz  # PyMuPDF
from PIL import Image
import pytesseract
from PyPDF2 import PdfMerger


# Allow overriding tesseract.exe location if not on PATH
_TESSERACT_CMD = os.environ.get("TESSERACT_CMD")
if _TESSERACT_CMD:
    pytesseract.pytesseract.tesseract_cmd = _TESSERACT_CMD
else:
    # Auto-detect common Windows install locations if PATH isn't set
    for candidate in (
        r"C:\\Program Files\\Tesseract-OCR\\tesseract.exe",
        r"C:\\Program Files (x86)\\Tesseract-OCR\\tesseract.exe",
    ):
        if os.path.exists(candidate):
            pytesseract.pytesseract.tesseract_cmd = candidate
            break


def _pixmap_to_pil(pix: fitz.Pixmap) -> Image.Image:
    # Ensure no alpha and use PNG bytes to keep conversion simple and robust
    if pix.alpha:
        pix = fitz.Pixmap(pix, 0)  # remove alpha
    png_bytes = pix.tobytes("png")
    return Image.open(io.BytesIO(png_bytes))


def pdf_bytes_to_searchable_pdf_bytes(pdf_bytes: bytes, dpi: int = 150, lang: str = "eng", quality: int = 85) -> bytes:
    if not isinstance(pdf_bytes, (bytes, bytearray)):
        raise TypeError("pdf_bytes must be bytes")

    # Open PDF from memory
    doc = fitz.open(stream=pdf_bytes, filetype="pdf")
    try:
        page_pdfs: list[io.BytesIO] = []

        # Render and OCR each page
        zoom = dpi / 72.0  # 72dpi is PDF default
        mat = fitz.Matrix(zoom, zoom)
        for page in doc:
            pix = page.get_pixmap(matrix=mat, alpha=False)
            pil_img = _pixmap_to_pil(pix)
            
            # Compress image to reduce file size (convert to RGB for JPEG)
            if pil_img.mode != 'RGB':
                pil_img = pil_img.convert('RGB')
            
            # Compress to JPEG in memory
            jpeg_buffer = io.BytesIO()
            pil_img.save(jpeg_buffer, format='JPEG', quality=quality, optimize=True)
            jpeg_buffer.seek(0)
            compressed_img = Image.open(jpeg_buffer)

            # Tesseract returns a searchable PDF (image + hidden text layer)
            page_pdf_bytes = pytesseract.image_to_pdf_or_hocr(compressed_img, extension="pdf", lang=lang)
            page_pdfs.append(io.BytesIO(page_pdf_bytes))

        # Merge all page PDFs
        merger = PdfMerger()
        try:
            for p in page_pdfs:
                p.seek(0)
                merger.append(p)
            output_stream = io.BytesIO()
            merger.write(output_stream)
            return output_stream.getvalue()
        finally:
            merger.close()
    finally:
        doc.close()


def pdf_file_to_searchable_pdf_file(input_path: str, output_path: str, dpi: int = 150, lang: str = "eng", quality: int = 85) -> None:
    if not os.path.isfile(input_path):
        raise FileNotFoundError(f"Input file not found: {input_path}")
    with open(input_path, "rb") as f:
        data = f.read()
    out_bytes = pdf_bytes_to_searchable_pdf_bytes(data, dpi=dpi, lang=lang, quality=quality)
    os.makedirs(os.path.dirname(output_path) or ".", exist_ok=True)
    with open(output_path, "wb") as f:
        f.write(out_bytes)
