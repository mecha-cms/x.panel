<?php namespace panel;

function _attr($input, &$attr, $p, $id, $i, $alt = []) {
    $attr = array_replace_recursive([
        'class[]' => $id !== false ? [$p, $p . ':' . $id, $p . ':' . $id . '.' . $i] : null,
        'id' => $id !== false ? $p . ':' . $id . '.' . $i : null
    ], $attr, $alt);
    if (!empty($input['kind'])) {
        $attr['class[]'] = array_merge($attr['class[]'], (array) $input['kind']);
    }
}

function _config($defs = [], ...$any) {
    $out = [];
    while ($k = array_shift($any)) {
        if ($v = \Config::get('panel.' . $k, [], true)) {
            $out = array_replace_recursive($out, $v);
            break;
        }
    }
    return array_replace_recursive($defs, $out);
}

function text($input, $icon = []) {
    if ($input === false && isset($icon[0])) {
        return icon($icon[0], ['class[]' => [1 => 'only']]);
    }
    $s = "";
    if (isset($icon[0])) {
        $s .= icon($icon[0], ['class[]' => [1 => 'left']]) . ' ';
    }
    $s .= '<span>' . $input . '</span>';
    if (isset($icon[1])) {
        $s .= ' ' . icon($icon[1], ['class[]' => [1 => 'right']]);
    }
    return $s;
}

function icon($input, $attr = []) {
    $icon_none = \HTML::unite('i', "", array_replace_recursive(['class[]' => ['icon']], $attr));
    if (is_string($input)) {
        if ($input === "") {
            return $icon_none;
        }
        return $input;
    } else if (isset($input['content'])) {
        if ($input['content'] === "") {
            return $icon_none;
        }
        return $input['content'];
    }
    // `icon(['M3,6H21V8H3V6M3,11H21V13H3V11M3,16H21V18H3V16Z'])`
    if (count($input) === 1) {
        $box = '0 0 24 24';
        $d = $input[0];
    // `icon(['0 0 24 24', 'M3,6H21V8H3V6M3,11H21V13H3V11M3,16H21V18H3V16Z'])`
    } else {
        $box = $input[0];
        $d = $input[1];
    }
    $attr = array_replace_recursive([
        'class[]' => ['icon'],
        'viewBox' => $box
    ], $attr);
    return \HTML::unite('svg', strpos($d, '<') === 0 ? $d : '<path d="' . $d . '"></path>', $attr);
}

function a($input, $id = 0, $attr = [], $i = 0) {
    if (is_string($input)) {
        return $input;
    }
    _attr($input, $attr, 'a', $id, $i, [
        'href' => a_href($input),
        'title' => isset($input['description']) ? \To::text($input['description']) : null
    ]);
    $s = \HTML::unite('a', text(isset($input['title']) ? $input['title'] : "", isset($input['icon']) ? $input['icon'] : []), $attr);
    if (isset($input['content'])) {
        return __replace__($input['content'], array_replace($input, ['content' => $s]));
    }
    return $s;
}

function a_href($input) {
    if (is_string($input)) {
        return $input;
    }
    global $url;
    // [1]. `path`
    // [2]. `url`
    // [3]. `link`
    $u = "";
    if (isset($input['path'])) {
        $u = $url . '/' . \Extend::state('panel', 'path') . '/::' . (isset($input['>>']) ? $input['>>'] : 'g') . '::/' . ltrim($input['path'], '/');
    } else if (isset($input['url'])) {
        $u = \URL::long($input['url']);
    } else if (isset($input['link'])) {
        $u = $input['link'];
    }
    if (isset($input['query'])) {
        $u .= \HTTP::query($input['query']);
    }
    if (isset($input['hash'])) {
        $u .= '#' . urlencode($input['hash']);
    }
    return $u;
}

