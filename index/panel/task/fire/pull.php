<?php namespace x\panel\task\fire;

function pull($_) {
    \extract($GLOBALS, \EXTR_SKIP);
    $n = \basename($path = (string) $_['path']);
    $key = 0 === \strpos($n, 'x.') ? 'x' : (0 === \strpos($n, 'y.') ? 'y' : "");
    $value = "" !== $key ? \substr($n, 2) : $n;
    $_['kick'] = $_REQUEST['kick'] ?? [
        'hash' => null,
        'part' => 0,
        'path' => "" !== $key ? $key . '/1' : ($state->x->panel->route ?? 'asset/1'),
        'query' => null,
        'task' => 'get'
    ];
    // Abort by previous hook’s return value if any
    if (!empty($_['alert']['error'])) {
        return $_;
    }
    if (null !== ($blob = \fetch('https://mecha-cms.com/git-dev/zip/' . $path . \To::query($_['query'] ?? [])))) {
        if (!\is_dir($folder = \ENGINE . \D . 'log' . \D . 'git' . \D . 'zip' . \D . 'mecha-cms')) {
            \mkdir($folder, 0775, true);
        }
        $version = $_['query']['version'] ?? "";
        \file_put_contents($folder . \D . $n . ($version ? '@v' . $version : "") . '.zip', $blob);
    }
    return $_;
}