<?php namespace panel;

function nav($input, $id = 0, $attr = [], $i = 0) {
    if (is_string($input)) {
        return $input;
    } else if (isset($input->content)) {
        return $input->content;
    }
    $attr = array_replace_recursive([
        'class[]' => ['nav', 'nav:' . $i, 'nav:' . $i . '.' . $id],
        'id' => 'nav:' . $i . '.' . $id
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
    $a = [];
    foreach ($input as $k => $v) {
        if (empty($v)) continue;
        if (is_string($v) && strpos($v, ':') === 0 && $c = \Config::get('panel.nav' . $v)) {
            $a[$k] = $c;
        } else {
            $a[$k] = o($v);
        }
    }
    foreach (\Anemon::eat(a($a))->sort([1, 'stack'], true)->vomit() as $k => $v) {
        $s .= nav_li(o($v), $k, [], $i);
    }
    $attr = array_replace_recursive([
        'class[]' => ['ul:' . $i, 'ul:' . $i . '.' . $id],
        'id' => 'ul:' . $i . '.' . $id
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
        'class[]' => ['li:' . $i, 'li:' . $i . '.' . $id, !empty($input->active) ? 'active' : null],
        'id' => 'li:' . $i . '.' . $id
    ], $attr);
    return \HTML::unite('li', nav_a($input, $id, [], $i) . (isset($input->{'+'}) ? nav_ul($input->{'+'}, $id, [], $i + 1) : ""), $attr);
}

function nav_li_search($input, $id = 0, $attr = [], $i = 0) {
    $attr = array_replace([[], []], $attr);
    return \HTML::unite('li', search($input, $id, $attr[1], $i), array_replace_recursive([
        'class[]' => ['li:' . $i . '.' . $id, !empty($input->active) ? 'active' : null],
        'id' => 'li:' . $i . '.' . $id
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
        'class[]' => ['a:' . $i, 'a:' . $i . '.' . $id],
        'href' => nav_a_href($input),
        'id' => 'a:' . $i . '.' . $id,
        'target' => isset($input->target) ? $input->target : null,
        'title' => isset($input->description) ? To::text($input->description) : null
    ], $attr);
    $title = isset($input->title) ? $input->title : $language->{$id};
    return \HTML::unite('a', text($title, array_replace_recursive(
        isset($input->icon) ? $input->icon : [],
        isset($input->{'+'}) ? [1 => '<svg class="icon arrow right" viewBox="0 0 24 24"><path d="' . ($i > 0 ? ($config->direction === 'ltr' ? 'M10,17L15,12L10,7V17Z' : 'M14,7L9,12L14,17V7Z') : 'M7,10L12,15L17,10H7Z') . '"></path></svg>'] : []
    )), $attr);
}

function nav_a_href($input) {
    global $url;
    $u = isset($input->path) ? $url . '/panel/::' . (isset($input->{'>>'}) ? $input->{'>>'} : 'g') . '::/' . ltrim($input->path, '/') : "";
    if (isset($input->url)) {
        $u = \URL::long($input->url);
    } else if (isset($input->link)) {
        $u = $input->link;
    }
    return $u;
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
        'class[]' => ['form', 'form:' . $i . '.' . $id],
        'id' => 'search:' . $i . '.' . $id,
        'action' => nav_a_href($input)
    ], $attr);
    return \HTML::unite('form', $s, $attr);
}

function files($folder, $chunk = [], $sort = 1, $actives = []) {
    $files = $folders = [];
    foreach (array_unique(array_merge(
        glob($folder . DS . '*', GLOB_NOSORT),
        glob($folder . DS . '.*', GLOB_NOSORT)
    )) as $v) {
        if (substr($v, -2) === DS . '.') continue;
        if (is_file($v)) {
            $files[] = $v;
        } else {
            $folders[] = $v;
        }
    }
    sort($files);
    sort($folders);
    $files = array_merge($folders, $files);
    $chunk = array_replace([20, 0], (array) $chunk);
    if ($files = \Anemon::eat($files)->chunk($chunk[0], $chunk[1])) {
        $s = '<ul class="files" data-folder="' . ($dir = \Path::F($folder, LOT, '/')) . '">';
        foreach ($files as $k => $v) {
            $n = basename($v);
            if ($n === '..' && trim(dirname($dir), '.') === "") continue;
            $h = strpos($n, '.') === 0 || strpos($n, '_') === 0 ? ' is-hidden' : "";
            $a = strpos(X . implode(X, $actives) . X, X . $n . X) !== false ? ' active' : "";
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
    $s .= '<a href="' . ($is_file ? \To::URL($path) : $url . '/panel/::g::/' . ($n !== '..' ? $dir . '/' . $n : dirname($dir))) . '"' . ($is_file ? ' target="_blank"' : "") . ' title="' . ($is_file ? \File::size($path) : $language->enter . '&hellip;') . '">' . $n . '</a>';
    $s .= '</h3>';
    return $s . '</li>';
}