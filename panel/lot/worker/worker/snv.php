<?php

// snv: sub–nav
$__snv = (array) Panel::get('snv', []);

$__o = [];

if (!isset($__snv['error']) || $__snv['error'] !== false) {
    if ($__log = File::exist(ENGINE . DS . 'log' . DS . 'error.log')) {
        preg_match_all('#^\s*\[(.+?)\]#m', File::open($__log)->read(), $__errors);
        if (!empty($__errors[1])) {
            $__o['error'] = isset($__snv['error']) && is_array($__snv['error']) ? $__snv['error'] : [
                'text' => $language->errors,
                'attributes' => [
                    'href' => $__state->path . '/::g::/error'
                ],
                'i' => count(array_unique($__errors[1]))
            ];
        }
    }
    unset($__snv['error']);
}

if (!isset($__snv['exit']) || $__snv['exit'] !== false) {
    $__o['exit'] = isset($__snv['exit']) && is_array($__snv['exit']) ? $__snv['exit'] : [
        'text' => $language->log_out,
        'attributes' => [
            'href' => $__state->path . '/::g::/exit'
        ]
    ];
    unset($__snv['exit']);
}

foreach ($__snv as $k => $v) {
    if ($v === false) continue;
    $__o[$k] = is_array($v) ? $v : [
        'text' => $language->{$k},
        'attributes' => [
            'href' => $__state->path . '/::g::/' . $k
        ]
    ];
}

$__snv = Anemon::eat($__o)->sort([1, 'text'], '?')->vomit(); // hold!

unset($__o);