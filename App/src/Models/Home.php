<?php

namespace App\Models;

use App\Core\Model;

class Home extends Model
{
    public int $home_id = 0;
    public string $home_address = "";
    public string $home_cep = "";
    public bool $status = true;
    public string $dt_insert;
    public function tableName(): string
    {
        return "home";
    }
    public function attributes(): array
    {
        return ["home_address", "home_cep"];
    }
    public function primaryKey(): string
    {
        return "home_id";
    }
    public function getHomeID(): false|int
    {
       if(!$this->home_id) {
          $this->addError("error", "no id found");
          return false;
       }
       return $this->home_id;
    }
    public function rules(): array
    {
        return [
            "home_address" => [self::RULE_REQUIRED,
                [self::RULE_UNIQUE, 'class'=>self::class]
            ],
            "home_cep" => [self::RULE_REQUIRED],
        ];
    }
    public function hydrated(): array
    {
        return ["home_id"=> $this->home_id, "home_address" => $this->home_address, "home_cep" => $this->home_cep, "status" => $this->status, "dt_insert" => $this->dt_insert];
    }
}