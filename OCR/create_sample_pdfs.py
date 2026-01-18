"""
Script untuk membuat sample PDF untuk testing OCR
"""

from PIL import Image, ImageDraw, ImageFont
from reportlab.pdfgen import canvas
from reportlab.lib.pagesizes import letter
import os

def create_text_pdf():
    """Create a normal text-based PDF"""
    filename = "sample_text_pdf.pdf"
    c = canvas.Canvas(filename, pagesize=letter)
    
    # Add text content
    c.setFont("Helvetica", 16)
    c.drawString(100, 750, "SAMPLE TEXT PDF - FOR TESTING")
    c.setFont("Helvetica", 12)
    c.drawString(100, 720, "Ini adalah contoh PDF yang berisi teks normal.")
    c.drawString(100, 700, "PDF ini dapat langsung dicari tanpa OCR.")
    c.drawString(100, 680, "")
    c.drawString(100, 660, "Dokumen ini dibuat untuk testing upload PDF teks.")
    c.drawString(100, 640, "Sistem akan mendeteksi bahwa ini adalah PDF teks,")
    c.drawString(100, 620, "dan langsung menyimpannya tanpa proses OCR.")
    
    c.save()
    print(f"✓ Created: {filename}")
    return filename

def create_image_pdf():
    """Create an image-based PDF (simulating scanned document)"""
    # Create an image with text
    img = Image.new('RGB', (800, 1000), color='white')
    draw = ImageDraw.Draw(img)
    
    # Draw text on image
    draw.text((50, 50), "SAMPLE IMAGE PDF - FOR OCR TESTING", fill='black')
    draw.text((50, 100), "Ini adalah contoh PDF yang berisi gambar teks.", fill='black')
    draw.text((50, 130), "PDF ini memerlukan OCR untuk dapat dicari.", fill='black')
    draw.text((50, 160), "", fill='black')
    draw.text((50, 190), "Ketika upload, sistem akan mendeteksi bahwa", fill='black')
    draw.text((50, 220), "ini adalah PDF gambar dan menampilkan alert.", fill='black')
    draw.text((50, 250), "", fill='black')
    draw.text((50, 280), "User dapat memilih untuk menjalankan OCR", fill='black')
    draw.text((50, 310), "atau membatalkan upload.", fill='black')
    
    # Save image temporarily
    temp_img = "temp_image.png"
    img.save(temp_img)
    
    # Create PDF from image
    filename = "sample_image_pdf.pdf"
    c = canvas.Canvas(filename, pagesize=letter)
    c.drawImage(temp_img, 0, 0, width=612, height=792)
    c.save()
    
    # Cleanup
    os.remove(temp_img)
    
    print(f"✓ Created: {filename}")
    return filename

if __name__ == "__main__":
    print("Creating sample PDFs for testing...\n")
    
    # Create samples folder if not exists
    if not os.path.exists("samples"):
        os.makedirs("samples")
        print("✓ Created samples/ folder\n")
    
    os.chdir("samples")
    
    text_pdf = create_text_pdf()
    image_pdf = create_image_pdf()
    
    print(f"\n✓ Sample PDFs created successfully!")
    print(f"\nTest files:")
    print(f"1. {text_pdf} - Normal text PDF (no OCR needed)")
    print(f"2. {image_pdf} - Image PDF (will trigger OCR alert)")
    print(f"\nLocation: samples/")
