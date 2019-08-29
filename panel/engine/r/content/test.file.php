<?php

require __DIR__ . DS . 'before.php';

echo _\lot\x\panel(['lot' => [
    'desk' => [
        'type' => 'desk',
        'lot' => [
            'body' => [
                'type' => 'desk.body',
                'lot' => [
                    'tab' => [
                        'type' => 'tab',
                        'lot' => [
                            0 => [
                                'title' => 'Test 1',
                                'lot' => [
                                    'files' => [
                                        'type' => 'files',
                                        'source' => PAGE
                                    ]
                                ]
                            ]
                        ]
                    ]
                ]
            ]
        ]
    ]
]], 0, '#');

require __DIR__ . DS . 'after.php';