function button($input, $id = 0, $attr = [], $i = 0) {
    if (is_string($input)) {
        return $input;
    }
    $href = a_href($input);
    _attr($input, $attr, 'button', $id, $i);
    if ($href !== "") {
        if (!empty($input['x'])) {
            $attr['class[]'][] = 'disabled';
        }
        $attr['href'] = $href;
    } else if (!empty($input['x'])) {
        $attr['disabled'] = true;
    }
    $s = text(isset($input['title']) ? $input['title'] : "", isset($input['icon']) ? $input['icon'] : []);
    $s = \HTML::unite($href !== "" ? 'a' : 'button', $s, $attr);
    if (isset($input['content'])) {
        return __replace__($input['content'], array_replace($input, ['content' => $s]));
    }
    return $s;
}

function links($input, $id = 0, $attr = [], $i = 0) {
    if (is_string($input)) {
        return $input;
    }
    global $language;
    $a = [];
    foreach (\Anemon::eat($input)->sort([1, 'stack'], true)->vomit() as $k => $v) {
        if (!isset($v['title'])) {
            $v['title'] = $language->{$k};
        }
        $a[] = '<li>' . a($v, $k, [], $i) . '</li>';
    }
    _attr($input, $attr, 'links', $id, $i);
    $s = \HTML::unite('ul', implode("", $a), $attr);
    if (isset($input['content'])) {
        return __replace__($input['content'], array_replace($input, ['content' => $s]));
    }
    return $s;
}

function tools($input, $id = 0, $attr = [], $i = 0) {
    if (is_string($input)) {
        return $input;
    }
    global $language;
    $a = [];
    foreach (\Anemon::eat($input)->sort([1, 'stack'], true)->vomit() as $k => $v) {
        if (!isset($v['title'])) {
            $v['title'] = $language->{$k};
        }
        $a[] = button($v, $k, [], $i);
    }
    _attr($input, $attr, 'tools', $id, $i);
    $s = \HTML::unite('div', implode(' ', $a), $attr);
    if (isset($input['content'])) {
        return __replace__($input['content'], array_replace($input, ['content' => $s]));
    }
    return $s;
}

function nav($input, $id = 0, $attr = [], $i = 0) {
    if (is_string($input)) {
        return $input;
    }
    _attr($input, $attr, 'nav', $id, $i);
    $s = \HTML::unite('nav', nav_ul($input, $id, [], $i), $attr);
    if (isset($input['content'])) {
        return __replace__($input['content'], array_replace($input, ['content' => $s]));
    }
    return $s;
}

function nav_ul($input, $id = 0, $attr = [], $i = 0) {
    if (is_string($input)) {
        return $input;
    }
    $s = "";
    foreach (\Anemon::eat($input)->sort([1, 'stack'], true)->vomit() as $k => $v) {
        $s .= nav_li($v, $k, [], $i);
    }
    _attr($input, $attr, 'ul', $id, $i);
    $s = \HTML::unite('ul', $s, $attr);
    if (isset($input['content'])) {
        return __replace__($input['content'], array_replace($input, ['content' => $s]));
    }
    return $s;
}

function nav_li($input, $id = 0, $attr = [], $i = 0) {
    if (is_string($input)) {
        return $input;
    }
    _attr($input, $attr, 'li', $id, $i);
    if (!empty($input['active'])) {
        $attr['class[]'][] = 'current';
    }
    $s = \HTML::unite('li', nav_a($input, $id, [], $i) . (isset($input['+']) ? nav_ul($input['+'], $id, [], $i + 1) : ""), $attr);
    if (isset($input['content'])) {
        return __replace__($input['content'], array_replace($input, ['content' => $s]));
    }
    return $s;
}

function nav_li_search($input, $id = 0, $attr = [], $i = 0) {
    $attr = array_replace([[], []], $attr);
    _attr($input, $attr[0], 'li', $id, $i);
    if (!empty($attr[0]['active'])) {
        $attr[0]['class[]'][] = 'current';
    }
    return \HTML::unite('li', search($input, $id, $attr[1], $i), $attr[0]);
}

