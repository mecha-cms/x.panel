<?php /* dechex(crc32('package')) */

// Invalid token
if (empty($_['form']['token']) || $_['form']['token'] !== $_['token']) {
    $_['alert']['error'][] = 'Invalid token.';
    return $_;
}

// Set response status
http_response_code(200);

$_['kick'] = $_['form']['kick'] ?? $url;

// File or folder does not exists
if (!$f = $_['f']) {
    $_['alert']['error'][] = 'No such file or folder.';
    return $_;
}

require __DIR__ . DS . '..' . DS . '..' . DS . 'composer' . DS . 'vendor' . DS . 'autoload.php';

// Extract
if (is_file($f) && 'zip' === pathinfo($f, PATHINFO_EXTENSION)) {
    if ($zip = wapmorgan\UnifiedArchive\UnifiedArchive::open($f)) {
        $xx = ',' . implode(',', array_keys(array_filter(File::$state['x'] ?? []))) . ',';
        foreach ($zip->getFileNames() as $v) {
            $x = pathinfo($v = strtr($v, '/', DS), PATHINFO_EXTENSION);
            // This prevents user(s) from uploading forbidden file(s)
            if ($x && false === strpos($xx, ',' . $x . ',')) {
                $_['alert']['error'][] = ['File extension %s is not allowed.', '<code>' . $x . '</code>'];
            // This prevents user(s) from accidentally overwrite the existing file(s)
            } else if (is_file($ff = dirname($f) . DS . $v)) {
                $_['alert']['error'][] = ['File %s already exists.', '<code>' . _\lot\x\panel\h\path($ff) . '</code>'];
            } else {
                $_SESSION['_']['file'][$ff] = 1;
                $_SESSION['_']['folder'][rtrim(dirname($ff), DS)] = 1;
            }
        }
        if (!empty($_['alert']['error'])) {
            $_['alert']['error'][] = ['Package %s could not be extracted due to the previous errors.', '<code>' . _\lot\x\panel\h\path($f) . '</code>'];
        } else if (false !== $zip->extractFiles(dirname($f))) {
            unset($zip);
            if (!empty($_['form']['let'])) {
                if (unlink($f)) {
                    $_['alert']['success'][] = ['Package %s successfully extracted and deleted.', '<code>' . _\lot\x\panel\h\path($f) . '</code>'];
                } else {
                    $_['alert']['error'][] = ['Package %s could not be deleted. Please delete it manually.', '<code>' . _\lot\x\panel\h\path($f) . '</code>'];
                }
            } else {
                $_['alert']['success'][] = ['Package %s successfully extracted.', '<code>' . _\lot\x\panel\h\path($f) . '</code>'];
            }
        }
        unset($zip);
    }
    return $_;
}

// Pack
$name = ($f ? basename($f) : uniqid()) . '@' . date('Y-m-d') . '.zip';
$o = new ZipStream\Option\Archive();
$o->setSendHttpHeaders(true);

$zip = new ZipStream\ZipStream($name, $o);

$d = dirname($f);
if (is_dir($f)) {
    if (isset($_['form']['d']) && !$_['form']['d']) {
        $d = $f; // Remove the root folder too
    }
    // Pack all file(s) but file(s) with these extension(s)
    if (isset($_['form']['x'])) {
        $xx = ',' . $_['form']['x'] . ',';
        foreach (g($f, 1, true) as $k => $v) {
            if (false !== strpos($xx, ',' . pathinfo($k, PATHINFO_EXTENSION) . ',')) {
                continue;
            }
            $zip->addFileFromPath(strtr($k, [
                $d . DS => "",
                DS => '/'
            ]), $k);
        }
    // Pack all file(s) with these extension(s) only
    } else if (isset($_['form']['v'])) {
        $xx = ',' . $_['form']['v'] . ',';
        foreach (g($f, 1, true) as $k => $v) {
            if (false === strpos($xx, ',' . pathinfo($k, PATHINFO_EXTENSION) . ',')) {
                continue;
            }
            $zip->addFileFromPath(strtr($k, [
                $d . DS => "",
                DS => '/'
            ]), $k);
        }
    // Pack all file(s)
    } else {
        foreach (g($f, 1, true) as $k => $v) {
            $zip->addFileFromPath(strtr($k, [
                $d . DS => "",
                DS => '/'
            ]), $k);
        }
    }
    $zip->finish();
} else if (is_file($f)) {
    $zip->addFileFromPath(strtr($f, [
        $d . DS => "",
        DS => '/'
    ]), $f);
    $zip->finish();
}

return $_;
