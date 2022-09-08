<?php namespace x\panel\task\fire;

function fuse ($_) {
    if (!\extension_loaded('zip')) {
        $_['alert']['error'][] = ['Missing %s extension.', 'PHP <a href="https://www.php.net/manual/en/class.ziparchive.php" rel="nofollow" target="_blank"><code>zip</code></a>'];
    }
    // Abort by previous hook’s return value if any
    if (!empty($_['alert']['error'])) {
        return $_;
    }
    \extract($GLOBALS, \EXTR_SKIP);
    $n = \basename($path = (string) $_['path']);
    $key = 0 === \strpos($n, 'x.') ? 'x' : (0 === \strpos($n, 'y.') ? 'y' : "");
    $test = \defined("\\TEST") && \TEST;
    $value = "" !== $key ? \substr($n, 2) : $n;
    $_['kick'] = $_REQUEST['kick'] ?? [
        'hash' => null,
        'part' => 0,
        'path' => "" !== $key ? $key . '/1' : ($state->x->panel->route ?? 'asset/1'),
        'query' => \x\panel\_query_set([
            'keep' => null,
            'minify' => null,
            'version' => null
        ]),
        'task' => 'get'
    ];
    if ("" === $key && 'mecha' === $value) {
        $folder = \PATH;
        $version = $_['query']['version'] ?? "";
        // Create a restore point from the currently installed version!
        $zip = new \ZipArchive;
        if (true === $zip->open($history = $folder . \D . '.' . $value . '.' . \date('Y-m-d-H-i-s') . '.zip', \ZipArchive::CREATE | \ZipArchive::OVERWRITE)) {
            $files = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($folder), \RecursiveIteratorIterator::LEAVES_ONLY);
            foreach ($files as $k => $v) {
                $from = $v->getRealPath();
                if (0 === \strpos($from . \D, \ENGINE . \D . 'log' . \D)) {
                    continue;
                }
                if ($test && (
                    false !== \strpos($from . \D, \D . '.git' . \D) ||
                    false !== \strpos($from . \D, \D . 'node_modules' . \D)
                )) {
                    continue;
                }
                $to = \substr($from, \strlen($folder) + 1);
                if (!$v->isDir()) {
                    $zip->addFile($from, $to);
                } else {
                    $zip->addEmptyDir($to);
                }
            }
            $zip->close();
            \chmod($history, 0600);
        }
        // Compare file/folder of currently installed version with the new version to be installed…
        $files_current = $files_next = [];
        foreach (\g($folder, null, true) as $k => $v) {
            if ($k === $history) {
                continue;
            }
            if ($test && (
                false !== \strpos($k . \D, \D . '.git' . \D) ||
                false !== \strpos($k . \D, \D . 'node_modules' . \D)
            )) {
                continue;
            }
            $files_current[\strtr($k, [$folder . \D => "", \D => '/'])] = $v;
        }
        $zip = new \ZipArchive;
        if (true === $zip->open($file = \ENGINE . \D . 'log' . \D . 'git' . \D . 'zip' . \D . 'mecha-cms' . \D . $n . ($version ? '@v' . $version : "") . '.zip')) {
            for ($i = 0; $i < $zip->numFiles; ++$i) {
                $k = \trim($v = \strtr($zip->statIndex($i)['name'], \D, '/'), '/');
                // Ignore default asset and page file(s)…
                if (0 === \strpos($k . '/', 'lot/asset/') || 0 === \strpos($k . '/', 'lot/page/')) {
                    continue;
                }
                $files_next[$k] = '/' === \substr($v, -1) ? 0 : 1;
            }
        }
        \krsort($files_current); // Reverse sort to make sure file(s) deleted before folder(s)
        \ksort($files_next);
        $counter = [[], [], []]; // `[added, deleted, updated]`
        // ++diff
        foreach ($files_next as $k => $v) {
            $counter[isset($files_current[$k]) ? 2 : 1][$k] = 1;
            $_SESSION['_'][0 === $v ? 'folder' : 'file'][$folder . \D . \strtr($k, '/', \D)] = 1;
            $f = $folder . \D . $k;
            // Prioritize folder over file
            if (0 === $v) {
                if (isset($files_current[$k]) && 1 === $files_current[$k]) {
                    \is_file($f) && \unlink($f); // Delete the file so that we can make folder
                }
                !\is_dir($f) && \mkdir($f, 0775, true);
                continue;
            }
            // Prepare to merge current `state.php` to the new `state.php` file!
            if ('state.php' === $k && isset($files_current[$k])) {
                \rename($f, \dirname($f) . \D . '.state.php');
                $files_current['.state.php'] = 1;
            }
            if (0 === \strpos($k, 'lot/') && '/state.php' === \substr($k, -10) && \preg_match('/^lot\/[xy]\/[^\/]+\/state\.php$/', $k)) {
                \rename($f, \dirname($f) . \D . '.state.php');
                $files_current[\dirname($k) . '/.state.php'] = 1;
            }
            if (!\is_dir($f)) {
                // Add file only if `$f` is a file or `$f` does not exist
                \file_put_contents($f, $zip->getFromName($k));
            }
        }
        // --diff
        foreach ($files_current as $k => $v) {
            $f = $folder . \D . $k;
            // Merge to `.\state.php`
            if ('.state.php' === $k) {
                if (isset($files_current['state.php'])) {
                    $current = (array) require $f;
                    $next = (array) require \dirname($f) . \D . 'state.php';
                    if (\file_put_contents(\dirname($f) . \D . 'state.php', '<?' . 'php return' . \z(\array_replace_recursive($next, $current)) . ';')) {
                        \unlink($f);
                    }
                }
                continue;
            }
            // Merge to `.\lot\x\*\state.php` or `.\lot\y\*\state.php`
            if (0 === \strpos($k, 'lot/') && '/.state.php' === \substr($k, -11) && \preg_match('/^lot\/[xy]\/[^\/]+\/\.state\.php$/', $k)) {
                if (isset($files_current[\dirname($k) . '/state.php'])) {
                    $current = (array) require $f;
                    $next = (array) require \dirname($f) . \D . 'state.php';
                    if (\file_put_contents(\dirname($f) . \D . 'state.php', '<?' . 'php return' . \z(\array_replace_recursive($next, $current)) . ';')) {
                        \unlink($f);
                    }
                }
                continue;
            }
            // Skip file(s) and folder(s) in `.\lot` folder
            if (0 === \strpos($k, 'lot/')) {
                continue; // TODO: Override core extension(s) and layout(s) anyway
            }
            if (!isset($files_next[$k])) {
                0 === $v ? \rmdir($f) : \unlink($f);
                $counter[0][$k] = 1;
            }
        }
        $page = new \Page(null, [
            'title' => 'Mecha',
            'version' => $version
        ]);
        $t = '<a href="' . \x\panel\to\link([
            'hash' => null,
            'part' => 0,
            'path' => ($state->x->panel->route ?? 'asset/1'),
            'query' => \x\panel\_query_set([
                'keep' => null,
                'minify' => null,
                'version' => null
            ]),
            'task' => 'get'
        ]) . '">' . ($page->title ?? $value) . '</a>';
        $_['alert']['success'][$folder] = ['%s successfully updated to version %s.', [$t, $page->version ?? $version]];
        $results = $traces = [];
        foreach ($counter as $k => $v) {
            foreach ($v as $kk => $vv) {
                $traces[] = '<code>' . (['❌', '➕', '✔️'][$k] ?? '✔️') . ' ' . \x\panel\from\path($folder . \D . $kk) . '</code>';
            }
            $v = \count($v);
            $results[] = \i('%d file' . (1 === $v ? "" : 's'), $v);
        }
        if ($test) {
            $info = $results[0] . ' deleted, ' . $results[1] . ' added, ' . $results[2] . ' updated.';
            $info .= '<br><br><small>';
            $info .= \implode('<br>', $traces);
            $info .= '</small>';
            $_['alert']['info'][$folder] = $info;
        }
        $zip->close();
        \unlink($file);
        return $_;
    }
    $folder = \LOT . \D . $key . \D . $value;
    $version = $_['query']['version'] ?? "";
    // Create a restore point from the currently installed version!
    if ("" !== $key) {
        $zip = new \ZipArchive;
        if (true === $zip->open($history = $folder . '.' . \date('Y-m-d-H-i-s') . '.zip', \ZipArchive::CREATE | \ZipArchive::OVERWRITE)) {
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
            \chmod($history, 0600);
        }
        // Compare file/folder of currently installed version with the new version to be installed…
        $files_current = $files_next = [];
        foreach (\g($folder, null, true) as $k => $v) {
            $files_current[\strtr($k, [$folder . \D => "", \D => '/'])] = $v;
        }
        $zip = new \ZipArchive;
        if (true === $zip->open($file = \ENGINE . \D . 'log' . \D . 'git' . \D . 'zip' . \D . 'mecha-cms' . \D . $n . ($version ? '@v' . $version : "") . '.zip')) {
            for ($i = 0; $i < $zip->numFiles; ++$i) {
                $k = \trim($v = \strtr($zip->statIndex($i)['name'], \D, '/'), '/');
                $files_next[$k] = '/' === \substr($v, -1) ? 0 : 1;
            }
        }
        \krsort($files_current); // Reverse sort to make sure file(s) deleted before folder(s)
        \ksort($files_next);
        $counter = [[], [], []]; // `[added, deleted, updated]`
        // ++diff
        foreach ($files_next as $k => $v) {
            $counter[isset($files_current[$k]) ? 2 : 1][$k] = 1;
            $_SESSION['_'][0 === $v ? 'folder' : 'file'][$folder . \D . \strtr($k, '/', \D)] = 1;
            $f = $folder . \D . $k;
            // Prioritize folder over file
            if (0 === $v) {
                if (isset($files_current[$k]) && 1 === $files_current[$k]) {
                    \is_file($f) && \unlink($f); // Delete the file so that we can make folder
                }
                !\is_dir($f) && \mkdir($f, 0775, true);
                continue;
            }
            // Prepare to merge current `state.php` to the new `state.php` file!
            if ('state.php' === $k && isset($files_current[$k])) {
                \rename($f, $folder . \D . '.state.php');
                $files_current['.state.php'] = 1;
            }
            if (!\is_dir($f)) {
                // Add file only if `$f` is a file or `$f` does not exist
                \file_put_contents($f, $zip->getFromName($k));
            }
        }
        // --diff
        foreach ($files_current as $k => $v) {
            $f = $folder . \D . $k;
            // Merge to `.\lot\x\*\state.php` or `.\lot\y\*\state.php`
            if ('.state.php' === $k) {
                if (isset($files_current['state.php'])) {
                    $current = (array) require $f;
                    $next = (array) require \dirname($f) . \D . 'state.php';
                    if (\file_put_contents(\dirname($f) . \D . 'state.php', '<?' . 'php return' . \z(\array_replace_recursive($next, $current)) . ';')) {
                        \unlink($f);
                    }
                }
                continue;
            }
            if (!isset($files_next[$k])) {
                0 === $v ? \rmdir($f) : \unlink($f);
                $counter[0][$k] = 1;
            }
        }
        $page = new \Page(\exist($folder . \D . 'about.page', 1) ?: null);
        $t = '<a href="' . \x\panel\to\link([
            'hash' => null,
            'part' => 0,
            'path' => "" !== $key ? $key . '/' . $value . '/1' : ($state->x->panel->route ?? 'asset/1'),
            'query' => \x\panel\_query_set([
                'keep' => null,
                'minify' => null,
                'version' => null
            ]),
            'task' => 'get'
        ]) . '">' . ($page->title ?? $value) . '</a>';
        $_['alert']['success'][$folder] = ['%s successfully updated to version %s.', [$t, $page->version ?? $version]];
        $results = $traces = [];
        foreach ($counter as $k => $v) {
            foreach ($v as $kk => $vv) {
                $traces[] = '<code>' . (['❌', '➕', '✔️'][$k] ?? '✔️') . ' ' . \x\panel\from\path($folder . \D . $kk) . '</code>';
            }
            $v = \count($v);
            $results[] = \i('%d file' . (1 === $v ? "" : 's'), $v);
        }
        if ($test) {
            $info = $results[0] . ' deleted, ' . $results[1] . ' added, ' . $results[2] . ' updated.';
            $info .= '<br><br><small>';
            $info .= \implode('<br>', $traces);
            $info .= '</small>';
            $_['alert']['info'][$folder] = $info;
        }
        $zip->close();
        \unlink($file);
    }
    return $_;
}