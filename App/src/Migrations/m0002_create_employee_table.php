<?php

use App\Core\App;

class m0002_create_employee_table
{
    public function up()
    {
        $db = App::$app->db;
        $sql = "CREATE TABLE IF NOT EXISTS `employee`(
        `employee_id` int PRIMARY KEY NOT NULL,
        `dt_insert` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        ) ENGINE = InnoDB
        DEFAULT CHARSET = latin1;";
        $db->pdo->exec($sql);

    }

    public function down()
    {
        $db = App::$app->db;
        $sql = "DROP TABLE IF EXISTS `employee`;";
        $db->pdo->exec($sql);
    }

}