<?php

$s = PANEL . DS . 'lot' . DS . 'asset' . DS . 'css' . DS;

Asset::set([
    $s . 'panel.min.css',
    $s . 'panel.code-mirror.min.css',
    $s . 'panel.t-i-b.min.css'
], [
    10,
    11,
    12
]);

$s = PANEL . DS . 'lot' . DS . 'asset' . DS . 'js' . DS;

Asset::set([
    $s . 'panel.min.js',
    $s . 'panel.code-mirror.min.js',
    $s . 'panel.t-i-b.min.js',
    $s . 'panel.fire.min.js'
], [
    10,
    11,
    12,
    13
]);

if ($fn = File::exist($shield_path . DS . 'index.php')) require $fn;

Hook::set('shield.output', function($content) {
    $s = [];
    foreach (get_defined_constants(true)['user'] as $k => $v) {
        $s[] = '$.' . $k . '=' . s(json_encode($v)) . ';';
    }
    return str_replace('</body>', '<script>!function($){' . implode("", $s) . '$.Language.lot=' . json_encode(Language::get()) . '}(Panel);</script></body>', $content);
}, 1);