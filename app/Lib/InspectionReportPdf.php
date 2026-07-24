<?php

namespace App\Lib;

class InspectionReportPdf extends InspectionPdf
{
    protected function view(): string
    {
        return 'pdf.inspection-report';
    }

    protected function storagePath(): string
    {
        return $this->inspection->inspectionReportPath();
    }

    protected function thumbnailPath(): string
    {
        return $this->inspection->inspectionReportThumbPath();
    }
}
