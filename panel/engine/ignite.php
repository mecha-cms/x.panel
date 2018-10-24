<?php namespace fn\panel;

// kind: [a, b, c]
function _attr($in, &$attr, $p, $id, $i, $alt = []) {
    $attr = \extend([
        'class[]' => $id !== false ? [$p, $p . ':' . $id, $p . ':' . $id . '.' . $i] : null,
        'id' => $id !== false ? $p . ':' . $id . '.' . $i : null
    ], $attr, $alt);
    if (!empty($in['kind'])) {
        $attr['class[]'] = \concat($attr['class[]'], (array) $in['kind']);
    }
}

function _walk($in, $fn) {
    foreach ($in as $k => $v) {
        if (is_array($v)) {
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

function _clean($in) {
    return _walk($in, function($v) {
        return \Is::void($v);
    });
}

function _config($defs = [], ...$any) {
    $out = [];
    while ($k = array_shift($any)) {
        if (is_string($k) && $v = \Config::get('panel.' . $k, [], true)) {
            $out = \extend($out, $v);
            break;
        }
    }
    return \extend($defs, $out);
}

function _glob($folder, &$files, &$folders) {
    if (is_array($folder)) {
        foreach ($folder as $v) {
            $v = strtr($v, '/', DS);
            if (substr($v, -1) === DS || is_file($v)) {
                $folders[] = $v;
            } else {
                $files[] = $v;
            }
        }
    } else {
        $folder = rtrim($folder, DS);
        // <https://stackoverflow.com/a/33059445/1163000>
        foreach (glob($folder . DS . '{,.}[!.,!..]*', GLOB_NOSORT | GLOB_MARK | GLOB_BRACE) as $v) {
            $n = basename($v);
            if (substr($v, -1) === DS) {
                $folders[] = rtrim($v, DS);
            } else {
                $files[] = $v;
            }
        }
    }
    natsort($files);
    natsort($folders);
}

// <http://salman-w.blogspot.com/2014/04/stackoverflow-like-pagination.html>
function _pager($current, $count, $chunk, $kin, $fn, $first, $previous, $next, $last) {
    $begin = 1;
    $end = (int) ceil($count / $chunk);
    $s = "";
    if ($end <= 1) {
        return $s;
    }
    if ($current <= $kin + $kin) {
        $min = $begin;
        $max = min($begin + $kin + $kin, $end);
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
            $s .= '<a href="' . call_user_func($fn, $current - 1) . '" title="' . $previous . '" rel="prev">' . $previous . '</a>';
        }
        $s .= '</span> ';
    }
    if ($first && $last) {
        $s .= '<span>';
        if ($min > $begin) {
            $s .= '<a href="' . call_user_func($fn, $begin) . '" title="' . $first . '" rel="prev">' . $begin . '</a>';
            if ($min > $begin + 1) {
                $s .= ' <span>&#x2026;</span>';
            }
        }
        for ($i = $min; $i <= $max; ++$i) {
            if ($current === $i) {
                $s .= ' <b title="' . $i . '">' . $i . '</b>';
            } else {
                $s .= ' <a href="' . call_user_func($fn, $i) . '" title="' . $i . '" rel="' . ($current >= $i ? 'prev' : 'next') . '">' . $i . '</a>';
            }
        }
        if ($max < $end) {
            if ($max < $end - 1) {
                $s .= ' <span>&#x2026;</span>';
            }
            $s .= ' <a href="' . call_user_func($fn, $end) . '" title="' . $last . '" rel="next">' . $end . '</a>';
        }
        $s .= '</span>';
    }
    if ($next) {
        $s .= ' <span>';
        if ($current === $end) {
            $s .= '<b title="' . $next . '">' . $next . '</b>';
        } else {
            $s .= '<a href="' . call_user_func($fn, $current + 1) . '" title="' . $next . '" rel="next">' . $next . '</a>';
        }
        $s .= '</span>';
    }
    return $s;
}

// content: ""
// description: ""
// icon: [[]] | [""]
// target: ""
// title: ""
// *_attr
// *a_href
function a($in, $id = 0, $attr = [], $i = 0) {
    if (is_string($in)) {
        return $in;
    }
    _attr($in, $attr, 'a', $id, $i, [
        'href' => a_href($in),
        'target' => isset($in['target']) ? $in['target'] : null,
        'title' => isset($in['description']) ? \To::text($in['description']) : null
    ]);
    $s = text(isset($in['title']) ? $in['title'] : "", isset($in['icon']) ? $in['icon'] : []);
    if (isset($in['content'])) {
        $s = \candy($in['content'], \extend($in, ['content' => $s]));
    }
    return \HTML::unite('a', $s, $attr);
}

// c: g | r | s
// hash: ""
// link: ""
// path: ""
// query: ""
// stack: +
// url: ""
function a_href($in) {
    if (is_string($in)) {
        return $in;
    }
    // `[link[path[url]]]`
    $u = "";
    if (isset($in['link'])) {
        $u = $in['link'];
    } else if (isset($in['url'])) {
        $u = \URL::long($in['url']);
    } else if (isset($in['path'])) {
        $u = rtrim(\URL::long(\Extend::state('panel', 'path') . '/::' . (isset($in['c']) ? $in['c'] : 'g') . '::/' . ltrim($in['path'], '/')), '/');
    }
    if (isset($in['query'])) {
        $u .= \HTTP::query($in['query'], [1 => '&']);
    }
    if (isset($in['hash'])) {
        $u .= '#' . urlencode($in['hash']);
    }
    return $u;
}

// active: ?
// content: ""
// description: ""
// icon: [[]] | [""]
// title: ""
// stack: +
// *_attr
// *a_href
function button($in, $id = 0, $attr = [], $i = 0) {
    if (is_string($in)) {
        return $in;
    }
    $href = a_href($in);
    _attr($in, $attr, 'button', $id, $i);
    if (isset($in['description'])) {
        $attr['title'] = \To::text($in['description']);
    }
    if ($href === "") {
        if (isset($in['active']) && !$in['active']) {
            $attr['disabled'] = true;
        }
        if (isset($in['name'])) {
            $attr['name'] = $in['name'];
        }
        if (isset($in['value'])) {
            $attr['value'] = $in['value'];
        }
    } else {
        if (isset($in['active']) && !$in['active']) {
            $attr['class[]'][] = 'disabled';
        }
        $attr['href'] = $href;
    }
    $s = text(isset($in['title']) ? $in['title'] : "", isset($in['icon']) ? $in['icon'] : []);
    if (isset($in['content'])) {
        $s = \candy($in['content'], \extend($in, ['content' => $s]));
    }
    return \HTML::unite($href !== "" ? 'a' : 'button', $s, $attr);
}

function data($path, $id = 0, $attr = [], $i = 0, $tools = []) {
    return \HTML::unite('li', basename($path));
    /*
    global $url;
    $n = basename($path);
    $dir = \Path::F($path, LOT, '/');
    _attr(0, $attr, 'file', $id, $i, [
        'class[]' => [
            9998 => 'is-' . (($is_file = is_file($path)) ? 'file' : 'folder'),
            9999 => $is_file ? 'x:' . strtolower(pathinfo($path, PATHINFO_EXTENSION)) : null
        ]
    ]);
    $s  = '<h3 class="title">';
    $s .= '<a href="' . ($is_file ? \To::URL($path) : $url . '/' . \Extend::state('panel', 'path') . '/::g::/' . ($n !== '..' ? $dir : dirname($dir)) . '/1') . '"' . ($is_file ? ' target="_blank"' : "") . ' title="' . ($is_file ? \File::size($path) : ($n === '..' ? basename(dirname($url->path)) : "")) . '">' . $n . '</a>';
    $s .= '</h3>';
    if ($n !== '..' && $tools) {
        $vv = dirname($dir) . '/' . $n;
        $s .= '<ul class="tools">';
        foreach ($tools as $k => $v) {
            if (!$v) continue;
            if (!isset($v['path'])) {
                $v['path'] = $vv;
            } else if (is_callable($v['path'])) {
                $v['path'] = call_user_func($v['path'], $k, $path, $id, $i);
            } else if ($v['path'] === false) {
                unset($v['path']);
                $v['link'] = 'javascript:;';
            }
            $s .= '<li>' . a($v, false) . '</li>';
        }
        $s .= '</ul>';
    }
    return \HTML::unite('li', $s, $attr);
    */
}

function datas($datas, $id = 0, $attr = [], $i = 0) {
    $files = $folders = [];
    _glob($pages, $files, $folders);
    $datas = q(\is($files, function($v) use($x) {
        return pathinfo($v, PATHINFO_EXTENSION) === 'data';
    }));
    _attr(0, $attr, 'datas', $id, $i);
    $s = "";
    foreach ($datas as $k => $v) {
        $s .= data($v, $k, [], $i);
    }
    return \HTML::unite('ul', $s, $attr);
}

// body: "" | *desk_body
// content: ""
// footer: "" | *desk_footer
// header: "" | *desk_header
// *_attr
function desk($in, $id = 0, $attr = [], $i = 0) {
    if (is_string($in)) {
        return $in;
    }
    _attr($in, $attr, 'desk', $id, $i);
    $s = "";
    if (isset($in['header'])) {
        $s .= desk_header($in['header'], $id, [], $i);
    }
    if (isset($in['body'])) {
        $s .= desk_body($in['body'], $id, [], $i);
    }
    if (isset($in['footer'])) {
        $s .= desk_footer($in['footer'], $id, [], $i);
    }
    if (isset($in['content'])) {
        $s = \candy($in['content'], \extend($in, ['content' => $s]));
    }
    return \HTML::unite('div', $s, $attr);
}

// content: ""
// fields: "" | *fields
// files: "" | ?
// tabs: "" | *tabs
// *_attr
function desk_body($in, $id = 0, $attr = [], $i = 0) {
    if (is_string($in)) {
        return $in;
    }
    _attr($in, $attr, 'body', $id, $i);
    $s = "";
    if (isset($in['files'])) {
        $panel = \Lot::get('panel');
        if (!is_string($in['files'])) {
            global $url;
            $chops = explode('/', $url->path);
            array_shift($chops);
            array_shift($chops);
            $in['files'] = LOT . DS . implode(DS, $chops);
        }
        $s .= call_user_func(__NAMESPACE__ . '\\' . \HTTP::get('view', $panel->view) . 's', $in['files'], $id, [], $i);
    } else if (isset($in['tabs'])) {
        $s .= tabs($in['tabs'], $id, [], $i);
    } else if (isset($in['fields'])) {
        $s .= fields($in['fields'], $id, [], $i);
    }
    if (isset($in['content'])) {
        $s = \candy($in['content'], \extend($in, ['content' => $s]));
    }
    return \HTML::unite('main', $s, $attr);
}

// content: ""
// pager: "" | ?
// tools: "" | *tools
// *_attr
function desk_footer($in, $id = 0, $attr = [], $i = 0) {
    if (is_string($in)) {
        return $in;
    }
    _attr($in, $attr, 'footer', $id, $i);
    $s = "";
    if (isset($in['tools'])) {
        $s .= tools($in['tools'], $id, [], $i);
    } else if (isset($in['pager'])) {
        if ($in['pager'] === true) {
            global $url;
            $chops = explode('/', $url->path);
            array_shift($chops);
            array_shift($chops);
            $in['pager'] = LOT . DS . implode(DS, $chops);
        }
        $s .= pager($in['pager'], $id, [], $i);
    }
    if (isset($in['content'])) {
        $s = \candy($in['content'], \extend($in, ['content' => $s]));
    }
    return \HTML::unite('footer', $s, $attr);
}

// content: ""
// tools: "" | *tools
// *_attr
function desk_header($in, $id = 0, $attr = [], $i = 0) {
    if (is_string($in)) {
        return $in;
    }
    _attr($in, $attr, 'header', $id, $i);
    $s = "";
    if (isset($in['tools'])) {
        $s .= tools($in['tools'], $id, [], $i);
    }
    if (isset($in['content'])) {
        $s = \candy($in['content'], \extend($in, ['content' => $s]));
    }
    return \HTML::unite('header', $s, $attr);
}

// active: ""
// actives: ""
// clone: ?
// content: ""
// description: ""
// height: "" | + | ?
// hidden: ?
// key: ""
// pattern: ""
// placeholder: ""
// stack: +
// title: ""
// type: button | button[] | color | editor | file | hidden | radio | range | select | select[] | source | text | textarea | toggle | toggle[]
// value: ""
// values: ""
// width: "" | + | ?
// *_attr
function field($key, $in, $id = 0, $attr = [], $i = 0) {
    if (is_string($in)) {
        return $in;
    }
    if (!empty($in['hidden'])) {
        return "";
    }
    global $language;
    $s = "";
    $kind = isset($in['kind']) ? (array) $in['kind'] : [];
    $style = [];
    $title = isset($in['title']) ? $in['title'] : $language->{isset($in['key']) ? $in['key'] : $key};
    $description = isset($in['description']) ? trim($in['description']) : null;
    $type = isset($in['type']) ? $in['type'] : 'textarea';
    $value = isset($in['value']) ? $in['value'] : null;
    $values = isset($in['values']) ? (array) $in['values'] : [];
    $placeholder = isset($in['placeholder']) ? $in['placeholder'] : $value;
    $pattern = isset($in['pattern']) ? $in['pattern'] : null;
    $width = !empty($in['width']) ? $in['width'] : null;
    $height = !empty($in['height']) ? $in['height'] : null;
    $clone = isset($in['clone']) ? $in['clone'] : 0; // TODO
    asort($values);
    $copy = $in;
    $copy['kind'] = ['type:' . $type];
    _attr($copy, $attr, 'field', $id, $i);
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
    $attr_alt = [
        'class[]' => $kind,
        'pattern' => $pattern
    ];
    _attr(0, $attr_alt, 'f', $id, $i, [
        'style[]' => $style
    ]);
    $s .= '<label for="f:' . $id . '.' . $i . '">' . $title . '</label>';
    $textarea = strpos(',content,editor,source,textarea,', ',' . $type . ',') !== false;
    $tag = $textarea ? 'div' : 'span';
    $s .= '<' . $tag . '>';
    if ($type === 'hidden') {
        return \Form::hidden($key, $value);
    } else if ($type === 'blob') {
        $attr_alt['class[]'][] = 'input';
        $s .= \Form::blob($key, $attr_alt);
    } else if ($type === 'select') {
        $attr_alt['class[]'][] = 'select';
        $s .= \Form::select($key, $values, $value, $attr_alt);
    } else if ($type === 'toggle[]') {
        $attr_alt['class[]'][] = 'input';
        $s .= '<span class="inputs block">';
        foreach ($values as $k => $v) {
            // $v = [$text, $checked ?? false, $value ?? 1]
            $v = (array) $v;
            $s .= \Form::check($key . '[' . $k . ']', isset($v[2]) ? $v[2] : 1, !empty($v[1]), $v[0], $attr_alt);
        }
        $s .= '</span>';
    } else if (strpos(',editor,source,textarea,', ',' . $type . ',') !== false) {
        $attr_alt['class[]'][] = 'textarea';
        if ($type === 'source') {
            $attr_alt['class[]'][] = 'code';
        }
        $s .= \Form::textarea($key, $value, $placeholder, $attr_alt);
    } else if (strpos(',color,date,email,number,pass,search,tel,text,url,', ',' . $type . ',') !== false) {
        $attr_alt['class[]'][] = 'input';
        $s .= call_user_func('\Form::' . $type, $key, $value, $placeholder, $attr_alt);
    } else /* if ($type === 'content') */ {
        $s .= $value;
    }
    if ($description) {
        if ($tag === 'div') {
            $s .= '<div class="hints">' . $description . '</div>';
        } else {
            $s .= ' <span class="hints">' . $description . '</span>';
        }
    }
    $s .= '</' . $tag . '>';
    if (isset($in['content'])) {
        $s = \candy($in['content'], \extend($in, ['content' => $s]));
    }
    if ($textarea) {
        $attr['class[]'][] = 'p';
        $s = \HTML::unite('div', $s, $attr);
    } else {
        $s = \HTML::unite('p', $s, $attr);
    }
    return $s;
}

// [...*field]
function fields($in, $id = 0, $attr = [], $i = 0) {
    if (is_string($in)) {
        return $in;
    }
    $s = "";
    $ii = 0;
    $hidden = [];
    foreach (\Anemon::eat($in)->sort([1, 'stack'], true) as $k => $v) {
        if (!$v) continue;
        if (isset($v['type']) && $v['type'] === 'hidden') {
            $hidden[$k] = $v;
            continue;
        }
        $s .= field($k, $v, isset($v['key']) ? $v['key'] : $k, [], $ii);
        ++$ii;
    }
    foreach (\Anemon::eat($hidden)->sort([1, 'stack'], true) as $k => $v) {
        $s .= field($k, $v, isset($v['key']) ? $v['key'] : $k, [], $ii);
        ++$ii;
    }
    return $s;
}

function file($path, $id = 0, $attr = [], $i = 0, $tools = []) {
    global $url;
    $n = basename($path);
    $dir = \Path::F($path, LOT, '/');
    _attr(0, $attr, 'file', $id, $i, [
        'class[]' => [
            9998 => 'is-' . (($is_file = is_file($path)) ? 'file' : 'folder'),
            9999 => $is_file ? 'x:' . strtolower(pathinfo($path, PATHINFO_EXTENSION)) : null
        ]
    ]);
    $s  = '<h3 class="title">';
    $s .= '<a href="' . ($is_file ? \To::URL($path) : $url . '/' . \Extend::state('panel', 'path') . '/::g::/' . ($n !== '..' ? $dir : dirname($dir)) . '/1') . '"' . ($is_file ? ' target="_blank"' : "") . ' title="' . ($is_file ? \File::size($path) : ($n === '..' ? basename(dirname($url->path)) : "")) . '">' . $n . '</a>';
    $s .= '</h3>';
    if ($n !== '..' && $tools) {
        $s .= '<ul class="tools">';
        foreach ($tools as $k => $v) {
            if (!$v) continue;
            if (!isset($v['path'])) {
                $v['path'] = dirname($dir) . '/' . $n;
            } else if (is_callable($v['path'])) {
                $v['path'] = call_user_func($v['path'], $k, $path, $id, $i);
            } else if ($v['path'] === false) {
                unset($v['path']);
                $v['link'] = 'javascript:;';
            }
            $s .= '<li>' . a($v, false) . '</li>';
        }
        $s .= '</ul>';
    }
    return \HTML::unite('li', $s, $attr);
}

function files($folder, $id = 0, $attr = [], $i = 0) {
    global $token, $url;
    $state = \Extend::state('panel', 'file');
    $files = $folders = [];
    _glob($folder, $files, $folders);
    $files = q(\concat($folders, $files));
    $dir = $s = "";
    _attr(0, $attr, 'files', $id, $i, is_string($folder) ? [
        'data[]' => ['folder' => ($dir = \Path::F($folder, LOT, '/'))]
    ] : []);
    $tools = \Anemon::eat(\Config::get('panel.$.file.tools', [], true))->sort([1, 'stack']);
    $files = $files = \Anemon::eat(q($files))->chunk($state['chunk'], $url->i === null ? 0 : $url->i - 1);
    if ($files->count()) {
        if (trim(dirname($dir), '.') !== "") {
            $files->prepend(dirname(LOT . DS . $dir) . DS . '..');
        }
        foreach ($files as $k => $v) {
            $n = basename($v);
            $h = $n !== '..' && (strpos($n, '.') === 0 || strpos($n, '_') === 0);
            $a = strpos(strtr(X . implode(X, (array) \Session::get('panel.file.active')) . X, '/', DS), X . $v . X) !== false;
            $s .= file($v, $id, [
                'class[]' => [
                    9996 => $h ? 'is-hidden' : null,
                    9997 => $a ? 'active' : null
                ]
            ], $i, $tools);
        }
    } else if (is_string($folder) && dirname($folder) !== LOT) {
        $s = file(dirname($folder) . DS . '..', $id, [], 0, $tools);
    }
    return \HTML::unite('ul', $s, $attr);
}

function icon($in, $attr = []) {
    $none = \HTML::unite('i', "", \extend(['class[]' => ['icon']], $attr));
    if (is_string($in)) {
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
    if (count($in) === 1) {
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
    $attr = \extend([
        'class[]' => ['icon'],
        'viewBox' => strpos($d, '#') !== 0 ? $box : null
    ], $attr);
    if (strpos($d, '#') === 0) {
        $d = '<use href="' . $d . '"></use>';
    } else if (strpos($d, '<') !== 0) {
        $d = '<path d="' . $d . '"></path>';
    }
    return \HTML::unite('svg', $d, $attr);
}

// [...*a]
// content: ""
function links($in, $id = 0, $attr = [], $i = 0) {
    if (is_string($in)) {
        return $in;
    }
    global $language;
    $a = [];
    foreach (\Anemon::eat($in)->sort([1, 'stack'], true) as $k => $v) {
        if (!$v) continue;
        if (!isset($v['title'])) {
            $v['title'] = $language->{$k};
        }
        $a[] = '<li>' . a($v, $k, [], $i) . '</li>';
    }
    _attr($in, $attr, 'links', $id, $i);
    $s = implode("", $a);
    if (isset($in['content'])) {
        $s = \candy($in['content'], \extend($in, ['content' => $s]));
    }
    return \HTML::unite('ul', $a, $attr);
}

function message($kind = "", $text) {
    $icons = svg('message');
    call_user_func('\Message::' . $kind, text($text, [[\alt($kind, $icons, $icons['$'])]]));
}

// content: ""
// *nav_ul
function nav($in, $id = 0, $attr = [], $i = 0) {
    if (is_string($in)) {
        return $in;
    }
    _attr($in, $attr, 'nav', $id, $i);
    $s = nav_ul($in, $id, [], $i);
    if (isset($in['content'])) {
        $s = \candy($in['content'], \extend($in, ['content' => $s]));
    }
    return \HTML::unite('nav', $s, $attr);
}

// content: ""
// *a
function nav_a($in, $id = 0, $attr = [], $i = 0) {
    if (is_string($in)) {
        return $in;
    }
    global $config, $language;
    if (!isset($in['title'])) {
        $in['title'] = $language->{$id};
    }
    if (isset($in['+'])) {
        $arrow = svg('arrow');
        $in['icon'] = \extend(isset($in['icon']) ? $in['icon'] : [], [
            1 => '<svg class="icon arrow right" viewBox="0 0 24 24"><path d="' . ($i > 0 ? $arrow[$config->direction === 'ltr' ? 'r' : 'l'] : $arrow['b']) . '"></path></svg>'
        ]);
    }
    return a($in, $id, $attr, $i);
}

// +: *nav_ul
// active: ?
// content: ""
// *nav_a
function nav_li($in, $id = 0, $attr = [], $i = 0) {
    if (is_string($in)) {
        return $in;
    }
    _attr($in, $attr, 'li', $id, $i);
    if (!empty($in['active'])) {
        $attr['class[]'][] = 'current';
    }
    $s = nav_a($in, $id, [], $i) . (isset($in['+']) ? nav_ul($in['+'], $id, [], $i + 1) : "");
    if (isset($in['content'])) {
        $s = \candy($in['content'], \extend($in, ['content' => $s]));
    }
    return \HTML::unite('li', $s, $attr);
}

function nav_li_search($in, $id = 0, $attr = [], $i = 0) {
    _attr($in, $attr, 'search', $id, $i);
    return search($in, $id, $attr, $i);
}

// [...*nav_li]
function nav_ul($in, $id = 0, $attr = [], $i = 0) {
    if (is_string($in)) {
        return $in;
    }
    $s = "";
    foreach (\Anemon::eat($in)->sort([1, 'stack'], true) as $k => $v) {
        if (!$v) continue;
        $s .= nav_li($v, $k, [], $i);
    }
    _attr($in, $attr, 'ul', $id, $i);
    return \HTML::unite('ul', $s, $attr);
}



function page($page, $id = 0, $attr = [], $i = 0, $tools = []) {
    $path = $page->path;
    _attr(0, $attr, 'item', $id, $i, [
        'class[]' => [
            9998 => 'is-file',
            9999 => 'state:' . $page->state
        ]
    ]);
    $s  = '<figure>';
    $s .= $page->has('image') ? $page->image(72, 72) : '<span class="img" style="background:#' . substr(md5($path), 0, 6) . ';">' . strip_tags($page->title)[0] . '</span>';
    $s .= '</figure>';
    $s .= '<header>';
    $s .= '<h3 class="title">';
    $s .= '<a href="' . $page->url . '">' . $page->title . '</a>';
    $s .= '</h3>';
    $s .= '</header>';
    $s .= '<div>';
    $s .= '<p class="description">' . \To::description($page->description ?: "") . '</p>';
    if ($tools) {
        $s .= '<ul class="tools">';
        foreach ($tools as $k => $v) {
            if (!$v) continue;
            if (!isset($v['path'])) {
                $v['path'] = \Path::F($path, LOT, '/') . '.' . $page->state;
            } else if (is_callable($v['path'])) {
                $v['path'] = call_user_func($v['path'], $k, $path, $id, $i);
            } else if ($v['path'] === false) {
                unset($v['path']);
                $v['link'] = 'javascript:;';
            }
            $s .= '<li>' . a($v, false) . '</li>';
        }
        $s .= '</ul>';
    }
    $s .= '</div>';
    return \HTML::unite('li', $s, $attr);
}

function pager($folder, $id = 0, $attr = [], $i = 0) {
    global $language, $url;
    $state = \Extend::state('panel', 'file');
    $files = $folders = [];
    _glob($folder, $files, $folders);
    $files = q(\concat($folders, $files));
    $s = _pager($url->i ?: 1, count($files), $state['chunk'], $state['kin'], function($i) use($url) {
        return $url->clean . '/' . $i . $url->query('&amp;') . $url->hash;
    }, $language->first, $language->previous, $language->next, $language->last);
    if ($s) {
        _attr(0, $attr, 'pager', $id, $i);
        return \HTML::unite('p', $s, $attr);
    }
    return "";
}

function pages($pages, $id = 0, $attr = [], $i = 0) {
    $files = $folders = [];
    _glob($pages, $files, $folders);
    $x = ',draft,page,archive,';
    $pages = q(\is($files, function($v) use($x) {
        return strpos($x, ',' . pathinfo($v, PATHINFO_EXTENSION) . ',') !== false;
    }));
    _attr(0, $attr, 'items', $id, $i);
    $s = "";
    $tools = \Anemon::eat(\Config::get('panel.$.page.tools', [], true))->sort([1, 'stack']);
    foreach ($pages as $k => $v) {
        $v = new \Page($v);
        $s .= page($v, $k, [], $i, $tools);
    }
    return \HTML::unite('ul', $s, $attr);
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

function search($in, $id = 0, $attr = [], $i = 0) {
    if (is_string($in)) {
        return $in;
    }
    global $language;
    $s = \Form::text(isset($in['q']) ? $in['q'] : 'q', \HTTP::get('q', null, false), isset($in['title']) ? \To::text($in['title']) : null, ['class[]' => ['input']]);
    $s .= ' ' . \Form::submit(null, null, $language->search, ['class[]' => ['button']]);
    $s = '<p class="field expand"><span>' . $s . '</span></p>';
    _attr($in, $attr, 'form', $id, $i, [
        'action' => a_href($in),
        'name' => 'search'
    ]);
    if (isset($in['content'])) {
        $s = \candy($in['content'], \extend($in, ['content' => $s]));
    }
    return \HTML::unite('form', $s, $attr);
}

function svg($key = null) {
    return \Config::get('panel.$.svg' . ($key ? '.' . $key : ""), null, true);
}

// content: ""
// fields: *fields
// files: *files
// stack: +
// title: ""
function tab($in, $id = 0, $attr = [], $i = 0, $active = false) {
    if (is_string($in)) {
        return $in;
    }
    global $language;
    $s = "";
    if (isset($in['fields'])) {
        $s .= fields($in['fields'], $id, [], $i);
    } else if (isset($in['files'])) {
        $panel = \Lot::get('panel');
        if (!is_string($in['files'])) {
            global $url;
            $chops = explode('/', $url->path);
            array_shift($chops);
            array_shift($chops);
            $in['files'] = LOT . DS . implode(DS, $chops);
        }
        $s .= call_user_func(__NAMESPACE__ . '\\' . \HTTP::get('view', $panel->view) . 's', $in['files'], $id, [], $i);
    }
    _attr($in, $attr, 'tab', $id, $i, [
        'title' => isset($in['title']) ? $in['title'] : $language->{$id},
        'data[]' => [
            'href' => a_href($in) ?: null,
            'icon' => isset($in['icon'][0]) ? $in['icon'][0] : null
        ]
    ]);
    if ($active) {
        $attr['class[]'][] = 'active';
    }
    if (isset($in['content'])) {
        $s = \candy($in['content'], \extend($in, ['content' => $s]));
    }
    return \HTML::unite('section', $s, $attr);
}

// [...*tab]
function tabs($in, $id = 0, $attr = [], $i = 0, $active = null) {
    if (is_string($in)) {
        return $in;
    }
    $s = "";
    if (!isset($active)) {
        // `?tab[0]=data`
        $active = \HTTP::get('tab.' . $i, null, false);
    }
    foreach (\Anemon::eat($in)->sort([1, 'stack'], true) as $k => $v) {
        if (!$v) continue;
        $s .= tab($v, $k, [], $i, $k === $active);
    }
    _attr($in, $attr, 'tabs', $id, $i);
    if (isset($in['content'])) {
        $s = \candy($in['content'], \extend($in, ['content' => $s]));
    }
    return \HTML::unite('div', $s, $attr);
}

function text($in, $icon = []) {
    if ($in === false && isset($icon[0])) {
        return icon($icon[0], ['class[]' => [1 => 'only']]);
    }
    $s = "";
    if (isset($icon[0])) {
        $s .= icon($icon[0], ['class[]' => [1 => 'left']]) . ' ';
    }
    $s .= '<span>' . $in . '</span>';
    if (isset($icon[1])) {
        $s .= ' ' . icon($icon[1], ['class[]' => [1 => 'right']]);
    }
    return $s;
}

// [...*button]
// content: ""
function tools($in, $id = 0, $attr = [], $i = 0) {
    if (is_string($in)) {
        return $in;
    }
    global $language;
    $a = [];
    foreach (\Anemon::eat($in)->sort([1, 'stack'], true) as $k => $v) {
        if (!$v) continue;
        if (!isset($v['title'])) {
            $v['title'] = $language->{$k};
        }
        if (isset($v['menus'])) {
            $hash = dechex(crc32($id . $k . $i));
            \Config::set('panel.$.menus.' . $hash, $v['menus']);
            $a[] = button($v, $k, ['id' => 'js:' . $hash], $i);
        } else {
            $a[] = button($v, $k, [], $i);
        }
    }
    _attr($in, $attr, 'tools', $id, $i);
    $s = implode(' ', $a);
    if (isset($in['content'])) {
        $s = \candy($in['content'], \extend($in, ['content' => $s]));
    }
    return \HTML::unite('div', $s, $attr);
}

function menus($in, $id = 0, $attr = [], $i = 0) {
    _attr(0, $attr, 'menus', $id, $i, [
        'hidden' => true
    ]);
    return nav_ul($in, $id, $attr, $i);
}