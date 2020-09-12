<?php

// `http://127.0.0.1/panel/::g::/.state`
$_['layout'] = 'state';

return require __DIR__ . DS . '..' . DS . $_['layout'] . '.php';
