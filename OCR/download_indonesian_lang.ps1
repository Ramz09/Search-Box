# Download Indonesian Language Data untuk Tesseract

Write-Host "Downloading Indonesian language data for Tesseract..." -ForegroundColor Cyan
Write-Host ""

# Find Tesseract installation
$tesseractPath = "C:\Program Files\Tesseract-OCR"
if (-not (Test-Path $tesseractPath)) {
    $tesseractPath = "C:\Program Files (x86)\Tesseract-OCR"
}

if (-not (Test-Path $tesseractPath)) {
    Write-Host "ERROR: Tesseract not found!" -ForegroundColor Red
    Write-Host "Please install Tesseract from: https://github.com/UB-Mannheim/tesseract/wiki"
    exit 1
}

Write-Host "Tesseract found at: $tesseractPath" -ForegroundColor Green

# Check tessdata folder
$tessdataPath = Join-Path $tesseractPath "tessdata"
if (-not (Test-Path $tessdataPath)) {
    Write-Host "ERROR: tessdata folder not found at $tessdataPath" -ForegroundColor Red
    exit 1
}

Write-Host "tessdata folder: $tessdataPath" -ForegroundColor Green
Write-Host ""

# Download Indonesian trained data
$indFile = Join-Path $tessdataPath "ind.traineddata"

if (Test-Path $indFile) {
    Write-Host "Indonesian language data already exists!" -ForegroundColor Yellow
    $response = Read-Host "Do you want to re-download? (y/n)"
    if ($response -ne 'y') {
        Write-Host "Skipping download." -ForegroundColor Yellow
        exit 0
    }
}

Write-Host "Downloading ind.traineddata..." -ForegroundColor Cyan

$url = "https://github.com/tesseract-ocr/tessdata/raw/main/ind.traineddata"

try {
    # Download file
    Invoke-WebRequest -Uri $url -OutFile $indFile -UseBasicParsing
    
    if (Test-Path $indFile) {
        $fileSize = (Get-Item $indFile).Length / 1MB
        Write-Host ""
        Write-Host "SUCCESS!" -ForegroundColor Green
        Write-Host "Indonesian language data downloaded successfully!" -ForegroundColor Green
        Write-Host "File: $indFile" -ForegroundColor Green
        Write-Host "Size: $([math]::Round($fileSize, 2)) MB" -ForegroundColor Green
        Write-Host ""
        Write-Host "You can now use 'ind' or 'ind+eng' for Indonesian OCR." -ForegroundColor Cyan
    }
} catch {
    Write-Host ""
    Write-Host "ERROR: Failed to download!" -ForegroundColor Red
    Write-Host $_.Exception.Message -ForegroundColor Red
    Write-Host ""
    Write-Host "Manual download:" -ForegroundColor Yellow
    Write-Host "1. Download from: $url"
    Write-Host "2. Save to: $tessdataPath"
    exit 1
}
