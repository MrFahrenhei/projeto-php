<?php

namespace App\Core;

use PDO;
use PDOException;
use PDOStatement;

class Database
{
    public PDO $pdo;
    public function __construct()
    {
        $host = getenv('MYSQL_HOST');
        $userDB = getenv('MYSQL_USER');
        $pswDB = getenv('MYSQL_PASSWORD');
        $nameDB = getenv('MYSQL_DATABASE');
        $dsn = "mysql:host=$host;dbname=$nameDB;charset=utf8mb4";
        $options = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_PERSISTENT => true,
        ];

        try {
            $this->pdo = new PDO($dsn, $userDB, $pswDB, $options);
        } catch (PDOException $e) {
            throw new PDOException($e->getMessage(), (int) $e->getCode());
        }
        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    public function applyMigrations(): void
    {
        $this->createMigrationTable();
        $newMigrations = [];
        $appliedMigrations = $this->getAppliedMigrations();
        $files = scandir(App::$ROOT_DIR.'/src/Migrations');
        $toApplyMigrations = array_diff($files, $appliedMigrations);
        foreach ($toApplyMigrations as $migration) {
            if($migration === '.' || $migration === '..') {
                continue;
            }
            require_once App::$ROOT_DIR.'/src/Migrations/'.$migration;
            $class = pathinfo($migration, PATHINFO_FILENAME);
            $instance = new $class();
            $this->log("Applying migration $migration").PHP_EOL;
            $instance->up();
            $this->log("Applied migration $migration").PHP_EOL;
            $newMigrations[] = $migration;
        }
        if(!empty($newMigrations)) {
            $this->saveMigration($newMigrations);
        }else{
            $this->log("No new Migrations were found.").PHP_EOL;
        }
    }
    private function createMigrationTable(): void
    {
        $this->pdo->exec("CREATE TABLE IF NOT EXISTS migrations (
        id INT AUTO_INCREMENT PRIMARY KEY, 
        migration VARCHAR(255), 
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        ) ENGINE = InnoDB;");
    }
    private function getAppliedMigrations(): array
    {
        $stmt = $this->pdo->prepare("SELECT migration FROM migrations;");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }
    private function saveMigration(array $migrations):void
    {
        $str = implode(", ", array_map(fn($m) => "('$m')", $migrations));
        $stmt = $this->pdo->prepare("INSERT INTO migrations (`migration`) VALUES $str;");
        $stmt->execute();
    }
    public function prepare(string $sql): false|PDOStatement
    {
        return $this->pdo->prepare($sql);
    }
    protected function log($message): void
    {
        echo '['.date("Y-m-d H:i:s").'] - '.$message.PHP_EOL;
    }
}