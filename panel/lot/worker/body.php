<div class="c">
<?php

$__f = File::exist([
    $__path_shield . DS . $site->is . DS . $__chops[0] . '.php',
    __DIR__ . DS . $site->is . DS . $__chops[0] . '.php'
]);

if (!$__f) {
    foreach (glob(EXTEND . DS . '*' . DS . 'lot' . DS . 'worker' . DS . $site->is . DS . $__chops[0] . '.php', GLOB_NOSORT) as $__v) {
        $__f = $__v;
        break;
    }
}

Shield::get($__f);

?>
</div>