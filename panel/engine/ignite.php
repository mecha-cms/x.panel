<?php namespace fn\panel;

// These are just helper function(s) used to reduce the repeating code over includable file(s).
// These function(s) should not be re-used in your custom extension, plugin and shield.

function _init($in, &$attr, $key, $id, $i, $alt = []) {
    if (!\array_key_exists('path', $in)) {
        global $panel;
        $in['path'] = \trim($panel->id . '/' . $panel->path, '/');
    }
    if (!\array_key_exists('title', $in)) {
        global $language;
        $in['title'] = $language->{\str_replace('.', "\\.", $id)};
    }
    if (!\array_key_exists('class[]', $attr)) {
        $attr['class[]'] = [];
    }
    $attr['class[]'] = \concat($attr['class[]'], [$key], $id !== false ? [$key . ':' . $id, $key . ':' . $id . '.' . $i] : []);
    if (!\array_key_exists('id', $attr)) {
        $attr['id'] = $id !== false ? $key . ':' . $id . '.' . $i : null;
    }
    if (!empty($in['kind'])) {
        $attr['class[]'] = \concat($attr['class[]'], (array) $in['kind']);
    }
    if (!empty($in['x'])) {
        $attr['class[]'][] = 'x';
    }
    if (\array_key_exists('i', $in)) {
        $attr['data[]']['i'] = $in['i'];
    }
    $attr = \extend($attr, $alt);
    return $in;
}

function _clean($in) {
    return _walk($in, function($v) {
        return \Is::void($v);
    });
}

function _glob($folder) {
    $folders = $files = [];
    if (\is_array($folder)) {
        foreach ($folder as $v) {
            $v = \strtr($v, '/', DS);
            if (\substr($v, -1) === DS || \is_dir($v)) {
                $folders[] = $v;
            } else {
                $files[] = $v;
            }
        }
    } else {
        $folder = \rtrim($folder, DS);
        // <https://stackoverflow.com/a/33059445/1163000>
        foreach (\glob($folder . DS . '{,.}[!.,!..]*', \GLOB_NOSORT | \GLOB_MARK | \GLOB_BRACE) as $v) {
            $n = \basename($v);
            if (\substr($v, -1) === DS) {
                $folders[] = \rtrim($v, DS);
            } else {
                $files[] = $v;
            }
        }
    }
    \natsort($files);
    \natsort($folders);
    return [$folders, $files];
}

function _hidden($in) {
    if (!\array_key_exists('hidden', (array) $in)) {
        return false;
    } else if (empty($in['hidden'])) {
        return false;
    }
    return isset($in['hidden']) && !empty($in['hidden']);
}

// <http://salman-w.blogspot.com/2014/04/stackoverflow-like-pagination.html>
function _pager($current, $count, $chunk, $kin, $fn, $first, $previous, $next, $last) {
    $begin = 1;
    $end = (int) \ceil($count / $chunk);
    $s = "";
    if ($end <= 1) {
        return $s;
    }
    if ($current <= $kin + $kin) {
        $min = $begin;
        $max = \min($begin + $kin + $kin, $end);
    } else if ($current > $end - $kin - $kin) {
        $min = $end - $kin - $kin;
        $max = $end;
    } else {
        $min = $current - $kin;
        $max = $current + $kin;
    }
    if ($previous) {
        $s = '<span>';
        if ($current === $begin) {
            $s .= '<b title="' . $previous . '">' . $previous . '</b>';
        } else {
            $s .= '<a href="' . \call_user_func($fn, $current - 1) . '" title="' . $previous . '" rel="prev">' . $previous . '</a>';
        }
        $s .= '</span> ';
    }
    if ($first && $last) {
        $s .= '<span>';
        if ($min > $begin) {
            $s .= '<a href="' . \call_user_func($fn, $begin) . '" title="' . $first . '" rel="prev">' . $begin . '</a>';
            if ($min > $begin + 1) {
                $s .= ' <span>&#x2026;</span>';
            }
        }
        for ($i = $min; $i <= $max; ++$i) {
            if ($current === $i) {
                $s .= ' <b title="' . $i . '">' . $i . '</b>';
            } else {
                $s .= ' <a href="' .\call_user_func($fn, $i) . '" title="' . $i . '" rel="' . ($current >= $i ? 'prev' : 'next') . '">' . $i . '</a>';
            }
        }
        if ($max < $end) {
            if ($max < $end - 1) {
                $s .= ' <span>&#x2026;</span>';
            }
            $s .= ' <a href="' . \call_user_func($fn, $end) . '" title="' . $last . '" rel="next">' . $end . '</a>';
        }
        $s .= '</span>';
    }
    if ($next) {
        $s .= ' <span>';
        if ($current === $end) {
            $s .= '<b title="' . $next . '">' . $next . '</b>';
        } else {
            $s .= '<a href="' . \call_user_func($fn, $current + 1) . '" title="' . $next . '" rel="next">' . $next . '</a>';
        }
        $s .= '</span>';
    }
    return $s;
}

