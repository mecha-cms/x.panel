<?php

$a = a(e(Config::get('panel.desk.body.tab.file.field.file[+][page].value', true)));

Config::set('panel.desk.body.tab.file.field', [
    'file[+][page]' => null,
    'file[+][path]' => ['title' => $language->home],
    'file[+][q]' => ['title' => $language->search],
    'file[+][page][sort][0]' => [
        'key' => 'order',
        'title' => $language->sort[0],
        'type' => 'radio[]',
        'value' => $a['sort'][0],
        'values' => [
           '-1' => 'Z &#x2192; A',
            '1' => 'A &#x2192; Z'
        ],
        'stack' => 20
    ],
    'file[+][page][sort][1]' => [
        'key' => 'by',
        'title' => $language->sort[1],
        'type' => 'text',
        'value' => $a['sort'][1],
        'placeholder' => $a['sort'][1] ?? 'time',
        'stack' => 20.1
    ],
    'file[+][page][chunk]' => [
        'key' => 'chunk',
        'type' => 'number',
        'range' => [1, 100],
        'value' => $a['chunk'] ?? null,
        'stack' => 20.2
    ]
]);