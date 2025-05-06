<?php

namespace App\Core;

use App\Exceptions\HomeNotFoundException;
use App\Models\Home;
use PDO;
use PDOStatement;
use stdClass;

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

    public function findMany(mixed $where): array
    {
       $tableName = $this->tableName();
       $attributes = array_keys($where);
       $sql = $this->implodeMany($attributes);
       $stmt = self::prepare("SELECT * FROM $tableName WHERE $sql");
       foreach($where as $attribute => $value) {
          $stmt->bindValue(":$attribute", $value);
       }
       $stmt->execute();
       return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * @throws HomeNotFoundException
     */
    public function findOne(mixed $where, $tableName = null, $className = null): Home
    {
        if(is_null($tableName)) {
            $tableName = static::tableName();
        }
        if(is_null($className)) {
            $className = static::class;
        }
        $attributes = array_keys($where);
        $sql = $this->implodeMany($attributes);
        $stmt = self::prepare("SELECT * FROM $tableName WHERE $sql");
        foreach($where as $attribute => $value) {
            $stmt->bindValue(":$attribute", $value);
        }
        $stmt->execute();
        $obj = $stmt->fetchObject($className);
        return ($obj) ?: throw new HomeNotFoundException();
    }

    public function deleteOne(mixed $seted, mixed $where, $tableName = null): bool
    {
        if(is_null($tableName)) {
            $tableName = static::tableName();
        }
        $setIT = array_keys($seted);
        $whereIt = array_keys($where);
        $setedSql = $this->implodeMany($setIT);
        $whereSql = $this->implodeMany($whereIt);
        $stmt = self::prepare("UPDATE $tableName SET $setedSql WHERE $whereSql");
        $this->bindValues($stmt, $where);
        $this->bindValues($stmt, $seted);
        return $stmt->execute();
    }

    public function updateOne(array $seted, mixed $where, $tableName = null): bool
    {
        if(is_null($tableName)) {
            $tableName = static::tableName();
        }
        $whereIt = array_keys($where);
        $setIt = array_keys($seted);
        $setedSQL = $this->implodeMany($setIt, ' , ');
        $whereSQL = $this->implodeMany($whereIt);
        $stmt = self::prepare("UPDATE $tableName SET $setedSQL WHERE $whereSQL");
        $this->bindValues($stmt, $where);
        $this->bindValues($stmt, $seted);
        return $stmt->execute();
    }
    private function implodeMany(mixed $values, string $div = " AND "): string
    {
        return implode($div, array_map(fn($attr) => "$attr = :$attr", $values));
    }
    private function bindValues(PDOStatement $stmt, array $data): void
    {
        foreach ($data as $key => $value) {
            $paramType = PDO::PARAM_STR;
            if (is_bool($value)) {
                $paramType = PDO::PARAM_BOOL;
            } elseif (is_int($value)) {
                $paramType = PDO::PARAM_INT;
            } elseif (is_null($value)) {
                $paramType = PDO::PARAM_NULL;
            }
            $stmt->bindValue(":$key", $value, $paramType);
        }
    }
    public function prepare(string $sql): PDOStatement
    {
        return App::$app->db->pdo->prepare($sql);
    }
    public function beginTransaction(): bool
    {
        return App::$app->db->pdo->beginTransaction();
    }
    public function commit(): bool
    {
        return App::$app->db->pdo->commit();
    }
    public function rollback(): bool
    {
       return App::$app->db->pdo->rollBack();
    }
}