function _tools($tools, $path, $id, $i) {
    $path = \strtr($path, '/', DS);
    $out = '<ul class="tools">';
    foreach ($tools as $k => $v) {
        if (!$v) continue;
        if (isset($v['if']) && \is_callable($v['if'])) {
            $v = \extend($v, \call_user_func($v['if'], $path, $v, $k, $id, $i));
            unset($v['if']);
        }
        if (_hidden($v)) {
            continue;
        } else if (!\array_key_exists('path', $v)) {
            $v['path'] = \str_replace(LOT . DS, "", $path);
        }
        $out .= '<li>' . a($v, false) . '</li>';
    }
    return $out . '</ul>';
}

function _walk($in, $fn) {
    foreach ($in as $k => $v) {
        if (\is_array($v)) {
            $o = _walk($v, $fn);
            if (!empty($o)) {
                $in[$k] = $o;
            } else {
                unset($in[$k]);
            }
        } else {
            if ($fn($v, $k)) {
                unset($in[$k]);
            }
        }
    }
    return $in;
}

function a($in, $id = 0, $attr = [], $i = 0) {
    if (\is_string($in)) {
        return $in;
    }
    $in = _init($in, $attr, 'a', $id, $i, [
        'href' => href($in),
        'target' => $in['target'] ?? null,
        'title' => isset($in['description']) ? \To::text($in['description']) : null
    ]);
    if (!\array_key_exists('active', $in)) {
        global $panel, $url;
        $p = $in['url'] ?? \trim($panel->r . '/::' . ($in['c'] ?? $panel->c) . '::/' . ($in['path'] ?? $panel->id . '/' . $panel->path), '/');
        if (\strpos($url->path . '/1/', $p . '/') === 0) {
            $in['active'] = true;
        }
    }
    if (!empty($in['active'])) {
        $attr['class[]'] = \concat($attr['class[]'] ?? [], ['active']);
    }
    $out = $in['content'] ?? text($in['title'] ?? "", $in['icon'] ?? []);
    return \HTML::unite('a', $out, $attr);
}

function href($in) {
    if (\is_string($in)) {
        return $in;
    } else if (!empty($in['x'])) {
        return 'javascript:;';
    }
    // `[link[path[url]]]`
    $out = "";
    if (isset($in['task'])) {
        $user = \Lot::get('user');
        $in['task'] = (array) $in['task'];
        $in['c'] = 'a';
        $in['query']['a'] = \array_shift($in['task']) ?? false;
        $in['query']['lot'] = \array_shift($in['task']) ?? false;
        $in['query']['token'] = $user->token;
        unset($in['task']);
    }
    if (isset($in['link'])) {
        $out = $in['link'];
    } else if (isset($in['url'])) {
        $out = \URL::long(\strtr($in['url'], DS, '/'));
    } else if (isset($in['path'])) {
        global $panel;
        $out = \rtrim(\URL::long($panel->r . '/::' . ($in['c'] ?? 'g') . '::/' . \ltrim(\strtr($in['path'], DS, '/'), '/')), '/');
    }
    if (isset($in['query'])) {
        $out .= \HTTP::query($in['query'], '&');
    }
    if (isset($in['hash'])) {
        $out .= '#' . \urlencode($in['hash']);
    }
    return $out;
}

function button($in, $id = 0, $attr = [], $i = 0) {
    if (\is_string($in)) {
        return $in;
    } else if (!empty($in['x'])) {
        $attr['disabled'] = true;
    }
    $in = _init($in, $attr, 'button', $id, $i, [
        'title' => isset($in['description']) ? \To::text($in['description']) : null
    ]);
    $is_button = isset($in['name']) || isset($in['value']) || \has(['button', 'reset', 'submit'], $in['type'] ?? "");
    if ($is_button) {
        isset($in['name']) && $attr['name'] = $in['name'];
        isset($in['type']) && $attr['type'] = $in['type'];
        isset($in['value']) && $attr['value'] = $in['value'];
    } else {
        $attr['href'] = href($in);
    }
    $out = $in['content'] ?? text($in['title'] ?? "", $in['icon'] ?? []);
    return \HTML::unite($is_button ? 'button' : 'a', $out, $attr);
}

