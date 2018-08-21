<?php namespace panel;

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
    } else if (isset($input->content)) {
        if ($input->content === "") {
            return $icon_none;
        }
        return $input->content;
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
    } else if (isset($input->content)) {
        return $input->content;
    }
    $attr = array_replace_recursive([
        'class[]' => ['a:' . $id, 'a:' . $id . '.' . $i],
        'href' => a_href($input),
        'id' => 'a:' . $id . '.' . $i,
        'title' => isset($input->description) ? \To::text($input->description) : null
    ], $attr);
    return \HTML::unite('a', text(isset($input->title) ? $input->title : "", isset($input->icon) ? $input->icon : []), $attr);
}

function a_href($input) {
    global $url;
    $u = isset($input->path) ? $url . '/' . \Extend::state('panel', 'path') . '/::' . (isset($input->{'>>'}) ? $input->{'>>'} : 'g') . '::/' . ltrim($input->path, '/') : "";
    if (isset($input->url)) {
        $u = \URL::long($input->url);
    } else if (isset($input->link)) {
        $u = $input->link;
    }
    if (isset($input->query)) {
        $u .= \HTTP::query(\a($input->query));
    }
    if (isset($input->hash)) {
        $u .= '#' . urlencode($input->hash);
    }
    return $u;
}

function button($input, $id = 0, $attr = [], $i = 0) {
    if (is_string($input)) {
        return $input;
    } else if (isset($input->content)) {
        return $input->content;
    }
    $href = a_href($input);
    $attr = array_replace_recursive([
        'class[]' => ['button:' . $id, 'button:' . $id . '.' . $i],
        'id' => 'button:' . $id . '.' . $i
    ], $attr);
    $attr['class[]'][] = 'button';
    if (!empty($input->x)) {
        if ($href !== "") {
            $attr['class[]'][] = 'disabled';
        } else {
            $attr['disabled'] = true;
        }
    }
    if ($href !== "") {
        return a($input, $id, $attr, $i);
    }
    return \HTML::unite('button', text(isset($input->title) ? $input->title : "", isset($input->icon) ? $input->icon : []), $attr);
}

function tools($input, $id = 0, $attr = [], $i = 0) {
    if (is_string($input)) {
        return $input;
    } else if (isset($input->content)) {
        return $input->content;
    }
    global $language;
    $a = [];
    foreach ($input as $k => $v) {
        if (!isset($v->title)) {
            $v->title = $language->{$k};
        }
        $vv = (array) $v;
        $a[] = button($v, $k, isset($vv[2]) ? \a($vv[2]) : [], $i);
    }
    $attr = array_replace_recursive([
        'class[]' => ['tools:' . $id, 'tools:' . $id . '.' . $i],
        'id' => 'tools:' . $id . '.' . $i
    ], $attr);
    $attr['class[]'][] = 'tools';
    return \HTML::unite('div', implode(' ', $a), $attr);
}

function nav($input, $id = 0, $attr = [], $i = 0) {
    if (is_string($input)) {
        return $input;
    } else if (isset($input->content)) {
        return $input->content;
    }
    $attr = array_replace_recursive([
        'class[]' => ['nav', 'nav:' . $id, 'nav:' . $id . '.' . $i],
        'id' => 'nav:' . $id . '.' . $i
    ], $attr);
    return \HTML::unite('nav', nav_ul($input, $id, [], $i), $attr);
}

function nav_ul($input, $id = 0, $attr = [], $i = 0) {
    if (is_string($input)) {
        return $input;
    } else if (isset($input->content)) {
        return $input->content;
    }
    $s = "";
    foreach (\Anemon::eat(\a($input))->sort([1, 'stack'], true)->vomit() as $k => $v) {
        $vv = (array) $v;
        $s .= nav_li(\o($v), $k, isset($vv[2]) ? \a($vv[2]) : [], $i);
    }
    $attr = array_replace_recursive([
        'class[]' => ['ul:' . $id, 'ul:' . $id . '.' . $i],
        'id' => 'ul:' . $id . '.' . $i
    ], $attr);
    return \HTML::unite('ul', $s, $attr);
}

