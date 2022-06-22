<?php namespace x\panel\task\fire;

function fuse($_) {
    // Abort by previous hook’s return value if any
    if (!empty($_['alert']['error'])) {
        return $_;
    }
    \extract($GLOBALS, \EXTR_SKIP);
    $n = \basename($path = (string) $_['path']);
    $key = 0 === \strpos($n, 'x.') ? 'x' : (0 === \strpos($n, 'y.') ? 'y' : "");
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
        // Specific task for core update(s)
        echo 'Do special task(s) for core update(s)...';
        exit;
    }
    $folder = \LOT . \D . $key . \D . $value;
    $version = $_['query']['version'] ?? "";
    // Create a restore point from the currently installed version!
    if (\extension_loaded('zip') && "" !== $key) {
        $zip = new \ZipArchive;
        if (true === $zip->open($folder . '.' . \date('Y-m-d-H-i-s') . '.zip', \ZipArchive::CREATE | \ZipArchive::OVERWRITE)) {
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
            $files_current[\strtr($k, [$folder . \D => "", \D => '/'])] = $v;
        }
        $zip = new \ZipArchive;
        if (true === $zip->open($file = \ENGINE . \D . 'log' . \D . 'git' . \D . 'zip' . \D . 'mecha-cms' . \D . $n . ($version ? '@v' . $version : "") . '.zip')) {
            for ($i = 0; $i < $zip->numFiles; ++$i) {
                $files_next[\trim($v = \strtr($zip->statIndex($i)['name'], \D, '/'), '/')] = '/' === \substr($v, -1) ? 0 : 1;
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
            if (!\is_dir($f)) {
                // Add file only if `$f` is a file or `$f` does not exist
                \file_put_contents($f, $zip->getFromName($k));
            }
        }
        // --diff
        foreach ($files_current as $k => $v) {
            $f = $folder . \D . $k;
            if (!isset($files_next[$k]) && '.state.php' !== $k) {
                0 === $v ? \rmdir($f) : \unlink($f);
                $counter[0][$k] = 1;
            }
        }
        if (isset($files_current['state.php']) && isset($files_current['.state.php'])) {
            $current = (array) require $folder . \D . '.state.php';
            $next = (array) require $folder . \D . 'state.php';
            \file_put_contents($folder . \D . 'state.php', '<?' . 'php return ' . \z(\array_replace_recursive($next, $current)) . ';');
            \unlink($folder . \D . '.state.php');
        }
        $page = new \Page(\exist($folder . \D . 'about.page', 1) ?: null);
        $t = '<a href="' . \x\panel\to\link([
            'hash' => null,
            'part' => 1,
            'path' => "" !== $key ? $key . '/' . $value : 'asset',
            'query' => \x\panel\_query_set([
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
        if (\defined("\\TEST") && \TEST) {
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