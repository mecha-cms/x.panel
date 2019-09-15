<?php namespace _\lot\x\panel\task\set;

function blob($_, $form) {
    extract($GLOBALS, \EXTR_SKIP);
    $e = $url->query('&', [
        'content' => false,
        'token' => false
    ]) . $url->hash;
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Abort by previous hook’s return value if any
        if (!empty($_['alert']['error'])) {
            return $_;
        }
        $test_x = ',' . \implode(',', \array_keys(\array_filter(\File::$config['x'] ?? $v['x[]'] ?? []))) . ',';
        $test_type = ',' . \implode(',', \array_keys(\array_filter(\File::$config['type'] ?? $v['type[]'] ?? []))) . ',';
        $test_size = \File::$config['size'] ?? [0, 0];
        foreach ($form['blob'] ?? [] as $k => $v) {
            // Check for error code
            if (!empty($v['error'])) {
                $_['alert']['error'][] = \Language::get('alert-info-file.' . $v['error']);
            }
            $name = \To::file($v['name']) ?: \uniqid();
            // Check for file extension
            $x = \pathinfo($name, \PATHINFO_EXTENSION);
            if ($x && \strpos($test_x, ',' . $x . ',') === false) {
                $_['alert']['error'][] = ['file-x', '<code>' . $x . '</code>'];
            }
            // Check for file type
            $type = $v['type'];
            if ($type && \strpos($test_type, ',' . $type . ',') === false) {
                $_['alert']['error'][] = ['file-type', '<code>' . $type . '</code>'];
            }
            // Check for file size
            $size = $v['size'];
            if ($size && $size < $test_size[0]) {
                $_['alert']['error'][] = ['file-size.0', '<code>' . \File::sizer($test_size) . '</code>'];
            }
            if ($size && $size > $test_size[1]) {
                $_['alert']['error'][] = ['file-size.1', '<code>' . \File::sizer($test_size) . '</code>'];
            }
            if (!empty($_['alert']['error'])) {
                continue;
            } else {
                $folder = \LOT . \DS . \strtr(\trim($v['to'] ?? $_['path'], '/'), '/', \DS);
                if (\is_file($f = $folder . \DS . $name)) {
                    $_['alert']['error'][] = ['file-exist', '<code>' . \_\lot\x\panel\h\path($f) . '</code>'];
                    continue;
                }
                if (!\is_dir($folder)) {
                    \mkdir($folder, 0775, true);
                }
                if (\move_uploaded_file($v['tmp_name'], $f)) {
                    $_['alert']['success'][] = ['blob-set', '<code>' . \_\lot\x\panel\h\path($f) . '</code>'];
                    $_['kick'] = $url . $_['//'] . '/::g::' . $_['path'] . '/1' . $e;
                    $_SESSION['_']['file'][$_['f'] = $f] = 1;
                    $_['ff'][] = $f;
                } else {
                    if (!\glob($folder . \DS . '*', \GLOB_NOSORT)) {
                        \rmdir($folder);
                    }
                    $_['alert']['error'][] = \To::sentence($language->isError);
                    continue;
                }
            }
        }
    }
    if (!empty($_['alert']['error'])) {
        unset($form['token']);
        $_SESSION['form'] = $form;
    }
    return $_;
}

function file($_, $form) {
    extract($GLOBALS, \EXTR_SKIP);
    $e = $url->query('&', [
        'content' => false,
        'token' => false
    ]) . $url->hash;
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Abort by previous hook’s return value if any
        if (!empty($_['alert']['error'])) {
            return $_;
        }
        $name = \basename(\To::file($form['file']['name'] ?? ""));
        $x = \pathinfo($name, \PATHINFO_EXTENSION);
        if ($name === "") {
            $_['alert']['error'][] = ['void-field', '<em>' . $language->name . '</em>', true];
        } else if (\strpos(',' . \implode(',', \array_keys(\array_filter(\File::$config['x'] ?? $form['x[]'] ?? []))) . ',', ',' . $x . ',') === false) {
            $_['alert']['error'][] = ['file-x', '<code>' . $x . '</code>'];
        } else if (\stream_resolve_include_path($f = $_['f'] . \DS . $name)) {
            $_['alert']['error'][] = ['file-exist', '<code>' . \_\lot\x\panel\h\path($f) . '</code>'];
        } else {
            if (isset($form['file']['content'])) {
                \file_put_contents($f, $form['file']['content']);
            }
            \chmod($f, \octdec($form['file']['seal'] ?? '0777'));
            $_['alert']['success'][] = ['file-set', '<code>' . \_\lot\x\panel\h\path($f) . '</code>'];
            $_['kick'] = $url . $_['//'] . '/::g::' . $_['path'] . '/1' . $e;
            $_SESSION['_']['file'][$_['f'] = $f] = 1;
        }
    }
    if (!empty($_['alert']['error'])) {
        unset($form['token']);
        $_SESSION['form'] = $form;
    }
    return $_;
}

function folder($_, $form) {
    extract($GLOBALS, \EXTR_SKIP);
    $e = $url->query('&', [
        'content' => false,
        'token' => false
    ]) . $url->hash;
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Abort by previous hook’s return value if any
        if (!empty($_['alert']['error'])) {
            return $_;
        }
        $name = \To::folder($form['folder']['name'] ?? "");
        if ($name === "") {
            $_['alert']['error'][] = ['void-field', '<em>' . $language->name . '</em>', true];
        } else if (\stream_resolve_include_path($f = $_['f'] . \DS . $name)) {
            $_['alert']['error'][] = ['folder-exist', '<code>' . $f . '</code>'];
        } else {
            \mkdir($f, \octdec($form['folder']['seal'] ?? '0755'), true);
            $_['alert']['success'][] = ['folder-set', '<code>' . \_\lot\x\panel\h\path($f) . '</code>'];
            if (!empty($form['folder']['kick'])) {
                $_['kick'] = $url . $_['//'] . '/::g::' . \strtr($f, [
                    \LOT => "",
                    \DS => '/'
                ]) . '/1' . $e;
            } else {
                $_['kick'] = $url . $_['//'] . '/::g::' . $_['path'] . '/1' . $e;
            }
            foreach (\step($_['f'] = $f, \DS) as $v) {
                $_SESSION['_']['folder'][$v] = 1;
            }
        }
    }
    if (!empty($_['alert']['error'])) {
        unset($form['token']);
        $_SESSION['form'] = $form;
    }
    return $_;
}

function _token($_, $form) {
    if (empty($form['token']) || $form['token'] !== $_['token']) {
        $_['alert']['error'][] = 'token';
    }
    return $_;
}

foreach (['blob', 'file', 'folder'] as $v) {
    \Hook::set('do.' . $v . '.set', __NAMESPACE__ . "\\_token", 0);
    \Hook::set('do.' . $v . '.set', __NAMESPACE__ . "\\" . $v, 10);
}