<?php

namespace App\Services;

use App\Models\Supplier;
use Illuminate\Http\Request;
use Image;
use Illuminate\Support\Facades\File;

class SupplierService
{
    public function uploadLogo(Request $request): ?string
    {
        $imageUrl = "";
        if ($request->hasfile('logo_url')) {

            $file = $request->file('logo_url');
            $assetName = $request->input('brand') . time();
            // generate a new filename. getClientOriginalExtension() for the file extension
            $filename =  $assetName . '.' . $file->getClientOriginalExtension();
            $imageUrl = 'brands/' . $filename;

            // save to storage/app/public/employees as the new $filename
            $image = Image::make($file);
            $image->save(storage_path('app/public/' . $imageUrl));
        }
        return $imageUrl;
    }

    public function getAllSupplier($data = []): Object
    {
        return Supplier::where('status', 1)->orderBy('brand', 'asc')->get();
    }
    public function getSupplier($id): Object
    {
        return Supplier::find($id);
    }

    public function createSupplier(array $userData): Supplier
    {
        return Supplier::create([
            'brand'     => $userData['brand'],
            'logo_url'  => $userData['logo_path'],
            'website'   => $userData['website'],
            'description' => isset($userData['description']) ? $userData['description'] : '',
            'country_id'  => $userData['country_id'],
            'manager_id'  => isset($userData['manager_id']) ? $userData['manager_id'] : 0
        ]);
    }

    public function deleteSupplier($id): void
    {
        // delete user
        $supplier = Supplier::find($id);
        $supplier->delete();
    }
    public function deleteImage(string $image_url): void
    {
        // delete image
        if ($image_url) {
            $image_path = storage_path('app/public/') . $image_url; // upload path  
            if (File::exists($image_path)) {
                File::delete($image_path);
            }
        }
    }

    public function updateSupplier(Supplier $supplier, array $userData): void
    {
        $update = [];

        if (isset($userData['brand']) && !empty($userData['brand'])) {
            $update['brand'] = $userData['brand'];
        }
        if (isset($userData['logo_path']) && !empty($userData['logo_path'])) {
            $update['logo_url'] = $userData['logo_path'];
        }
        if (isset($userData['website']) && !empty($userData['website'])) {
            $update['website'] = $userData['website'];
        }
        if (isset($userData['description']) && !empty($userData['description'])) {
            $update['description'] = $userData['description'];
        }
        if (isset($userData['country_id']) && !empty($userData['country_id'])) {
            $update['country_id'] = $userData['country_id'];
        }
        if (isset($userData['manager_id']) && !empty($userData['manager_id'])) {
            $update['manager_id'] = $userData['manager_id'];
        }

        $supplier->update($update);
    }

    public function getLocalSupplier($data = []): Object
    {
        return Supplier::where('status', 1)
            ->whereIn('country_id', [1]) // UAE supplier's only
            ->orderBy('brand', 'asc')
            ->get();
    }
}
