<?php namespace x\panel;

function _abort($value, $key, $fn) {
    if (\defined("\\TEST") && \TEST) {
        \abort('Unable to convert data <code>' . \strtr(\htmlspecialchars(\json_encode($value, \JSON_PRETTY_PRINT)), [' ' => '&nbsp;', "\n" => '<br>']) . '</code> because function <code>' . $fn . '(array $value, int|string $key)</code> does not exist.');
    }
}

function _asset_get() {
    // Capture all asset(s) data previously added by the extension(s) and layout you use, then mark them as ignored asset(s) so you can preserve
    // the asset(s) data but won’t make it load into the panel interface unless you explicitly change the `skip` property value to `false`.
    $data = [];
    foreach (\Asset::get() as $k => $v) {
        if ('script' === $k || 'style' === $k || 'template' === $k) {
            foreach ($v as $kk => $vv) {
                $data[$k][$kk] = [
                    'content' => $vv[1],
                    'data' => (array) ($vv[2] ?? []),
                    'skip' => true,
                    'stack' => $vv['stack']
                ];
            }
            continue;
        }
        foreach ($v as $kk => $vv) {
            $data[$kk] = [
                'data' => (array) ($vv[2] ?? []),
                'path' => $vv['path'],
                'skip' => true,
                'stack' => $vv['stack']
            ];
        }
    }
    $folder = \stream_resolve_include_path(\dirname(__DIR__));
    $z = \defined("\\TEST") && \TEST ? '.' : '.min.';
    $data[$folder . \D . 'index' . $z . 'css'] = ['stack' => 20];
    $data[$folder . \D . 'index' . $z . 'js'] = ['stack' => 20];
    $GLOBALS['_']['asset'] = \array_replace_recursive($GLOBALS['_']['asset'], $data);
    unset($data);
}

function _asset_let() {
    \Asset::let(); // Remove front-end asset(s)!
}

function _asset_set() {
    $_ = $GLOBALS['_'];
    if (!empty($_['asset'])) {
        foreach ((array) $_['asset'] as $k => $v) {
            if (false === $v || null === $v || !empty($v['skip'])) {
                continue;
            }
            if ('script' === $k || 'style' === $k || 'template' === $k) {
                foreach ((array) $_['asset'][$k] as $kk => $vv) {
                    if (false === $vv || null === $vv || !empty($vv['skip'])) {
                        continue;
                    }
                    if (!\is_numeric($kk)) {
                        $vv['data']['id'] = $kk;
                    }
                    if (isset($vv['id'])) {
                        $vv['data']['id'] = $vv['id'];
                    }
                    if (!\array_key_exists('content', $vv) && !empty($vv['type'])) {
                        $vv['content'] = \x\panel\type($vv, $kk);
                    }
                    $content = (string) ($vv['content'] ?? "");
                    $data = (array) ($vv['data'] ?? []);
                    $stack = (float) ($vv['stack'] ?? 10);
                    \call_user_func("\\Asset::" . $k, $content, $stack, $data);
                }
                continue;
            }
            $path = (string) ($v['path'] ?? $v['link'] ?? $v['url'] ?? $k);
            $stack = (float) ($v['stack'] ?? 10);
            if (!\is_numeric($k) && (!empty($v['link']) || !empty($v['path']) || !empty($v['url']))) {
                $v['data']['id'] = $k;
            }
            if (isset($v['id'])) {
                $v['data']['id'] = $v['id'];
            }
            \Asset::set($path, $stack, (array) ($v['data'] ?? []));
        }
    }
}

// Fix #13 <https://stackoverflow.com/a/53893947/1163000>
function _cache_let(string $path) {
    if (!\is_file($path)) {
        return $path;
    }
    if (\function_exists("\\opcache_invalidate") && \strlen((string) \ini_get('opcache.restrict_api')) < 1) {
        \opcache_invalidate($path, true);
    } else if (\function_exists("\\apc_compile_file")) {
        \apc_compile_file($path);
    }
    return $path;
}

