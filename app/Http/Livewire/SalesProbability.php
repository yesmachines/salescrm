<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Services\QuotationService;

class SalesProbability extends Component
{
    public $assigned;
    public $period;
    public $probability;

    public function render(QuotationService $quotationService)
    {
        if (!$this->period) {
            $start_date = \Carbon\Carbon::now()->startOfMonth();
            $end_date = \Carbon\Carbon::now()->endOfMonth();
        } else {
            $this->period = (int) $this->period;

            $start_date = \Carbon\Carbon::today();
            $end_date = \Carbon\Carbon::today()->subDays($this->period)->endOfDay();
        }

        $data = [
            'assigned_to' => [$this->assigned],
            'is_active' => [1],
            'closing_start_date' => $start_date,
            'closing_end_date'   => $end_date,
            'winning_probability' => $this->probability,
            'isPagination'  => true
        ];

        $salesprobability = $quotationService->getAllQuotes($data);

        return view('livewire.sales-probability', ['salesprobability' => $salesprobability]);
    }
}