function data($path, $id = 0, $attr = [], $i = 0, $tools = []) {
    _init([], $attr, 'data', $id, $i);
    global $panel, $url;
    $out  = '<h3 class="title">';
    $out .= '<span title="' . \File::sizer(filesize($path)) . '">' . \Path::N($path) . '</span>';
    $out .= '</h3>';
    if ($tools) {
        $out .= _tools($tools, $path, $id, $i);
    }
    return \HTML::unite('li', $out, $attr);
}

function datas($datas, $id = 0, $attr = [], $i = 0) {
    global $panel, $url;
    $files = q(\glob($datas . DS . '*.data', \GLOB_NOSORT));
    \sort($files);
    \Config::set('panel.+.explore', $files);
    $out = "";
    $directory = \is_string($datas) ? \str_replace([LOT . DS, DS], ["", '/'], $datas) : null;
    _init([], $attr, 'datas', $id, $i);
    $files = \Anemon::eat($files)->chunk($panel->state->file->chunk, $url->i === null ? 0 : $url->i - 1);
    if ($files->count()) {
        $tools = \Anemon::eat(\Config::get('panel.+.data.tool', [], true))->sort([1, 'stack']);
        $session = \strtr(X . \implode(X, (array) \Session::get('panel.file.active')) . X, '/', DS);
        foreach ($files as $k => $v) {
            $n = \basename($v);
            $h = $n !== '..' && (\strpos($n, '.') === 0 || \strpos($n, '_') === 0);
            $a = \strpos($session, X . $v . X) !== false;
            $out .= data($v, $id, [
                'class[]' => [
                    -2 => $h ? 'is-hidden' : null,
                    -1 => $a ? 'active' : null
                ]
            ], $i, $tools);
        }
    }
    return \HTML::unite('ul', $out, $attr);
}

function desk($in, $id = 0, $attr = [], $i = 0) {
    if (\is_string($in)) {
        return $in;
    }
    $in = _init($in, $attr, 'desk', $id, $i);
    if (isset($in['content'])) {
        $out = $in['content'];
    } else {
        $out = "";
        isset($in['header']) && $out .= desk_header($in['header'], $id, [], $i);
        isset($in['body']) && $out .= desk_body($in['body'], $id, [], $i);
        isset($in['footer']) && $out .= desk_footer($in['footer'], $id, [], $i);
    }
    return \HTML::unite('div', $out, $attr);
}

function desk_body($in, $id = 0, $attr = [], $i = 0) {
    if (\is_string($in)) {
        return $in;
    } else if (isset($in['content'])) {
        $out = $in['content'];
    } else {
        $out = "";
        if (isset($in['explore'])) {
            global $panel;
            $fn = __NAMESPACE__ . "\\" . \HTTP::get('view', $panel->view) . 's';
            if (\is_callable($fn)) {
                $out .= \call_user_func($fn, $in['explore'], $id, [], $i);
            } else {
                $out .= \call_user_func(__NAMESPACE__ . "\\files", $in['explore'], $id, [], $i);
            }
        } else if (isset($in['tab'])) {
            $out .= tabs($in['tab'], $id, [], $i);
        } else if (isset($in['field'])) {
            $out .= fields($in['field'], $id, [], $i);
        }
    }
    $in = _init($in, $attr, 'body', $id, $i);
    return \HTML::unite('main', $out, $attr);
}

function desk_footer($in, $id = 0, $attr = [], $i = 0) {
    if (\is_string($in)) {
        return $in;
    }
    $in = _init($in, $attr, 'footer', $id, $i);
    if (isset($in['content'])) {
        $out = $in['content'];
    } else {
        $out = "";
        if (isset($in['tool'])) {
            $out .= tools($in['tool'], $id, [], $i);
        } else if (isset($in['pager'])) {
            $out .= pager($id, [], $i);
        }
    }
    return \HTML::unite('footer', $out, $attr);
}

