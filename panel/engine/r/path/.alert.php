<?php

if (1 === count($_['chops']) && !empty($_GET['count'])) {
    http_response_code(200);
    header('Content-Type: text/plain');
    $i = q(g(LOT . $_['path'], 'page'));
    echo $i > 0 ? $i : "";
    exit;
}