<?php

namespace App\Services;

use App\Models\Currency;
use Illuminate\Http\Request;

class CurrencyService
{
    public function getAllCurrency($data = []): Object
    {
        return Currency::where('status', 1)
            ->orderBy("code", "asc")->get();
    }
    public function getCurrency($id): Object
    {
        return Currency::find($id);
    }

    public function createCurrency(array $userData): Currency
    {
        return Currency::create([
            'name'    => $userData['name'],
            'code'    => $userData['code'],
            'status'    => $userData['status']
        ]);
    }

    public function DeleteCurrency($id): void
    {
        // delete user
        $currency = Currency::find($id);
        $currency->delete();
    }

    public function updateCurrency(Currency $currency, array $userData): void
    {
        $update = [];

        if (isset($userData['name']) && !empty($userData['name'])) {
            $update['name'] = $userData['name'];
        }
        if (isset($userData['code']) && !empty($userData['code'])) {
            $update['code'] = $userData['code'];
        }
        if (isset($userData['status']) && !empty($userData['status'])) {
            $update['status'] = $userData['status'];
        }

        $currency->update($update);
    }
}