function _decor_set(array $attr, array $value = []) {
    $decors = (array) ($value['decors'] ?? []);
    if (!empty($attr['style']) && \is_string($attr['style'])) {
        $key = $value = "";
        foreach (\preg_split('/(\/\*[\s\S]*?\*\/|"(?:[^"\\\]|\\\.)*"|\'(?:[^\'\\\]|\\\.)*\'|;)/', $attr['style'], -1, \PREG_SPLIT_DELIM_CAPTURE | \PREG_SPLIT_NO_EMPTY) as $v) {
            if (';' === $v || '/*' === \substr($v, 0, 2) && '*/' === \substr($v, -2)) {
                continue;
            }
            $v = \trim($v);
            if (\strpos($v, ':') > 0) {
                [$key, $value] = \preg_split('/\s*:\s*/', $v);
                if (!\array_key_exists($key, $decors)) {
                    $decors[$key] = $value;
                }
            } else if ($key && isset($decors[$key])) {
                $decors[$key] .= $v;
            }
        }
    }
    $out = "";
    foreach ($decors as $k => $v) {
        $out .= $k . ': ' . (\is_int($v) ? $v . 'px' : $v) . ';';
    }
    $attr['style'] = "" !== $out ? $out : null;
    return $attr;
}

// Check for update(s)
function _git_sync() {
    $_ = $GLOBALS['_'];
    if (!\is_dir($folder = \ENGINE . \D . 'log' . \D . 'git' . \D . 'versions')) {
        \mkdir($folder, 0775, true);
    }
    if (!\is_file($file = $folder . \D . 'mecha-cms.php')) {
        $versions = [];
        // Sync version(s) data every day
        if (false === \choke(60 * 60 * 24 /* 1 day */, 'git/versions/mecha-cms')) {
            foreach (\explode("\n", \fetch('https://mecha-cms.com/git-dev/versions/mecha-cms') ?? "") as $v) {
                $v = \explode(' ', $v);
                $versions[$v[1] ?? ""] = $v[0];
            }
            \file_put_contents($file, '<?' . 'php return ' . \z($versions) . ';');
        }
    } else {
        $versions = (array) require $file;
    }
    foreach ($versions as $k => $v) {
        if ('^' === $v) {
            continue;
        }
        // Core
        if ('mecha' === $k) {
            $page = new \Page(null, [
                'title' => 'Mecha',
                'version' => \VERSION
            ]);
            $t = $page->title;
        // Extension
        } else if (0 === \strpos($k, 'x.') && \is_file($file = \LOT . \D . 'x' . \D . \substr($k, 2) . \D . 'about.page')) {
            $page = new \Page($file);
            $t = '<a href="' . \x\panel\to\link([
                'hash' => null,
                'part' => 1,
                'path' => 'x/' . \substr($k, 2),
                'query' => null
            ]) . '">' . $page->title . '</a>';
        // Layout
        } else if (0 === \strpos($k, 'y.') && \is_file($file = \LOT . \D . 'y' . \D . \substr($k, 2) . \D . 'about.page')) {
            $page = new \Page($file);
            $t = '<a href="' . \x\panel\to\link([
                'hash' => null,
                'part' => 1,
                'path' => 'y/' . \substr($k, 2),
                'query' => null
            ]) . '">' . $page->title . '</a>';
        } else {
            continue; // Skip!
        }
        $version_current = \e(\array_replace([0, 0, 0], \explode('.', $page->version ?? '0.0.0')));
        $version_next = \e(\array_replace([0, 0, 0], \explode('.', $v)));
        $ready = \is_file($zip = \ENGINE . \D . 'log' . \D . 'git' . \D . 'zip' . \D . 'mecha-cms' . \D . $k . '@v' . $v . '.zip');
        // Major update
        if ($version_next[0] > $version_current[0]) {
            $alert = \i($ready ? '%s is ready to install.' : '%s has risky major updates to download.', [$t]);
        // Minor update
        } else if ($version_next[0] === $version_current[0] && $version_next[1] > $version_current[1]) {
            $alert = \i($ready ? '%s is ready to install.' : '%s has minor updates to download.');
        // Patch update
        } else if ($version_next[0] === $version_current[0] && $version_next[1] === $version_current[1] && $version_next[2] > $version_current[2]) {
            $alert = \i($ready ? '%s is ready to install.' : '%s has non-risky patch updates to download.');
        } else {
            continue; // Skip!
        }
        $title = \x\panel\type\title([
            'content' => [$alert, [$t]],
            'level' => -1
        ], 0);
        $tasks = \x\panel\type\tasks\link([
            '0' => 'span',
            'lot' => [
                'pull' => [
                    'description' => $ready ? 'Install' : 'Download',
                    'icon' => $ready ? 'M13,2.03V2.05L13,4.05C17.39,4.59 20.5,8.58 19.96,12.97C19.5,16.61 16.64,19.5 13,19.93V21.93C18.5,21.38 22.5,16.5 21.95,11C21.5,6.25 17.73,2.5 13,2.03M11,2.06C9.05,2.25 7.19,3 5.67,4.26L7.1,5.74C8.22,4.84 9.57,4.26 11,4.06V2.06M4.26,5.67C3,7.19 2.25,9.04 2.05,11H4.05C4.24,9.58 4.8,8.23 5.69,7.1L4.26,5.67M15.5,8.5L10.62,13.38L8.5,11.26L7.44,12.32L10.62,15.5L16.56,9.56L15.5,8.5M2.06,13C2.26,14.96 3.03,16.81 4.27,18.33L5.69,16.9C4.81,15.77 4.24,14.42 4.06,13H2.06M7.1,18.37L5.67,19.74C7.18,21 9.04,21.79 11,22V20C9.58,19.82 8.23,19.25 7.1,18.37Z' : 'M13,2.03C17.73,2.5 21.5,6.25 21.95,11C22.5,16.5 18.5,21.38 13,21.93V19.93C16.64,19.5 19.5,16.61 19.96,12.97C20.5,8.58 17.39,4.59 13,4.05V2.05L13,2.03M11,2.06V4.06C9.57,4.26 8.22,4.84 7.1,5.74L5.67,4.26C7.19,3 9.05,2.25 11,2.06M4.26,5.67L5.69,7.1C4.8,8.23 4.24,9.58 4.05,11H2.05C2.25,9.04 3,7.19 4.26,5.67M2.06,13H4.06C4.24,14.42 4.81,15.77 5.69,16.9L4.27,18.33C3.03,16.81 2.26,14.96 2.06,13M7.1,18.37C8.23,19.25 9.58,19.82 11,20V22C9.04,21.79 7.18,21 5.67,19.74L7.1,18.37M12,16.5L7.5,12H11V8H13V12H16.5L12,16.5Z',
                    'stack' => 10,
                    'title' => $v,
                    'url' => [
                        'hash' => null,
                        'part' => null,
                        'path' => 'mecha-cms/' . $k,
                        'query' => [
                            'minify' => 1,
                            'token' => $_['token'],
                            'version' => $v
                        ],
                        'task' => 'fire/git'
                    ]
                ]
            ]
        ], 0);
        $_['alert']['info'][$zip] = '<span role="group">' . $title . ' ' . $tasks . '</span>';
    }
    // Recursive-replace instead of re-assign the value because icon(s) data is also updated!
    $GLOBALS['_'] = \array_replace_recursive($GLOBALS['_'], $_);
}

