<?php


namespace App\Mail;

use App\Models\DocumentRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\URL;
use Milon\Barcode\DNS2D;

class DocumentApproved extends Mailable
{
    use Queueable, SerializesModels;

    public $request;
    public $secretary;
    public $resident;
    public $qrCodeBase64;

    public function __construct(DocumentRequest $request, $secretary)
    {
        $this->request = $request;
        $this->secretary = $secretary;
        $this->resident = $request->resident;

        // ✅ FIXED: Generate correct route link with key name
    // ✅ Step 1: Corrected QR content route generation
$qrContent = route('document.verify', ['reference_code' => $request->reference_code]);

// ✅ Step 2: Generate QR code image as Base64
$this->qrCodeBase64 = base64_encode(
    (new DNS2D)->getBarcodePNG($qrContent, 'QRCODE')
);

    }

    public function build()
    {
        return $this->subject('📄 Your Document Request Has Been Approved')
                    ->view('emails.document-approved')
                    ->with([
                        'request' => $this->request,
                        'secretary' => $this->secretary,
                        'resident' => $this->resident,
                        'qrCodeBase64' => $this->qrCodeBase64,
                    ]);
    }
}