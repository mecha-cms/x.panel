<?php

Hook::set('do.blob.set', function ($_) {
    if ('POST' !== $_SERVER['REQUEST_METHOD']) {
        return $_;
    }
    if (!empty($_['alert']['error']) || $_['status'] >= 400) {
        return $_;
    }
    $r = basename($folder = $_['folder'] ?? "");
    if (!extension_loaded('zip')) {
        $_['alert']['error'][$folder] = ['Missing %s extension.', 'PHP <a href="https://www.php.net/manual/en/class.ziparchive.php" rel="nofollow" target="_blank"><code>zip</code></a>'];
        return $_;
    }
    $error = false;
    if (isset($_POST['blobs']) && is_array($_POST['blobs'])) {
        foreach ($_POST['blobs'] as $k => $v) {
            if (!empty($v['status'])) {
                $error = true;
                continue;
            }
            $name = pathinfo($v['name'], PATHINFO_FILENAME);
            $x = pathinfo($v['name'], PATHINFO_EXTENSION);
            // Match `x.foo-bar`, `x.foo-bar@main`, `x.foo-bar@v1.0.0` or <https://semver.org#is-there-a-suggested-regular-expression-regex-to-check-a-semver-string>
            if (preg_match('/^' . x($r)  . '\.([^@]+)(?:[@](?:main|v(0|[1-9]\d*)\.(0|[1-9]\d*)\.(0|[1-9]\d*)(?:-((?:0|[1-9]\d*|\d*[a-zA-Z-][0-9a-zA-Z-]*)(?:\.(?:0|[1-9]\d*|\d*[a-zA-Z-][0-9a-zA-Z-]*))*))?(?:\+([0-9a-zA-Z-]+(?:\.[0-9a-zA-Z-]+)*))?))?$/', $name, $m)) {
                $wrap = $m[1];
            } else {
                $wrap = strtok($name, '@');
            }
            $_POST['options'][$k]['folder'] = $wrap; // Wrap package in a folder
            $_POST['options'][$k]['zip']['extract'] = true; // Extract package
            $_POST['options'][$k]['zip']['keep'] = false; // Delete package
            // Allow ZIP archive(s) only
            if ('zip' !== $x) {
                $error = true;
                $_['alert']['error'][] = ['File extension %s is not allowed.', '<code>' . $x . '</code>'];
            }
        }
    }
    if (!$error && $folder) {}
    return $_;
}, 9.9);

return x\panel\type\blob\x($_);