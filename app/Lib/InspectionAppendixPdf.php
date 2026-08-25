<?php

namespace App\Lib;

class InspectionAppendixPdf extends InspectionPdf
{
    protected function view(): string
    {
        return 'pdf.appendix';
    }

    protected function storagePath(): string
    {
        return $this->inspection->appendixPath();
    }

    protected function thumbnailPath(): string
    {
        return $this->inspection->appendixThumbPath();
    }
}