function desk_header($in, $id = 0, $attr = [], $i = 0) {
    if (\is_string($in)) {
        return $in;
    } else if (isset($in['content'])) {
        $out = $in['content'];
    } else {
        $out = "";
        if (isset($in['tool'])) {
            $out .= tools($in['tool'], $id, [], $i);
        }
    }
    $in = _init($in, $attr, 'header', $id, $i);
    return \HTML::unite('header', $out, $attr);
}

function field($key, $in, $id = 0, $attr = [], $i = 0) {
    if (\is_string($in)) {
        return $in;
    } else if (isset($in['content'])) {
        $out = $in['content'];
    } else {
        global $language;
        $out = "";
        $k = \ltrim(\ltrim($key, '.!*'), '\\');
        $kind = (array) ($in['kind'] ?? []);
        $style = [];
        $title = $in['title'] ?? $language->{$in['key'] ?? $k};
        $description = $in['description'] ?? null;
        $active = $in['active'] ?? false;
        $type = $in['type'] ?? 'textarea';
        $value = $in['value'] ?? \HTTP::get('f.' . \str_replace(['.', '[', ']', X], [X, '.', "", "\\."], $k), null, false) ?? null;
        $values = (array) ($in['values'] ?? []);
        $placeholder = $in['placeholder'] ?? $value;
        $pattern = $in['pattern'] ?? null;
        $width = $in['width'] ?? null;
        $height = $in['height'] ?? null;
        $range = (array) ($in['range'] ?? []);
        $syntax = $in['syntax'] ?? null;
        $expand = !empty($in['expand']);
        $block = !empty($in['block']);
        $clone = $in['clone'] ?? 0; // TODO
        \asort($values);
        $copy = $in;
        $copy['kind'] = ['type:' . $type];
        $copy = _init($copy, $attr, 'field', $id, $i);
        if ($range && !$description) {
            $description = \implode('&#x2013;', $range);
        }
        if ($width === true) {
            $kind[] = 'width';
        } else if (\is_numeric($width)) {
            $style['width'] = $width . 'px';
        }
        if ($height === true) {
            $kind[] = 'height';
        } else if (\is_numeric($height)) {
            $style['height'] = $height . 'px';
        }
        if ($expand) {
            $attr['class[]'][] = 'expand';
        }
        // Add `*` mark for required field(s)
        if (\strpos($key, '*') === 0) {
            $attr['class[]'][] = 'required';
        }
        $alt = [
            'class[]' => $kind,
            'pattern' => $pattern
        ];
        _init([], $alt, 'f', $id, $i, [
            'style[]' => $style
        ]);
        $out .= '<label for="f:' . $id . '.' . $i . '" title="' . $k . '">' . $title . '</label>';
        $textarea = \has(['content', 'source', 'textarea'], $type);
        $node = $in[0] ?? ($textarea ? 'div' : 'span');
        $out .= '<' . $node . '>';
        if ($type === 'hidden') {
            return \Form::hidden($key, $value);
        } else if ($type === 'blob') {
            $alt['class[]'][] = 'input';
            $out .= \Form::blob($key, $alt);
        } else if ($type === 'select') {
            $alt['class[]'][] = 'select';
            $out .= \Form::select($key, $values, $value, $alt);
        } else if ($type === 'radio[]') {
            unset($alt['id']);
            $alt['class[]'][] = 'input';
            $out .= '<span class="inputs ' . ($block ? 'block' : 'inline') . '">';
            $out .= \Form::radio($key, $values, $value, $alt);
            $out .= '</span>';
        } else if ($type === 'toggle[]') {
            unset($alt['id']);
            $alt['class[]'][] = 'input';
            $out .= '<span class="inputs ' . ($block ? 'block' : 'inline') . '">';
            $a = [];
            foreach ($values as $k => $v) {
                // $v = [$text, $checked ?? false, $value ?? 1]
                $v = (array) $v;
                $a[] = \Form::check($key . '[' . $k . ']', $v[2] ?? 1, !empty($v[1]), $v[0], $alt);
            }
            $out .= \implode(\HTML::br(), $a) . '</span>';
        } else if ($type === 'toggle') {
            $alt['class[]'][] = 'input';
            $out .= \Form::check($key, $value, $active, $description, $alt);
        } else if (\has(['source', 'textarea'], $type)) {
            $alt['class[]'][] = 'textarea';
            if ($type === 'source') {
                $alt['class[]'][] = 'code';
                $alt['data[]']['syntax'] = $syntax;
            }
            $out .= \Form::textarea($key, $value, $placeholder, $alt);
        } else if (\has(['color', 'date', 'email', 'number', 'pass', 'search', 'tel', 'text', 'url'], $type)) {
            $alt['class[]'][] = 'input';
            if ($range) {
                if ($type === 'number') {
                    $alt['min'] = $range[0] ?? null;
                    $alt['max'] = $range[1] ?? null;
                } else if ($type === 'range') {
                    $value = [$range[0] ?? 0, $value, $range[1] ?? 100];
                }
            }
            $out .= \call_user_func("\\Form::" . $type, $key, $value, $placeholder, $alt);
        } else /* if ($type === 'content') */ {
            $out .= $value;
        }
        if ($description) {
            if ($node === 'div') {
                $out .= '<div class="hints">' . $description . '</div>';
            } else {
                $out .= ' <span class="hints">' . $description . '</span>';
            }
        }
        $out .= '</' . $node . '>';
        if ($textarea) {
            $attr['class[]'][] = 'p';
            return \HTML::unite('div', $out, $attr);
        }
    }
    return \HTML::unite('p', $out, $attr);
}

