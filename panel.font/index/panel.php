<?php

if ('.state' === $_['path']) {
    Hook::set('_', function($_) {
        $_['lot']['desk']['lot']['form']['lot'][1]['lot']['tabs']['lot']['panel']['lot']['fields']['lot']['font'] = [
            'lot' => [
                'monospace' => 'Mono',
                'sans-serif' => 'Sans',
                'serif' => 'Serif'
            ],
            'name' => 'state[font]',
            'stack' => 50,
            'title' => 'Font',
            'type' => 'option',
            'value' => 'sans-serif'
        ];
        return $_;
    }, 10.2);
}