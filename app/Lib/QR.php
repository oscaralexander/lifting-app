<?php

namespace App\Lib;

use App\Models\Sticker;
use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Color\Color;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel;
use Endroid\QrCode\Writer\PdfWriter;
use Endroid\QrCode\Writer\Result\PdfResult;
use Endroid\QrCode\Writer\Result\PngResult;
use Endroid\QrCode\Writer\PngWriter;
use setasign\Fpdi\Fpdi;
use Illuminate\Support\Facades\Storage;

class QR
{
    public static function generateQrCode(string $data, string $format = 'pdf'): PdfResult|PngResult
    {
        $writer = $format === 'pdf' ? new PdfWriter() : new PngWriter();
        $builder = new Builder(
            backgroundColor: new Color(255, 255, 255),
            data: $data,
            encoding: new Encoding('UTF-8'),
            errorCorrectionLevel: ErrorCorrectionLevel::High,
            margin: 0,
            size: 1600,
            validateResult: false,
            writer: $format === 'pdf' ? new PdfWriter() : new PngWriter(),
        );

        return $builder->build();
    }

    public static function makeSticker(Sticker $sticker): string
    {
        // Create sticker PDF
        $sheet = new Fpdi();
        $sheet->AddPage('L', [91, 86]);
        $sheet->setSourceFile(resource_path('stickers/template.pdf'));
        $template = $sheet->importPage(1);
        $sheet->useTemplate($template, 0, 0, 91, 86);

        // Generate QR code PDF
        $qr = static::generateQrCode($sticker->url);
        $qrPath = Storage::disk('public')->path(env('APP_PATH_STICKERS') . '/' . $sticker->hash . '_qr.pdf');
        $qrPdf = $qr->getPdf();
        $qrPdf->Output('F', $qrPath, true);

        // Place QR code
        $sheet->setSourceFile($qrPath);
        $qrCode = $sheet->importPage(1);
        $sheet->useTemplate($qrCode, 9, 11, 35, 35);

        // Delete QR code PDF
        unlink($qrPath);

        // Place hash
        $sheet->addFont('JetBrains Mono', 'B', 'jetbrains_mono_bold.php', resource_path('stickers/fonts'));
        $sheet->SetFont('JetBrains Mono', 'B', 16);
        $stringWidth = $sheet->GetStringWidth($sticker->hash);
        $sheet->Text(66 - $stringWidth * .5, 45, $sticker->hash);

        // Save PDF
        $path = Storage::disk('public')->path(env('APP_PATH_STICKERS') . '/' . $sticker->hash . '.pdf');
        $sheet->Output('F', $path, true);
        return $path;
    }
}