function fields($in, $id = 0, $attr = [], $i = 0) {
    if (\is_string($in)) {
        return $in;
    }
    $out = "";
    $j = 0;
    $hidden = [];
    foreach (\Anemon::eat($in)->sort([1, 'stack'], true) as $k => $v) {
        if (!$v || _hidden($v)) continue;
        if (isset($v['type']) && $v['type'] === 'hidden') {
            $hidden[$k] = $v;
            continue;
        }
        $out .= field($k, $v, $v['key'] ?? $k, [], $j);
        ++$j;
    }
    foreach (\Anemon::eat($hidden)->sort([1, 'stack'], true) as $k => $v) {
        $out .= field($k, $v, $v['key'] ?? $k, [], $j);
        ++$j;
    }
    return $out;
}

function file($path, $id = 0, $attr = [], $i = 0, $tools = []) {
    $name = \basename($path);
    $directory = \str_replace([LOT . DS, DS], ["", '/'], $path);
    _init([], $attr, 'file', $id, $i);
    $attr['class[]'] = \concat($attr['class[]'], [
        'is-' . (($is_file = \is_file($path)) ? 'file' : 'folder'),
        $is_file ? 'x:' . \Path::X($path, '#') : null
    ]);
    global $panel, $url;
    $out  = '<h3 class="title">';
    $out .= '<a href="' . ($is_file ? \To::URL($path) : $url . '/' . $panel->r . '/::g::/' . ($name !== '..' ? $directory : \dirname($directory)) . '/1' . $url->query('&amp;')) . '"' . ($is_file ? ' target="_blank"' : "") . ' title="' . ($is_file ? \File::sizer(filesize($path)) : ($name === '..' ? \basename(\dirname($url->path)) : "")) . '">' . $name . '</a>';
    $out .= '</h3>';
    if ($name !== '..' && $tools) {
        $out .= _tools($tools, $path, $id, $i);
    }
    return \HTML::unite('li', $out, $attr);
}

function files($folder, $id = 0, $attr = [], $i = 0) {
    global $panel, $url;
    $files = q(\concat(..._glob($folder)));
    \Config::set('panel.+.explore', $files);
    $out = "";
    $directory = \is_string($folder) ? \str_replace([LOT . DS, DS], ["", '/'], $folder) : null;
    _init([], $attr, 'files', $id, $i);
    $files = \Anemon::eat($files)->chunk($panel->state->file->chunk, $url->i === null ? 0 : $url->i - 1);
    if ($files->count()) {
        if (\is_string($folder) && \trim(\dirname($directory), '.') !== "") {
            $files->prepend(\dirname(LOT . DS . $directory) . DS . '..');
        }
        $tools = \Anemon::eat(\Config::get('panel.+.file.tool', [], true))->sort([1, 'stack']);
        $session = \strtr(X . \implode(X, (array) \Session::get('panel.file.active')) . X, '/', DS);
        foreach ($files as $k => $v) {
            $n = \basename($v);
            $h = $n !== '..' && (\strpos($n, '.') === 0 || \strpos($n, '_') === 0);
            $a = \strpos($session, X . $v . X) !== false;
            $out .= file($v, $id, [
                'class[]' => [
                    -2 => $h ? 'is-hidden' : null,
                    -1 => $a ? 'active' : null
                ]
            ], $i, $tools);
        }
    } else if (\is_string($folder) && \dirname($folder) !== LOT) {
        $out = file(\dirname($folder) . DS . '..', $id, [], 0);
    }
    return \HTML::unite('ul', $out, $attr);
}

