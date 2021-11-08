<?php

if ($_['i'] && $q = ($_GET['q'] ?? null)) {
    $GLOBALS['_']['alert']['info'][] = i('Search results for query %s', '<em>' . $q . '</em>') . ' <a class="f:r" href="' . $url->clean . '/1' . $url->query('&', [
        'q' => false
    ]) . $url->hash . '" title="' . i('Exit search') . '">' . x\panel\to\icon(['M19,6.41L17.59,5L12,10.59L6.41,5L5,6.41L10.59,12L5,17.59L6.41,19L12,13.41L17.59,19L19,17.59L13.41,12L19,6.41Z'])[0] . '</a>';
}

if (defined('DEBUG') && DEBUG && is_file($f = ENGINE . DS . 'log' . DS . 'error')) {
    $errors = x\panel\from\path(trim(n(file_get_contents($f))));
    $out = i('Please fix ' . (substr_count($errors, "\n") === 0 ? 'this error' : 'these errors') . ':');
    $out .= '<br><br>';
    $out .= '<code style="display: inline-block; font-size: 70%; line-height: 1.25em;">' . strtr(htmlspecialchars($errors), [
        "\n" => '<br>'
    ]) . '</code>';
    $out .= '<br><br>';
    $out .= i('If you think you have fixed the error' . (substr_count($errors, "\n") === 0 ? "" : 's') . ', you can then %s.', ['<a href="' . $_['/'] . '/::f::/510d4904' . $url->query('&amp;', [
        'kick' => URL::short($url->current, false),
        'token' => $_['token'],
        'type' => false
    ]) . '">' . i('remove the log file') . '</a>']);
    $GLOBALS['_']['alert']['error'][] = $out;
}