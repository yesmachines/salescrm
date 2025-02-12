<?php

namespace App\Services;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use DB;
use Illuminate\Support\Arr;
use App\Models\Order;
use App\Models\PrDeliveryTerm;
use App\Models\PrPaymentTerm;
use App\Models\PrItem;
use App\Models\PurchaseRequisition;
use App\Models\PrCharge;
use Faker\Core\Number;

class PurchaseRequisitionService
{
    public function getPurchaseRequisition($id): Object
    {
        return PurchaseRequisition::find($id);
    }
    public function insertPR(array $userData): Object
    {
        $purchase = PurchaseRequisition::create($userData);

        return $purchase;
    }
    public function getReferenceNumber($reference,$count): ?string
    {
        // Fetch the latest order and stock records for the current year
        $latestPR = PurchaseRequisition::where('pr_number', 'LIKE', "{$reference}%")->latest()->first();

        $lastNum = 0;
        if ($latestPR && isset($latestPR->pr_number)) {
            $reference_array = $latestPR ? explode('_', $latestPR->pr_number) : 0;
            $lastNum = isset($reference_array[2]) ? $reference_array[2] : 1;
        } else {
            $reference_array = explode("_", $reference);
        }
        // Determine the maximum sequential number among the latest order and stock
        $next = $lastNum + 1;
        $reference_num = implode("_", array($reference_array[0], $reference_array[1], $count));

        return $reference_num;
    }
    public function insertPRSupplierDetails(array $userData): Object
    {
        $purchaseDelivery = PrDeliveryTerm::create($userData);

        return $purchaseDelivery;
    }
    public function updatePRSupplierDetails(array $userData, $id): int
    {
        $purchaseDelivery = PrDeliveryTerm::where('pr_id', $id)->update($userData);

        return $purchaseDelivery;
    }
    public function insertPRItems(array $userData): Object
    {
        $purchaseItem = PrItem::create($userData);

        return $purchaseItem;
    }

    public function deletePRItems($ids): int
    {
        return PrItem::whereIn('id', $ids)->delete();
    }
    public function updatePRItems(array $userData, $id): int
    {
        $purchaseItem = PrItem::find($id)->update($userData);

        return $purchaseItem;
    }
    public function insertPRPaymentTerms(array $userData): Object
    {
        $purchasePaymentTerm = PrPaymentTerm::create($userData);

        return $purchasePaymentTerm;
    }
    public function updatePRPaymentTerms(array $userData, $id): int
    {
        $purchasePaymentTerm = PrPaymentTerm::find($id)->update($userData);

        return $purchasePaymentTerm;
    }
    public function insertPRCharges(array $userData): Object
    {
        $purchaseCharges = PrCharge::create($userData);

        return $purchaseCharges;
    }
    public function updatePRCharges(array $userData, $id): int
    {
        $purchaseCharges = PrCharge::find($id)->update($userData);

        return $purchaseCharges;
    }
    public function updatePR(array $userData): Object
    {
        $purchase = PurchaseRequisition::find($userData['pr_id']);

        $purchase->total_price = $userData['total_price'];
        $purchase->save();

        return $purchase;
    }
    public function deletePR($id): void
    {
        PrItem::where("pr_id", $id)->delete();

        PrPaymentTerm::where("pr_id", $id)->delete();

        PrDeliveryTerm::where("pr_id", $id)->delete();

        PurchaseRequisition::find($id)->delete();
    }
}