function icon($in, $attr = []) {
    $none = \HTML::unite('i', "", \extend(['class[]' => ['icon']], $attr));
    if (\is_string($in)) {
        // `icon("")`
        if ($in === "") {
            return $none;
        }
        return $in;
    } else if (isset($in['content'])) {
        if ($in['content'] === "") {
            return $none;
        }
        return $in['content'];
    }
    // `icon(['M3,6H21V8H3V6M3,11H21V13H3V11M3,16H21V18H3V16Z'])`
    if (\count($in) === 1) {
        // `icon([""])`
        if ($in[0] === "") {
            return $none;
        }
        $box = '0 0 24 24';
        $d = $in[0];
    // `icon(['0 0 24 24', 'M3,6H21V8H3V6M3,11H21V13H3V11M3,16H21V18H3V16Z'])`
    } else {
        $box = $in[0];
        $d = $in[1];
    }
    // Cache!
    $GLOBALS['SVG'][$d] = $box;
    $attr = \extend(['class[]' => ['icon']], $attr);
    if (\strpos($d, '<') !== 0) {
        $d = '<use href="#i:' . \dechex(\crc32($d . $box)) . '"></use>';
    }
    return \HTML::unite('svg', $d, $attr);
}

function links($in, $id = 0, $attr = [], $i = 0) {
    if (\is_string($in)) {
        return $in;
    } else if (isset($in['content'])) {
        $out = $in['content'];
    } else {
        global $language;
        $a = [];
        foreach (\Anemon::eat($in)->sort([1, 'stack'], true) as $k => $v) {
            if (!$v || _hidden($v)) continue;
            if (!isset($v['title'])) {
                $v['title'] = $language->{$k};
            }
            $a[] = '<li>' . a($v, $k, [], $i) . '</li>';
        }
        $out = \implode("", $a);
    }
    $in = _init($in, $attr, 'links', $id, $i);
    return \HTML::unite('ul', $out, $attr);
}

function nav($in, $id = 0, $attr = [], $i = 0) {
    if (\is_string($in)) {
        return $in;
    }
    /* $in = */ _init($in, $attr, 'nav', $id, $i);
    return \HTML::unite('nav', $in['content'] ?? nav_ul($in, $id, [], $i), $attr);
}

function nav_a($in, $id = 0, $attr = [], $i = 0) {
    if (\is_string($in)) {
        return $in;
    }
    global $config, $language;
    if (!empty($in['+'])) {
        $arrow = svg('arrow');
        $in['icon'] = \extend($in['icon'] ?? [], [
            1 => icon([$i > 0 ? $arrow[$config->direction === 'ltr' ? 'r' : 'l'] : $arrow['b']], [
                'class[]' => [
                    -1 => 'arrow',
                    -2 => 'right'
                ]
            ])
        ]);
    }
    unset($in['i']);
    $in = _init($in, $attr, 'a', $id, $i);
    return a($in, $id, $attr, $i);
}

function nav_li($in, $id = 0, $attr = [], $i = 0) {
    if (\is_string($in)) {
        return $in;
    } else if (isset($in['content'])) {
        return $in['content'];
    }
    $in = _init($in, $attr, 'li', $id, $i);
    if (!\array_key_exists('active', $in)) {
        global $panel, $url;
        $p = $in['url'] ?? \trim($panel->r . '/::' . ($in['c'] ?? $panel->c) . '::/' . ($in['path'] ?? $panel->id . '/' . $panel->path), '/');
        if (\strpos($url->path . '/1/', $p . '/') === 0) {
            $in['active'] = true;
        }
    }
    if (!empty($in['active'])) {
        $attr['class[]'][] = 'current';
        unset($in['active']);
    }
    $out = nav_a($in, $id, [], $i) . (isset($in['+']) ? nav_ul($in['+'], $id, [], $i + 1) : "");
    return \HTML::unite('li', $out, $attr);
}

function nav_li_search($in, $id = 0, $attr = [], $i = 0) {
    $in = _init($in, $attr, 'search', $id, $i);
    return \HTML::unite('li', search($in, $id, $attr, $i), $attr);
}

