<?php

namespace App\Core;

class View
{
    public function renderView(mixed $params): false|string
    {
        header('Content-type: application/json');
        foreach ($params as $key => $value) {
            $$key = $value;
        }
        return json_encode($params, JSON_UNESCAPED_UNICODE);
    }
}