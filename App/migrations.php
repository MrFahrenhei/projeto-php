<?php

use App\Core\App;

require_once __DIR__.'/vendor/autoload.php';

$app = new App(__DIR__);
$app->db->applyMigrations();

//docker exec -it php_service php /var/www/html/migrations.php