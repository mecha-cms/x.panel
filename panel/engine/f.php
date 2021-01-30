<?php namespace _\lot\x\panel;

function _abort($value, $key, $fn) {
    if (\defined("\\DEBUG") && \DEBUG) {
        \Guard::abort('Unable to convert data <code>' . \strtr(\htmlspecialchars(\json_encode($value, \JSON_PRETTY_PRINT)), [' ' => '&nbsp;', "\n" => '<br>']) . '</code> because function <code>' . $fn . '</code> does not exist.');
    }
}

function _error_form_check($_) {
    // TODO
}

function _error_route_check($_) {
    extract($GLOBALS, \EXTR_SKIP);
    $f = $_['f'];
    if (
        // Trying to set file under a file path
        's' === $_['task'] && $f && \is_file($f)
    ) {
        $_['alert']['info'][] = ['File %s already exists.', ['<code>' . \_\lot\x\panel\from\path($f) . '</code>']];
        $_['kick'] = \str_replace('::s::', '::g::', $url->current);
        return $_;
    }
    if (
        // Trying to get file that does not exist
        'g' === $_['task'] && !$f ||
        // Trying to set file from a folder that does not exist
        's' === $_['task'] && (!$f || !\is_dir($f))
    ) {
        $_['is']['error'] = 404;
        $_['title'] = \i('Error');
        $_['[layout]']['type:' . $_['type']] = false;
    }
    return ($GLOBALS['_'] = $_);
}

function _error_user_check($_) {
    extract($GLOBALS, \EXTR_SKIP);
    $status = $user['status'];
    $kick = static function() use($_, $status, $url, $user) {
        \Alert::error(\i('Permission denied.') . '<br><small>' . $url->current . '</small>');
        \Guard::kick($url . $_['/'] . '/::g::/user/' . $user->name(true) . $url->query('&', [
            'tab' => false,
            'type' => false
        ]) . $url->hash);
    };
    $rules = (array) \State::get('x.panel.guard.status.' . $status, true);
    if (isset($rules['bar'])) {
        // Single menu
        foreach ([
            'link' => 0,
            's' => 0,
            'search' => 0,
            'user' => 2
        ] as $k => $v) {
            if (!isset($_['lot']['bar']['lot'][$v]['lot'][$k])) {
                continue;
            }
            if (isset($rules['bar'][$k])) {
                $vv = $rules['bar'][$k];
                if (\is_callable($vv)) {
                    $vv = \call_user_func($vv, $_['task'], $_['path']);
                }
                if (false === $vv) {
                    $_['lot']['bar']['lot'][$v]['lot'][$k]['skip'] = true;
                }
                if (\is_array($vv)) {
                    $_['lot']['bar']['lot'][$v]['lot'][$k] = \array_replace($_['lot']['bar']['lot'][$v]['lot'][$k], $vv);
                }
            }
        }
        // Multiple menu
        foreach ([
            'folder' => 0,
            'site' => 1
        ] as $k => $v) {
            if (!isset($_['lot']['bar']['lot'][$v]['lot'][$k]['lot'])) {
                continue;
            }
            if (isset($rules['bar'][$k])) {
                if (false === $rules['bar'][$k]) {
                    $_['lot']['bar']['lot'][$v]['lot'][$k]['skip'] = true;
                }
                if (\is_array($rules['bar'][$k])) {
                    foreach ($rules['bar'][$k] as $kk => $vv) {
                        if (\is_callable($vv)) {
                            $vv = \call_user_func($vv, $_['task'], $_['path']);
                        }
                        if (false === $vv) {
                            $_['lot']['bar']['lot'][$v]['lot'][$k]['lot'][$kk]['skip'] = true;
                        }
                        if (\is_array($vv)) {
                            $_['lot']['bar']['lot'][$v]['lot'][$k]['lot'][$kk] = \array_replace($_['lot']['bar']['lot'][$v]['lot'][$k]['lot'][$kk], $vv);
                        }
                    }
                }
            }
        }
    }
    // `task` has a higher priority than `route`
    if (isset($rules['task'])) {
        foreach ($rules['task'] as $k => $v) {
            if ($m = \Route::is($_['/'] . '/:task/' . $k)) {
                $task = \array_shift($m[2]);
                if (\is_callable($v)) {
                    $v = \call_user_func($v, ...$m[2]);
                }
                if (false === $v) {
                    $kick();
                }
                if (\is_array($v)) {
                    foreach ($v as $kk => $vv) {
                        if ('::' . $kk . '::' === $task) {
                            if (false === $vv) {
                                $kick();
                            }
                        }
                    }
                }
                $skip_route_check = true;
            }
        }
    }
    // `route` must comes after `task` to get the `$skip_route_check` value
    if (isset($rules['route']) && empty($skip_route_check)) {
        foreach ($rules['route'] as $k => $v) {
            if ($m = \Route::is($_['/'] . '/:task/' . $k)) {
                // Replace `::g::` with `g` only
                $m[2][0] = \strtr($m[2][0], ['::' => ""]);
                if (\is_callable($v)) {
                    $v = \call_user_func($v, ...$m[2]);
                }
                if (false === $v) {
                    $kick();
                }
            }
        }
    }
    return $_;
}

