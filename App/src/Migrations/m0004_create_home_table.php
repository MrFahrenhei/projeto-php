<?php

use App\Core\App;

class m0004_create_home_table
{
    public function up()
    {
        $db = App::$app->db;
        $sql = "CREATE TABLE IF NOT EXISTS `home`(
        `home_id`          int(11) NOT NULL AUTO_INCREMENT,
        `home_address`        varchar(255) DEFAULT NULL,
        `home_cep` int(11)      DEFAULT NULL,
        `status`                  BOOLEAN DEFAULT true,
        `dt_insert`               TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        PRIMARY KEY (`home_id`)
        ) ENGINE = InnoDB
          DEFAULT CHARSET = latin1;";
        $db->pdo->exec($sql);
    }

    public function down()
    {
        $db = App::$app->db;
        $sql = "DROP TABLE IF EXISTS `home`;";
        $db->pdo->exec($sql);
    }
}
