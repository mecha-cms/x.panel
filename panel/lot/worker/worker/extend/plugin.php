<?php

$__plugins = [];

foreach (glob(EXTEND . DS . 'plugin' . DS . 'lot' . DS . 'worker' . DS . '*' . DS . '__index.php') as $v) {
    $s = Path::D($v) . DS;
    $__plugins[$v] = (float) File::open([$s . '__index.stack', $s . 'index.stack'])->get(0, 10);
}

asort($__plugins);

foreach (array_keys($__plugins) as $v) {
    require $v;
}