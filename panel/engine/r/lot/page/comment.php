<?php

// `http://127.0.0.1/panel/::g::/comment/2019-10-11-23-14-06.page`
$_['type'] = [
    'archive' => 'page/comment',
    'data' => 'data',
    'draft' => 'page/comment',
    'page' => 'page/comment'
][pathinfo($_['f'], PATHINFO_EXTENSION)] ?? $_['type'];

$lot = require __DIR__ . DS . '..' . DS . 'page.php';

// TODO

return $lot;