function nav_li($input, $id = 0, $attr = [], $i = 0) {
    if (is_string($input)) {
        return $input;
    } else if (isset($input->content)) {
        return $input->content;
    }
    $attr = array_replace_recursive([
        'class[]' => ['li:' . $id, 'li:' . $id . '.' . $i, !empty($input->active) ? 'active' : null],
        'id' => 'li:' . $id . '.' . $i
    ], $attr);
    return \HTML::unite('li', nav_a($input, $id, [], $i) . (isset($input->{'+'}) ? nav_ul($input->{'+'}, $id, [], $i + 1) : ""), $attr);
}

function nav_li_search($input, $id = 0, $attr = [], $i = 0) {
    $attr = array_replace([[], []], $attr);
    return \HTML::unite('li', search($input, $id, $attr[1], $i), array_replace_recursive([
        'class[]' => ['li:' . $id . '.' . $i, !empty($input->active) ? 'active' : null],
        'id' => 'li:' . $id . '.' . $i
    ], $attr[0]));
}

function nav_a($input, $id = 0, $attr = [], $i = 0) {
    if (is_string($input)) {
        return $input;
    } else if (isset($input->content)) {
        return $input->content;
    }
    global $config, $language, $url;
    $attr = array_replace_recursive([
        'target' => isset($input->target) ? $input->target : null
    ], $attr);
    if (!isset($input->title)) {
        $input->title = $language->{$id};
    }
    if (isset($input->{'+'})) {
        $input->icon = array_replace_recursive(isset($input->icon) ? $input->icon : [], [
            1 => '<svg class="icon arrow right" viewBox="0 0 24 24"><path d="' . ($i > 0 ? ($config->direction === 'ltr' ? 'M10,17L15,12L10,7V17Z' : 'M14,7L9,12L14,17V7Z') : 'M7,10L12,15L17,10H7Z') . '"></path></svg>'
        ]);
    }
    return a($input, $id, $attr, $i);
}

function search($input, $id = 0, $attr = [], $i = 0) {
    if (is_string($input)) {
        return $input;
    } else if (isset($input->content)) {
        return $input->content;
    }
    global $config, $language;
    $s = \Form::text(isset($input->q) ? $input->q : 'q', null, isset($input->title) ? \To::text($input->title) : null, ['class[]' => ['input']]);
    $s .= ' ' . \Form::submit(null, null, $language->search, ['class[]' => ['button']]);
    $s = '<p class="field expand"><span>' . $s . '</span></p>';
    $attr = array_replace_recursive([
        'class[]' => ['form', 'form:' . $id . '.' . $i],
        'id' => 'search:' . $id . '.' . $i,
        'action' => a_href($input)
    ], $attr);
    return \HTML::unite('form', $s, $attr);
}

function files($folder, $step = 1) {
    $files = $folders = [];
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
    if ($files = \Anemon::eat($files)->chunk($state['chunk'], $step === null ? 0 : $step - 1)) {
        $s = '<ul class="files" data-folder="' . ($dir = \Path::F($folder, LOT, '/')) . '">';
        if (trim(dirname($dir), '.') !== "") {
            array_unshift($files, dirname(LOT . DS . $dir) . DS . '..');
        }
        foreach ($files as $k => $v) {
            $n = basename($v);
            $h = strpos($n, '.') === 0 || strpos($n, '_') === 0 ? ' is-hidden' : "";
            $a = strpos(X . implode(X, (array) \Session::get('panel.files.active')) . X, X . $n . X) !== false ? ' active' : "";
            $s .= file($v, $k, $h . $a);
        }
        return $s . '</ul>';
    }
    return "";
}

function file($path, $key, $class) {
    global $language, $url;
    $n = basename($path);
    $dir = \Path::F($path, LOT, '/');
    $s  = '<li class="file is-' . (($is_file = is_file($path)) ? 'file' : 'folder') . $class . '">';
    $s .= '<h3 class="title">';
    $s .= '<a href="' . ($is_file ? \To::URL($path) : $url . '/panel/::g::/' . ($n !== '..' ? $dir : dirname($dir))) . '"' . ($is_file ? ' target="_blank"' : "") . ' title="' . ($is_file ? \File::size($path) : $language->enter . '&#x2026;') . '">' . $n . '</a>';
    $s .= '</h3>';
    return $s . '</li>';
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

function pager($folder) {
    global $language, $url;
    $state = \Extend::state('panel', 'file');
    if ($files = isset($GLOBALS[$id = '.' . crc32($folder)]) ? $GLOBALS[$id] : false) {
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
    return $s ? '<p class="pager">' . $s . '</p>' : "";
}