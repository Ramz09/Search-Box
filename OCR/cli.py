import argparse
import os
import sys

from ocr_pdf import pdf_file_to_searchable_pdf_file


def parse_args() -> argparse.Namespace:
    p = argparse.ArgumentParser(description="Convert image-based PDF to searchable PDF using Tesseract OCR.")
    p.add_argument("-i", "--input", required=True, help="Path to input PDF")
    p.add_argument("-o", "--output", required=True, help="Path to output searchable PDF")
    p.add_argument("-l", "--lang", default="eng", help="Tesseract language(s), e.g., eng or eng+spa (default: eng)")
    p.add_argument("-d", "--dpi", type=int, default=150, help="Render DPI (default: 150)")
    p.add_argument("-q", "--quality", type=int, default=85, help="JPEG quality 50-100 (default: 85)")
    return p.parse_args()


def main() -> int:
    args = parse_args()

    in_path = os.path.abspath(args.input)
    out_path = os.path.abspath(args.output)

    if not in_path.lower().endswith(".pdf"):
        print("Error: input must be a PDF.", file=sys.stderr)
        return 2
    if not out_path.lower().endswith(".pdf"):
        print("Error: output must be a PDF path.", file=sys.stderr)
        return 2

    try:
        print(f"[OCR] Converting: {in_path}")
        print(f"[OCR] Language: {args.lang} | DPI: {args.dpi} | Quality: {args.quality}")
        pdf_file_to_searchable_pdf_file(in_path, out_path, dpi=args.dpi, lang=args.lang, quality=args.quality)
        print(f"[OCR] Done: {out_path}")
        return 0
    except Exception as e:
        print(f"[OCR] Failed: {e}", file=sys.stderr)
        return 1


if __name__ == "__main__":
    raise SystemExit(main())