function _key_set($key) {
    if (\is_object($key)) {
        return \spl_object_id($key);
    }
    return null === $key || \is_scalar($key) ? $key : \md5(\json_encode($key));
}

function _state_set() {
    $_ = $GLOBALS['_'];
    if ($_['status'] >= 400) {
        $_['is']['error'] = $_['status'];
    }
    if (null !== $_['type']) {
        $_['[y]']['type:' . $_['type']] = true;
    }
    foreach (['are', 'can', 'has', 'is', 'not', '[y]'] as $v) {
        if (isset($_[$v]) && \is_array($_[$v])) {
            \State::set($v, $_[$v]);
        }
    }
    $GLOBALS['_'] = $_;
}

function _tag_set(array $attr, array $value = []) {
    $tags = (array) ($value['tags'] ?? []);
    if (\array_keys($tags) === \range(0, \count($tags) - 1)) {
        // Convert `[0, 1, 2]` to `{0: true, 1: true, 2: true}`
        $tags = \array_fill_keys($tags, true);
    }
    foreach (['are', 'as', 'can', 'has', 'is', 'not', 'of', 'with'] as $v) {
        if (!empty($value[$v])) {
            foreach ($value[$v] as $kk => $vv) {
                $tags[$v . ':' . $kk] = $vv;
            }
        }
    }
    if (!empty($attr['class']) && \is_string($attr['class'])) {
        foreach (\preg_split('/\s+/', $attr['class']) as $v) {
            if (!\array_key_exists($v, $tags)) {
                $tags[$v] = true;
            }
        }
    }
    $tags = \array_keys(\array_filter($tags));
    \sort($tags);
    $attr['class'] = $tags ? \implode(' ', $tags) : null;
    return $attr;
}