function _set() {
    // Load panel definition from a file stored in `.\lot\x\*\index\panel.php`
    foreach ($GLOBALS['X'][1] as $v) {
        \is_file($v = \Path::F($v) . \DS . 'panel.php') && (static function($v) {
            extract($GLOBALS, \EXTR_SKIP);
            require $v;
            if (isset($_) && \is_array($_)) {
                $GLOBALS['_'] = \array_replace_recursive($GLOBALS['_'], $_);
            }
        })($v);
    }
    // Load panel definition from a file stored in `.\lot\layout\index\panel.php`
    \is_file($v = \LOT . \DS . 'layout' . \DS . 'index' . \DS . 'panel.php') && (static function($v) {
        extract($GLOBALS, \EXTR_SKIP);
        require $v;
        if (isset($_) && \is_array($_)) {
            $GLOBALS['_'] = \array_replace_recursive($GLOBALS['_'], $_);
        }
    })($v);
    return $GLOBALS['_'];
}

function _set_asset() {
    if (!empty($GLOBALS['_']['asset'])) {
        foreach ((array) $GLOBALS['_']['asset'] as $k => $v) {
            if (null === $v || false === $v || !empty($v['skip'])) {
                continue;
            }
            if ('script' === $k || 'style' === $k || 'template' === $k) {
                foreach ((array) $GLOBALS['_']['asset'][$k] as $kk => $vv) {
                    if (null === $vv || false === $vv || !empty($vv['skip'])) {
                        continue;
                    }
                    if (!\is_numeric($kk)) {
                        $vv[2]['id'] = $kk;
                    }
                    if (isset($vv['id'])) {
                        $vv[2]['id'] = $vv['id'];
                    }
                    $content = (string) ($vv[1] ?? $vv['content'] ?? "");
                    $stack = (float) ($vv['stack'] ?? 10);
                    \call_user_func("\\Asset::" . $k, $content, $stack, (array) ($vv[2] ?? []));
                }
                continue;
            }
            $path = (string) ($v['path'] ?? $v['link'] ?? $v['url'] ?? $k);
            $stack = (float) ($v['stack'] ?? 10);
            if (!\is_numeric($k) && (
                !empty($v['link']) ||
                !empty($v['path']) ||
                !empty($v['url'])
            )) {
                $v[2]['id'] = $k;
            }
            if (isset($v['id'])) {
                $v[2]['id'] = $v['id'];
            }
            \Asset::set($path, $stack, (array) ($v[2] ?? []));
        }
    }
    return $GLOBALS['_'];
}

function _set_class(&$value, array $tags = []) {
    $a = \explode(' ', $value['class'] ?? "");
    $b = \_\lot\x\panel\from\tags((array) $tags);
    $c = \array_unique(\array_filter(\array_merge($a, $b)));
    \sort($c);
    $value['class'] = $c ? \implode(' ', $c) : null;
}

