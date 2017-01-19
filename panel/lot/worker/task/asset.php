<?php

$s = PANEL . DS . 'lot' . DS . 'asset' . DS . 'css' . DS;

Asset::set([
    $s . 'panel.min.css',
    $state['tools']['code-mirror'] ? $s . 'panel.code-mirror.min.css' : false,
    $state['tools']['t-i-b'] ? $s . 'panel.t-i-b.min.css' : false
], [
    10,
    11,
    12
]);

$s = PANEL . DS . 'lot' . DS . 'asset' . DS . 'js' . DS;

Asset::set([
    $s . 'panel.min.js',
    $state['tools']['code-mirror'] ? $s . 'panel.code-mirror.min.js' : false,
    $state['tools']['code-mirror'] ? $s . 'panel.code-mirror.fire.min.js' : false,
    $state['tools']['t-i-b'] ? $s . 'panel.t-i-b.min.js' : false,
    $state['tools']['t-i-b'] ? $s . 'panel.t-i-b.fire.min.js' : false
], [
    10,
    11,
    11.1,
    12,
    12.1
]);

if ($fn = File::exist($shield_path . DS . 'index.php')) require $fn;

Hook::set('shield.output', function($content) {
    $s = [];
    foreach (get_defined_constants(true)['user'] as $k => $v) {
        $s[] = '$.' . $k . '=' . s(json_encode($v)) . ';';
    }
    return str_replace('</body>', '<script>!function($){' . implode("", $s) . '$.Language.lot=' . json_encode(Language::get()) . '}(Panel);</script></body>', $content);
}, 1);