<?php

namespace App\Controllers;

use App\Core\Controllers;
use App\Core\Request;
use App\Exceptions\ClassNotFoundException;
use App\Models\Customer;
use DateTimeImmutable;

class AuthController extends Controllers
{
    public function createCustomer(Request $request): string
    {
        $customer = new Customer();
        if($request->isPost()){
            $customer->loadData($request->getBody());
            if($customer->validate() && $customer->save()){
                return $this->render(['Success'=>"Customer created"]);
            }
            return $this->render($customer->errors);
        }
        return $this->render(['error' => 'Something went wrong.']);
    }

    /**
     * @throws ClassNotFoundException
     */
    public function getSingleCustomer(Request $request, int $customer_id): string
    {
        $customer = new Customer();
        if($request->isGet()){
            $customer->setCustomerID($customer_id);
            if($customer->getCustomerID() && empty($customer->errors)){
                $singleCustomer = $customer->findOne(["customer_id"=>$customer->customer_id]);
                return $this->render($singleCustomer->hydrated());
            }
            return $this->render($customer->errors);
        }
        return $this->render(['error' => 'Something went wrong.']);
    }
    public function getAllCustomers(Request $request): string
    {
        $customer = new Customer();
        if($request->isGet()){
            $hydratedList = [];
            foreach ($customer->findMany() as $row) {
                $hydratedList[] = $customer->hydrateFromArray($row)->hydrated();
            }
            return $this->render($hydratedList);
        }
        return $this->render(['error' => 'Something went wrong.']);
    }
    public function updateCustomer(Request $request): string
    {
        $customer = new Customer();
        if($request->isPut()){
            $customer->loadData($request->getBody());
            if($customer->getCustomerID() && empty($customer->errors)){
                $updateData = $customer->loadedFields;
                unset($updateData[$customer->primaryKey()]);
                if($customer->updateOne($updateData, ["customer_id"=>$customer->customer_id])){
                    return $this->render(['success' => 'Customer edited successfully.']);
                }
            }
            return $this->render($customer->errors);
        }
        return $this->render(['error' => 'Something went wrong.']);
    }

    /**
     * @throws ClassNotFoundException
     */
    public function login(Request $request): string
    {
        $customer = new Customer();
        if($request->isPost()){
            $customer->loadData($request->getBody());
            /**
             *@var Customer $customerFromDb
             */
            $customerFromDb = (new Customer())->findOne(['customer_email' => $customer->customer_email]);
            if($customer->confirmPassword($customer->customer_password, $customerFromDb->customer_password) && empty($customer->errors)){
                $issuedAt = new DateTimeImmutable();
                $exp = $issuedAt->modify('+6 minutes')->getTimestamp();
                $payload = [
                    'iss' => 'Avetools',
                    'type' => $customerFromDb->customer_type,
                    'sub' => $customerFromDb->customer_id,
                    'name'=> $customerFromDb->customer_name,
                    'exp' => $exp,
                ];
                $customerFromDb->setTokenPayload($payload);
                return $this->render($customerFromDb->hydrated());
            }
            return $this->render($customer->errors);
        }
        return $this->render(['error' => 'Something went wrong.']);
    }
}