<?php

namespace App\Services;

use App\Models\Visitor;
use App\Models\VisitorLog;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Config;
use DB;

class VisitorService
{
  public function createGuest(array $userData): Visitor
  {
    $code = $this->getReferenceNumber();

    return Visitor::create([
      'fullname'     => $userData['fullname'],
      'company'      => $userData['company'],
      'email'        => $userData['email'] ? $userData['email'] : '',
      'mobile'       => $userData['mobile'] ? $userData['mobile'] : '',
      'codeno'       => $code,
      'last_checkin' => date("Y-m-d h:i:s")
    ]);
  }

  public function nextNumberSeries()
  {
    $idnum = 0;
    do {
      $last = Visitor::latest()->first();
      $idnum =  ($last) ? $last->id : 0;

      $idnum = $idnum + 1;
    } while (Visitor::where("id", $idnum)->count() > 0);

    return $idnum;
  }

  public function getReferenceNumber(): ?string
  {
    $last = $this->nextNumberSeries();

    $randStr =  "YES/OH/" . date('y') . '/' . $last;

    return $randStr;
  }

  public function getVisitor($id): Visitor
  {
    return Visitor::find($id);
  }

  public function getVisitorsList($data = []): Object
  {
    $sql = Visitor::orderBy('id', 'desc');

    if (isset($data["keyword"]) && !empty($data["keyword"])) {
      $sql->where('fullname', 'LIKE', '%' . trim($data["keyword"]) . '%');
    }

    return $sql->get();
  }

  public function getAllVisitor($data = []): Object
  {

    $sql =  DB::table('open_house');
    if (isset($data["keyword"]) && !empty($data["keyword"])) {
      $sql->where('fullname', 'LIKE', '%' . trim($data["keyword"]) . '%');
    }
    $sql->orderBy('id', 'desc');

    return $sql->get();
  }

  public function createVisitorLog(array $input): VisitorLog
  {
    return VisitorLog::create([
      'visitor_id' => $input['visitor_id'],
      'checkin' => \Carbon\Carbon::now(),
      'purpose' => $input['purpose']
    ]);
  }
}
