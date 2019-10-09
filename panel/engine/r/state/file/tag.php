<?php

// `http://127.0.0.1/panel/::g::/user/foo-bar.page`
$GLOBALS['_']['content'] = $_['content'] = [
    'archive' => 'page.tag',
    'data' => 'data',
    'draft' => 'page.tag',
    'page' => 'page.tag'
][pathinfo($_['f'], PATHINFO_EXTENSION)] ?? $_['content'];

// TODO: Tag ID

return require __DIR__ . DS . '..' . DS . $_['content'] . '.php';