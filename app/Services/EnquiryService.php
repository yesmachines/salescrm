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
            ->select(
                'e.*',
                'v.company',
                'v.fullname',
                'v.email',
                'v.mobile',
                'v.codeno'
            );

        $sql->orderBy('id', 'desc');

        $enquiries = $sql->get();

        return $enquiries;
    }
}
