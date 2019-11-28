<?php

if ('POST' !== $_SERVER['REQUEST_METHOD'] && 'php' === pathinfo($_['path'], PATHINFO_EXTENSION)) {
    $GLOBALS['_']['alert']['warning'][md5(__FILE__)] = 'Unless you are very familiar with what you are doing, I advise you not to edit the layout files directly through the control panel interface. It might prevent your page from loading completely when you make some mistakes.';
}

return require __DIR__ . DS . '..' . DS . $_['layout'] . '.php';