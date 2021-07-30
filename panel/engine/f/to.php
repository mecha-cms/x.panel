<?php namespace x\panel\to;

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

function content($value) {
    return \is_array($value) ? new \HTML($value) : (string) $value;
}

function description($value) {
    $out = (string) \x\panel\type\description(['content' => $value], 0);
    return "" !== $out ? $out : null;
}

function field($value, $key) {
    $value['id'] = $value['id'] ?? 'f:' . \dechex(\crc32($key));
    $name = $value['name'] ?? $key;
    $is_active = !isset($value['active']) || $value['active'];
    $is_locked = !empty($value['locked']);
    $is_vital = !empty($value['vital']);
    $tags_status = [
        'has:pattern' => !empty($value['pattern']),
        'is:active' => $is_active,
        'is:locked' => $is_locked,
        'is:vital' => $is_vital,
        'not:active' => !$is_active,
        'not:locked' => !$is_locked,
        'not:vital' => !$is_vital
    ];
    $state = $value['state'] ?? [];
    $content = [
        0 => 'textarea',
        1 => \htmlspecialchars($value['value'] ?? ""),
        2 => [
            'autofocus' => !empty($value['focus']),
            'data-state' => $state ? \json_encode($state) : null,
            'disabled' => !$is_active,
            'id' => $value['id'],
            'maxlength' => $value['max'] ?? null,
            'minlength' => $value['min'] ?? null,
            'name' => $name,
            'pattern' => $value['pattern'] ?? null,
            'placeholder' => \i(...((array) ($value['hint'] ?? []))),
            'readonly' => $is_locked,
            'required' => $is_vital
        ]
    ];
    \x\panel\_set_class($content[2], \array_replace($tags_status, $value['tags'] ?? []));
    $value['content'] = $content;
    return $value;
}

// Fix #13 <https://stackoverflow.com/a/53893947/1163000>
function fresh($value) {
    if (\function_exists("\\opcache_invalidate") && \strlen((string) \ini_get('opcache.restrict_api')) < 1) {
        \opcache_invalidate($value, true);
    } else if (\function_exists("\\apc_compile_file")) {
        \apc_compile_file($value);
    }
    return $value;
}

function icon($value) {
    return \x\panel\type\icon(['lot' => $value], 0);
}

function lot($lot, &$count = 0, $sort = true) {
    if (!\is_array($lot)) {
        return;
    }
    if ($sort) {
        if (true === $sort) {
            $sort = [1, 'stack', 10];
        }
        $lot = (new \Anemon($lot))->sort($sort, true);
    }
    $out = "";
    foreach ($lot as $k => $v) {
        if (null === $v || false === $v || !empty($v['skip'])) {
            continue;
        }
        $v = \x\panel\type($v, $k);
        if ($v) {
            ++$count;
        }
        $out .= $v;
    }
    return $out;
}

function title($value, $level = -1) {
    $out = (string) \x\panel\type\title([
        'content' => $value,
        'level' => $level
    ], 0);
    return "" !== $out ? $out : null;
}

function w($value, $also = null) {
    return \w('<!--0-->' . $value, 'abbr,b,br,cite,code,del,dfn,em,i,img,ins,kbd,mark,q,small,span,strong,sub,sup,svg,time,u,var' . ($also ? ',' . $also : ""));
}
