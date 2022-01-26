<?php namespace x\panel\task\g;

// TODO: Allow to execute delete task via `POST` request

function blob($_) {
}

function data($_) {
}

function file($_) {
}

function folder($_) {
}

function page($_) {
    extract($GLOBALS, \EXTR_SKIP);
    $hash = $_['form']['lot']['hash'] ?? "";
    $e = \To::query(\array_replace([
        'stack' => $_['form']['lot']['stack'] ?? [],
        'tab' => $_['form']['lot']['tab'] ?? []
    ], $_['form']['lot']['query'] ?? [])) . ("" !== $hash ? '#' . $hash : "");
    if ('post' === $_['form']['type']) {
        // Abort by previous hook’s return value if any
        if (isset($_['kick']) || !empty($_['alert']['error'])) {
            return $_;
        }
        $f = $_['f'];
        $name = (string) \To::kebab($_['form']['lot']['page']['name'] ?? $_['form']['lot']['page']['title'] ?? "");
        $x = $_['form']['lot']['page']['x'] ?? 'page';
        if ("" === $name) {
            $name = \date('Y-m-d-H-i-s');
        }
        unset($_['form']['lot']['page']['name'], $_['form']['lot']['page']['x']);
        $page = [];
        $p = (array) ($state->x->page->page ?? []);
        foreach ($_['form']['lot']['page'] as $k => $v) {
            if (
                // Skip `null` value
                null === $v ||
                // Skip empty value
                \is_array($v) && 0 === \count($v) ||
                \is_string($v) && "" === \trim($v) ||
                // Skip default value
                isset($p[$k]) && $p[$k] === $v
            ) {
                continue;
            }
            if (\is_array($v)) {
                if ($v = \drop(\array_replace_recursive($page[$k] ?? [], $v))) {
                    $page[$k] = $v;
                }
            } else {
                $page[$k] = $v;
            }
        }
        $_['form']['lot']['file']['content'] = $_POST['file']['content'] = \To::page($page);
        $_['form']['lot']['file']['name'] = $name . '.' . $x;
        $_ = file($_); // Move to `file`
        $ff = $_['f']; // Get new file name
        if (empty($_['alert']['error'])) {
            if (!\is_dir($dd = \Path::F($ff))) {
                \mkdir($dd, 0755, true);
            }
            if ($ff !== $f && \is_dir($d = \Path::F($f))) {
                \rename($d, $dd);
            }
            if (isset($_['form']['lot']['data'])) {
                foreach ((array) $_['form']['lot']['data'] as $k => $v) {
                    $fff = $dd . \DS . $k . '.data';
                    if ((\is_array($v) && $v = \drop($v)) || "" !== \trim($v)) {
                        if (!\stream_resolve_include_path($fff) || \is_writable($fff)) {
                            \file_put_contents($fff, \is_array($v) ? \json_encode($v) : \s($v));
                            \chmod($fff, 0600);
                        } else {
                            $_['alert']['error'][$fff] = ['File %s is not writable.', ['<code>' . \x\panel\from\path($fff) . '</code>']];
                        }
                    } else {
                        \is_file($fff) && \unlink($fff);
                    }
                }
            }
        }
    }
    if (\is_file($ff = $_['f'])) {
        $key = \ucfirst(\ltrim($_['id'], '_.-'));
        $path = '<code>' . \x\panel\from\path($f ?? $ff) . '</code>';
        $alter = [
            'File %s already exists.' => ['%s %s already exists.', [$key, $path]],
            'File %s successfully updated.' => ['%s %s successfully updated.', [$key, $path]]
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

function state($_) {
    extract($GLOBALS, \EXTR_SKIP);
    $hash = $_['form']['lot']['hash'] ?? "";
    $e = \To::query(\array_replace([
        'stack' => $_['form']['lot']['stack'] ?? [],
        'tab' => $_['form']['lot']['tab'] ?? []
    ], $_['form']['lot']['query'] ?? [])) . ("" !== $hash ? '#' . $hash : "");
    if ('post' === $_['form']['type']) {
        // Abort by previous hook’s return value if any
        if (isset($_['kick']) || !empty($_['alert']['error'])) {
            return $_;
        }
        if (\is_file($f = \LOT . \DS . \trim(\strtr($_['form']['lot']['path'] ?? $_['path'], '/', \DS), \DS))) {
            $_['f'] = $f = \realpath($f); // For hook(s)
            $v = \drop($_['form']['lot']['state'] ?? []);
            $_['form']['lot']['file']['content'] = $_POST['file']['content'] = '<?php return ' . \z($v) . ';';
            $_ = file($_); // Move to `file`
        }
        $_['kick'] = $_['form']['lot']['kick'] ?? $_['/'] . '/::g::/' . $_['path'] . $e;
        if (!empty($_['alert']['error'])) {
            unset($_POST['token']);
            $_SESSION['form'] = $_POST;
        }
    }
    return $_;
}

foreach (['blob', 'data', 'file', 'folder', 'page', 'state'] as $v) {
    \Hook::set('do.' . $v . '.get', __NAMESPACE__ . "\\" . $v, 10);
}