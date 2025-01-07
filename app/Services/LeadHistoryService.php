<?php

namespace App\Services;

use App\Models\Employee;
use Carbon\Carbon;
use Illuminate\Http\Request;
use DB;
use Ramsey\Uuid\Type\Integer;

class LeadHistoryService
{
    public function addHistory(array $userData, $leadId): void
    {
        DB::table('lead_histories')->insert([
            'lead_id'      => $leadId,
            'status_id'    => $userData['status_id'],
            'comment'      => $userData['comment'],
            'userid'       => auth()->id(),
            'priority'     => isset($userData['priority']) ? $userData['priority'] : 'low',
            'created_at'   => \Carbon\Carbon::now(),
            'updated_at'   => \Carbon\Carbon::now()
        ]);
    }

    public function updateHistory(array $userData, $leadId): void
    {
        $checkIfSame = DB::table('lead_histories')
            ->where('lead_id', $leadId)
            ->where('status_id', $userData['status_id'])
            ->orderBy('id', 'desc')
            ->count();

        if (!$checkIfSame) {
            // if last status is not same us current one
            DB::table('lead_histories')->insert([
                'lead_id'      => $leadId,
                'status_id'    => $userData['status_id'],
                'comment'      => $userData['comment'],
                'userid'       => auth()->id(),
                'priority'     => isset($userData['priority']) ? $userData['priority'] : 'low',
                'created_at'   => \Carbon\Carbon::now(),
                'updated_at'   => \Carbon\Carbon::now()
            ]);
        }
    }

    public function getLatestStatus($leadId): Object
    {
        return DB::table('lead_histories as lh')
            ->join('lead_statuses as ls', 'ls.id', '=', 'lh.status_id')
            ->leftJoin('users as u', 'lh.userid', '=', 'u.id')
            ->select('lh.*', 'ls.name as status', 'u.name as username')
            ->where('lh.lead_id', $leadId)
            ->orderBy('id', 'desc')
            ->first();
    }

    public function getHistories($leadId): Object
    {
        return DB::table('lead_histories as lh')
            ->join('lead_statuses as ls', 'ls.id', '=', 'lh.status_id')
            ->leftJoin('users as u', 'lh.userid', '=', 'u.id')
            ->select('lh.*', 'ls.name as status', 'u.name as username')
            ->where('lh.lead_id', $leadId)
            ->orderBy('lh.id', 'desc')
            ->get();
    }

    public function deleteHistory($leadId): void
    {
        DB::table('lead_histories')
            ->where('lead_id', $leadId)
            ->delete();
    }
    
    public function getCallLogs($leadId): Object
    {
        return DB::table('lead_call_histories as lh')
            ->where('lh.lead_id', $leadId)
            ->orderBy('lh.created_at')
            ->get()->each(function ($call) {
            $meetingTimeInUserTimezone = Carbon::parse($call->called_at, 'UTC')->setTimezone(config('app.timezone'));
            $call->called_at = $meetingTimeInUserTimezone->format('M d,Y h:i:s A');
            $call->time = $meetingTimeInUserTimezone->format('h:i A');
        });
    }
}
