<?php namespace _\lot\x\panel\task\set;

// Redirect if file already exists
if (($f = $_['f']) && \is_file($f)) {
    \Alert::info(\Language::get('alert-error-file-exist', ['<code>' . \_\lot\x\panel\h\path($f) . '</code>']));
    \Guard::kick(\str_replace('::s::', '::g::', $url->current));
}

function blob($_, $form) {
    extract($GLOBALS, \EXTR_SKIP);
    $e = $url->query('&', [
        'content' => false,
        'tab'=> false,
        'token' => false
    ]) . $url->hash;
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Abort by previous hook’s return value if any
        if (!empty($_['alert']['error'])) {
            return $_;
        }
        $test_x = ',' . \implode(',', \array_keys(\array_filter(\File::$config['x'] ?? $v['blob']['x'] ?? []))) . ',';
        $test_type = ',' . \implode(',', \array_keys(\array_filter(\File::$config['type'] ?? $v['blob']['type'] ?? []))) . ',';
        $test_size = \File::$config['size'] ?? $v['blob']['size'] ?? [0, 0];
        foreach ($form['blob'] ?? [] as $k => $v) {
            // Check for error code
            if (!empty($v['error'])) {
                $_['alert']['error'][] = \Language::get('alert-info-file.' . $v['error']);
            }
            $name = \To::file($v['name']) ?? '0';
            $x = \pathinfo($name, \PATHINFO_EXTENSION);
            $type = $v['type'] ?? 'application/octet-stream';
            $size = $v['size'] ?? 0;
            // Check for file extension
            if ($x && \strpos($test_x, ',' . $x . ',') === false) {
                $_['alert']['error'][] = ['file-x', '<code>' . $x . '</code>', true];
            // Check for file type
            } else if ($type && \strpos($test_type, ',' . $type . ',') === false) {
                $_['alert']['error'][] = ['file-type', '<code>' . $type . '</code>', true];
            }
            // Check for file size
            if ($size < $test_size[0]) {
                $_['alert']['error'][] = ['file-size.0', '<code>' . \File::sizer($test_size) . '</code>', true];
            } else if ($size > $test_size[1]) {
                $_['alert']['error'][] = ['file-size.1', '<code>' . \File::sizer($test_size) . '</code>', true];
            }
            if (!empty($_['alert']['error'])) {
                continue;
            } else {
                $folder = \LOT . \DS . \strtr(\trim($v['to'] ?? $_['path'], '/'), '/', \DS);
                if (\is_file($f = $folder . \DS . $name)) {
                    $_['alert']['error'][] = ['file-exist', '<code>' . \_\lot\x\panel\h\path($f) . '</code>', true];
                    continue;
                }
                if (!\is_dir($folder)) {
                    \mkdir($folder, \octdec($v['seal'] ?? '0775'), true);
                }
                if (\move_uploaded_file($v['tmp_name'], $f)) {
                    $_['alert']['success'][] = ['blob-set', '<code>' . \_\lot\x\panel\h\path($f) . '</code>', true];
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

function data($_, $form) {
    extract($GLOBALS, \EXTR_SKIP);
    $e = $url->query('&', [
        'content' => false,
        'tab' => ['data'],
        'token' => false,
        'x' => false
    ]) . $url->hash;
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $name = \basename(\To::file($form['data']['name'] ?? ""));
        $form['file']['name'] = $name !== "" ? $name . '.data' : "";
        $form['file']['content'] = $form['data']['content'] ?? "";
        $_ = file($_, $form); // Move to `file`
        if (empty($_['alert']['error']) && !empty($_GET['x'])) {
            $_['kick'] = $url . $_['//'] . '/::g::' . $_['path'] . '.' . $_GET['x'] . $e;
        }
    }
    return $_;
}

function file($_, $form) {
    extract($GLOBALS, \EXTR_SKIP);
    $e = $url->query('&', [
        'content' => false,
        'tab'=> false,
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
            $_['alert']['error'][] = ['void-field', '<strong>' . $language->name . '</strong>', true];
        } else if (\strpos(',' . \implode(',', \array_keys(\array_filter(\File::$config['x'] ?? $form['x[]'] ?? []))) . ',', ',' . $x . ',') === false) {
            $_['alert']['error'][] = ['file-x', '<code>' . $x . '</code>', true];
        } else if (\stream_resolve_include_path($f = $_['f'] . \DS . $name)) {
            $_['alert']['error'][] = [(\is_dir($f) ? 'folder' : 'file') . '-exist', '<code>' . \_\lot\x\panel\h\path($f) . '</code>', true];
        } else {
            if (isset($form['file']['content'])) {
                \file_put_contents($f, $form['file']['content']);
            }
            \chmod($f, \octdec($form['file']['seal'] ?? '0777'));
            $_['alert']['success'][] = ['file-set', '<code>' . \_\lot\x\panel\h\path($f) . '</code>', true];
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
        'tab'=> false,
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
            $_['alert']['error'][] = [(\is_dir($f) ? 'folder' : 'file') . '-exist', '<code>' . $f . '</code>', true];
        } else {
            \mkdir($f, \octdec($form['folder']['seal'] ?? '0755'), true);
            $_['alert']['success'][] = ['folder-set', '<code>' . \_\lot\x\panel\h\path($f) . '</code>', true];
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

function page($_, $form) {
    extract($GLOBALS, \EXTR_SKIP);
    $e = $url->query('&', [
        'content' => false,
        'tab'=> false,
        'token' => false
    ]) . $url->hash;
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Abort by previous hook’s return value if any
        if (!empty($_['alert']['error'])) {
            return $_;
        }
        $name = \To::kebab($form['page']['name'] ?? $form['page']['title'] ?? "");
        $x = $form['page']['x'] ?? 'page';
        if ($name === "") {
            $name = \date('Y-m-d-H-i-s');
        }
        if (isset($form['page']['time'])) {
            $form['page']['time'] = (new \Date($form['page']['time']))->format('Y-m-d H:i:s');
        }
        if (isset($form['page']['update'])) {
            $form['page']['update'] = (new \Date($form['page']['update']))->format('Y-m-d H:i:s');
        }
        $form['file']['content'] = \To::page(\array_filter($form['page'] ?? []));
        $form['file']['name'] = $name . '.' . $x;
        $_ = file($_, $form); // Move to `file`
        if (empty($_['alert']['error'])) {
            if (!\is_dir($d = \Path::F($_['f']))) {
                \mkdir($d, 0755, true);
            }
            if (isset($form['data'])) {
                if (isset($form['data']['time'])) {
                    $form['data']['time'] = (new \Date($form['data']['time']))->format('Y-m-d H:i:s');
                } else {
                    $form['data']['time'] = \date('Y-m-d H:i:s', $t); // Force
                }
                if (isset($form['data']['update'])) {
                    $form['data']['update'] = (new \Date($form['data']['time']))->format('Y-m-d H:i:s');
                }
                foreach ((array) $form['data'] as $k => $v) {
                    \file_put_contents($ff = $d . \DS . $k . '.data', \is_array($v) ? \json_encode($v) : \s($v));
                    \chmod($ff, 0600);
                }
            }
        }
    }
    if (\is_file($f = $_['f'])) {
        $title = '<strong>' . (new \Page($f))->title . '</strong>';
        $alter = [
            'file-exist' => 'page-exist',
            'file-set' => ['page-set', $title]
        ];
        foreach ($_['alert'] as $k => &$v) {
            foreach ($v as $kk => &$vv) {
                if (\is_array($vv)) {
                    if (isset($alter[$vv[0]])) {
                        $vv = \array_replace($vv, $alter[$vv[0]]);
                    }
                } else if (\is_string($vv)) {
                    $vv = $alter[$vv] ?? $vv;
                }
            }
        }
    }
    return $_;
}

function _token($_, $form) {
    if (empty($form['token']) || $form['token'] !== $_['token']) {
        $_['alert']['error'][] = 'token';
    }
    return $_;
}

foreach (['blob', 'data', 'file', 'folder', 'page'] as $v) {
    \Hook::set('do.' . $v . '.set', __NAMESPACE__ . "\\_token", 0);
    \Hook::set('do.' . $v . '.set', __NAMESPACE__ . "\\" . $v, 20);
}