function nav_ul($in, $id = 0, $attr = [], $i = 0) {
    if (\is_string($in)) {
        return $in;
    } else if (isset($in['content'])) {
        return $in['content'];
    }
    $out = "";
    foreach (\Anemon::eat($in)->sort([1, 'stack'], true) as $k => $v) {
        if (!$v || _hidden($v)) continue;
        $out .= nav_li($v, $k, [], $i);
    }
    $in = _init($in, $attr, 'ul', $id, $i);
    return \HTML::unite('ul', $out, $attr);
}

function page($page, $id = 0, $attr = [], $i = 0, $tools = []) {
    global $panel, $url;
    $path = $page->path;
    _init([], $attr, 'page', $id, $i, [
        'class[]' => [
            -1 => 'is-file',
            -2 => 'x:' . $page->x
        ]
    ]);
    $out  = '<figure>';
    $out .= '<img alt="" src="' . ($page->image ? $page->image(72, 72) : $url . '/' . $panel->r . '/::g::/-/' . \substr(\md5($path), 0, 6) . '.png') . '" width="72" height="72">';
    $out .= '</figure>';
    $out .= '<header>';
    $out .= '<h3 class="title">';
    $out .= $page->url && $page->x !== 'draft' ? '<a href="' . $page->url . '" target="_blank">' . $page->title . '</a>' : '<span>' . $page->title . '</span>';
    $out .= '</h3>';
    $out .= '</header>';
    $out .= '<div>';
    $out .= '<p class="description">' . \To::snippet($page->description ?: "", true, $panel->state->page->snippet) . '</p>';
    $tools && ($out .= _tools($tools, $path, $id, $i));
    $out .= '</div>';
    return \HTML::unite('li', $out, $attr);
}

function pager($id = 0, $attr = [], $i = 0) {
    global $panel, $language, $url;
    $state = $panel->state->{$panel->view};
    $out = _pager($url->i ?: 1, \count(\Config::get('panel.+.explore', [], true)), $state->chunk, $state->kin, function($i) use($url) {
        return $url->clean . '/' . $i . $url->query('&amp;') . $url->hash;
    }, $language->first, $language->previous, $language->next, $language->last);
    if ($out) {
        _init([], $attr, 'pager', $id, $i);
        return \HTML::unite('p', $out, $attr);
    }
    return "";
}

function pages($pages, $id = 0, $attr = [], $i = 0) {
    _init([], $attr, 'pages', $id, $i);
    $out = "";
    $x = 'draft,page,archive';
    global $panel, $url;
    $state = $panel->state->page;
    $chunk = [$state->chunk, $url->i === null ? 0 : $url->i - 1];
    if (!\is_array($pages)) {
        $pages = \Get::pages($pages, $x, $state->sort, 'path')->vomit();
    }
    \Config::set('panel.+.explore', $pages = q($pages));
    $pages = \Anemon::eat($pages)->chunk(...$chunk);
    if ($pages->count()) {
        $tools = \Anemon::eat(\Config::get('panel.+.page.tool', [], true))->sort([1, 'stack']);
        $session = \strtr(X . \implode(X, (array) \Session::get('panel.file.active')) . X, '/', DS);
        foreach ($pages as $v) {
            $a = \strpos($session, X . $v . X) !== false;
            $v = new \Page($v);
            $out .= page($v, $v->id, [
                'class[]' => [
                    -3 => $v->state === 'draft' ? 'is-hidden' : null,
                    -4 => $a ? 'active' : null
                ]
            ], $i, $tools);
        }
    }
    return \HTML::unite('ul', $out, $attr);
}

function q($files, $query = "") {
    if (($query = \trim(\HTTP::get('q', $query, false))) !== "") {
        $query = \explode(' ', \strtolower($query));
        $files = \array_filter($files, function($v) use($query) {
            $v = \str_replace('-', "", \basename($v));
            foreach ($query as $q) {
                return \strpos($v, $q) !== false;
            }
            return false;
        });
    }
    return $files;
}

function search($in, $id = 0, $attr = [], $i = 0) {
    if (\is_string($in)) {
        return $in;
    } else if (isset($in['content'])) {
        $out = $in['content'];
    } else {
        global $language, $panel;
        $out  = \Form::text('q', \HTTP::get('q', null, false), \To::text($in['title']), ['class[]' => ['input']]);
        if ($view = \HTTP::get('view')) {
            $out .= \Form::hidden('view', $view);
        }
        $out .= ' ' . \Form::submit(null, null, $language->search, ['class[]' => ['button']]);
        $out  = '<p class="field expand"><span>' . $out . '</span></p>';
    }
    $in = _init($in, $attr, 'form', $id, $i, [
        'action' => href($in),
        'name' => 'search'
    ]);
    return \HTML::unite('form', $out, $attr);
}

