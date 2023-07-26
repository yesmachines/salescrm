<?php

namespace App\Services;

use App\Models\Employee;
use Carbon\Carbon;
use Illuminate\Http\Request;
use DB;
use Ramsey\Uuid\Type\Integer;

class QuoteHistoryService
{
    public function addHistory(array $userData, $quoteId): void
    {
        DB::table('quotation_histories')->insert([
            'quotation_id'      => $quoteId,
            'status_id'    => $userData['status_id'],
            'comment'      => $userData['comment'],
            'userid'       => auth()->id(),
            'created_at'   => \Carbon\Carbon::now(),
            'updated_at'   => \Carbon\Carbon::now()
        ]);
    }

    public function updateHistory(array $userData, $quoteId): void
    {
        $checkIfSame = DB::table('quotation_histories')
            ->where('quotation_id', $quoteId)
            ->where('status_id', $userData['status_id'])
            ->orderBy('id', 'desc')
            ->count();

        if (!$checkIfSame) {
            // if last status is not same us current one
            DB::table('quotation_histories')->insert([
                'quotation_id'      => $quoteId,
                'status_id'    => $userData['status_id'],
                'comment'      => $userData['comment'],
                'userid'       => auth()->id(),
                'created_at'   => \Carbon\Carbon::now(),
                'updated_at'   => \Carbon\Carbon::now()
            ]);
        } else {
            $update = [];

            if (isset($userData['updated_at']) && $userData['updated_at']) {
                $date = strtotime($userData['updated_at']);
                $update['updated_at'] =  date('Y-m-d H:i:s', $date);
            }

            DB::table('quotation_histories')
                ->where('quotation_id', $quoteId)
                ->where('status_id', 6)
                ->update($update);
        }
    }

    public function getLatestStatus($quoteId): Object
    {
        return DB::table('quotation_histories as qh')
            ->join('quotation_statuses as ls', 'ls.id', '=', 'qh.status_id')
            ->leftJoin('users as u', 'qh.userid', '=', 'u.id')
            ->select('qh.*', 'ls.name as status', 'u.name as username')
            ->where('qh.quotation_id', $quoteId)
            ->orderBy('id', 'desc')
            ->first();
    }

    public function getHistories($quoteId): Object
    {
        return DB::table('quotation_histories as qh')
            ->join('quotation_statuses as ls', 'ls.id', '=', 'qh.status_id')
            ->leftJoin('users as u', 'qh.userid', '=', 'u.id')
            ->select('qh.*', 'ls.name as status', 'u.name as username')
            ->where('qh.quotation_id', $quoteId)
            ->orderBy('qh.id', 'desc')
            ->get();
    }

    public function deleteHistory($quoteId): void
    {
        DB::table('quotation_histories')
            ->where('quotation_id', $quoteId)
            ->delete();
    }
}
