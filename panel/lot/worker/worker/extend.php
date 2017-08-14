<?php

foreach (Config::get('+extend', []) as $__k => $__v) {
    if (Path::B($__k) === '__index.php') {
        call_user_func(function() use($__k) {
            extract(Lot::get(null, []));
            require $__k;
        });
    }
}

// Load panel extension(s)…
$__ext = PANEL . DS . 'lot' . DS . 'extend';
$__extends = [];
foreach (g($__ext . DS . '*', '{index,__index}.php', "", false) as $__v) {
    $__extends[$__v] = (float) File::open(Path::D($__v) . DS . 'index.stack')->get(0, 10);
}
asort($__extends);
Config::set('panel.+extend', $__extends);
foreach (array_keys($__extends) as $__v) {
    call_user_func(function() use($__v) {
        extract(Lot::get(null, []));
        require $__v;
    });
}

$__c = [];
foreach ($__extends as $__k => $__v) {
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
if (Cache::expire($__ext, $__id)) {
    $__content = [];
    foreach ($__c as $__k => $__v) {
        $__i18n = new Page($__k, [], 'language');
        $__fn = 'From::' . __c2f__($__i18n->type, '_');
        $__c = $__i18n->content;
        $__content = array_replace_recursive($__content, is_callable($__fn) ? call_user_func($__fn, $__c) : (array) $__c);
    }
    Cache::set($__ext, $__content, $__id);
} else {
    $__content = Cache::get($__ext, []);
}

// Load panel extension(s)’ language…
Language::set($__content);