function nav_a($input, $id = 0, $attr = [], $i = 0) {
    if (is_string($input)) {
        return $input;
    }
    global $config, $language;
    if (!empty($input['target'])) {
        $attr['target'] = $input['target'];
    }
    if (!isset($input['title'])) {
        $input['title'] = $language->{$id};
    }
    if (isset($input['+'])) {
        $input['icon'] = array_replace_recursive(isset($input['icon']) ? $input['icon'] : [], [
            1 => '<svg class="icon arrow right" viewBox="0 0 24 24"><path d="' . ($i > 0 ? ($config->direction === 'ltr' ? 'M10,17L15,12L10,7V17Z' : 'M14,7L9,12L14,17V7Z') : 'M7,10L12,15L17,10H7Z') . '"></path></svg>'
        ]);
    }
    $s = a($input, $id, $attr, $i);
    if (isset($input['content'])) {
        return __replace__($input['content'], array_replace($input, ['content' => $s]));
    }
    return $s;
}

function search($input, $id = 0, $attr = [], $i = 0) {
    if (is_string($input)) {
        return $input;
    }
    global $language;
    $s = \Form::text(isset($input['q']) ? $input['q'] : 'q', \HTTP::get('q', null, false), isset($input['title']) ? \To::text($input['title']) : null, ['class[]' => ['input']]);
    $s .= ' ' . \Form::submit(null, null, $language->search, ['class[]' => ['button']]);
    $s = '<p class="field expand"><span>' . $s . '</span></p>';
    _attr($input, $attr, 'form', $id, $i, [
        'action' => a_href($input)
    ]);
    $s = \HTML::unite('form', $s, $attr);
    if (isset($input['content'])) {
        return __replace__($input['content'], array_replace($input, ['content' => $s]));
    }
    return $s;
}

function q($files, $query = "") {
    if (($query = trim(\HTTP::get('q', $query, false))) !== "") {
        $query = explode(' ', strtolower($query));
        $files = array_filter($files, function($v) use($query) {
            $v = basename($v);
            foreach ($query as $q) {
                return strpos($v, $q) !== false;
            }
            return false;
        });
    }
    return $files;
}

function files($folder, $id = 0, $attr = [], $i = 0) {
    global $language, $url;
    $files = $folders = [];
    $folder = rtrim($folder, DS);
    $state = \Extend::state('panel', 'file');
    foreach (array_unique(array_merge(
        glob($folder . DS . '*', GLOB_NOSORT),
        glob($folder . DS . '.*', GLOB_NOSORT)
    )) as $v) {
        $n = basename($v);
        if ($n === '.' || $n === '..') continue;
        if (is_file($v)) {
            $files[] = $v;
        } else {
            $folders[] = $v;
        }
    }
    sort($files);
    sort($folders);
    $GLOBALS['.' . crc32($folder)] = ($files = array_merge($folders, $files));
    if ($files = \Anemon::eat(q($files))->chunk($state['chunk'], $url->i === null ? 0 : $url->i - 1)) {
        _attr(0, $attr, 'files', $id, $i, [
            'data[]' => ['folder' => ($dir = \Path::F($folder, LOT, '/'))]
        ]);
        if (trim(dirname($dir), '.') !== "") {
            array_unshift($files, dirname(LOT . DS . $dir) . DS . '..');
        }
        $s = "";
        $tools = _config([
            'g' => [
                'title' => false,
                'description' => $language->edit,
                'icon' => [['M5,3C3.89,3 3,3.89 3,5V19A2,2 0 0,0 5,21H19A2,2 0 0,0 21,19V12H19V19H5V5H12V3H5M17.78,4C17.61,4 17.43,4.07 17.3,4.2L16.08,5.41L18.58,7.91L19.8,6.7C20.06,6.44 20.06,6 19.8,5.75L18.25,4.2C18.12,4.07 17.95,4 17.78,4M15.37,6.12L8,13.5V16H10.5L17.87,8.62L15.37,6.12Z']],
                '>>' => 'g',
                'stack' => 10
            ],
            'r' => [
                'title' => false,
                'description' => $language->delete,
                'icon' => [['M6,19A2,2 0 0,0 8,21H16A2,2 0 0,0 18,19V7H6V19M8,9H16V19H8V9M15.5,4L14.5,3H9.5L8.5,4H5V6H19V4H15.5Z']],
                '>>' => 'r',
                'stack' => 10.1
            ]
        ], 'file.tools', 'file:' . $id . '.tools');
        $tools = \Anemon::eat($tools)->sort([1, 'stack'])->vomit();
        foreach ($files as $k => $v) {
            $n = basename($v);
            $h = strpos($n, '.') === 0 || strpos($n, '_') === 0;
            $a = strpos(X . implode(X, (array) \Session::get('panel.file.active')) . X, X . $n . X) !== false;
            $s .= file($v, $id, [
                'class[]' => [
                    9997 => $h ? 'is-hidden' : null,
                    9998 => $a ? 'active' : null
                ]
            ], $i, $tools);
        }
        return \HTML::unite('ul', $s, $attr);
    }
    return "";
}

