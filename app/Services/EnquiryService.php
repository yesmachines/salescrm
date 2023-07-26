<?php

namespace App\Services;

use App\Models\Enquiry;
use Illuminate\Http\Request;
use DB;

class EnquiryService
{
    public function createEnquiry(array $userData): Enquiry
    {
        return Enquiry::create([
            'visitorid'     => $userData['visitorid'],
            'submitted_by'  => $userData['submitted_by'],
            'brand'         => $userData['brand'],
            'description'   => $userData['description']
        ]);
    }

    public function getEnquiry($id): Enquiry
    {
        return Enquiry::find($id);
    }

    public function getAllEnquiries($data = []): Object
    {
        $sql = Enquiry::orderBy('id', 'desc');

        if (isset($data['visitorid']) && !empty($data['visitorid'])) {
            $sql->where('visitorid', $data['visitorid']);
        }
        $enquiries = $sql->get();

        return $enquiries;
    }
    public function allEnquiries($data = []): Object
    {
        $sql = DB::table('enquiries as e')
            ->leftJoin('visitors as v', 'e.visitorid', '=', 'v.id')
            ->leftJoin('customers as u', 'v.customer_id', '=', 'u.id')
            ->leftJoin('companies as c', 'v.company_id', '=', 'c.id')
            ->select(
                'e.*',
                'c.company',
                'u.fullname',
                'u.email',
                'u.phone'
            );

        $sql->orderBy('id', 'desc');

        $enquiries = $sql->get();

        return $enquiries;
    }
}
