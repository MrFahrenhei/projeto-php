<?php

namespace App\Models;

use App\Core\JwtAuth;
use App\Core\Model;
use Exception;

class Customer extends Model
{
    public int $customer_id;
    public string $customer_name = '';
    public string $customer_type = '';
    public string $customer_email = '';
    public string $customer_password = '';
    public string $customer_contact = '';
    public string $customer_address = '';
    public string $token;
    public string $dt_insert;

    public function tableName(): string
    {
        return 'customer';
    }
    public function attributes(): array
    {
        return ['customer_name','customer_type', 'customer_email', 'customer_password', 'customer_contact', 'customer_address'];
    }

    public function primaryKey(): string
    {
        return "customer_id";
    }
    public function getRoles(): array
    {
        return ['employee'=>Employee::class];
    }
    public function setCustomerID(int $customer_id): void
    {
       $this->customer_id = $customer_id;
    }
    public function getCustomerID(): false|int
    {
        if(!$this->customer_id){
            $this->addError("error", "no id found");
            return false;
        }
        return $this->customer_id;
    }
    public function save(): bool
    {
        $this->customer_password = password_hash($this->customer_password, PASSWORD_ARGON2ID);
        parent::beginTransaction();
        $result = true;
        try{
            $customer_save = parent::save();
            if(!$customer_save){
                parent::rollback();
                $result = false;
            }
            $roles = $this->getRoles();
            if(array_key_exists($this->customer_type, $roles)){
                $class = $roles[$this->customer_type];
                $instance = new $class();
                $instance->{$instance->primaryKey()} = $this->customer_id;
                $saved = $instance->save();
                if(!$saved){
                    parent::rollback();
                    $result = false;
                }
                parent::commit();
            }
        }catch (Exception $e){
            echo $e->getMessage().PHP_EOL;
            parent::rollback();
            $result = false;
        }
        return $result;
    }
    public function rules(): array
    {
        return [
            'customer_name' => [self::RULE_REQUIRED],
            'customer_type' => [
                self::RULE_REQUIRED,
                self::RULE_ROLES,
            ],
            'customer_email' => [
                self::RULE_REQUIRED,
                self::RULE_EMAIL,
                [self::RULE_UNIQUE, 'class'=>self::class]
            ],
            'customer_password' => [
                self::RULE_REQUIRED,
                [self::RULE_MIN, 'min'=>5]
            ],
        ];
    }
    public function setTokenPayload(array $payload): void
    {
        $this->token = (new JwtAuth())::generate($payload);
    }
    public function hydrated(): array
    {
        $return = [
            "customer_id"=> $this->customer_id,
            "customer_name"=> $this->customer_name,
            "customer_type"=>$this->customer_type,
            "customer_email"=> $this->customer_email,
            "customer_contact"=> $this->customer_contact,
            "customer_address"=> $this->customer_address,
            "dt_insert"=> $this->dt_insert,
        ];
        if(!empty($this->token)){
            $return["token"] = $this->token;
        }
        return $return;
    }
}