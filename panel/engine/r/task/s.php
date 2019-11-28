<?php namespace _\lot\x\panel\task\set;

function blob($_, $lot) {
    extract($GLOBALS, \EXTR_SKIP);
    $e = $url->query('&', [
        'layout' => false,
        'tab'=> false,
        'token' => false,
        'trash' => false
    ]) . $url->hash;
    if ('POST' === $_SERVER['REQUEST_METHOD']) {
        // Abort by previous hook’s return value if any
        if (isset($_['kick']) || !empty($_['alert']['error'])) {
            return $_;
        }
        $test_x = ',' . \implode(',', \array_keys(\array_filter(\File::$state['x'] ?? []))) . ',';
        $test_type = ',' . \implode(',', \array_keys(\array_filter(\File::$state['type'] ?? []))) . ',';
        $test_size = \File::$state['size'] ?? [0, 0];
        foreach ($lot['blob'] ?? [] as $k => $v) {
            // Check for error code
            if (!empty($v['error'])) {
                $_['alert']['error'][] = 'Blob: [' . $v['error'] . ']';
            } else {
                $name = \To::file(\lcfirst($v['name'])) ?? '0';
                $x = \pathinfo($name, \PATHINFO_EXTENSION);
                $type = $v['type'] ?? 'application/octet-stream';
                $size = $v['size'] ?? 0;
                // Check for file extension
                if ($x && false === \strpos($test_x, ',' . $x . ',')) {
                    $_['alert']['error'][] = ['File extension %s is not allowed.', '<code>' . $x . '</code>'];
                // Check for file type
                } else if ($type && false === \strpos($test_type, ',' . $type . ',')) {
                    $_['alert']['error'][] = ['File type %s is not allowed.', '<code>' . $type . '</code>'];
                }
                // Check for file size
                if ($size < $test_size[0]) {
                    $_['alert']['error'][] = ['Minimum file size allowed to upload is %s.', '<code>' . \File::sizer($test_size) . '</code>'];
                } else if ($size > $test_size[1]) {
                    $_['alert']['error'][] = ['Maximum file size allowed to upload is %s.', '<code>' . \File::sizer($test_size) . '</code>'];
                }
            }
            if (!empty($_['alert']['error'])) {
                continue;
            } else {
                $folder = \LOT . \DS . \strtr(\trim($v['to'] ?? $_['path'], '/'), '/', \DS);
                if (\is_file($f = $folder . \DS . $name)) {
                    $_['alert']['error'][] = ['File %s already exists.', '<code>' . \_\lot\x\panel\h\path($f) . '</code>'];
                    continue;
                }
                if (!\is_dir($folder)) {
                    \mkdir($folder, \octdec($v['seal'] ?? '0775'), true);
                }
                if (\move_uploaded_file($v['tmp_name'], $f)) {
                    $_['alert']['success'][] = ['File %s successfully uploaded.', '<code>' . \_\lot\x\panel\h\path($f) . '</code>'];
                    $_['kick'] = $url . $_['/'] . '::g::' . $_['path'] . '/1' . $e;
                    $_SESSION['_']['file'][$_['f'] = $f] = 1;
                    $_['ff'][] = $f;
                    // Extract package
                    if (
                        !empty($lot['o']['extract']) &&
                        \extension_loaded('zip') &&
                        ('zip' === $x || 'application/zip' === $type)
                    ) {
                        $zip = new \ZipArchive;
                        $zip_extract = true;
                        if (true === $zip->open($f)) {
                            for ($i = 0, $j = $zip->numFiles; $i < $j; ++$i) {
                                $n = \strtr($zip->getNameIndex($i), '/', \DS);
                                if (\DS === \substr($n, -1)) {
                                    continue; // Skip folder(s)
                                }
                                $x = \pathinfo($n, \PATHINFO_EXTENSION);
                                // This prevents user(s) from uploading forbidden file(s)
                                if ("" !== $x && false === \strpos($test_x, ',' . $x . ',')) {
                                    $_['alert']['error'][] = ['File extension %s is not allowed.', '<code>' . $x . '</code>'];
                                    $zip_extract = false;
                                // This prevents user(s) from accidentally overwrite the existing file(s)
                                } else if (\stream_resolve_include_path($ff = \rtrim($folder . \DS . $n, \DS))) {
                                    $_['alert']['error'][] = ['File %s already exists.', '<code>' . \_\lot\x\panel\h\path($ff) . '</code>'];
                                    $zip_extract = false;
                                // Mark all file(s) and folder(s) from package after succeeded
                                } else {
                                    $_SESSION['_']['file'][$ff] = 1;
                                    $_SESSION['_']['folder'][\rtrim($folder . DS . \dirname($n), \DS)] = 1;
                                }
                            }
                            if ($zip_extract && $zip->extractTo($folder)) {
                                if (!empty($lot['o']['let'])) {
                                    $zip->close();
                                    \unlink($f);
                                    $_['alert']['success'][] = ['Package %s successfully extracted and deleted.', '<code>' . \_\lot\x\panel\h\path($f) . '</code>'];
                                } else {
                                    $_['alert']['success'][] = ['Package %s successfully extracted.', '<code>' . \_\lot\x\panel\h\path($f) . '</code>'];
                                }
                            } else {
                                $_['alert']['error'][] = ['Package %s could not be extracted due to the previous errors.', '<code>' . \_\lot\x\panel\h\path($f) . '</code>'];
                            }
                        }
                        $zip->close();
                    }
                } else {
                    if (0 === \q(\g($folder))) {
                        \rmdir($folder);
                    }
                    $_['alert']['error'][] = 'Error.';
                    continue;
                }
            }
        }
    }
    if (!empty($_['alert']['error'])) {
        unset($lot['token']);
        $_SESSION['form'] = $lot;
    }
    return $_;
}

