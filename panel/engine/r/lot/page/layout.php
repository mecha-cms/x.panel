<?php

$_ = require __DIR__ . DS . '..' . DS . 'page.php';

if ('post' !== $_['form']['type'] && 'php' === pathinfo($_['f'], PATHINFO_EXTENSION)) {
    $_['alert']['warning'][md5(__FILE__)] = 'Unless you are very familiar with what you are doing, I advise you not to edit the layout files directly through the control panel interface. It might prevent your page from loading completely when you make some mistakes.';
}

return $_;
