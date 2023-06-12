<?php

$query = $_GET['query'] ?? null;

if (null !== $query && !empty($_['part'])) {
    $title = x\panel\lot\type\title([
        'content' => i('Search results for query %s', ['<em>' . $query . '</em>']),
        'level' => -1
    ], 0);
    $tasks = x\panel\lot\type\tasks\link([
        '0' => 'span',
        'lot' => [
            'exit' => [
                'description' => ['Exit %s', 'search'],
                'icon' => 'M19,6.41L17.59,5L12,10.59L6.41,5L5,6.41L10.59,12L5,17.59L6.41,19L12,13.41L17.59,19L19,17.59L13.41,12L19,6.41Z',
                'stack' => 10,
                'title' => false,
                'url' => ['query' => ['query' => null]]
            ]
        ],
        'tags' => ['p' => false]
    ], 0);
    $GLOBALS['_']['alert']['info'][__FILE__] = '<span role="group">' . $title . ' ' . $tasks . '</span>';
}

// Always monitor the remaining disk space!
// TODO: Make this alert can be set by the user
$limit = 1000000; // 1 MB
if (disk_free_space(PATH) <= $limit) {
    $GLOBALS['_']['alert']['info'][] = ['There is no more than %s of disk space left.', size($limit)];
}

if (defined('TEST') && TEST) {
    foreach (['error', 'error-x', 'error-y'] as $v) {
        if (!is_file($log = ENGINE . D . 'log' . D . $v)) {
            continue;
        }
        $errors = x\panel\from\path(trim(n(file_get_contents($log)) ?? ""));
        $one = 0 === substr_count($errors, "\n");
        $out = i('Please fix ' . ($one ? 'this error' : 'these errors') . ':');
        $out .= '<br><br>';
        $out .= '<code>' . strtr(htmlspecialchars($errors), ["\n" => '<br>']) . '</code>';
        $out .= '<br><br>';
        $out .= i('If you think you have fixed the error' . ($one ? "" : 's') . ', you can %s.', ['<a href="' . x\panel\to\link([
            'part' => 0,
            'path' => $v,
            'query' => x\panel\_query_set([
                'kick' => short($url->current),
                'token' => $_['token']
            ]),
            'task' => 'fire/fix'
        ]) . '">' . i('remove the log') . '</a>']);
        $GLOBALS['_']['alert']['error'][$log] = $out;
    }
}