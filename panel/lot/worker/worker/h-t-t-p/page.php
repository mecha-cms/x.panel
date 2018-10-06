<?php

if (!$page = HTTP::post('page', [], false)) {
    return;
}

if ($c === 'g' && $a < 0) {
    // Delete folder
    $d = Path::F($file);
    if ($a === -2) {
        File::open($d)->moveTo(str_replace(LOT . DS, LOT . DS . 'trash' . DS . $_date . DS, dirname($d)));
    } else if ($a === -1) {
        File::open($d)->delete();
    }
}

$headers = [
    'title' => function($s) {
        return w($s, HTML_WISE_I) ?: false;
    },
    'description' => function($s) {
        return w($s, HTML_WISE_I . ',p') ?: false;
    },
    'type' => false,
    'link' => false,
    'author' => function($s) {
        return w($s) ?: false;
    },
    'content' => ""
];

$o = (array) Config::get($id, [], true);
if (count($o) === 1 && isset($o[0])) {
    $o = false; // numeric array, not a page configuration file
}

foreach ($headers as $k => $v) {
    if (!isset($page[$k])) continue;
    if (is_callable($v)) {
        $v = call_user_func($v, $page[$k]);
    } else {
        $v = $page[$k];
    }
    if ($o !== false && isset($o[$k]) && $o[$k] === $v) {
        $v = false;
    }
    $headers[$k] = $v;
    unset($page[$k]);
}

$headers = array_replace_recursive($headers, From::YAML(HTTP::post(':', ""), '  ', [], false), $page);
$headers = array_filter($headers, function($v) {
    return isset($v) && $v !== false && $v !== "" && !is_callable($v);
});
$time = date(DATE_WISE);
$name = To::slug(HTTP::post('slug', isset($headers['title']) ? $headers['title'] : "", false)) ?: $time;
if (!Message::$x) {
    if ($c === 'g') {
        $nn = Path::N($n);
        if (!Folder::exist($dd = LOT . DS . $path . DS . $nn)) {
            Folder::set($dd, 0775);
        }
        if ($nn !== $name) {
            File::open($dd)->renameTo($name); // rename folder
        }
    } else {
        if (!Folder::exist($dd = LOT . DS . $path . DS . $name)) {
            Folder::set($dd, 0775);
        }
    }
    $data = HTTP::post('data', [], false);
    if (!isset($data['time']) && $name !== $time) {
        $data['time'] = $time;
    }
    foreach ($data as $k => $v) {
        if (Is::void($v)) continue;
        File::set(is_array($v) ? json_encode($v) : $v)->saveTo($dd . DS . To::slug($k) . '.data', $consent);
    }
}

Set::post('name', $name);
Set::post('file.content', Page::unite($headers) ?: "---\n...");