function _type_parent_set($value, $parent) {
    foreach ($value as &$v) {
        $type = $v['type'] ?? "";
        if ($type !== $parent && 0 !== \strpos($type, $parent . '/')) {
            // Add parent to `type`
            $type = $parent . '/' . $type;
        }
        $v['type'] = \trim($type, '/');
    }
    unset($v);
    return $value;
}

function _value_set(array $value, $key = null) {
    return \array_replace_recursive([
        '0' => null,
        '1' => null,
        '2' => [],
        'active' => null,
        'are' => [],
        'as' => [],
        'can' => [],
        'content' => null,
        'count' => null,
        'current' => null,
        'decors' => [],
        'description' => null,
        'has' => [],
        'hint' => null,
        'icon' => null,
        'id' => null,
        'image' => null,
        'is' => [],
        'key' => \x\panel\_key_set($key),
        'keys' => [],
        'link' => null,
        'lot' => [],
        'name' => null,
        'not' => [],
        'of' => [],
        'size' => null,
        'skip' => null,
        'stack' => 10,
        'tags' => [],
        'title' => null,
        'type' => null,
        'url' => null,
        'value' => null,
        'values' => [],
        'with' => []
    ], $value);
}

function type($value, $key) {
    if (\is_string($value)) {
        return $value;
    }
    if (false === $value || null === $value || !empty($value['skip'])) {
        return "";
    }
    $value = \x\panel\_value_set($value, $key);
    if (isset($value[1]) && "" !== $value[1]) {
        return new \HTML($value);
    }
    $out = "";
    if ($type = \strtolower(\f2p(\strtr($value['type'] ?? "", '-', '_')))) {
        if (\function_exists($fn = __NAMESPACE__ . "\\type\\" . $type)) {
            if ($v = \call_user_func($fn, $value, $key)) {
                $out .= \is_array($v) ? \implode("\n", $v) : $v;
            }
        } else {
            $out .= \x\panel\_abort($value, $key, $fn);
        }
    } else {
        // Automatically forms an interface based on the presence of `content` or `lot` property
        if (isset($value['content'])) {
            if ($v = \x\panel\type\content($value, $key)) {
                $out .= $v;
            }
        } else if (isset($value['lot'])) {
            if ($v = \x\panel\type\lot($value, $key)) {
                $out .= $v;
            }
        } else {
            if (\defined("\\TEST") && \TEST) {
                $out .= \htmlspecialchars(\json_encode($value));
            }
            // Skip!
        }
    }
    return $out;
}

require __DIR__ . D . 'f' . D . 'from.php';
require __DIR__ . D . 'f' . D . 'to.php';
require __DIR__ . D . 'f' . D . 'type.php';