function _set_state() {
    foreach (['are', 'can', 'has', 'is', 'not', '[layout]'] as $v) {
        if (isset($GLOBALS['_'][$v]) && \is_array($GLOBALS['_'][$v])) {
            \State::set($v, $GLOBALS['_'][$v]);
        }
    }
    return $GLOBALS['_'];
}

function _set_style(&$value, array $styles = []) {
    $a = $value['style'] ?? "";
    $b = \preg_split('/;\s*/', false !== \strpbrk($a, '\'"') ? \preg_replace_callback('/"(?:[^"\\\]|\\\.)*"|\'(?:[^\'\\\]|\\\.)*\'/', function($m) {
        return \strtr($m[0], [';' => \P]);
    }, $a) : $a);
    $c = [];
    foreach ($b as $bb) {
        $bbb = \explode(':', $bb, 2);
        $c[\trim($bbb[0])] = isset($bbb[1]) ? \strtr(\trim($bbb[1]), [\P => ';']) : null;
    }
    $d = "";
    foreach (\array_replace($c, $styles) as $k => $v) {
        if (null === $v || false === $v) {
            continue;
        }
        $d .= $k . ': ' . (\is_numeric($v) ? $v . 'px' : $v) . ';';
    }
    $value['style'] = "" !== $d ? $d : null;
}

function _set_type_prefix(&$value, $prefix) {
    foreach ($value as &$v) {
        $type = $v['type'] ?? null;
        if ($type !== $prefix && 0 !== \strpos($type, $prefix . '/')) {
            // Add prefix to `type`
            $type = $prefix . '/' . $type;
        }
        $v['type'] = \trim($type, '/');
    }
    unset($v);
}

function type($value, $key) {
    if (\is_string($value)) {
        return $value;
    }
    if (!empty($value['skip'])) {
        return "";
    }
    $out = "";
    if ($type = isset($value['type']) ? \strtr($value['type'], [
        '/' => "\\",
        '-' => '_',
        '.' => '__'
    ]) : null) {
        if ($type && \function_exists($fn = __NAMESPACE__ . "\\type\\" . $type)) {
            $out .= \call_user_func($fn, $value, $key);
        } else if (isset($value['content'])) {
            if ($type && \function_exists($fn = __NAMESPACE__ . "\\type\\content\\" . $type)) {
                $out .= \call_user_func($fn, $value, $key);
            } else {
                if (\defined("\\DEBUG") && \DEBUG) {
                    $value['title'] = ['Function %s does not exist.', ['<code>' . $fn . '</code>']];
                }
                $out .= \_\lot\x\panel\type\content($value, $key);
            }
        } else if (isset($value['lot'])) {
            if ($type && \function_exists($fn = __NAMESPACE__ . "\\type\\lot\\" . $type)) {
                $out .= \call_user_func($fn, $value, $key);
            } else {
                if (\defined("\\DEBUG") && \DEBUG) {
                    $value['title'] = ['Function %s does not exist.', ['<code>' . $fn . '</code>']];
                }
                $out .= \_\lot\x\panel\type\lot($value, $key);
            }
        } else {
            $out .= \_\lot\x\panel\_abort($value, $key, $fn);
        }
    } else {
        if (isset($value['content'])) {
            $out .= \_\lot\x\panel\type\content($value, $key);
        } else if (isset($value['lot'])) {
            $out .= \_\lot\x\panel\type\lot($value, $key);
        } else {
            // Skip!
        }
    }
    return $out;
}

require __DIR__ . \DS . 'f' . \DS . 'form.php';
require __DIR__ . \DS . 'f' . \DS . 'from.php';
require __DIR__ . \DS . 'f' . \DS . 'route.php';
require __DIR__ . \DS . 'f' . \DS . 'to.php';
require __DIR__ . \DS . 'f' . \DS . 'type.php';
