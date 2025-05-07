<?php

namespace App\Controllers;

use App\Core\Controllers;
use App\Core\Request;
use App\Models\Customer;

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

    public function getSingleCustomer(Request $request): string
    {
        $customer = new Customer();
        if($request->isGet()){
            $customer->loadData($request->getBody());
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
        if($request->isPost()){
            $customer->loadData($request->getBody());
            if($customer->getCustomerID() && empty($customer->errors)){
                $updateData = $customer->loadedFields;
                echo json_encode($updateData);
                unset($updateData[$customer->primaryKey()]);
                if($customer->updateOne($updateData, ["customer_id"=>$customer->customer_id])){
                    return $this->render(['success' => 'Customer edited successfully.']);
                }
            }
            return $this->render($customer->errors);
        }
        return $this->render(['error' => 'Something went wrong.']);
    }
}