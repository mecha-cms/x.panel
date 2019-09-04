<?php namespace _\lot\x\panel\h;

function c($in, array $class = []) {
    $a = \implode(' ', $class);
    $b = \implode(' ', (array) ($in['tags'] ?? []));
    $c = \array_unique(\array_filter(\array_merge(\explode(' ', $a), \explode(' ', $b))));
    \sort($c);
    $c = \implode(' ', $c);
    return $c !== "" ? $c : null;
}

function content($content) {
    return \is_array($content) ? new \HTML($content) : (string) $content;
}

function description($in, $or = null) {
    $description = $in['description'] ?? $or;
    if (!isset($description)) {
        return;
    }
    $out = [
        0 => 'p',
        1 => $description,
        2 => []
    ];
    $out[2]['class'] = \_\lot\x\panel\h\c($in, ['description']);
    return new \HTML($out);
}

function field($in, $key) {
    $in['id'] = $in['id'] ?? 'f:' . \dechex(\crc32($key));
    $name = $in['name'] ?? $key;
    $input = [
        0 => 'textarea',
        1 => \htmlspecialchars($in['value'] ?? ""),
        2 => [
            'class' => "",
            'disabled' => isset($in['active']) && !$in['active'],
            'id' => $in['id'],
            'name' => $name,
            'pattern' => $in['pattern'] ?? null,
            'placeholder' => $in['placeholder'] ?? null,
            'readonly' => !empty($in['read-only']),
            'required' => !empty($in['required'])
        ]
    ];
    $in['content'] = $input;
    return $in;
}

function icon($in) {
    $icon = \array_replace([null, null], (array) $in);
    if ($icon[0] && strpos($icon[0], '<') === false) {
        $GLOBALS['SVG'][$id = \dechex(\crc32($icon[0]))] = $icon[0];
        $icon[0] = '<svg><use href="#i:' . $id . '"></use></svg>';
    }
    if ($icon[1] && strpos($icon[1], '<') === false) {
        $GLOBALS['SVG'][$id = \dechex(\crc32($icon[1]))] = $icon[1];
        $icon[1] = '<svg><use href="#i:' . $id . '"></use></svg>';
    }
    return $icon;
}

function lot($lot, $fn = null) {
    if (!\is_array($lot)) {
        return;
    }
    $out = "";
    foreach ((new \Anemon($lot))->sort([1, 'stack', 10], true) as $k => $v) {
        if (!empty($v['hidden'])) {
            continue;
        }
        $out .= $fn ? \call_user_func($fn, $v, $k) : \_\lot\x\panel($v, $k);
    }
    return $out;
}

function session($name, $in) {
    $out = [
        'file' => (array) ($in['file'] ?? []),
        'pattern' => $in['pattern'] ?? null,
        'required' => $in['required'] ?? null,
        'read-only' => $in['read-only'] ?? null,
        'task' => $in['task'] ?? null
    ];
    // Store setting to be used by security
    $_SESSION['panel']['field'][$name] = \array_filter($out);
}

function title($in, $i = -1, $or = null) {
    $title = $in['title'] ?? $or;
    if (!isset($title) && !isset($in['icon'])) {
        return;
    }
    $tag = false;
    if ($i === -1) {
        $tag = 'span';
    } else if ($i === 0) {
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
    if ($title !== null && $title !== false) {
        $title = '<span>' . $title . '</span>';
    }
    $out[1] = $icon[0] . $title . $icon[1];
    $out[2]['class'] = \_\lot\x\panel\h\c($in, ['title']);
    return new \HTML($out);
}

function w($in) {
    return \w($in, 'abbr,b,br,cite,code,del,dfn,em,i,img,ins,kbd,mark,q,span,strong,sub,sup,svg,time,u,var');
}