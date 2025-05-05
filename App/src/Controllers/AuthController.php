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
}