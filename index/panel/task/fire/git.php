<?php namespace x\panel\task\fire;

function git($_) {
    $path = (string) $_['path'];
    $query = (array) ($_['query'] ?? []);
    if (null !== ($blob = \fetch('https://mecha-cms.com/git-dev/zip/' . $path . \To::query($query)))) {
        if (!\is_dir($folder = \ENGINE . \D . 'log' . \D . 'git' . \D . 'zip' . \D . 'mecha-cms')) {
            \mkdir($folder, 0775, true);
        }
        $version = $query['version'] ?? "";
        \file_put_contents($folder . \D . \basename($path) . ($version ? '@v' . $version : "") . '.zip', $blob);
    }
    $_['kick'] = $query['kick'] ?? [
        'hash' => null,
        'part' => 1,
        'path' => 'x',
        'query' => null,
        'task' => 'get'
    ];
    return $_;
}