function file($path, $id = 0, $attr = [], $i = 0, $tools = []) {
    global $language, $url;
    $n = basename($path);
    $dir = \Path::F($path, LOT, '/');
    _attr(0, $attr, 'file', $id, $i, [
        'class[]' => [9999 => 'is-' . (($is_file = is_file($path)) ? 'file' : 'folder')]
    ]);
    $s  = '<h3 class="title">';
    $s .= '<a href="' . ($is_file ? \To::URL($path) : $url . '/' . \Extend::state('panel', 'path') . '/::g::/' . ($n !== '..' ? $dir : dirname($dir))) . '/1"' . ($is_file ? ' target="_blank"' : "") . ' title="' . ($is_file ? \File::size($path) : ($n === '..' ? $language->exit . ' ' . basename($path) : "")) . '">' . $n . '</a>';
    $s .= '</h3>';
    $vv = dirname($dir) . '/' . $n;
    if ($tools) {
        $s .= '<ul class="tools">';
        foreach ($tools as $k => $v) {
            if (!$v) continue;
            if (!isset($v['path'])) {
                $v['path'] = $vv;
            } else if (is_callable($v['path'])) {
                $v['path'] = call_user_func($v['path'], $k, $path, $id, $i);
            }
            $s .= '<li>' . a($v, false) . '</li>';
        }
        $s .= '</ul>';
    }
    return \HTML::unite('li', $s, $attr);
}

