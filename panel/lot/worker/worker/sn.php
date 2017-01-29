<?php

// sn: sub–nav
$__sn = (array) Panel::get('sn', []);

$__o = [];

if (!isset($__sn['error']) || $__sn['error'] !== false) {
    if ($__log = File::exist(ENGINE . DS . 'log' . DS . 'error.log')) {
        preg_match_all('#^\s*\[(.+?)\]#m', File::open($__log)->read(), $__errors);
        if (!empty($__errors[1])) {
            $__o['error'] = isset($__sn['error']) && is_array($__sn['error']) ? $__sn['error'] : [
                'text' => $language->errors,
                'attributes' => [
                    'href' => $__state->path . '/::g::/error'
                ],
                'i' => count(array_unique($__errors[1]))
            ];
        }
    }
    unset($__sn['error']);
}

if (!isset($__sn['exit']) || $__sn['exit'] !== false) {
    $__o['exit'] = isset($__sn['exit']) && is_array($__sn['exit']) ? $__sn['exit'] : [
        'text' => $language->log_out,
        'attributes' => [
            'href' => $__state->path . '/::g::/exit'
        ]
    ];
    unset($__sn['exit']);
}

foreach ($__sn as $k => $v) {
    if ($v === false) continue;
    $__o[$k] = is_array($v) ? $v : [
        'text' => $language->{$k},
        'attributes' => [
            'href' => $__state->path . '/::g::/' . $k
        ]
    ];
}

$__sn = Anemon::eat($__o)->sort([1, 'text'], '?')->vomit(); // hold!

unset($__o);