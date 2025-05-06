<?php

namespace App\Models;

use App\Core\Model;

class Employee extends Model
{
    public int $employee_id;
    public string $dt_insert;
    public function tableName(): string
    {
        return 'employee';
    }
    public function attributes(): array
    {
        return ['employee_id'];
    }
    public function primaryKey(): string
    {
        return 'employee_id';
    }
    public function rules(): array
    {
        return [];
    }
}