function _pager($files, $chunk, $range, $path, $first, $previous, $next, $last) {
    global $url;
    $s = "";
    $path .= '/';
    $current = (int) $url->i ?: 1;
    $count = count($files);
    $div = (int) floor($range / 2);
    $q = $url->query('&amp;'); // Include current URL query(es)…
    $chunk = (int) ceil($count / $chunk);
    $has_previous = $current > 1 ? $current - 1 : false;
    $has_next = $current < $chunk ? $current + 1 : false;
    if ($chunk > 1) {
        if (!empty($previous)) {
            $s .= '<span>';
            $s .= $has_previous ? '<a href="' . $path . $has_previous . $q . '" title="' . $previous . '" rel="prev">' . $previous . '</a>' : '<span>' . $previous . '</span>';
            $s .= '</span> ';
        }
        if (!empty($range)) {
            $s .= '<span>';
            // Enable range view if `$chunk` is greater than `$range`
            if ($chunk > $range) {
                // Jump!
                if ($current >= $range) {
                    $s .= '<a href="' . $path . '1' . $q . '" title="' . $first . '" rel="prev">1</a>';
                    $s .= ' <span>&#x2026;</span>';
                }
                // Closer to the first chunk
                if ($current < $range) {
                    for ($i = 1; $i <= $range; ++$i) {
                        if ($i > 1) {
                            $s .= ' ';
                        }
                        $s .= $i === $current ? '<b>' . $i . '</b>' : '<a href="' . $path . $i . $q . '" title="' . $i . '" rel="' . ($i < $current ? 'prev' : 'next') . '">' . $i . '</a>';
                    }
                // Closer to the last chunk
                } else if ($current >= ($chunk - $div - 1)) {
                    for ($i = $chunk - $range + 1; $i <= $chunk; ++$i) {
                        if ($i > 1) {
                            $s .= ' ';
                        }
                        $s .= $i === $current ? '<b>' . $i . '</b>' : '<a href="' . $path . $i . $q . '" title="' . $i . '" rel="' . ($i < $current ? 'prev' : 'next') . '">' . $i . '</a>';
                    }
                // Somewhere in the middle of the chunk
                } else if ($current >= $range && $current < ($chunk - $div)) {
                    for ($i = $current - $div; $i <= ($current + $div); ++$i) {
                        if ($i > 1) {
                            $s .= ' ';
                        }
                        $s .= $i === $current ? '<b>' . $i . '</b>' : '<a href="' . $path . $i . $q . '" title="' . $i . '" rel="' . ($i < $current ? 'prev' : 'next') . '">' . $i . '</a>';
                    }
                }
                // Jump!
                if ($current < ($chunk - $range + $div)) {
                    $s .= ' <span>&#x2026;</span>';
                    $s .= ' <a href="' . $path . $chunk . $q . '" title="' . $last . '" rel="next">' . $chunk . '</a>';
                }
            // Disable range view if `$chunk` is less than `$range`
            } else {
                for ($i = 1; $i <= $chunk; ++$i) {
                    if ($i > 1) {
                        $s .= ' ';
                    }
                    $s .= $i === $current ? '<b>' . $i . '</b>' : '<a href="' . $path . $i . $q . '" title="' . ($i === 1 ? $first : ($i === $chunk ? $last : $i)) . '" rel="' . ($i < $current ? 'prev' : 'next') . '">' . $i . '</a>';
                }
            }
            $s .= '</span> ';
        }
        if (!empty($next)) {
            $s .= '<span>';
            $s .= $has_next ? '<a href="' . $path . $has_next . $q . '" title="' . $next . '" rel="next">' . $next . '</a>' : '<span>' . $next . '</span>';
            $s .= '</span>';
        }
    }
    return $s;
}

function pager($folder, $id = 0, $attr = [], $i = 0) {
    global $language, $url;
    $folder = rtrim($folder, DS);
    $state = \Extend::state('panel', 'file');
    if ($files = isset($GLOBALS[$k = '.' . crc32($folder)]) ? $GLOBALS[$k] : false) {
        $files = [];
        foreach (array_unique(array_merge(
            glob($folder . DS . '*', GLOB_NOSORT),
            glob($folder . DS . '.*', GLOB_NOSORT)
        )) as $v) {
            $n = basename($v);
            if ($n === '.' || $n === '..') continue;
            $files[] = $v;
        }
        $files = q($files);
    }
    $s = _pager(
        $files,
        $state['chunk'],
        $state['range'],
        $url->clean,
        $language->first,
        $language->previous,
        $language->next,
        $language->last
    );
    if ($s) {
        _attr(0, $attr, 'pager', $id, $i);
        return \HTML::unite('p', $s, $attr);
    }
    return "";
}

function tabs($input, $active = null, $id = 0, $attr = [], $i = 0) {
    if (is_string($input)) {
        return $input;
    }
    global $language;
    $s = "";
    if (!isset($active)) {
        // `?tab=1` or `?tab:page=1`
        $active = \HTTP::get('tab:' . $id, \HTTP::get('tab', null, false), null, false);
    }
    foreach (\Anemon::eat($input)->sort([1, 'stack'], true)->vomit() as $k => $v) {
        $ss = "";
        if (isset($v['fields'])) {
            $ss .= fields($v['fields'], $id, [], $i);
        } else if (isset($v['files'])) {
            if (!is_string($v['files'])) {
                global $url;
                $chops = explode('/', $url->path);
                array_shift($chops);
                array_shift($chops);
                $v['files'] = LOT . DS . implode(DS, $chops);
            }
            $ss .= files($v['files'], $id, [], $i);
        }
        if (isset($v['content'])) {
            $ss = __replace__($v['content'], ['content' => $ss]);
        }
        if (!isset($v['title'])) {
            $v['title'] = $language->{$k};
        }
        $s .= '<section class="tab:' . $k . ($k === $active ? ' active' : "") . '" id="tab:' . $k . '" title="' . $v['title'] . '">' . $ss . '</section>';
    }
    _attr($input, $attr, 'tabs', $id, $i);
    $s = \HTML::unite('div', $s, $attr);
    if (isset($input['content'])) {
        return __replace__($input['content'], array_replace($input, ['content' => $s]));
    }
    return $s;
}

