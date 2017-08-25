<?php

foreach (Config::get('+plugin', []) as $k => $v) {
    if (Path::B($k) === '__index.php') {
        call_user_func(function() use($k) {
            extract(Lot::get(null, []));
            require $k;
        });
    }
}

// Load panel plugin(s)…
$__plu = PANEL . DS . 'lot' . DS . 'extend' . DS . 'plugin' . DS . 'lot' . DS . 'worker';
$__plugins = [];
foreach (g($__plu . DS . '*', '{index,__index}.php') as $v) {
    $__plugins[$v] = (float) File::open(Path::D($v) . DS . 'index.stack')->get(0, 10);
}
asort($__plugins);
Config::set('panel.+plugin', $__plugins);
foreach (array_keys($__plugins) as $v) {
    call_user_func(function() use($v) {
        extract(Lot::get(null, []));
        require $v;
    });
}

$__c = [];
foreach ($__plugins as $__k => $__v) {
    $__f = Path::D($__k) . DS;
    $__i18n = $__f . 'lot' . DS . 'language' . DS;
    if ($__l = File::exist([
        $__i18n . $config->language . '.page',
        $__i18n . 'en-us.page'
    ])) {
        $__c[$__l] = filemtime($__l);
    }
}

$__id = array_sum($__c);
if (Cache::expire($__plu, $__id)) {
    $__content = [];
    foreach ($__c as $__k => $__v) {
        $__i18n = new Page($__k, [], 'language');
        $__fn = 'From::' . __c2f__($__i18n->type, '_');
        $__c = $__i18n->content;
        $__content = array_replace_recursive($__content, is_callable($__fn) ? call_user_func($__fn, $__c) : (array) $c);
    }
    Cache::set($__plu, $__content, $__id);
} else {
    $__content = Cache::get($__plu, []);
}

// Load panel plugin(s)’ language…
Language::set($__content);