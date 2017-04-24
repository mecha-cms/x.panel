<?php

$__nav = (array) Config::get('panel.n', []);

$__o = [];

if (!isset($__nav['error']) || $__nav['error'] !== false) {
    if ($__log = File::exist(ENGINE . DS . 'log' . DS . 'error.log')) {
        preg_match_all('#^\s*\[(.+?)\]#m', File::open($__log)->read(), $__errors);
        if (!empty($__errors[1])) {
            $__o['error'] = isset($__nav['error']) && is_array($__nav['error']) ? $__nav['error'] : [
                'text' => $language->errors,
                'attributes' => [
                    'href' => $__state->path . '/::g::/error'
                ],
                'i' => count(array_unique($__errors[1]))
            ];
        }
    }
    unset($__nav['error']);
}

if (!isset($__nav['exit']) || $__nav['exit'] !== false) {
    $__o['exit'] = isset($__nav['exit']) && is_array($__nav['exit']) ? $__nav['exit'] : [
        'text' => $language->exit,
        'attributes' => [
            'href' => $__state->path . '/::g::/exit'
        ]
    ];
    unset($__nav['exit']);
}

if (!isset($__nav[""]) || $__nav[""] !== false) {
    $__o[""] = isset($__nav[""]) && is_array($__nav[""]) ? $__nav[""] : [
        'text' => $language->visit . ' ' . $language->site,
        'attributes' => [
            'href' => $url . "",
            'target' => '_blank'
        ]
    ];
    unset($__nav[""]);
}

foreach ($__nav as $k => $v) {
    if ($v === false) continue;
    $__o[$k] = is_array($v) ? $v : [
        'text' => $language->{$k},
        'attributes' => [
            'href' => $__state->path . '/::g::/' . $k
        ]
    ];
}

$__nav = Anemon::eat($__o)->sort([1, 'text'], '?')->vomit(); // hold!

unset($__o);