function fields($input, $id = 0, $attr = [], $i = 0) {
    if (is_string($input)) {
        return $input;
    }
    $s = "";
    $ii = 0;
    foreach (\Anemon::eat($input)->sort([1, 'stack'], true)->vomit() as $k => $v) {
        $s .= field($k, $v, $id, [], $ii);
        ++$ii;
    }
    return $s;
}

function field($key, $input, $id = 0, $attr = [], $i = 0) {
    if (is_string($input)) {
        return $input;
    }
    global $language;
    _attr($input, $attr, 'field', $id, $i);
    $s = "";
    $kind = isset($input['kind']) ? (array) $input['kind'] : [];
    $style = [];
    $title = $language->{isset($input['key']) ? $input['key'] : $key};
    $type = isset($input['type']) ? $input['type'] : null;
    $value = isset($input['value']) ? $input['value'] : null;
    $placeholder = isset($input['placeholder']) ? $input['placeholder'] : $value;
    $width = !empty($input['width']) ? $input['width'] : null;
    $height = !empty($input['height']) ? $input['height'] : null;
    if ($width === true) {
        $kind[] = 'width';
    } else if (is_numeric($width)) {
        $style['width'] = $width . 'px';
    }
    if ($height === true) {
        $kind[] = 'height';
    } else if (is_numeric($height)) {
        $style['height'] = $height . 'px';
    }
    $attr_alt = [];
    _attr(0, $attr_alt, 'f', $id, $i, [
        'style[]' => $style
    ]);
    $kind[] = $type;
    $attr_alt['class[]'] = array_merge($attr_alt['class[]'], $kind);
    $s .= '<label for="f:' . $id . '.' . $i . '">' . $title . '</label>';
    $tag = $type === 'textarea' ? 'div' : 'span';
    $s .= '<' . $tag . '>';
    if ($type === 'hidden') {
        return \Form::hidden($key, $value);
    } else if ($type === 'text') {
        $attr_alt['class[]'][] = 'input';
        $s .= \Form::text($key, $value, $placeholder, $attr_alt);
    } else if ($type === 'editor' || $type === 'source') {
        $attr_alt['class[]'][] = 'textarea';
        if ($type === 'source') {
            $attr_alt['class[]'][] = 'code';
        }
        $s .= \Form::textarea($key, $value, $placeholder, $attr_alt);
    } else {
        $attr_alt['class[]'][] = 'textarea';
        $s .= \Form::textarea($key, $value, $placeholder, $attr_alt);
    }
    $s .= '</' . $tag . '>';
    if ($type === 'textarea') {
        $attr['class[]'][] = 'p';
        $s = \HTML::unite('div', $s, $attr);
    } else {
        $s = \HTML::unite('p', $s, $attr);
    }
    if (isset($input['content'])) {
        return __replace__($input['content'], array_replace($input, ['content' => $s]));
    }
    return $s;
}

function desk($input, $id = 0, $attr = [], $i = 0) {
    if (is_string($input)) {
        return $input;
    }
    _attr($input, $attr, 'desk', $id, $i);
    $s = "";
    if (isset($input['header'])) {
        $s .= desk_header($input['header'], $id, [], $i);
    }
    if (isset($input['body'])) {
        $s .= desk_body($input['body'], $id, [], $i);
    }
    if (isset($input['footer'])) {
        $s .= desk_footer($input['footer'], $id, [], $i);
    }
    $s = \HTML::unite('div', $s, $attr);
    if (isset($input['content'])) {
        return __replace__($input['content'], array_replace($input, ['content' => $s]));
    }
    return $s;
}

