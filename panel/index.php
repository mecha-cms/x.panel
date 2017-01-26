<?php

define('PANEL', __DIR__);

$__state = Extend::state(__DIR__);

$s = PANEL . DS . 'lot' . DS;
define('PANEL_404', File::exist([
    $s . 'shield' . DS . $__state['shield'] . DS . '404.php',
    $s . 'worker' . DS . '404.php',
    SHIELD . DS . $config->shield . DS . '404.php'
]));

require __DIR__ . DS . 'engine' . DS . 'plug' . DS . 'form.php';
require __DIR__ . DS . 'engine' . DS . 'fire.php';