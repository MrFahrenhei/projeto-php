<?php

namespace App\Core;

abstract class Rules
{
    public array $loadedFields = [];
    public const string RULE_REQUIRED = 'required';
    public const string RULE_EMAIL = 'email';
    public const string RULE_UNIQUE = 'unique';
    public const string RULE_MIN = 'min';
    public const string RULE_VALID_ID = 'valid_id';
    public const string RULE_ROLES = 'roles';
    public array $errors = [];
    abstract public function rules(): array;
    public function loadData(array $data): void
    {
        if (empty($data)) {
            if (property_exists($this, 'errors')) {
                $this->addError("error", "empty body");
            }
            return;
        }
        foreach($data as $key => $value){
            if(property_exists($this, $key)){
                $this->{$key} = $value;
            }
        }
    }
    public function validate(): bool
    {
        foreach($this->rules() as $attribute => $rules){
            $value = $this->{$attribute};
            foreach($rules as $rule){
                $ruleName = $rule;
                if(!is_string($rule)){
                    $ruleName = $rule[0];
                }
                if($ruleName === self::RULE_REQUIRED && !$value){
                    $this->addErrorForRule($attribute, self::RULE_REQUIRED);
                }
                if($ruleName === self::RULE_EMAIL && !filter_var($value, FILTER_VALIDATE_EMAIL)){
                    $this->addErrorForRule($attribute, self::RULE_EMAIL);
                }
                if($ruleName === self::RULE_MIN && strlen($value) < $rule['min']){
                    $this->addErrorForRule($attribute, self::RULE_MIN, $rule);
                }
                if($ruleName === self::RULE_ROLES && !in_array($value, ['admin','employee','support'])){
                    $this->addErrorForRule($attribute, self::RULE_ROLES);
                }
                if($ruleName === self::RULE_UNIQUE){
                    $className = $rule['class'];
                    $tableName = $className::tableName();
                    $unique = $rule['attribute'] ?? $attribute;
                    $stmt = App::$app->db->prepare("SELECT * FROM $tableName WHERE $unique = :attr");
                    $stmt->bindValue(':attr', $value);
                    $stmt->execute();
                    $result = $stmt->fetchObject($className);
                    if($result){
                        $this->addErrorForRule($attribute, self::RULE_UNIQUE, ['field'=>$attribute]);
                    }
                }
            }
        }
       return empty($this->errors);
    }

    private function addErrorForRule(string $attribute, string $rule, array $params = []): void
    {
        $message = $this->errorMessage()[$rule] ?? '';
        foreach($params as $key => $value){
            $message = str_replace(":$key", $value, $message);
        }
        $this->errors[$attribute][] = $message;
    }

    public function addError(string $attribute, string $message): void
    {
       $this->errors[$attribute][] = $message;
    }
    private function errorMessage(): array
    {
        return [
            self::RULE_REQUIRED => 'This field is required.',
            self::RULE_EMAIL => 'The field must be a valid email address.',
            self::RULE_UNIQUE => 'This field must be unique, :field already exists.',
            self::RULE_MIN => 'This field must be at least :min characters.',
            self::RULE_VALID_ID => 'This field must be a valid ID.',
            self::RULE_ROLES => 'This field must be of one of the type.',
        ];
    }
}