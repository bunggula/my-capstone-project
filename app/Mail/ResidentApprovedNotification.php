<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\Resident;
use App\Models\User;
use Illuminate\Support\Facades\Log;

class ResidentApprovedNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $resident;
    public $approver;

    public function __construct(Resident $resident, User $approver)
    {
        $this->resident = $resident;
        $this->approver = $approver;

        // Debug logs
        Log::info('[ResidentApprovedNotification] Resident: ' . $resident->first_name . ' ' . $resident->last_name);

        if ($approver) {
            Log::info('[ResidentApprovedNotification] Approver ID: ' . $approver->id);
            Log::info('[ResidentApprovedNotification] Approver full name: ' . $approver->full_name);
        } else {
            Log::warning('[ResidentApprovedNotification] Approver is NULL');
        }
    }

    public function build()
    {
        return $this->subject('Your Registration Has Been Approved')
                    ->view('emails.resident-approved')
                    ->with([
                        'resident' => $this->resident,
                        'approver' => $this->approver,
                    ]);
    }
}
