<?php

if (!empty($_GET['tab'][0]) && 'license' === $_GET['tab'][0] && !is_file($f = ENGINE . DS . 'log' . DS . '5768f419')) {
    if (!is_dir($d = dirname($f))) {
        mkdir($d, 0775, true);
    }
    file_put_contents($f, date('Y-m-d H:i:s'));
}
