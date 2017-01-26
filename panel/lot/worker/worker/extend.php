<?php

$__extends = [];
foreach (glob(EXTEND . DS . '*' . DS . '__index.php') as $v) {
    $s = Path::D($v) . DS;
    $__extends[$v] = (float) File::open([$s . '__index.stack', $s . 'index.stack'])->get(0, 10);
}
asort($__extends);
foreach (array_keys($__extends) as $v) require $v;