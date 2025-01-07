<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Illuminate\Contracts\View\View;

class MeetingMTMGExport implements FromView, ShouldAutoSize {

    protected $formattedMeetings;
    protected $maxMeetings;

    public function __construct($formattedMeetings, $maxMeetings) {
        $this->formattedMeetings = $formattedMeetings;
        $this->maxMeetings = $maxMeetings;
    }

    public function view(): View {
        return view('meetings.mtmg', [
            'formattedMeetings' => $this->formattedMeetings,
            'maxMeetings' => $this->maxMeetings
        ]);
    }
}
