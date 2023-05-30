<?php

namespace App\Service;

use Dompdf\Dompdf;
use Dompdf\Options;

class PdfService {
    
    private $domPdf;

    public function __construct()
    {
        $this->domPdf = new Dompdf();

        $pdfOptions = new Options();

        $pdfOptions->set('defaultFont', 'Arial');
        $pdfOptions->set('isRemoteEnabled', true);

        $this->domPdf->setOptions($pdfOptions);

    }

    public function showPdf($html){
        // dd($html);
        $this->domPdf->loadHtml($html);

        $this->domPdf->setPaper([0, 0,  240, 370], 'portrait');


        $this->domPdf->render();

        $this->domPdf->stream('recu.pdf', [
            'Attachement' => false
        ]);
    }
}