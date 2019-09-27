<?php

// `http://127.0.0.1/panel/::g::/page/1`
$GLOBALS['_']['content'] = 'page';

return require __DIR__ . DS . '..' . DS . $_['content'] . 's.php';