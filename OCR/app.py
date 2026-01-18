from __future__ import annotations

import io
import os
from typing import Optional

from flask import Flask, request, send_file, abort, Response

from ocr_pdf import pdf_bytes_to_searchable_pdf_bytes

app = Flask(__name__)

# Basic size guard (adjust as needed)
app.config["MAX_CONTENT_LENGTH"] = 200 * 1024 * 1024  # 200MB


@app.get("/")
def index() -> Response:
    return (
        """
        <!doctype html>
        <html lang="en">
        <head>
            <meta charset="utf-8" />
            <meta name="viewport" content="width=device-width, initial-scale=1" />
            <title>PDF OCR</title>
            <style>
              body { font-family: system-ui, sans-serif; margin: 2rem; }
              .card { max-width: 560px; padding: 1.25rem; border: 1px solid #e5e7eb; border-radius: 8px; }
              label { display: block; margin-top: .75rem; font-weight: 600; }
              input[type=number], input[type=text], input[type=file] { width: 100%; padding: .5rem; margin-top: .25rem; }
              button { margin-top: 1rem; padding: .6rem 1rem; background: #111827; color: white; border: none; border-radius: 6px; cursor: pointer; }
            </style>
        </head>
        <body>
            <div class="card">
                <h2>PDF OCR (Image → Searchable PDF)</h2>
                <form action="/ocr" method="post" enctype="multipart/form-data">
                    <label>PDF File</label>
                    <input name="file" type="file" accept="application/pdf" required />

                    <label>Language (Tesseract)</label>
                    <input name="lang" type="text" value="eng" placeholder="e.g., eng or eng+spa" />

                    <label>DPI (Resolusi: 150=Cepat, 300=Detail)</label>
                    <input name="dpi" type="number" value="150" min="72" max="600" />

                    <label>Kualitas JPEG (0-100)</label>
                    <input name="quality" type="number" value="85" min="50" max="100" />

                    <button type="submit">Convert</button>
                </form>
            </div>
        </body>
        </html>
        """,
        200,
        {"Content-Type": "text/html; charset=utf-8"},
    )


@app.post("/ocr")
def ocr_endpoint():
    f = request.files.get("file")
    if not f:
        abort(400, description="Missing file upload")

    filename = getattr(f, "filename", "uploaded.pdf") or "uploaded.pdf"
    if not filename.lower().endswith(".pdf"):
        abort(400, description="Only PDF files are supported")

    lang = request.form.get("lang") or request.args.get("lang") or "eng"
    try:
        dpi = int(request.form.get("dpi") or request.args.get("dpi") or 150)
    except Exception:
        dpi = 150
    
    try:
        quality = int(request.form.get("quality") or request.args.get("quality") or 85)
    except Exception:
        quality = 85

    try:
        in_bytes = f.read()
        out_bytes = pdf_bytes_to_searchable_pdf_bytes(in_bytes, dpi=dpi, lang=lang, quality=quality)
    except Exception as e:
        abort(500, description=f"OCR failed: {e}")

    out_name = f"OCR {os.path.basename(filename)}"
    return send_file(
        io.BytesIO(out_bytes),
        mimetype="application/pdf",
        as_attachment=True,
        download_name=out_name,
        max_age=0,
    )


if __name__ == "__main__":
    port = int(os.environ.get("PORT", "5000"))
    app.run(host="127.0.0.1", port=port, debug=True)
