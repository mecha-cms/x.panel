<?php namespace x\panel\task\fire;

function fuse($_) {
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
    $folder = \LOT . \D . $key . \D . $value;
    // Create a restore point from the currently installed version!
    if (\extension_loaded('zip') && "" !== $key) {
        $zip = new \ZipArchive;
        if (true === $zip->open($folder . '.zip', \ZipArchive::CREATE | \ZipArchive::OVERWRITE)) {
            $files = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($folder), \RecursiveIteratorIterator::LEAVES_ONLY);
            foreach ($files as $k => $v) {
                $from = $v->getRealPath();
                $to = \substr($from, \strlen($folder) + 1);
                if (!$v->isDir()) {
                    $zip->addFile($from, $to);
                } else {
                    $zip->addEmptyDir($to);
                }
            }
            $zip->close();
        }
        // Compare file/folder of currently installed version with the new version to be installed…
        $files_current = $files_next = [];
        foreach (\g($folder, null, true) as $k => $v) {
            $files_current[\strtr($k, [$folder . \D => ""])] = $v;
        }
        $zip = new \ZipArchive;
        $version = $_['query']['version'] ?? "";
        if (true === $zip->open(\ENGINE . \D . 'log' . \D . 'git' . \D . 'zip' . \D . 'mecha-cms' . \D . $n . ($version ? '@v' . $version : "") . '.zip')) {
            for($i = 0; $i < $zip->numFiles; ++$i) {
                $files_next[\trim($v = \strtr($zip->statIndex($i)['name'], \D, '/'), '/')] = '/' === \substr($v, -1) ? 0 : 1;
            }
        }
        \ksort($files_current);
        \ksort($files_next);
        // TODO
        \test($files_current, $files_next); exit;
        // ++diff
        foreach ($files_next as $k => $v) {
            if (0 === $v && !isset($files_current[$k])) {
                \mkdir($folder . \D . $k, 0775, true);
                continue;
            }
            // Special case for `state.php`: merge value(s) instead of replace!
            if ('state.php' === $k) {
                // TODO
                continue;
            }
            \file_put_contents($folder . \D . $k, $zip->getFromName($k));
        }
        // --diff
        foreach ($files_current as $k => $v) {
            if (!isset($files_next[$k])) {
                \unlink($folder . \D . $k);
            }
        }
        $zip->close();
    }
    return $_;
}