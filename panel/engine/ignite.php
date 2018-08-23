<?php namespace panel;

function _attr($input, &$attr, $p, $id, $i, $alt = []) {
    $attr = array_replace_recursive($attr, [
        'class[]' => [$p, $p . ':' . $id, $p . ':' . $id . '.' . $i],
        'id' => $p . ':' . $id . '.' . $i
    ], $alt);
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
    global $url;
    $u = isset($input['path']) ? $url . '/' . \Extend::state('panel', 'path') . '/::' . (isset($input['>>']) ? $input['>>'] : 'g') . '::/' . ltrim($input['path'], '/') : "";
    if (isset($input['url'])) {
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
        $attr['class[]'][] = 'active';
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
        $attr[0]['class[]'][] = 'active';
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
    $s = \Form::text(isset($input['q']) ? $input['q'] : 'q', null, isset($input['title']) ? \To::text($input['title']) : null, ['class[]' => ['input']]);
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

function files($folder, $id = 0, $attr = [], $i = 0) {
    global $url;
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
    if ($files = \Anemon::eat($files)->chunk($state['chunk'], $url->i === null ? 0 : $url->i - 1)) {
        _attr(0, $attr, 'files', $id, $i, [
            'data[]' => ['folder' => ($dir = \Path::F($folder, LOT, '/'))]
        ]);
        if (trim(dirname($dir), '.') !== "") {
            array_unshift($files, dirname(LOT . DS . $dir) . DS . '..');
        }
        $s = "";
        foreach ($files as $k => $v) {
            $n = basename($v);
            $h = strpos($n, '.') === 0 || strpos($n, '_') === 0;
            $a = strpos(X . implode(X, (array) \Session::get('panel.file.active')) . X, X . $n . X) !== false;
            $s .= file($v, $k, [
                'class[]' => [
                    9997 => $h ? 'is-hidden' : null,
                    9998 => $a ? 'active' : null
                ]
            ], $i);
        }
        return \HTML::unite('ul', $s, $attr);
    }
    return "";
}

function file($path, $id = 0, $attr = [], $i = 0) {
    global $language, $url;
    $n = basename($path);
    $dir = \Path::F($path, LOT, '/');
    _attr(0, $attr, 'file', $id, $i, [
        'class[]' => [9999 => 'is-' . (($is_file = is_file($path)) ? 'file' : 'folder')]
    ]);
    $s  = '<h3 class="title">';
    $s .= '<a href="' . ($is_file ? \To::URL($path) : $url . '/' . \Extend::state('panel', 'path') . '/::g::/' . ($n !== '..' ? $dir : dirname($dir))) . '"' . ($is_file ? ' target="_blank"' : "") . ' title="' . ($is_file ? \File::size($path) : $language->enter . '&#x2026;') . '">' . $n . '</a>';
    $s .= '</h3>';
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
    foreach (\Anemon::eat($input)->sort([1, 'stack'], true)->vomit() as $k => $v) {
        $ss = "";
        if (isset($v['field'])) {
            $ss .= fields($v['field'], $id . '.tab', [], $i);
        } else if (isset($v['files'])) {
            $ss .= files(...$v['files']);
        }
        if (isset($v['content'])) {
            $ss = str_replace('%{1}%', $ss, $v['content']);
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

\Config::set('panel.field', [
    'type' => 'textarea',
    'width' => true,
    'height' => false
]);

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
    $input = _config($input, 'field', 'field:' . $id);
    global $language;
    _attr($input, $attr, 'field', $id, $i);
    $s = "";
    $kind = isset($input['kind']) ? (array) $input['kind'] : [];
    $style = [];
    $title = $language->{isset($input['key']) ? $input['key'] : $key};
    $type = isset($input['type']) ? $input['type'] : null;
    $value = isset($input['value']) ? $input['value'] : null;
    $placeholder = isset($input['placeholder']) ? $input['placeholder'] : null;
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
    $attr_alt['class[]'] = array_merge($attr_alt['class[]'], $kind);
    $s .= '<label for="f:' . $id . '.' . $i . '">' . $title . '</label>';
    $s .= '<span>';
    if ($type === 'hidden') {
        return \Form::hidden($key, $value);
    } else if ($type === 'text') {
        $attr_alt['class[]'][] = 'input';
        $s .= \Form::text($key, $value, $placeholder, $attr_alt);
    } else {
        $attr_alt['class[]'][] = 'textarea';
        $s .= \Form::textarea($key, $value, $placeholder, $attr_alt);
    }
    $s .= '</span>';
    $s = \HTML::unite('p', $s, $attr);
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
    if (isset($input['tool'])) {
        $s .= tools($input['tool'], $id, [], $i);
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
        $s .= files($input['files'], $id, [], $i);
    } else if (isset($input['field'])) {
        $s .= fields($input['field'], $id, [], $i);
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
    if (isset($input['tool'])) {
        $s .= tools($input['tool'], $id, [], $i);
    } else if (isset($input['pager'])) {
        $s .= pager($input['pager'], $id, [], $i);
    }
    $s = \HTML::unite('footer', $s, $attr);
    if (isset($input['content'])) {
        return __replace__($input['content'], array_replace($input, ['content' => $s]));
    }
    return $s;
}