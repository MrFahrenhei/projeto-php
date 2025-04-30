<?php

use App\Core\App;

class m0003_add_constraint_employee
{
    public function up()
    {
        $db = App::$app->db;
        $checkConstraintSql = "SELECT COUNT(*) 
                FROM information_schema.TABLE_CONSTRAINTS 
                WHERE CONSTRAINT_SCHEMA = DATABASE() AND 
                TABLE_NAME = 'employee' AND CONSTRAINT_NAME = 'employee_ibfk_1';";
        $constraintExists = $db->pdo->query($checkConstraintSql)->fetchColumn();
        if (!$constraintExists) {
            $sql = "ALTER TABLE `employee` 
            ADD CONSTRAINT `employee_ibfk_1` FOREIGN KEY (`employee_id`) REFERENCES `customer` (`customer_id`);";
            $db->pdo->exec($sql);
        }

    }

    public function down()
    {
        $db = App::$app->db;
        $sql = "ALTER TABLE `employee` DROP FOREIGN KEY `employee_ibfk_1`;";
        $db->pdo->exec($sql);
    }


}