function data($_, $lot) {
    extract($GLOBALS, \EXTR_SKIP);
    $e = $url->query('&', [
        'layout' => false,
        'tab' => ['data'],
        'token' => false,
        'trash' => false
    ]) . $url->hash;
    if ('POST' === $_SERVER['REQUEST_METHOD']) {
        $name = \basename(\To::file(\lcfirst($lot['data']['name'] ?? "")));
        $lot['file']['name'] = "" !== $name ? $name . '.data' : "";
        $lot['file']['content'] = $lot['data']['content'] ?? "";
        $_ = file($_, $lot); // Move to `file`
        if (empty($_['alert']['error']) && $parent = \glob(\dirname($_['f']) . '.{archive,draft,page}', \GLOB_BRACE | \GLOB_NOSORT)) {
            $_['kick'] = $url . $_['/'] . '::g::' . $_['path'] . '.' . \pathinfo($parent[0], \PATHINFO_EXTENSION) . $e;
        }
    }
    return $_;
}

function file($_, $lot) {
    extract($GLOBALS, \EXTR_SKIP);
    $e = $url->query('&', [
        'layout' => false,
        'tab'=> false,
        'token' => false,
        'trash' => false
    ]) . $url->hash;
    if ('POST' === $_SERVER['REQUEST_METHOD']) {
        // Abort by previous hook’s return value if any
        if (isset($_['kick']) || !empty($_['alert']['error'])) {
            return $_;
        }
        $name = \basename(\To::file(\lcfirst($lot['file']['name'] ?? "")));
        $x = \pathinfo($name, \PATHINFO_EXTENSION);
        // Special case for PHP file(s)
        if ('php' === $x && isset($lot['file']['content'])) {
            // This should be enough to detect PHP syntax error before saving
            \token_get_all($lot['file']['content'], \TOKEN_PARSE);
        }
        if ("" === $name) {
            $_['alert']['error'][] = ['Please fill out the %s field.', 'Name'];
        } else if (false === \strpos(',' . \implode(',', \array_keys(\array_filter(\File::$state['x'] ?? $lot['x[]'] ?? []))) . ',', ',' . $x . ',')) {
            $_['alert']['error'][] = ['File extension %s is not allowed.', '<code>' . $x . '</code>'];
        } else if (\stream_resolve_include_path($f = $_['f'] . \DS . $name)) {
            $_['alert']['error'][] = [(\is_dir($f) ? 'Folder' : 'File') . ' %s already exists.', '<code>' . \_\lot\x\panel\h\path($f) . '</code>'];
            $_['f'] = $f;
        } else {
            if (\array_key_exists('content', $lot['file'] ?? [])) {
                \file_put_contents($f, $lot['file']['content'] ?? "");
            }
            \chmod($f, \octdec($lot['file']['seal'] ?? '0777'));
            $_['alert']['success'][] = ['File %s successfully created.', '<code>' . \_\lot\x\panel\h\path($f) . '</code>'];
            $_['kick'] = $url . $_['/'] . '::g::' . $_['path'] . '/1' . $e;
            $_SESSION['_']['file'][$_['f'] = $f] = 1;
        }
    }
    if (!empty($_['alert']['error'])) {
        unset($lot['token']);
        $_SESSION['form'] = $lot;
    }
    return $_;
}

