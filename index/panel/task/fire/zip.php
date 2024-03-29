<?php namespace x\panel\task\fire;

function zip($_) {
    if (!\extension_loaded('zip')) {
        $_['alert']['error'][] = ['Missing %s extension.', 'PHP <a href="https://www.php.net/manual/en/class.ziparchive.php" rel="nofollow" target="_blank"><code>zip</code></a>'];
        return $_;
    }
    // Abort by previous hook’s return value if any
    if (isset($_['kick']) || !empty($_['alert']['error']) || $_['status'] >= 400) {
        return $_;
    }
    $file = $_['file'];
    $folder = $_['folder'];
    $file_zip = $file && \is_file($file) && 'zip' === \pathinfo($file, \PATHINFO_EXTENSION);
    $kick = [
        'part' => $file_zip ? 1 : 0,
        'path' => $file_zip ? \dirname($_['path']) : $_['path'],
        'query' => \x\panel\_query_set(),
        'task' => 'get'
    ];
    $_['kick'] = $kick;
    if (!$file && !$folder) {
        $_['alert']['error'][] = ['Failed to run task %s.', '<code>' . __FUNCTION__ . '()</code>'];
        $_['alert']['error'][] = ['File %s does not exist.', '<code>' . ($f = \x\panel\from\path(\LOT . \D . $_['path'])) . '</code>'];
        $_['alert']['error'][] = ['Folder %s does not exist.', '<code>' . $f . '</code>'];
        return $_;
    }
    if (\is_array($wrap = \s($_REQUEST['folder'] ?? "")) || 'false' === $wrap || 'null' === $wrap) {
        $wrap = "";
    } else if ('true' === $wrap) {
        // Add default root folder with `?folder=true`
        $wrap = \D . \pathinfo($file, \PATHINFO_FILENAME);
    } else if ($wrap || '0' === $wrap) {
        // Add custom root folder with `?folder=asdf`
        $wrap = \D . \trim(\strtr($wrap, "\\", \D), \D);
    }
    // Calling this task on a file with extension `.zip` will automatically perform “extract”
    if ($file_zip) {
        \http_response_code(200); // Set correct response status!
        $zip = new \ZipArchive;
        $parent = \dirname($file);
        if ("" !== $wrap) {
            $parent = \rtrim($parent . $wrap, \D);
        }
        if (true === $zip->open($file)) {
            $test_x = \P . \implode(\P, \array_keys(\array_filter((array) (\State::get('x.panel.guard.file.x', true) ?? [])))) . \P;
            for ($i = 0; $i < $zip->numFiles; ++$i) {
                $x = \pathinfo($v = \strtr($zip->getNameIndex($i), '/', \D), \PATHINFO_EXTENSION);
                if (\D === \substr($v, -1)) {
                    continue; // Skip folder!
                }
                $v = $parent . \D . $v;
                // This prevents user(s) from uploading forbidden file(s)
                if ($x && false === \strpos($test_x, \P . $x . \P)) {
                    $_['alert']['error'][$v] = ['File extension %s is not allowed.', '<code>' . $x . '</code>'];
                // This prevents user(s) from accidentally overwrite the existing file(s)
                } else if (\is_file($v)) {
                    $_['alert']['error'][$v] = ['File %s already exists.', '<code>' . \x\panel\from\path($v) . '</code>'];
                // This prevents user(s) from uploading PHP file(s) with syntax error in it (if file with `.php` extension is allowed to upload)
                } else if ('php' === $x && $content = $zip->getFromIndex($i)) {
                    try {
                        \token_get_all($content, \TOKEN_PARSE);
                        $_SESSION['_']['files'][$v] = 1;
                    } catch (\Throwable $e) {
                        $_['alert']['error'][$v] = (string) $e;
                    }
                } else {
                    $_SESSION['_']['files'][$v] = 1;
                    $_SESSION['_']['folders'][\rtrim(\dirname($v), \D)] = 1;
                }
            }
            if (!empty($_['alert']['error'])) {
                $_['alert']['error'][$file] = ['Package %s could not be extracted due to the previous errors.', '<code>' . \x\panel\from\path($file) . '</code>'];
            } else {
                if (!\is_dir($parent)) {
                    \mkdir($parent, 0755, true);
                }
                $zip->extractTo($parent);
                $zip->close();
                $_['alert']['success'][$file] = ['Package %s successfully extracted.', '<code>' . \x\panel\from\path($file) . '</code>'];
                if (empty($_REQUEST['zip']['keep'])) {
                    if (\unlink($file)) {
                        $_['alert']['success'][$file] = ['Package %s successfully extracted and deleted.', '<code>' . \x\panel\from\path($file) . '</code>'];
                    } else {
                        $_['alert']['error'][$file] = ['Package %s could not be deleted. Please delete it manually.', '<code>' . \x\panel\from\path($file) . '</code>'];
                    }
                }
            }
        } else {
            $_['alert']['error'][$file] = $zip->getStatusString();
            $zip->close();
        }
        return $_;
    }
    // Else, perform “pack”
    require \LOT . \D . 'x' . \D . 'panel' . \D . 'engine' . \D . 'r' . \D . 'vendor' . \D . 'autoload.php';
    \http_response_code(200); // Set correct response status to make it work!
    $name = $_REQUEST['zip']['name'] ?? (($file || $folder ? \basename($file ?: $folder) : \uniqid()) . '@' . \date('Y-m-d') . '.zip');
    $parent = \dirname($file ?: $folder);
    $options = new \ZipStream\Option\Archive();
    $options->setContentDisposition('attachment');
    $options->setContentType('application/octet-stream');
    $options->setFlushOutput(true);
    $options->setSendHttpHeaders(true);
    $zip = new \ZipStream\ZipStream($name, $options);
    if ($folder && \is_dir($folder)) {
        $parent .= \D . \basename($folder);
        foreach (\g($folder, 1, true) as $k => $v) {
            $zip->addFileFromPath(\strtr($k, [$parent . \D => $wrap]), $k);
        }
    } else if ($file && \is_file($file)) {
        $zip->addFileFromPath(\strtr($file, [$parent . \D => $wrap]), $file);
    }
    $zip->finish();
    exit;
}