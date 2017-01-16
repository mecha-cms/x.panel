<?php

$plugins = [];

foreach (glob(EXTEND . DS . 'plugin' . DS . 'lot' . DS . 'worker' . DS . '*' . DS . '__index.php') as $v) {
    $s = Path::D($v) . DS;
    $plugins[$v] = (float) File::open([$s . '__index.stack', $s . 'index.stack'])->get(0, 10);
}

asort($plugins);

foreach (array_keys($plugins) as $v) {
    require $v;
}