<?php

namespace App\Services;

use App\Models\Lead;
use App\Models\Customer;
use App\Models\LeadStatus;
use Illuminate\Http\Request;
use Ramsey\Uuid\Type\Integer;
use DB;

class LeadService
{

    public function createLead(array $userData): Lead
    {
        return Lead::create([
            'company_id'       => $userData['company_id'],
            'customer_id'      => $userData['customer_id'],
            'lead_type'        => $userData['lead_type'],
            'enquiry_date'     => (isset($userData['enquiry_date']) && !empty($userData['enquiry_date'])) ? $userData['enquiry_date'] : null,
            'details'          => $userData['details'],
            'assigned_to'      => $userData['assigned_to'],
            'assigned_on'      => (isset($userData['assigned_on']) && !empty($userData['assigned_on'])) ? $userData['assigned_on'] : null,
            'respond_on'       => (isset($userData['respond_on']) && !empty($userData['respond_on'])) ? $userData['respond_on'] : null,
            'status_id'        => $userData['status_id'],
            'expo_id'        => $userData['expo_id']
        ]);
    }

    public function getLead($id): Lead
    {
        return Lead::find($id);
    }

    public function getAllLead($data = []): Object
    {
        //   \DB::connection()->enableQueryLog();

        $sql = Lead::orderBy('id', 'desc'); // exclude converted leads

        if (isset($data['customer_id'])) {
            $sql->where('customer_id', $data['customer_id']);
        }
        if (isset($data['status_id'])) {
            $sql->whereIn('status_id', $data['status_id']);
        }
        if (isset($data['enquiry_start_date']) && isset($data['enquiry_end_date'])) {
            $sql->whereBetween('enquiry_date', array($data['enquiry_start_date'], $data['enquiry_end_date']));
        }
        if (isset($data['assigned_to'])) {
            $sql->whereIn('assigned_to', $data['assigned_to']);
        }

        $leads =  $sql->get();
        //  $queries = \DB::getQueryLog();

        //  dd($queries);


        return $leads;
    }

    public function getLeadStatusById($id): Object
    {
        return LeadStatus::find($id);
    }

    public function getLeadStatus($ignoreStatus = false): array
    {
        if ($ignoreStatus) { // to ignore status check, TRUE
            return LeadStatus::orderBy('priority', 'asc')->pluck('name', 'id')->toArray();
        } else { // not ignore status check, FALSE
            return LeadStatus::where('status', 1)->orderBy('priority', 'asc')->pluck('name', 'id')->toArray();
        }
    }

    public function deleteLead($id): void
    {
        // delete user
        Lead::find($id)->delete();
    }

    public function updateLead(Lead $lead, array $userData): void
    {
        $update = [];
        if (isset($userData['company_id'])) {
            $update['company_id'] = $userData['company_id'];
        }
        if (isset($userData['customer_id'])) {
            $update['customer_id'] = $userData['customer_id'];
        }
        if (isset($userData['lead_type'])) {
            $update['lead_type'] = $userData['lead_type'];
        }
        if (isset($userData['enquiry_date']) && !empty($userData['enquiry_date'])) {
            $update['enquiry_date'] = $userData['enquiry_date'];
        }
        if (isset($userData['details'])) {
            $update['details'] = $userData['details'];
        }
        if (isset($userData['assigned_to'])) {
            $update['assigned_to'] = $userData['assigned_to'];
        }
        if (isset($userData['assigned_on']) && !empty($userData['assigned_on'])) {
            $update['assigned_on'] = $userData['assigned_on'];
        }
        if (isset($userData['respond_on']) && !empty($userData['respond_on'])) {
            $update['respond_on'] = $userData['respond_on'];
        }
        if (isset($userData['status_id'])) {
            $update['status_id'] = $userData['status_id'];
        }
        if (isset($userData['expo_id'])) {
            $update['expo_id'] = $userData['expo_id'];
        }else{
            $update['expo_id'] = null;
        }

        $lead->update($update);
    }


    public function getLeadsCount($data = []): int
    {
        $sql = Lead::orderBy('id', 'desc');

        if (isset($data['status_id'])) {
            $sql->whereIn('status_id', $data['status_id']);
        }
        if (isset($data['assigned_to'])) {
            $sql->whereIn('assigned_to', $data['assigned_to']);
        }
        if (isset($data['start_date']) && isset($data['end_date'])) {
            $sql->whereBetween('enquiry_date', array($data['start_date'], $data['end_date']));
        }

        return $sql->get()->count();
    }

    public function checkQualifyLead($id): int
    {
        /* 2 = Good
        * 3 = VeryGood  
        * 5 = Future Potential
        */
        return Lead::where('id', $id)->whereIn('status_id', [2, 3, 5])->count();
    }

    public function leadsByEmployee($data = []): Object
    {
        $sql = DB::table('leads as l')
            ->join('employees as e', 'l.assigned_to', '=', 'e.id')
            ->join('users as u', 'e.user_id', '=', 'u.id')
            ->select(
                'u.name as employee'
            )
            ->selectRaw("count(*) as leadcount")
            ->selectRaw("count(case when status_id = 4 then 1 end) as bullshit")
            ->selectRaw("count(case when status_id != 4 then 1 end) as proceed");

        if (isset($data['start_date']) && isset($data['end_date'])) {
            $sql->whereBetween('l.enquiry_date', array($data['start_date'], $data['end_date']));
        }

        $sql->groupBy('l.assigned_to')->orderBy('employee', 'asc');

        return $sql->get();
    }
    public function leadsByType($data = []): Object
    {
        $sql = DB::table('leads as l')
            // ->join('employees as e', 'l.assigned_to', '=', 'e.id')
            //  ->join('users as u', 'e.user_id', '=', 'u.id')
            ->selectRaw("count(*) as leadcount")
            ->selectRaw("count(case when lead_type = 'internal' then 1 end) as inside")
            ->selectRaw("count(case when lead_type = 'external' then 1 end) as outside");

        if (isset($data['start_date']) && isset($data['end_date'])) {
            $sql->whereBetween('l.enquiry_date', array($data['start_date'], $data['end_date']));
        }
        if (isset($data['assigned_to'])) {
            $sql->where('l.assigned_to', $data['assigned_to']);
        }
        if (isset($data['status_id'])) {
            $sql->whereIn('l.status_id', $data['status_id']);
        }

        return $sql->get();
    }
      public function leadStatus()
    {
        return LeadStatus::where('status',1)->get();
    }
}