function svg($key = null) {
    return \Config::get('panel.+.svg' . ($key ? '.' . $key : ""), null, true);
}

function tab($in, $id = 0, $attr = [], $i = 0, $active = false) {
    if (\is_string($in)) {
        return $in;
    } else if (isset($in['content'])) {
        $out = $in['content'];
    } else {
        global $language;
        $out = "";
        if (isset($in['field'])) {
            $out .= fields($in['field'], $id, [], $i);
        } else if (isset($in['explore'])) {
            global $panel;
            $fn = __NAMESPACE__ . "\\" . \HTTP::get('view', $panel->view) . 's';
            if (\is_callable($fn)) {
                $out .= \call_user_func($fn, $in['explore'], $id, [], $i);
            } else {
                $out .= \call_user_func(__NAMESPACE__ . "\\files", $in['explore'], $id, [], $i);
            }
        }
    }
    $in = _init($in, $attr, 'tab', $id, $i, [
        'data[]' => [
            'href' => href($in) ?: null,
            'icon' => $in['icon'][0] ?? $in['icon'] ?? null
        ]
    ]);
    $attr['title'] = $in['title'] ?? $language->{\str_replace('.', "\\.", $id)};
    $active && ($attr['class[]'][] = 'active');
    return \HTML::unite('section', $out, $attr);
}

function tabs($in, $id = 0, $attr = [], $i = 0, $active = null) {
    if (\is_string($in)) {
        return $in;
    } else if (isset($in['content'])) {
        $out = $in['content'];
    } else {
        $out = "";
        $in = \Anemon::eat($in)->sort([1, 'stack'], true)->vomit();
        if (!isset($active)) {
            // `?tab[0]=data`
            $active = \HTTP::get('tab.' . $i, \array_keys($in)[0] ?? null, false);
        }
        // `?tab[0]=data&tabs[0]=false`
        if (\HTTP::is('get', 'tabs.' . $i) && !\HTTP::get('tabs.' . $i)) {
            if (!isset($in[$active])) {
                \Config::set('panel.error', true);
            } else {
                $out .= tab($in[$active], $active, [], $i);
            }
        } else {
            foreach ($in as $k => $v) {
                if (!$v || _hidden($v)) continue;
                $out .= tab($v, $k, [], $i, $k === $active);
            }
        }
    }
    $in = _init($in, $attr, 'tabs', $id, $i);
    return \HTML::unite('div', $out, $attr);
}

function text($in, $icon = []) {
    if ($in === false && isset($icon[0])) {
        return icon($icon[0], ['class[]' => [1 => 'only']]);
    }
    $out = "";
    if (isset($icon[0])) {
        $out .= icon($icon[0], ['class[]' => [1 => 'left']]) . ' ';
    }
    $out .= '<span>' . $in . '</span>';
    if (isset($icon[1])) {
        $out .= ' ' . icon($icon[1], ['class[]' => [1 => 'right']]);
    }
    return $out;
}

function tools($in, $id = 0, $attr = [], $i = 0) {
    if (\is_string($in)) {
        return $in;
    } else if (isset($in['content'])) {
        $out = $in['content'];
    } else {
        global $language;
        $a = [];
        foreach (\Anemon::eat($in)->sort([1, 'stack'], true) as $k => $v) {
            if (!$v || _hidden($v)) continue;
            if (!isset($v['title'])) {
                $v['title'] = $language->{$k};
            }
            if (!empty($v['+']) && \array_filter($v['+'])) {
                $hash = \dechex(\crc32($id . $k . $i));
                \Config::set('panel.+.menu.' . $hash, $v['+']);
                $a[] = button($v, $k, ['id' => 'js:' . $hash], $i);
            } else {
                if (\array_key_exists('+', $v)) {
                    $v['x'] = true;
                }
                $a[] = button($v, $k, [], $i);
            }
        }
        $out = \implode(' ', $a);
    }
    $in = _init($in, $attr, 'tools', $id, $i);
    return \HTML::unite('div', $out, $attr);
}

function menus($in, $id = 0, $attr = [], $i = 0) {
    _init([], $attr, 'menus', $id, $i, [
        'hidden' => true
    ]);
    return nav_ul($in, $id, $attr, $i);
}