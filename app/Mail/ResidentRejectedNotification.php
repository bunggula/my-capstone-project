<?php

namespace App\Mail;

use App\Models\Resident;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class ResidentRejectedNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $resident;
    public $approver;
    public $reason;

    public function __construct(Resident $resident, $approver, $reason)
    {
        $this->resident = $resident;
        $this->approver = $approver;
        $this->reason = $reason;
    }
    

    public function build()
    {
        \Log::info('[ResidentRejectedNotification] Approver Full Name:', [
            'name' => $this->approver->full_name ?? 'N/A'
        ]);
    
        return $this->subject('Resident Registration Rejected')
                    ->view('emails.rejected')
                    ->with([
                        'resident' => $this->resident,
                        'approver' => $this->approver,
                        'reason' => $this->reason,
                    ]);
    }
    
    
}
