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
    $f = __DIR__ . \D . '..' . \D . 'lot' . \D . 'asset';
    $f = \stream_resolve_include_path($f) . \D;
    $z = \defined("\\TEST") && \TEST ? '.' : '.min.';
    $data['panel.skin'] = [
        'id' => false,
        'path' => $f . 'index' . $z . 'css',
        'stack' => 20
    ];
    $data[$f . \D . 'index' . $z . 'js'] = ['stack' => 20];
    foreach (['bar', 'column', 'file', 'link', 'menu', 'page', 'proxy', 'row', 'stack', 'tab', 'task'] as $v) {
        $data[$f . 'index' . \D . $v . $z . 'js'] = ['stack' => 30];
    }
    foreach (['option', 'query', 'source'] as $v) {
        $data[$f . 'index' . \D . 'field' . \D . $v . $z . 'js'] = ['stack' => 40];
    }
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

function _class_set(array $attr, array $value = []) {
    $a = [];
    $tags = (array) ($value['tags'] ?? []);
    foreach (\explode(' ', $attr['class'] ?? "") as $v) {
        if (\array_key_exists($v, $tags) && !$tags[$v]) {
            continue;
        }
        $a[] = $v;
    }
    $b = \x\panel\from\tags($tags);
    $c = \array_unique(\array_filter(\array_merge($a, $b)));
    \sort($c);
    $attr['class'] = $c ? \implode(' ', $c) : null;
    return $attr;
}

function _key_set($key) {
    if (\is_object($key)) {
        return \spl_object_id($key);
    }
    return \is_scalar($key) ? $key : \md5(\json_encode($key));
}

function _state_set() {
    $_ = $GLOBALS['_'];
    if ($_['status'] >= 400) {
        $_['is']['error'] = $_['status'];
    }
    if (null !== $_['type']) {
        $_['[layout]']['type:' . $_['type']] = true;
    }
    foreach (['are', 'can', 'has', 'is', 'not', '[layout]'] as $v) {
        if (isset($_[$v]) && \is_array($_[$v])) {
            \State::set($v, $_[$v]);
        }
    }
    $GLOBALS['_'] = $_;
}

function _style_set(array $attr, array $value = []) {
    $a = $attr['style'] ?? "";
    $styles = (array) ($value['styles'] ?? []);
    $b = \preg_split('/;\s*/', false !== \strpbrk($a, '\'"') ? \preg_replace_callback('/"(?:[^"\\\]|\\\.)*"|\'(?:[^\'\\\]|\\\.)*\'/', static function($m) {
        return \strtr($m[0], [';' => \P]);
    }, $a) : $a);
    $c = [];
    foreach ($b as $bb) {
        $bbb = \explode(':', $bb, 2);
        $c[\trim($bbb[0])] = isset($bbb[1]) ? \strtr(\trim($bbb[1]), [\P => ';']) : null;
    }
    $d = "";
    foreach (\array_replace($c, $styles) as $k => $v) {
        if (false === $v || null === $v) {
            continue;
        }
        $d .= $k . ': ' . (\is_numeric($v) ? $v . 'px' : $v) . ';';
    }
    $attr['style'] = "" !== $d ? $d : null;
    return $attr;
}

function _type_parent_set(&$value, $parent) {
    foreach ($value as &$v) {
        $type = $v['type'] ?? "";
        if ($type !== $parent && 0 !== \strpos($type, $parent . '/')) {
            // Add parent to `type`
            $type = $parent . '/' . $type;
        }
        $v['type'] = \trim($type, '/');
    }
    unset($v);
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
        'description' => null,
        'has' => [],
        'icon' => null,
        'id' => null,
        'image' => null,
        'is' => [],
        'key' => \x\panel\_key_set($key),
        'link' => null,
        'lot' => [],
        'not' => [],
        'of' => [],
        'skip' => null,
        'stack' => 10,
        'styles' => [],
        'tags' => [],
        'title' => null,
        'type' => null,
        'url' => null,
        'value' => null,
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
    $out = "";
    $value = \x\panel\_value_set($value, $key);
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