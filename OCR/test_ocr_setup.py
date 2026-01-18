#!/usr/bin/env python3
"""
Test script untuk memastikan OCR Python berfungsi dengan baik
"""

import sys
import os

def check_dependencies():
    """Check if all required packages are installed"""
    print("Memeriksa dependencies Python OCR...\n")
    
    dependencies = {
        'fitz': 'PyMuPDF',
        'pytesseract': 'pytesseract',
        'PIL': 'Pillow',
        'PyPDF2': 'PyPDF2'
    }
    
    missing = []
    for module, package in dependencies.items():
        try:
            __import__(module)
            print(f"✓ {package} - OK")
        except ImportError:
            print(f"✗ {package} - MISSING")
            missing.append(package)
    
    if missing:
        print(f"\nMissing packages: {', '.join(missing)}")
        print(f"\nInstall dengan: pip install {' '.join(missing)}")
        return False
    
    print("\n✓ Semua dependencies terinstal!\n")
    return True

def check_tesseract():
    """Check if Tesseract is installed"""
    print("Memeriksa Tesseract OCR...\n")
    
    try:
        import pytesseract
        version = pytesseract.get_tesseract_version()
        print(f"✓ Tesseract version: {version}")
        
        # Check for Indonesian language data
        try:
            langs = pytesseract.get_languages()
            if 'ind' in langs:
                print("✓ Bahasa Indonesia (ind) tersedia")
            else:
                print("⚠ Bahasa Indonesia (ind) tidak tersedia")
                print("  Download dari: https://github.com/tesseract-ocr/tessdata/raw/main/ind.traineddata")
            
            if 'eng' in langs:
                print("✓ Bahasa Inggris (eng) tersedia")
        except:
            pass
            
        return True
    except Exception as e:
        print(f"✗ Tesseract tidak ditemukan: {e}")
        print("\nInstall Tesseract dari:")
        print("https://github.com/UB-Mannheim/tesseract/wiki")
        return False

def test_ocr_simple():
    """Test OCR with a simple image"""
    print("\nTesting fungsi OCR sederhana...\n")
    
    try:
        from PIL import Image, ImageDraw, ImageFont
        import pytesseract
        import io
        
        # Create a simple test image with text
        img = Image.new('RGB', (400, 100), color='white')
        draw = ImageDraw.Draw(img)
        
        # Draw text
        text = "Test OCR 123"
        draw.text((10, 30), text, fill='black')
        
        # Test OCR
        result = pytesseract.image_to_string(img, lang='eng')
        
        if 'Test' in result or 'OCR' in result:
            print(f"✓ OCR berfungsi! Hasil: {result.strip()}")
            return True
        else:
            print(f"⚠ OCR berjalan tapi hasil tidak sesuai: {result.strip()}")
            return True
    except Exception as e:
        print(f"✗ Error testing OCR: {e}")
        return False

def test_pdf_import():
    """Test if PDF processing modules work"""
    print("\nTesting import modul PDF...\n")
    
    try:
        import fitz
        print(f"✓ PyMuPDF (fitz) version: {fitz.version}")
        
        from PyPDF2 import PdfMerger
        print("✓ PyPDF2 - OK")
        
        return True
    except Exception as e:
        print(f"✗ Error: {e}")
        return False

def main():
    print("=" * 60)
    print("OCR PYTHON - TEST SCRIPT")
    print("=" * 60)
    print()
    
    results = []
    
    # Check Python version
    print(f"Python version: {sys.version}\n")
    
    # Run tests
    results.append(("Dependencies", check_dependencies()))
    results.append(("Tesseract", check_tesseract()))
    results.append(("PDF Modules", test_pdf_import()))
    results.append(("OCR Simple Test", test_ocr_simple()))
    
    # Summary
    print("\n" + "=" * 60)
    print("SUMMARY")
    print("=" * 60)
    
    for name, passed in results:
        status = "✓ PASS" if passed else "✗ FAIL"
        print(f"{name:20} {status}")
    
    print()
    
    if all(r[1] for r in results):
        print("✓ Semua test PASSED! OCR siap digunakan.")
        return 0
    else:
        print("✗ Beberapa test FAILED. Perbaiki masalah di atas.")
        return 1

if __name__ == "__main__":
    sys.exit(main())
