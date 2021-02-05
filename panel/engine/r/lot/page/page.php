<?php

// `http://127.0.0.1/panel/::g::/page/foo-bar.page`
$_['type'] = [
    'archive' => 'page/page',
    'data' => 'data',
    'draft' => 'page/page',
    'page' => 'page/page'
][pathinfo($_['f'], PATHINFO_EXTENSION)] ?? $_['type'];

$_ = require __DIR__ . DS . '..' . DS . 'page.php';

return $_;
