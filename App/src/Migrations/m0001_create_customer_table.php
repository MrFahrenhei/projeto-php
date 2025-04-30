<?php

use App\Core\App;

class m0001_create_customer_table
{
    public function up()
    {
        $db = App::$app->db;
        $sql = "CREATE TABLE IF NOT EXISTS `customer`(
        `customer_id`         int AUTO_INCREMENT PRIMARY KEY       NOT NULL,
        `customer_name`       varchar(255)                             DEFAULT NULL,
        `customer_type`       enum ('admin','employee', 'support') DEFAULT NULL,
        `customer_email`      varchar(255)                             DEFAULT NULL,
        `customer_password`   varchar(255)                             DEFAULT NULL,
        `customer_contact`    varchar(15)                              DEFAULT NULL,
        `customer_address`     varchar(200)                             DEFAULT NULL,
        `dt_insert`           TIMESTAMP DEFAULT CURRENT_TIMESTAMP      NOT NULL) 
        ENGINE = InnoDB
        DEFAULT CHARSET = latin1;";
        $db->pdo->exec($sql);

    }

    public function down()
    {
        $db = App::$app->db;
        $sql = "DROP TABLE IF EXISTS `customer`";
        $db->pdo->exec($sql);
    }

}