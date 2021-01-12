<?php namespace _\lot\x\panel\h;

function _error_route_check($_) {
    extract($GLOBALS, \EXTR_SKIP);
    $f = $_['f'];
    if (
        // Trying to set file under a file path
        's' === $_['task'] && $f && \is_file($f)
    ) {
        $_['alert']['info'][] = ['File %s already exists.', ['<code>' . \_\lot\x\panel\h\path($f) . '</code>']];
        $_['kick'] = \str_replace('::s::', '::g::', $url->current);
        return $_;
    } else if (
        // Trying to get file that does not exist
        'g' === $_['task'] && !$f ||
        // Trying to set file from a folder that does not exist
        's' === $_['task'] && (!$f || !\is_dir($f))
    ) {
        $_['is']['error'] = 404;
        $_['title'] = \i('Error');
        $_['[layout]']['type:' . $_['type']] = false;
        return $_;
    }
    return null;
}

function _user_action_limit_check($_) {
    extract($GLOBALS, \EXTR_SKIP);
    $status = $user['status'];
    $kick = static function() use($_, $status, $url, $user) {
        \Alert::error(\i('Permission denied for your current user status: %s', '<code>' . $status . '</code>') . '<br><small>' . $url->current . '</small>');
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

function c(&$out, array $tags0 = [], array $tags1 = []) {
    $a = \explode(' ', $out['class'] ?? "");
    $b = \_\lot\x\panel\h\tags((array) $tags0);
    $c = \_\lot\x\panel\h\tags((array) $tags1);
    $d = \array_unique(\array_filter(\array_merge($a, $b, $c)));
    \sort($d);
    $out['class'] = $d ? \implode(' ', $d) : null;
}

function color($color) {
    // Convert RGB color string into HEX color string
    // <https://www.regular-expressions.out/numericranges.html>
    if (0 === \strpos($color, 'rgb') && \preg_match('/^\s*rgba?\s*\(\s*([01]?[0-9]?[0-9]|2[0-4][0-9]|25[0-5])\s*,\s*([01]?[0-9]?[0-9]|2[0-4][0-9]|25[0-5])\s*,\s*([01]?[0-9]?[0-9]|2[0-4][0-9]|25[0-5])(?:\s*,\s*([01]|0?\.\d+))?\s*\)\s*$/', $color, $m)) {
        $color = '#' . \sprintf('%02x%02x%02x', (int) $m[1], (int) $m[2], (int) $m[3]);
    }
    // Validate HEX color string
    $s = \strlen($color);
    if ((4 === $s || 7 === $s) && '#' === $color[0] && \ctype_xdigit(\substr($color, 1))) {
        // Convert short HEX color string into long HEX color string
        if (4 === $s) {
            $m = \str_split(\substr($color, 1));
            $color = '#' . ($m[0] . $m[0]) . ($m[1] . $m[1]) . ($m[2] . $m[2]);
        }
        return $color;
    }
    return null;
}

function content($content) {
    return \is_array($content) ? new \HTML($content) : (string) $content;
}

function description($in, $or = null) {
    $description = $in['description'] ?? $or;
    if (!isset($description) || false === $description) {
        return;
    }
    $out = [
        0 => 'p',
        1 => \w('<span>' . \i(...\array_values("" !== $description ? (array) $description : (array) $or)) . '</span>', ['a', 'abbr', 'b', 'code', 'del', 'em', 'i', 'ins', 'span', 'strong', 'sub', 'sup']),
        2 => []
    ];
    unset($in['tags']);
    \_\lot\x\panel\h\c($out[2], ['description' => 1]);
    return new \HTML($out);
}

function field($in, $key) {
    $in['id'] = $in['id'] ?? 'f:' . \dechex(\crc32($key));
    $name = $in['name'] ?? $key;
    if ($disabled = isset($in['active']) && !$in['active']) {
        $in['tags']['not:active'] = 1;
    // `else if` because mixing both `disabled` and `readonly` attribute does not make sense
    } else if ($readonly = !empty($in['frozen'])) {
        $in['tags']['is:frozen'] = 1;
    }
    // TODO: Need a better key name
    if ($required = !empty($in['required'])) {
        $in['tags']['is:required'] = 1;
    }
    $input = [
        0 => 'textarea',
        1 => \htmlspecialchars($in['value'] ?? ""),
        2 => [
            'autofocus' => !empty($in['focus']),
            'class' => "",
            'disabled' => $disabled ?? null,
            'id' => $in['id'],
            'name' => $name,
            'pattern' => $in['pattern'] ?? null,
            'placeholder' => \i(...((array) ($in['alt'] ?? []))),
            'readonly' => $readonly ?? null,
            'required' => $required ?? null
        ]
    ];
    $in['content'] = $input;
    return $in;
}

// Fix #13 <https://stackoverflow.com/a/53893947/1163000>
function fresh($in) {
    if (\function_exists("\\opcache_invalidate") && \strlen((string) \ini_get('opcache.restrict_api')) < 1) {
        \opcache_invalidate($in, true);
    } else if (\function_exists("\\apc_compile_file")) {
        \apc_compile_file($in);
    }
    return $in;
}

function icon($in) {
    $icon = \array_replace([null, null], (array) $in);
    if ($icon[0] && false === strpos($icon[0], '<')) {
        $GLOBALS['SVG'][$id = \dechex(\crc32($icon[0]))] = $icon[0];
        $icon[0] = '<svg height="12" width="12"><use href="#i:' . $id . '"></use></svg>';
    }
    if ($icon[1] && false === strpos($icon[1], '<')) {
        $GLOBALS['SVG'][$id = \dechex(\crc32($icon[1]))] = $icon[1];
        $icon[1] = '<svg height="12" width="12"><use href="#i:' . $id . '"></use></svg>';
    }
    return $icon;
}

function lot($lot, $fn = null, &$count = 0) {
    if (!\is_array($lot)) {
        return;
    }
    $out = "";
    foreach ((new \Anemon($lot))->sort([1, 'stack', 10], true) as $k => $v) {
        if (null === $v || false === $v || !empty($v['skip'])) {
            continue;
        }
        $v = $fn ? \call_user_func($fn, $v, $k) : \_\lot\x\panel($v, $k);
        if ($v) {
            ++$count;
        }
        $out .= $v;
    }
    return $out;
}

function p(&$lot, $prefix) {
    foreach ($lot as &$v) {
        $type = $v['type'] ?? null;
        if ($type !== $prefix && 0 !== \strpos($type, $prefix . '/')) {
            // Add prefix to `type`
            $type = $prefix . '/' . $type;
        }
        $v['type'] = \trim($type, '/');
    }
    unset($v);
}

function path($in) {
    return \strtr($in, [
        '/' => \DS,
        \ROOT => '.'
    ]);
}

function tags($in) {
    // `[0, 1, 2]`
    if (\array_keys($in) === \range(0, \count($in) - 1)) {
        return $in;
    }
    // `{0: true, 1: true, 2: true}`
    return \array_keys(\array_filter($in));
}

function title($in, $i = -1, $or = null) {
    $title = $in['title'] ?? $or;
    if ((!isset($title) || false === $title) && (!isset($in['icon']) || empty($in['icon']))) {
        return;
    }
    $tag = false;
    if (-1 === $i) {
        $tag = 'span';
    } else if (0 === $i) {
        $tag = 'p';
    } else if ($i > 0) {
        $tag = 'h' . $i;
    }
    $out = [
        0 => $tag,
        1 => "",
        2 => []
    ];
    $icon = \_\lot\x\panel\h\icon($in['icon'] ?? [null, null]);
    if (null !== $title && false !== $title) {
        $title = \w('<span>' . \i(...\array_values((array) $title)) . '</span>', ['a', 'abbr', 'b', 'code', 'del', 'em', 'i', 'ins', 'span', 'strong', 'sub', 'sup']);
    }
    $out[1] = $icon[0] . $title . $icon[1];
    unset($in['tags']);
    \_\lot\x\panel\h\c($out[2], [
        'has:icon' => !!($icon[0] || $icon[1]),
        'has:title' => !!$title,
        'title' => 1
    ]);
    return new \HTML($out);
}

function w($in, $also = null) {
    return \w('<div>' . $in . '</div>', 'abbr,b,br,cite,code,del,dfn,em,i,img,ins,kbd,mark,q,span,strong,sub,sup,svg,time,u,var' . ($also ? ',' . $also : ""));
}
