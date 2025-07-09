<?php namespace x\panel\task\fire;

function recover($_) {
    // Abort by previous hook’s return value if any
    if (isset($_['kick']) || !empty($_['alert']['error']) || $_['status'] >= 400) {
        return $_;
    }
    $_['kick'] = $_REQUEST['kick'] ?? [
        'path' => 'trash',
        'query' => \x\panel\_query_set(),
        'task' => 'get'
    ];
    if (!$folder = $_['folder']) {
        $_['alert']['error'][] = ['%s does not exist.', 'Folder'];
        return $_; // Folder does not exist
    }
    foreach (\g($folder, null, true) as $k => $v) {
        $to = \LOT . \D . ($kk = \strtr($k, [$folder . \D => ""]));
        if (0 === $v) {
            $_SESSION['_']['folders'][$to] = 1;
            continue;
        }
        if (!\is_dir($parent = \dirname($to))) {
            \mkdir($parent, 0775, true);
        }
        if (\is_file($to)) {
            continue; // File already exists
        }
        \rename($k, $to);
        $_SESSION['_']['files'][$to] = 1;
    }
    // Remove empty folder(s)
    foreach (\g($folder, 0, true) as $k => $v) {
        \rmdir($k);
    }
    \rmdir($folder);
    $_['alert']['success'][$folder] = 'Files successfully recovered.';
    return $_;
}