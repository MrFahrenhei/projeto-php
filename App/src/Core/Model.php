<?php

namespace App\Core;

use PDOStatement;

abstract class Model extends Rules
{
    abstract public function tableName(): string;
    abstract public function attributes(): array;
    abstract public function primaryKey(): string;

    public function save(): bool
    {
       $tableName = $this->tableName();
       $attributes = $this->attributes();
       $param = array_map(fn($attr) => ":$attr", $attributes);
       $stmt = self::prepare(
           "INSERT INTO $tableName 
           (" . implode(', ', $attributes) . ") 
           VALUES 
           (" . implode(', ', $param) . ")
           ");
       foreach ($attributes as $attribute) {
           $stmt->bindValue(":$attribute", $this->{$attribute});
       }
       $result = $stmt->execute();
       $this->{$this->primaryKey()} = App::$app->db->pdo->lastInsertId();
       return $result;
    }
    public function prepare(string $sql): PDOStatement
    {
        return App::$app->db->pdo->prepare($sql);
    }
}