<?php

namespace App\Services;

use App\Models\Visitor;
use Illuminate\Http\Request;
use Carbon\Carbon;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class VisitorService
{
    public function createGuest(array $userData): Visitor
    {
        //  dd($userData);

        return Visitor::create([
            'company_id'        => $userData['company_id'],
            'customer_id'       => $userData['customer_id'],
            'codeno'            => $this->getReferenceNumber(),
            'checkin'           => \Carbon\Carbon::now(),
            'purpose'           => "Open House Registration"
        ]);
    }
    public function getReferenceNumber(): ?string
    {
        $last = Visitor::latest()->first();
        $lastId = ($last) ? $last->id : 0;

        $randStr =  "YES/OH/" . date('y') . '/' . ($lastId + 1);

        return $randStr;
    }
    public function getVisitor($id): Visitor
    {
        return Visitor::find($id);
    }

    public function getAllVisitor($data = []): Object
    {
        $sql = Visitor::orderBy('id', 'desc');

        if (isset($data['company_id'])) {
            $sql->where('company_id', $data['company_id']);
        }

        return $sql->get();
    }
}
