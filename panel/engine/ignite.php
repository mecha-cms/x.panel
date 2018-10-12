<?php namespace fn\panel;

\Config::set('panel.$.svg', json_decode(file_get_contents(__DIR__ . DS . '..' . DS . 'lot' . DS . 'asset' . DS . 'json' . DS . 'svg.json'), true));

// kind: [a, b, c]
function _attr($input, &$attr, $p, $id, $i, $alt = []) {
    $attr = \extend([
        'class[]' => $id !== false ? [$p, $p . ':' . $id, $p . ':' . $id . '.' . $i] : null,
        'id' => $id !== false ? $p . ':' . $id . '.' . $i : null
    ], $attr, $alt);
    if (!empty($input['kind'])) {
        $attr['class[]'] = \concat($attr['class[]'], (array) $input['kind']);
    }
}

function _walk($input, $fn) {
    foreach ($input as $k => $v) {
        if (is_array($v)) {
            $o = _walk($v, $fn);
            if (!empty($o)) {
                $input[$k] = $o;
            } else {
                unset($input[$k]);
            }
        } else {
            if ($fn($v, $k)) {
                unset($input[$k]);
            }
        }
    }
    return $input;
}

function _clean($input) {
    return _walk($input, function($v) {
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
            $v = str_replace('/', DS, $v);
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
    sort($files);
    sort($folders);
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
function a($input, $id = 0, $attr = [], $i = 0) {
    if (is_string($input)) {
        return $input;
    }
    _attr($input, $attr, 'a', $id, $i, [
        'href' => a_href($input),
        'target' => isset($input['target']) ? $input['target'] : null,
        'title' => isset($input['description']) ? \To::text($input['description']) : null
    ]);
    $s = text(isset($input['title']) ? $input['title'] : "", isset($input['icon']) ? $input['icon'] : []);
    if (isset($input['content'])) {
        $s = \candy($input['content'], \extend($input, ['content' => $s]));
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
function a_href($input) {
    if (is_string($input)) {
        return $input;
    }
    // `[link[path[url]]]`
    $u = "";
    if (isset($input['link'])) {
        $u = $input['link'];
    } else if (isset($input['url'])) {
        $u = \URL::long($input['url']);
    } else if (isset($input['path'])) {
        $u = rtrim(\URL::long(\Extend::state('panel', 'path') . '/::' . (isset($input['c']) ? $input['c'] : 'g') . '::/' . ltrim($input['path'], '/')), '/');
    }
    if (isset($input['query'])) {
        $u .= \HTTP::query($input['query'], [1 => '&']);
    }
    if (isset($input['hash'])) {
        $u .= '#' . urlencode($input['hash']);
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
function button($input, $id = 0, $attr = [], $i = 0) {
    if (is_string($input)) {
        return $input;
    }
    $href = a_href($input);
    _attr($input, $attr, 'button', $id, $i);
    if (isset($input['description'])) {
        $attr['title'] = \To::text($input['description']);
    }
    if ($href === "") {
        if (isset($input['active']) && !$input['active']) {
            $attr['disabled'] = true;
        }
        if (isset($input['name'])) {
            $attr['name'] = $input['name'];
        }
        if (isset($input['value'])) {
            $attr['value'] = $input['value'];
        }
    } else {
        if (isset($input['active']) && !$input['active']) {
            $attr['class[]'][] = 'disabled';
        }
        $attr['href'] = $href;
    }
    $s = text(isset($input['title']) ? $input['title'] : "", isset($input['icon']) ? $input['icon'] : []);
    if (isset($input['content'])) {
        $s = \candy($input['content'], \extend($input, ['content' => $s]));
    }
    return \HTML::unite($href !== "" ? 'a' : 'button', $s, $attr);
}

// body: "" | *desk_body
// content: ""
// footer: "" | *desk_footer
// header: "" | *desk_header
// *_attr
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
    if (isset($input['content'])) {
        $s = \candy($input['content'], \extend($input, ['content' => $s]));
    }
    return \HTML::unite('div', $s, $attr);
}

// content: ""
// fields: "" | *fields
// files: "" | ?
// tabs: "" | *tabs
// *_attr
function desk_body($input, $id = 0, $attr = [], $i = 0) {
    if (is_string($input)) {
        return $input;
    }
    _attr($input, $attr, 'body', $id, $i);
    $s = "";
    if (isset($input['files'])) {
        if ($input['files'] === true) {
            global $url;
            $chops = explode('/', $url->path);
            array_shift($chops);
            array_shift($chops);
            $input['files'] = LOT . DS . implode(DS, $chops);
        }
        $s .= files($input['files'], $id, [], $i);
    } else if (isset($input['tabs'])) {
        $s .= tabs($input['tabs'], $id, [], $i);
    } else if (isset($input['fields'])) {
        $s .= fields($input['fields'], $id, [], $i);
    }
    if (isset($input['content'])) {
        $s = \candy($input['content'], \extend($input, ['content' => $s]));
    }
    return \HTML::unite('main', $s, $attr);
}

// content: ""
// pager: "" | ?
// tools: "" | *tools
// *_attr
function desk_footer($input, $id = 0, $attr = [], $i = 0) {
    if (is_string($input)) {
        return $input;
    }
    _attr($input, $attr, 'footer', $id, $i);
    $s = "";
    if (isset($input['tools'])) {
        $s .= tools($input['tools'], $id, [], $i);
    } else if (isset($input['pager'])) {
        if ($input['pager'] === true) {
            global $url;
            $chops = explode('/', $url->path);
            array_shift($chops);
            array_shift($chops);
            $input['pager'] = LOT . DS . implode(DS, $chops);
        }
        $s .= pager($input['pager'], $id, [], $i);
    }
    if (isset($input['content'])) {
        $s = \candy($input['content'], \extend($input, ['content' => $s]));
    }
    return \HTML::unite('footer', $s, $attr);
}

// content: ""
// tools: "" | *tools
// *_attr
function desk_header($input, $id = 0, $attr = [], $i = 0) {
    if (is_string($input)) {
        return $input;
    }
    _attr($input, $attr, 'header', $id, $i);
    $s = "";
    if (isset($input['tools'])) {
        $s .= tools($input['tools'], $id, [], $i);
    }
    if (isset($input['content'])) {
        $s = \candy($input['content'], \extend($input, ['content' => $s]));
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
function field($key, $input, $id = 0, $attr = [], $i = 0) {
    if (is_string($input)) {
        return $input;
    }
    if (!empty($input['hidden'])) {
        return "";
    }
    global $language;
    $s = "";
    $kind = isset($input['kind']) ? (array) $input['kind'] : [];
    $style = [];
    $title = isset($input['title']) ? $input['title'] : $language->{isset($input['key']) ? $input['key'] : $key};
    $description = isset($input['description']) ? trim($input['description']) : null;
    $type = isset($input['type']) ? $input['type'] : 'textarea';
    $value = isset($input['value']) ? $input['value'] : null;
    $values = isset($input['values']) ? (array) $input['values'] : [];
    $placeholder = isset($input['placeholder']) ? $input['placeholder'] : $value;
    $pattern = isset($input['pattern']) ? $input['pattern'] : null;
    $width = !empty($input['width']) ? $input['width'] : null;
    $height = !empty($input['height']) ? $input['height'] : null;
    $clone = isset($input['clone']) ? $input['clone'] : 0; // TODO
    asort($values);
    $copy = $input;
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
    if (isset($input['content'])) {
        $s = \candy($input['content'], \extend($input, ['content' => $s]));
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
function fields($input, $id = 0, $attr = [], $i = 0) {
    if (is_string($input)) {
        return $input;
    }
    $s = "";
    $ii = 0;
    $hidden = [];
    foreach (\Anemon::eat($input)->sort([1, 'stack'], true)->vomit() as $k => $v) {
        if (isset($v['type']) && $v['type'] === 'hidden') {
            $hidden[$k] = $v;
            continue;
        }
        $s .= field($k, $v, isset($v['key']) ? $v['key'] : $k, [], $ii);
        ++$ii;
    }
    foreach (\Anemon::eat($hidden)->sort([1, 'stack'], true)->vomit() as $k => $v) {
        $s .= field($k, $v, isset($v['key']) ? $v['key'] : $k, [], $ii);
        ++$ii;
    }
    return $s;
}

function files($folder, $id = 0, $attr = [], $i = 0) {
    global $language, $token, $url;
    $state = \Extend::state('panel', 'file');
    $files = $folders = [];
    _glob($folder, $files, $folders);
    $files = q(\concat($folders, $files));
    $dir = $s = "";
    _attr(0, $attr, 'files', $id, $i, is_string($folder) ? [
        'data[]' => ['folder' => ($dir = \Path::F($folder, LOT, '/'))]
    ] : []);
    $tools = _config([
        'g' => [
            'title' => false,
            'description' => $language->edit,
            'icon' => [['M5,3C3.89,3 3,3.89 3,5V19A2,2 0 0,0 5,21H19A2,2 0 0,0 21,19V12H19V19H5V5H12V3H5M17.78,4C17.61,4 17.43,4.07 17.3,4.2L16.08,5.41L18.58,7.91L19.8,6.7C20.06,6.44 20.06,6 19.8,5.75L18.25,4.2C18.12,4.07 17.95,4 17.78,4M15.37,6.12L8,13.5V16H10.5L17.87,8.62L15.37,6.12Z']],
            'c' => 'g',
            'stack' => 10
        ],
        'r' => [
            'title' => false,
            'description' => $language->delete,
            'icon' => [['M6,19A2,2 0 0,0 8,21H16A2,2 0 0,0 18,19V7H6V19M8,9H16V19H8V9M15.5,4L14.5,3H9.5L8.5,4H5V6H19V4H15.5Z']],
            'c' => 'r',
            'query' => [
                'a' => -2,
                'token' => $token,
            ],
            'stack' => 10.1
        ]
    ], '$.file.tools');
    $tools = \Anemon::eat($tools)->sort([1, 'stack'])->vomit();
    if ($files = \Anemon::eat(q($files))->chunk($state['chunk'], $url->i === null ? 0 : $url->i - 1)) {
        if (trim(dirname($dir), '.') !== "") {
            array_unshift($files, dirname(LOT . DS . $dir) . DS . '..');
        }
        foreach ($files as $k => $v) {
            $n = basename($v);
            $h = $n !== '..' && (strpos($n, '.') === 0 || strpos($n, '_') === 0);
            $a = strpos(str_replace('/', DS, X . implode(X, (array) \Session::get('panel.file.active')) . X), X . $v . X) !== false;
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
}

function icon($input, $attr = []) {
    $none = \HTML::unite('i', "", \extend(['class[]' => ['icon']], $attr));
    if (is_string($input)) {
        // `icon("")`
        if ($input === "") {
            return $none;
        }
        return $input;
    } else if (isset($input['content'])) {
        if ($input['content'] === "") {
            return $none;
        }
        return $input['content'];
    }
    // `icon(['M3,6H21V8H3V6M3,11H21V13H3V11M3,16H21V18H3V16Z'])`
    if (count($input) === 1) {
        // `icon([""])`
        if ($input[0] === "") {
            return $none;
        }
        $box = '0 0 24 24';
        $d = $input[0];
    // `icon(['0 0 24 24', 'M3,6H21V8H3V6M3,11H21V13H3V11M3,16H21V18H3V16Z'])`
    } else {
        $box = $input[0];
        $d = $input[1];
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
    $s = implode("", $a);
    if (isset($input['content'])) {
        $s = \candy($input['content'], \extend($input, ['content' => $s]));
    }
    return \HTML::unite('ul', $a, $attr);
}

function message($kind = "", $text) {
    $icons = svg('message');
    call_user_func('\Message::' . $kind, text($text, [[\Anemon::alter($kind, $icons, $icons['$'])]]));
}

// content: ""
// *nav_ul
function nav($input, $id = 0, $attr = [], $i = 0) {
    if (is_string($input)) {
        return $input;
    }
    _attr($input, $attr, 'nav', $id, $i);
    $s = nav_ul($input, $id, [], $i);
    if (isset($input['content'])) {
        $s = \candy($input['content'], \extend($input, ['content' => $s]));
    }
    return \HTML::unite('nav', $s, $attr);
}

// content: ""
// *a
function nav_a($input, $id = 0, $attr = [], $i = 0) {
    if (is_string($input)) {
        return $input;
    }
    global $config, $language;
    if (!isset($input['title'])) {
        $input['title'] = $language->{$id};
    }
    if (isset($input['+'])) {
        $arrow = svg('arrow');
        $input['icon'] = \extend(isset($input['icon']) ? $input['icon'] : [], [
            1 => '<svg class="icon arrow right" viewBox="0 0 24 24"><path d="' . ($i > 0 ? $arrow[$config->direction === 'ltr' ? 'r' : 'l'] : $arrow['b']) . '"></path></svg>'
        ]);
    }
    return a($input, $id, $attr, $i);
}

// +: *nav_ul
// active: ?
// content: ""
// *nav_a
function nav_li($input, $id = 0, $attr = [], $i = 0) {
    if (is_string($input)) {
        return $input;
    }
    _attr($input, $attr, 'li', $id, $i);
    if (!empty($input['active'])) {
        $attr['class[]'][] = 'current';
    }
    $s = nav_a($input, $id, [], $i) . (isset($input['+']) ? nav_ul($input['+'], $id, [], $i + 1) : "");
    if (isset($input['content'])) {
        $s = \candy($input['content'], \extend($input, ['content' => $s]));
    }
    return \HTML::unite('li', $s, $attr);
}

function nav_li_search($input, $id = 0, $attr = [], $i = 0) {
    _attr($input, $attr, 'search', $id, $i);
    return search($input, $id, $attr, $i);
}

// [...*nav_li]
function nav_ul($input, $id = 0, $attr = [], $i = 0) {
    if (is_string($input)) {
        return $input;
    }
    $s = "";
    foreach (\Anemon::eat($input)->sort([1, 'stack'], true)->vomit() as $k => $v) {
        $s .= nav_li($v, $k, [], $i);
    }
    _attr($input, $attr, 'ul', $id, $i);
    return \HTML::unite('ul', $s, $attr);
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
    if (isset($input['content'])) {
        $s = \candy($input['content'], \extend($input, ['content' => $s]));
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
function tab($input, $id = 0, $attr = [], $i = 0, $active = false) {
    if (is_string($input)) {
        return $input;
    }
    global $language;
    $s = "";
    if (isset($input['fields'])) {
        $s .= fields($input['fields'], $id, [], $i);
    } else if (isset($input['files'])) {
        if (!is_string($input['files'])) {
            global $url;
            $chops = explode('/', $url->path);
            array_shift($chops);
            array_shift($chops);
            $input['files'] = LOT . DS . implode(DS, $chops);
        }
        $s .= files($input['files'], $id, [], $i);
    }
    _attr($input, $attr, 'tab', $id, $i, [
        'title' => isset($input['title']) ? $input['title'] : $language->{$id},
        'data[]' => [
            'href' => a_href($input) ?: null,
            'icon' => isset($input['icon'][0]) ? $input['icon'][0] : null
        ]
    ]);
    if ($active) {
        $attr['class[]'][] = 'active';
    }
    if (isset($input['content'])) {
        $s = \candy($input['content'], \extend($input, ['content' => $s]));
    }
    return \HTML::unite('section', $s, $attr);
}

// [...*tab]
function tabs($input, $id = 0, $attr = [], $i = 0, $active = null) {
    if (is_string($input)) {
        return $input;
    }
    $s = "";
    if (!isset($active)) {
        // `?tab[0]=data`
        $active = \HTTP::get('tab.' . $i, null, false);
    }
    foreach (\Anemon::eat($input)->sort([1, 'stack'], true)->vomit() as $k => $v) {
        $s .= tab($v, $k, [], $i, $k === $active);
    }
    _attr($input, $attr, 'tabs', $id, $i);
    if (isset($input['content'])) {
        $s = \candy($input['content'], \extend($input, ['content' => $s]));
    }
    return \HTML::unite('div', $s, $attr);
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

// [...*button]
// content: ""
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
        if (isset($v['menus'])) {
            $hash = dechex(crc32($id . $k . $i));
            \Config::set('panel.$.menus.' . $hash, $v['menus']);
            $a[] = button($v, $k, ['id' => 'js:' . $hash], $i);
        } else {
            $a[] = button($v, $k, [], $i);
        }
    }
    _attr($input, $attr, 'tools', $id, $i);
    $s = implode(' ', $a);
    if (isset($input['content'])) {
        $s = \candy($input['content'], \extend($input, ['content' => $s]));
    }
    return \HTML::unite('div', $s, $attr);
}

function menus($input, $id = 0, $attr = [], $i = 0) {
    _attr(0, $attr, 'menus', $id, $i, [
        'hidden' => true
    ]);
    return nav_ul($input, $id, $attr, $i);
}