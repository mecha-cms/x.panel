<?php

$extends = [];

foreach (glob(EXTEND . DS . '*' . DS . '__index.php') as $v) {
    $s = Path::D($v) . DS;
    $extends[$v] = (float) File::open([$s . '__index.stack', $s . 'index.stack'])->get(0, 10);
}

asort($extends);

foreach (array_keys($extends) as $v) {
    require $v;
}