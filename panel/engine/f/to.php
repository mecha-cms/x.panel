<?php namespace _\lot\x\panel\to;

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

function description($value, $or = null) {
    $description = $value['description'] ?? $or;
    if (!isset($description) || false === $description) {
        return;
    }
    $out = [
        0 => 'p',
        1 => \w('<span>' . \i(...\array_values("" !== $description ? (array) $description : (array) $or)) . '</span>', ['a', 'abbr', 'b', 'code', 'del', 'em', 'i', 'ins', 'strong', 'sub', 'sup']),
        2 => ['class' => 'description']
    ];
    return new \HTML($out);
}

function field($value, $key) {
    $value['id'] = $value['id'] ?? 'f:' . \dechex(\crc32($key));
    $name = $value['name'] ?? $key;
    $is_active = !isset($value['active']) || $value['active'];
    $is_lock = !empty($value['lock']);
    $is_vital = !empty($value['vital']);
    $tags_status = [
        'has:pattern' => !empty($value['pattern']),
        'is:active' => $is_active,
        'is:lock' => $is_lock,
        'is:vital' => $is_vital,
        'not:active' => !$is_active,
        'not:lock' => !$is_lock,
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
            'name' => $name,
            'pattern' => $value['pattern'] ?? null,
            'placeholder' => \i(...((array) ($value['hint'] ?? []))),
            'readonly' => $is_lock,
            'required' => $is_vital
        ]
    ];
    \_\lot\x\panel\_set_class($content[2], \array_replace($tags_status, $value['tags'] ?? []));
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
    $icon = \array_replace([null, null], (array) $value);
    if ($icon[0] && false === \strpos($icon[0], '<')) {
        $GLOBALS['SVG'][$id = \dechex(\crc32($icon[0]))] = $icon[0];
        $icon[0] = '<svg height="12" width="12"><use href="#i:' . $id . '"></use></svg>';
    }
    if ($icon[1] && false === \strpos($icon[1], '<')) {
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
        $v = $fn ? \call_user_func($fn, $v, $k) : \_\lot\x\panel\type($v, $k);
        if ($v) {
            ++$count;
        }
        $out .= $v;
    }
    return $out;
}

function title($value, $i = -1, $or = null) {
    $title = $value['title'] ?? $or;
    if ((!isset($title) || false === $title) && (!isset($value['icon']) || empty($value['icon']))) {
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
    $icon = \_\lot\x\panel\to\icon($value['icon'] ?? [null, null]);
    if (null !== $title && false !== $title) {
        $title = \w('<span>' . \i(...\array_values((array) $title)) . '</span>', \array_filter(['a', 'abbr', 'b', 'code', 'del', 'em', 'i', 'ins', -2 === $i ? null : 'span', 'strong', 'sub', 'sup']));
    }
    $out[1] = $icon[0] . $title . $icon[1];
    unset($value['tags']);
    \_\lot\x\panel\_set_class($out[2], [
        'has:icon' => !!($icon[0] || $icon[1]),
        'has:title' => !!$title,
        'title' => 1
    ]);
    return new \HTML($out);
}

function w($value, $also = null) {
    return \w('<div>' . $value . '</div>', 'abbr,b,br,cite,code,del,dfn,em,i,img,ins,kbd,mark,q,span,strong,sub,sup,svg,time,u,var' . ($also ? ',' . $also : ""));
}