function desk_header($input, $id = 0, $attr = [], $i = 0) {
    if (is_string($input)) {
        return $input;
    }
    _attr($input, $attr, 'header', $id, $i);
    $s = "";
    if (isset($input['tools'])) {
        $s .= tools($input['tools'], $id, [], $i);
    }
    $s = \HTML::unite('header', $s, $attr);
    if (isset($input['content'])) {
        return __replace__($input['content'], array_replace($input, ['content' => $s]));
    }
    return $s;
}

function desk_body($input, $id = 0, $attr = [], $i = 0) {
    if (is_string($input)) {
        return $input;
    }
    _attr($input, $attr, 'body', $id, $i);
    $s = "";
    if (isset($input['files'])) {
        if (!is_string($input['files'])) {
            global $url;
            $chops = explode('/', $url->path);
            array_shift($chops);
            array_shift($chops);
            $input['files'] = LOT . DS . implode(DS, $chops);
        }
        $s .= files($input['files'], $id, [], $i);
    } else if (isset($input['tabs'])) {
        $s .= tabs($input['tabs'], null, $id, [], $i);
    } else if (isset($input['fields'])) {
        $s .= fields($input['fields'], $id, [], $i);
    }
    $s = \HTML::unite('main', $s, $attr);
    if (isset($input['content'])) {
        return __replace__($input['content'], array_replace($input, ['content' => $s]));
    }
    return $s;
}

function desk_footer($input, $id = 0, $attr = [], $i = 0) {
    if (is_string($input)) {
        return $input;
    }
    _attr($input, $attr, 'footer', $id, $i);
    $s = "";
    if (isset($input['tools'])) {
        $s .= tools($input['tools'], $id, [], $i);
    } else if (isset($input['pager'])) {
        if (!is_string($input['pager'])) {
            global $url;
            $chops = explode('/', $url->path);
            array_shift($chops);
            array_shift($chops);
            $input['pager'] = LOT . DS . implode(DS, $chops);
        }
        $s .= pager($input['pager'], $id, [], $i);
    }
    $s = \HTML::unite('footer', $s, $attr);
    if (isset($input['content'])) {
        return __replace__($input['content'], array_replace($input, ['content' => $s]));
    }
    return $s;
}

function message($kind = "", $text) {
    call_user_func('\Message::' . $kind, text($text, [[\Anemon::alter($kind, [
        'info' => 'M13,9H11V7H13M13,17H11V11H13M12,2A10,10 0 0,0 2,12A10,10 0 0,0 12,22A10,10 0 0,0 22,12A10,10 0 0,0 12,2Z',
        'error' => 'M12,2C17.53,2 22,6.47 22,12C22,17.53 17.53,22 12,22C6.47,22 2,17.53 2,12C2,6.47 6.47,2 12,2M15.59,7L12,10.59L8.41,7L7,8.41L10.59,12L7,15.59L8.41,17L12,13.41L15.59,17L17,15.59L13.41,12L17,8.41L15.59,7Z',
        'success' => 'M12,2A10,10 0 0,1 22,12A10,10 0 0,1 12,22A10,10 0 0,1 2,12A10,10 0 0,1 12,2M11,16.5L18,9.5L16.59,8.09L11,13.67L7.91,10.59L6.5,12L11,16.5Z',
        'warn' => 'M13,13H11V7H13M13,17H11V15H13M12,2A10,10 0 0,0 2,12A10,10 0 0,0 12,22A10,10 0 0,0 22,12A10,10 0 0,0 12,2Z'
    ], 'M15.07,11.25L14.17,12.17C13.45,12.89 13,13.5 13,15H11V14.5C11,13.39 11.45,12.39 12.17,11.67L13.41,10.41C13.78,10.05 14,9.55 14,9C14,7.89 13.1,7 12,7A2,2 0 0,0 10,9H8A4,4 0 0,1 12,5A4,4 0 0,1 16,9C16,9.88 15.64,10.67 15.07,11.25M13,19H11V17H13M12,2A10,10 0 0,0 2,12A10,10 0 0,0 12,22A10,10 0 0,0 22,12C22,6.47 17.5,2 12,2Z')]]));
}