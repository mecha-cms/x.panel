<?php

$name = $_['state']['name'];
if ($name && is_file($f = __DIR__ . DS . '..' . DS . '..' . DS . 'lot' . DS . 'content' . DS . $name . DS . 'index.php')) {
    require $f;
}