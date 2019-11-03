<?php

// `http://127.0.0.1/panel/::g::/user/foo-bar.page`
$GLOBALS['_']['layout'] = $_['layout'] = [
    'archive' => 'page.tag',
    'data' => 'data',
    'draft' => 'page.tag',
    'page' => 'page.tag'
][pathinfo($_['f'], PATHINFO_EXTENSION)] ?? $_['layout'];

return require __DIR__ . DS . '..' . DS . $_['layout'] . '.php';