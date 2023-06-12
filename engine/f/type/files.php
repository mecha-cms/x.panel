<?php namespace x\panel\type\files;

function cache(array $_ = []) {
    $token = $_['token'] ?? null;
    $type = $_['type'] ?? 'files/cache';
    return \x\panel\type\files(\array_replace_recursive([
        'lot' => [
            'desk' => [
                'lot' => [
                    'form' => [
                        'lot' => [
                            0 => [
                                'lot' => [
                                    'tasks' => [
                                        'lot' => [
                                            'blob' => ['skip' => true],
                                            'file' => ['skip' => true],
                                            'folder' => ['skip' => true],
                                            'let' => [
                                                'icon' => 'M5,13H19V11H5M3,17H17V15H3M7,7V9H21V7',
                                                'skip' => false,
                                                'stack' => 10,
                                                'title' => 'Flush',
                                                'type' => 'link',
                                                'url' => [
                                                    'query' => \x\panel\_query_set(['token' => $token]),
                                                    'task' => 'fire/flush'
                                                ]
                                            ]
                                        ]
                                    ]
                                ]
                            ]
                        ]
                    ]
                ]
            ]
        ],
        'type' => $type
    ], $_));
}

function trash(array $_ = []) {
    $token = $_['token'] ?? null;
    $type = $_['type'] ?? 'files/trash';
    return \x\panel\type\files(\array_replace_recursive([
        'lot' => [
            'desk' => [
                'lot' => [
                    'form' => [
                        'lot' => [
                            0 => [
                                'lot' => [
                                    'tasks' => [
                                        'lot' => [
                                            'blob' => ['skip' => true],
                                            'file' => ['skip' => true],
                                            'folder' => ['skip' => true],
                                            'let' => [
                                                'icon' => 'M5,13H19V11H5M3,17H17V15H3M7,7V9H21V7',
                                                'skip' => false,
                                                'stack' => 10,
                                                'title' => 'Flush',
                                                'type' => 'link',
                                                'url' => [
                                                    'query' => \x\panel\_query_set(['token' => $token]),
                                                    'task' => 'fire/flush'
                                                ]
                                            ]
                                        ]
                                    ]
                                ]
                            ]
                        ]
                    ]
                ]
            ]
        ],
        'type' => $type
    ], $_));
}

function x(array $_ = []) {
    $type = $_['type'] ?? 'files/x';
    return \x\panel\type\files(\array_replace_recursive([
        // TODO
        'type' => $type
    ], $_));
}

function y(array $_ = []) {
    $type = $_['type'] ?? 'files/y';
    return \x\panel\type\files(\array_replace_recursive([
        // TODO
        'type' => $type
    ], $_));
}