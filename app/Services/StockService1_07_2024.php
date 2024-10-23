<?php

namespace App\Services;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use DB;
use Illuminate\Support\Arr;
use App\Models\Customer;
use App\Models\Company;
use App\Models\Stock;
use App\Models\StockItem;
use App\Models\StockSupplier;
use App\Models\StockPayment;
use App\Models\StockCharge;
use App\Models\Order;
use App\Models\User;
use App\Models\Employee;


class StockService
{

  public function insertStock(array $userData): Object
  {
    $empId=Auth::id();
    $empId=Auth::id();
    $employeeManager = DB::table('employee_managers')
    ->join('employees', 'employee_managers.employee_id', '=', 'employees.id')
    ->where('employees.user_id', $empId)
    ->select('employee_managers.manager_id')
    ->first();
    $insert = [
      'purchase_mode'  => $userData['purchase_mode'],
      'os_number'  =>  $this->getReferenceNumber(),
      'os_date'  => $userData['os_date'],
      'buying_price'  => $userData['buying_price'],
      'created_by'  =>  $employeeManager->manager_id,
    ];
    $stock = Stock::create($insert);
    if(isset($userData['item_name'])){
      foreach ($userData['item_name'] as $key => $itemName) {
        $stockItem = new StockItem();
        $stockItem->stock_id =   $stock->id ;
        $stockItem->item_name = $itemName;
        $stockItem->partno = $userData['partno'][$key];
        $stockItem->quantity = $userData['quantity'][$key];
        $stockItem->yes_number = $userData['yes_number'][$key];
        $stockItem->total_amount = $userData['total_amount'][$key];
        $stockItem->expected_delivery = $userData['expected_delivery'][$key];
        $stockItem->status = $userData['status'][$key];
        $stockItem->remarks = $userData['item_remark'][$key];
        $stockItem->save();

      }
    }

    if(isset($userData['supplier_id'])){
      $stockSupplier = StockSupplier::create([
        'stock_id' =>  $stock->id,
        'supplier_id' => $userData['supplier_id'],
        'price_basis' => $userData['price_basis'],
        'delivery_term' => $userData['delivery_term'],
        'remarks' =>  $userData['supplier_remark'],

      ]);
    }
    if(isset($userData['charges'])){
      foreach ($userData['charges'] as $index => $stockCharges) {
        $stockCharges = StockCharge::create([
          'stock_id' => $stock->id,
          'title' => $stockCharges,
          'currency' => 'aed',
          'considered' => $userData['considered'][$index],
          'remarks' =>  $userData['charge_remark'][$index],

        ]);
      }
    }
    if(isset($userData['payment_term'])){
      foreach ($userData['payment_term'] as $index => $stockPayments) {

        $stockPayments = StockPayment::create([
          'stock_id' => $stock->id,
          'payment_term' => $stockPayments,
          'expected_date' => $userData['expected_date'][$index],
          'status' => $userData['status'][$index],
          'remarks' =>  $userData['payment_remark'][$index],

        ]);


      }
    }

    return $stock;
  }
  // public function getReferenceNumber(): ?string
  // {
  //     $StartNo = 001;
  //     Order::lockForUpdate()->get();
  //
  //     $lastRow = Order::whereYear('os_date', '=', date('Y'))
  //         //->where('os_number', 'not like', "%Rev%")
  //         ->orderBy("id", "desc")
  //         ->first();
  //
  //     $last = 0;
  //     if ($lastRow) {
  //         $alast = explode("/", $lastRow->os_number);
  //         $last = (count($alast) > 0) ? (int) $alast[3] : 0;
  //     }
  //     $lastId = ($last) ? ($StartNo + $last) : $StartNo;
  //
  //     $randStr =  "YES/OS/" . date('y') . '/' . $lastId;
  //
  //     return $randStr;
  // }
  public function getReferenceNumber(): ?string
  {
    $currentYear = date('y'); // Current year

    // Fetch the latest order and stock records for the current year
    $latestOrder = Order::whereYear('os_date', '=', date('Y'))->latest()->first();
    $latestStock = Stock::whereYear('created_at', '=', date('Y'))->latest()->first();

    // Determine the maximum sequential number among the latest order and stock
    $lastOrderSequentialNumber = $latestOrder ? (int)explode('/', $latestOrder->os_number)[3] : 0;
    $lastStockSequentialNumber = $latestStock ? (int)explode('/', $latestStock->os_number)[3] : 0;
    $sequentialNumber = max($lastOrderSequentialNumber, $lastStockSequentialNumber) + 1;

    // Format the reference number
    $randStr = "YES/OS/{$currentYear}/" . str_pad($sequentialNumber, 3, '0', STR_PAD_LEFT);

    return $randStr;
  }


  public function getStock($id): Object
  {
    return Stock::find($id);
  }
  public function updateStock($id, array $userData)
  {
    $stock = Stock::findOrFail($id);
    $empId=Auth::id();
    $employeeManager = DB::table('employee_managers')
    ->join('employees', 'employee_managers.employee_id', '=', 'employees.id')
    ->where('employees.user_id', $empId)
    ->select('employee_managers.manager_id')
    ->first();

    $stock->update([
      'purchase_mode' => $userData['purchase_mode'],
      'os_date' => $userData['os_date'],
      'buying_price' => $userData['buying_price'],
      'created_by' =>  $employeeManager->manager_id,
    ]);

    if (isset($userData['item_name'])) {
      StockItem::where('stock_id', $id)->delete();
      foreach ($userData['item_name'] as $key => $itemName) {
        StockItem::create([
          'stock_id' => $stock->id,
          'item_name' => $itemName,
          'partno' => $userData['partno'][$key],
          'quantity' => $userData['quantity'][$key],
          'yes_number' => $userData['yes_number'][$key],
          'total_amount' => $userData['total_amount'][$key],
          'expected_delivery' => $userData['expected_delivery'][$key],
          'status' => $userData['status'][$key],
          'remarks' => $userData['item_remark'][$key],
        ]);
      }
    }

    if (isset($userData['supplier_id'])) {
      StockSupplier::where('stock_id', $stock->id)->update([
        'supplier_id' => $userData['supplier_id'],
        'price_basis' => $userData['price_basis'],
        'delivery_term' => $userData['delivery_term'],
        'remarks' => $userData['supplier_remark'],
      ]);
    }

    if (isset($userData['charges'])) {
      StockCharge::where('stock_id', $stock->id)->delete();
      foreach ($userData['charges'] as $index => $stockCharges) {
        StockCharge::create([
          'stock_id' => $stock->id,
          'title' => $stockCharges,
          'currency' => 'aed',
          'considered' => $userData['considered'][$index],
          'remarks' => $userData['charge_remark'][$index],
        ]);
      }
    }

    if (isset($userData['payment_term'])) {
      StockPayment::where('stock_id', $stock->id)->delete();
      foreach ($userData['payment_term'] as $index => $stockPayments) {
        StockPayment::create([
          'stock_id' => $stock->id,
          'payment_term' => $stockPayments,
          'expected_date' => $userData['expected_date'][$index],
          'status' => $userData['status'][$index],
          'remarks' => $userData['payment_remark'][$index],
        ]);
      }
    }

    return $stock;
  }


}
