<?php

$_['alert'] = [];
$_['kick'] = $_['form']['lot']['kick'] ?? $url;

is_file($f = ENGINE . DS . 'log' . DS . 'error') && unlink($f);

return $_;