function folder($_, $lot) {
    extract($GLOBALS, \EXTR_SKIP);
    $e = $url->query('&', [
        'layout' => false,
        'tab'=> false,
        'token' => false,
        'trash' => false
    ]) . $url->hash;
    if ('POST' === $_SERVER['REQUEST_METHOD']) {
        // Abort by previous hook’s return value if any
        if (isset($_['kick']) || !empty($_['alert']['error'])) {
            return $_;
        }
        $name = \To::folder($lot['folder']['name'] ?? "");
        if ("" === $name) {
            $_['alert']['error'][] = ['Please fill out the %s field.', 'Name'];
        } else if (\stream_resolve_include_path($f = $_['f'] . \DS . $name)) {
            $_['alert']['error'][] = [(\is_dir($f) ? 'Folder' : 'File') . ' %s already exists.', '<code>' . $f . '</code>'];
        } else {
            \mkdir($f, \octdec($lot['folder']['seal'] ?? '0755'), true);
            $_['alert']['success'][] = ['Folder %s successfully created.', '<code>' . \_\lot\x\panel\h\path($f) . '</code>'];
            if (!empty($lot['o']['kick'])) {
                $_['kick'] = $url . $_['/'] . '::g::' . \strtr($f, [
                    \LOT => "",
                    \DS => '/'
                ]) . '/1' . $e;
            } else {
                $_['kick'] = $url . $_['/'] . '::g::' . $_['path'] . '/1' . $e;
            }
            foreach (\step($_['f'] = $f, \DS) as $v) {
                $_SESSION['_']['folder'][$v] = 1;
            }
        }
    }
    if (!empty($_['alert']['error'])) {
        unset($lot['token']);
        $_SESSION['form'] = $lot;
    }
    return $_;
}

function page($_, $lot) {
    extract($GLOBALS, \EXTR_SKIP);
    $e = $url->query('&', [
        'layout' => false,
        'tab'=> false,
        'token' => false,
        'trash' => false
    ]) . $url->hash;
    if ('POST' === $_SERVER['REQUEST_METHOD']) {
        // Abort by previous hook’s return value if any
        if (isset($_['kick']) || !empty($_['alert']['error'])) {
            return $_;
        }
        $name = \To::kebab($lot['page']['name'] ?? $lot['page']['title'] ?? "");
        $x = $lot['page']['x'] ?? 'page';
        if ("" === $name) {
            $name = \date('Y-m-d-H-i-s');
        }
        unset($lot['page']['name'], $lot['page']['x']);
        $page = [];
        $p = (array) ($state->x->page ?? []);
        foreach ($lot['page'] as $k => $v) {
            if (
                // Skip empty value
                "" === \trim($v) ||
                // Skip default value
                isset($p[$k]) && $p[$k] === $v
            ) {
                continue;
            }
            $page[$k] = $v;
        }
        $lot['file']['content'] = \To::page($page);
        $lot['file']['name'] = $name . '.' . $x;
        $_ = file($_, $lot); // Move to `file`
        if (empty($_['alert']['error'])) {
            if (!\is_dir($d = \Path::F($_['f']))) {
                \mkdir($d, 0755, true);
            }
            if (isset($lot['data'])) {
                foreach ((array) $lot['data'] as $k => $v) {
                    if ("" !== \trim($v)) {
                        \file_put_contents($ff = $d . \DS . $k . '.data', \is_array($v) ? \json_encode($v) : \s($v));
                        \chmod($ff, 0600);
                    }
                }
            }
        }
    }
    if (\is_file($f = $_['f'])) {
        $key = \ucfirst(\ltrim($_['chops'][0], '_.-'));
        $path = '<code>' . \_\lot\x\panel\h\path($f) . '</code>';
        $alter = [
            'File %s already exists.' => ['%s %s already exists.', [$key, $path]],
            'File %s successfully created.' => ['%s %s successfully created.', [$key, $path]]
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

function state($_, $lot) {
    // State must be exists, so there is no such create event, only update
    return $_;
}

function _token($_, $lot) {
    if (empty($lot['token']) || $lot['token'] !== $_['token']) {
        $_['alert']['error'][] = 'Invalid token.';
    }
    return $_;
}

foreach (['blob', 'data', 'file', 'folder', 'page', 'state'] as $v) {
    \Hook::set('do.' . $v . '.set', __NAMESPACE__ . "\\_token", 0);
    \Hook::set('do.' . $v . '.set', __NAMESPACE__ . "\\" . $v, 10);
}