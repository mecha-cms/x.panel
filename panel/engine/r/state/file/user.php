<?php

// `http://127.0.0.1/panel/::g::/user/foo-bar.page`
$GLOBALS['_']['content'] = $_['content'] = [
    'archive' => 'page.user',
    'data' => 'data',
    'draft' => 'page.user',
    'page' => 'page.user'
][pathinfo($_['f'], PATHINFO_EXTENSION)] ?? $_['content'];

return require __DIR__ . DS . '..' . DS . $_['content'] . '.php';