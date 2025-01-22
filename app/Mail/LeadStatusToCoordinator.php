<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class LeadStatusToCoordinator extends Mailable {

    use Queueable,
        SerializesModels;

    public $enquiry;
    public $coordinator;

    public function __construct($enquiry, $coordinator) {
        $this->enquiry = $enquiry;
        $this->coordinator = $coordinator;
    }

    public function build() {
        return $this->view('emails.lead_update_status_to_cord')
                        ->subject("Enquiry Updates")
                        ->with(['enquiry' => $this->enquiry, 'coordinator' => $this->coordinator]);
    }
}
