<?php

namespace App\Models;

use App\Core\Model;

class Customer extends Model
{
    public int $customer_id;
    public string $customer_name = '';
    public string $customer_type = '';
    public string $customer_email = '';
    public string $customer_password = '';
    public string $customer_contact = '';
    public string $customer_address = '';
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

    public function save(): bool
    {
        $this->customer_password = password_hash($this->customer_password, PASSWORD_ARGON2ID);
        return parent::save();
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
}