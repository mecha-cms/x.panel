<?php

// `http://127.0.0.1/panel/::g::/page/foo-bar.page`
$GLOBALS['_']['layout'] = $_['layout'] = [
    'archive' => 'page.page',
    'data' => 'data',
    'draft' => 'page.page',
    'page' => 'page.page'
][pathinfo($_['f'], PATHINFO_EXTENSION)] ?? $_['layout'];

return require __DIR__ . DS . '..' . DS . $_['layout'] . '.php';