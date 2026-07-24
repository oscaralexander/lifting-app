<?php

namespace App\Lib;

class InspectionCertificatePdf extends InspectionPdf
{
    protected int $thumbnailSize = 600;

    protected function view(): string
    {
        return 'pdf.certificate';
    }

    protected function storagePath(): string
    {
        return $this->inspection->certificatePath();
    }

    protected function thumbnailPath(): string
    {
        return $this->inspection->certificateThumbPath();
    }
}
