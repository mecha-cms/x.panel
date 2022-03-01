<?php

$query = $_GET['query'] ?? null;

if (null !== $query && !empty($_['part'])) {
    $title = x\panel\type\title([
        'content' => i('Search results for query %s', ['<em>' . $query . '</em>']),
        'level' => -1
    ], 0);
    $tasks = x\panel\type\tasks\link([
        '0' => 'span',
        'lot' => [
            'x' => [
                'description' => ['Exit %s', 'search'],
                'icon' => 'M19,6.41L17.59,5L12,10.59L6.41,5L5,6.41L10.59,12L5,17.59L6.41,19L12,13.41L17.59,19L19,17.59L13.41,12L19,6.41Z',
                'stack' => 10,
                'title' => false,
                'url' => x\panel\to\link([
                    'query' => ['query' => null]
                ])
            ]
        ]
    ], 0);
    $GLOBALS['_']['alert']['info'][__FILE__] = '<span role="group">' . $title . ' ' . $tasks . '</span>';
}

if (defined('TEST') && TEST && is_file($log = ENGINE . D . 'log' . D . 'error')) {
    $errors = x\panel\from\path(trim(n(file_get_contents($log))));
    $one = 0 === substr_count($errors, "\n");
    $out = i('Please fix ' . ($one ? 'this error' : 'these errors') . ':');
    $out .= '<br><br>';
    $out .= '<code style="display:inline-block;font-size:70%;line-height:1.25em;">' . strtr(htmlspecialchars($errors), ["\n" => '<br>']) . '</code>';
    $out .= '<br><br>';
    $out .= i('If you think you have fixed the error' . ($one ? "" : 's') . ', you can then %s.', ['<a href="' . x\panel\to\link([
        'part' => 1,
        'path' => null,
        'query' => [
            'kick' => short($url->current),
            'token' => $_['token']
        ],
        'task' => 'fire/fix'
    ]) . '">' . i('remove the log file') . '</a>']);
    $GLOBALS['_']['alert']['error'][$log] = $out;
}