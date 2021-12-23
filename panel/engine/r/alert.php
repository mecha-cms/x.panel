<?php

$query = $_GET['query'] ?? null;

if (null !== $query && preg_match('/\/[1-9]\d*$/', $_['path'])) {
    $icon = x\panel\to\icon(['M19,6.41L17.59,5L12,10.59L6.41,5L5,6.41L10.59,12L5,17.59L6.41,19L12,13.41L17.59,19L19,17.59L13.41,12L19,6.41Z'])[0];
    $_['alert']['info'][__FILE__] = i('Search results for query %s', ['<em>' . $query . '</em>']) . ' <a class="f:r" href="' . $url->current(false, false) . $url->query([
        'query' => false
    ]) . $url->hash . '" title="' . i('Exit search') . '">' . $icon . '</a>';
}

if (defined('TEST') && TEST && is_file($log = ENGINE . D . 'log' . D . 'error')) {
    $errors = x\panel\from\path(trim(n(file_get_contents($log))));
    $one = 0 === substr_count($errors, "\n");
    $out = i('Please fix ' . ($one ? 'this error' : 'these errors') . ':');
    $out .= '<br><br>';
    $out .= '<code style="display:inline-block;font-size:70%;line-height:1.25em;">' . strtr(htmlspecialchars($errors), ["\n" => '<br>']) . '</code>';
    $out .= '<br><br>';
    $out .= i('If you think you have fixed the error' . ($one ? "" : 's') . ', you can then %s.', ['<a href="' . x\panel\to\link([
        'path' => 'fire/510d4904',
        'query' => [
            'kick' => short($url->current),
            'token' => $_['token'],
            'type' => false
        ]
    ]) . '">' . i('remove the log file') . '</a>']);
    $GLOBALS['_']['alert']['error'][$log] = $out;
}

























