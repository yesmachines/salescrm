<?php

namespace App\Services;

use App\Models\Company;
use App\Models\Customer;
use Illuminate\Http\Request;

class CustomerService
{

    public function createCustomer(array $userData): Customer
    {
        return Customer::create([
            'fullname'      => $userData['fullname'],
            'company_id'    => $userData['company_id'],
            'email'         => $userData['email'],
            'phone'         => $userData['phone'],
            // 'location'      => isset($userData['location']) ? $userData['location'] : ''
        ]);
    }
    public function createCompany(array $userData): Company
    {
        return Company::create([
            'company'       => $userData['company'],
            'reference_no'  => $this->getReferenceNumber(),
            'email_address' => (isset($userData['email_address'])) ? $userData['email_address'] : '',
            'landphone'     => (isset($userData['landphone'])) ? $userData['landphone'] : '',
            'address'       => (isset($userData['address'])) ? $userData['address'] : '',
            'website'       => (isset($userData['website'])) ? $userData['website'] : '',
            'region_id'     => isset($userData['region_id']) ? $userData['region_id'] : '',
            'country_id'    => isset($userData['country_id']) ? $userData['country_id'] : ''
        ]);
    }

    public function getReferenceNumber(): ?string
    {
        $last = Company::latest()->first();
        $lastId = ($last) ? $last->id : 0;

        $randStr =  "YES/COM/" . ($lastId + 1);

        return $randStr;
    }
    public function getAllCustomer($data = []): Object
    {
        $sql = Customer::orderBy('id', 'desc');
        if (isset($data['company_id'])) {
            $sql->where('company_id', $data['company_id']);
        }
        if (isset($data['status'])) {
            $sql->where('status', $data['status']);
        }

        if (isset($data['fullname'])) { // search for customer
            $fname = trim($data['fullname']);

            $sql->where('fullname', 'like', $fname . '%');
        }

        if (isset($data['company'])) { // search for company
            $name = trim($data['company']);

            $sql->whereHas('company', function ($query) use ($name) {
                $query->where('company', 'like', $name . '%');
            });
            // ->with(['companies' => function ($query) use ($name) {
            //     $query->where('company', 'like', '%' . $name . '%');
            // }]);
        }
        return $sql->get();
    }

    public function getCompanies(): array
    {
        return Company::where('status', 1)->orderBy('company', 'asc')->pluck('company', 'id')->toArray();
    }

    public function getAllCompany($data = []): Object
    {
        $sql = Company::orderBy('company', 'asc');

        if (isset($data['company'])) { // search for company
            $company = trim($data['company']);

            $sql->where('company', 'LIKE', "%{$company}%");
        }
        if (isset($data['status'])) {
            $sql->where('status', $data['status']);
        }
        return $sql->orderBy('company', 'asc')->get();
    }


    public function getCompany($id): Object
    {
        return Company::find($id);
    }

    public function getCustomer($id): Object
    {
        return Customer::find($id);
    }

    public function DeleteCustomer($id): void
    {
        // delete user
        $customer = Customer::find($id);
        $customer->delete();
    }

    public function updateCustomer(Customer $customer, array $userData): void
    {
        $update = [
            'fullname'   => $userData['fullname'],
            'company_id' => $userData['company_id'],
            'email'      => $userData['email'],
            'phone'      => $userData['phone']
        ];
        // if (isset($userData['location']) && !empty($userData['location'])) {
        //     $update['location'] = $userData['location'];
        // }

        $customer->update($update);
    }

    public function updateCompany(Company $company, array $userData): void
    {

        if (isset($userData['company'])) {
            $update['company'] = $userData['company'];
        }
        if (isset($userData['email_address']) && !empty($userData['email_address'])) {
            $update['email_address'] = $userData['email_address'];
        }
        if (isset($userData['landphone']) && !empty($userData['landphone'])) {
            $update['landphone'] = $userData['landphone'];
        }
        if (isset($userData['address']) && !empty($userData['address'])) {
            $update['address'] = $userData['address'];
        }
        if (isset($userData['website']) && !empty($userData['website'])) {
            $update['website'] = $userData['website'];
        }
        if (isset($userData['region_id']) && !empty($userData['region_id'])) {
            $update['region_id'] = $userData['region_id'];
        }
        if (isset($userData['country_id']) && !empty($userData['country_id'])) {
            $update['country_id'] = $userData['country_id'];
        }

        $company->update($update);
    }
}
