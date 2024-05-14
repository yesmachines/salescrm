<?php

namespace App\Services;

use App\Models\CurrencyConversion;
use Illuminate\Http\Request;

class ConversionService
{
    public function getAllConversion($data = []): Object
    {
      return CurrencyConversion::where('status', 1)
    ->orderByDesc('created_at')
    ->get();

    }
    public function getConversion($id): Object
    {
        return CurrencyConversion::find($id);
    }

    public function createConversion(array $userData): CurrencyConversion
    {
        return CurrencyConversion::create([
            'currency'    => $userData['currency'],
            'standard_rate'    => $userData['standard_rate'],
            'date_on'    => $userData['date_on'],
            'status'    => $userData['status']
        ]);
    }

    public function DeleteConversion($id): void
    {
        // delete user
        $conversion = CurrencyConversion::find($id);
        $conversion->delete();
    }

    public function updateConversion(CurrencyConversion $conversion, array $userData): void
    {
        $update = [];

        if (isset($userData['currency']) && !empty($userData['currency'])) {
            $update['currency'] = $userData['currency'];
        }
        if (isset($userData['standard_rate']) && !empty($userData['standard_rate'])) {
            $update['standard_rate'] = $userData['standard_rate'];
        }
        if (isset($userData['date_on']) && !empty($userData['date_on'])) {
            $update['date_on'] = $userData['date_on'];
        }
        if (isset($userData['status']) && !empty($userData['status'])) {
            $update['status'] = $userData['status'];
        }

        $conversion->update($update);
    }
}
