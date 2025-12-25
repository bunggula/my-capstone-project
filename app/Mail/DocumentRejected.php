<?php

namespace App\Mail;

use App\Models\DocumentRequest;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class DocumentRejected extends Mailable
{
    use Queueable, SerializesModels;

    public $request;
    public $secretary;

    /**
     * Create a new message instance.
     */
    public function __construct(DocumentRequest $request, User $secretary)
    {
        $this->request = $request;
        $this->secretary = $secretary;
    }

    /**
     * Build the message.
     */
    public function build()
    {
        return $this->subject('Document Request Rejected')
                    ->view('emails.document_rejected');
    }
}
