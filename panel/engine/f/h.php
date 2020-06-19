<?php namespace _\lot\x\panel\h;

function c(&$out, $in, $tags = []) {
    $a = \explode(' ', $out['class'] ?? "");
    $b = (array) ($in['tags'] ?? []);
    $c = \array_unique(\array_filter(\array_merge($a, $b, $tags)));
    \sort($c);
    $out['class'] = $c ? \implode(' ', $c) : null;
}

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

function content($content) {
    return \is_array($content) ? new \HTML($content) : (string) $content;
}

function description($in, $or = null) {
    $description = $in['description'] ?? $or;
    if (!isset($description) || false === $description) {
        return;
    }
    $out = [
        0 => 'p',
        1 => \w('<span>' . \i(...\array_values("" !== $description ? (array) $description : (array) $or)) . '</span>', ['a', 'abbr', 'b', 'code', 'del', 'em', 'i', 'ins', 'span', 'strong', 'sub', 'sup']),
        2 => []
    ];
    unset($in['tags']);
    \_\lot\x\panel\h\c($out[2], $in, ['description']);
    return new \HTML($out);
}

function field($in, $key) {
    $in['id'] = $in['id'] ?? 'f:' . \dechex(\crc32($key));
    $name = $in['name'] ?? $key;
    if ($disabled = isset($in['active']) && !$in['active']) {
        $in['tags'][] = 'not:active';
    // `else if` because mixing both `disabled` and `readonly` attribute does not make sense
    } else if ($readonly = !empty($in['frozen'])) {
        $in['tags'][] = 'is:frozen';
    }
    // TODO: Need a better key name
    if ($required = !empty($in['required'])) {
        $in['tags'][] = 'is:required';
    }
    $input = [
        0 => 'textarea',
        1 => \htmlspecialchars($in['value'] ?? ""),
        2 => [
            'autofocus' => !empty($in['focus']),
            'class' => "",
            'disabled' => $disabled ?? null,
            'id' => $in['id'],
            'name' => $name,
            'pattern' => $in['pattern'] ?? null,
            'placeholder' => \i(...((array) ($in['alt'] ?? []))),
            'readonly' => $readonly ?? null,
            'required' => $required ?? null
        ]
    ];
    $in['content'] = $input;
    return $in;
}

function icon($in) {
    $icon = \array_replace([null, null], (array) $in);
    if ($icon[0] && false === strpos($icon[0], '<')) {
        $GLOBALS['SVG'][$id = \dechex(\crc32($icon[0]))] = $icon[0];
        $icon[0] = '<svg height="12" width="12"><use href="#i:' . $id . '"></use></svg>';
    }
    if ($icon[1] && false === strpos($icon[1], '<')) {
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
        if (null === $v || false === $v || !empty($v['hidden'])) {
            continue;
        }
        $v = $fn ? \call_user_func($fn, $v, $k) : \_\lot\x\panel($v, $k);
        if ($v) {
            ++$count;
        }
        $out .= $v;
    }
    return $out;
}

function p(&$lot, $prefix) {
    foreach ($lot as &$v) {
        $type = $v['type'] ?? null;
        if ($type !== $prefix && 0 !== \strpos($type, $prefix . '/')) {
            // Add prefix to `type`
            $type = $prefix . '/' . $type;
        }
        $v['type'] = \trim($type, '/');
    }
    unset($v);
}

function path($in) {
    return \strtr($in, [
        '/' => \DS,
        \ROOT => '.'
    ]);
}

function title($in, $i = -1, $or = null) {
    $title = $in['title'] ?? $or;
    if ((!isset($title) || false === $title) && (!isset($in['icon']) || empty($in['icon']))) {
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
    $icon = \_\lot\x\panel\h\icon($in['icon'] ?? [null, null]);
    if (null !== $title && false !== $title) {
        $title = \w('<span>' . \i(...\array_values((array) $title)) . '</span>', ['a', 'abbr', 'b', 'code', 'del', 'em', 'i', 'ins', 'span', 'strong', 'sub', 'sup']);
    }
    $out[1] = $icon[0] . $title . $icon[1];
    unset($in['tags']);
    \_\lot\x\panel\h\c($out[2], $in, [
        'title',
        $title ? 'has:title' : null,
        $icon[0] || $icon[1] ? 'has:icon' : null
    ]);
    return new \HTML($out);
}

function w($in, $also = null) {
    return \w('<div>' . $in . '</div>', 'abbr,b,br,cite,code,del,dfn,em,i,img,ins,kbd,mark,q,span,strong,sub,sup,svg,time,u,var' . ($also ? ',' . $also : ""));
}
