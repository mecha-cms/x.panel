<?php namespace x\panel\to;

function color($color) {
    $color = \trim($color);
    // `rgba(255, 255, 255, 0.5)`
    $pattern_1 = '/^rgba?\s*\(\s*([01]?[0-9]?[0-9]|2[0-4][0-9]|25[0-5])\s*,\s*([01]?[0-9]?[0-9]|2[0-4][0-9]|25[0-5])\s*,\s*([01]?[0-9]?[0-9]|2[0-4][0-9]|25[0-5])(?:\s*,\s*([01]|0?\.\d+))?\s*\)$/';
    // `rgba(255 255 255 / 0.5)`
    $pattern_2 = '/^rgba?\s*\(\s*([01]?[0-9]?[0-9]|2[0-4][0-9]|25[0-5])\s+([01]?[0-9]?[0-9]|2[0-4][0-9]|25[0-5])\s+([01]?[0-9]?[0-9]|2[0-4][0-9]|25[0-5])(?:\s*\/\s*([01]|0?\.\d+))?\s*\)$/';
    // Convert RGB color string into HEX color string
    if (0 === \strpos($color, 'rgb')) {
        if (\preg_match($pattern_1, $color, $m)) {
            $color = '#' . \sprintf('%02x%02x%02x', (int) $m[1], (int) $m[2], (int) $m[3]);
        }
        if (\preg_match($pattern_2, $color, $m)) {
            $color = '#' . \sprintf('%02x%02x%02x', (int) $m[1], (int) $m[2], (int) $m[3]);
        }
    }
    // Validate HEX color string
    $count = \strlen($color);
    if ((4 === $count || 7 === $count) && '#' === $color[0] && \ctype_xdigit(\substr($color, 1))) {
        // Convert short HEX color string into long HEX color string
        if (4 === $count) {
            $m = \str_split(\substr($color, 1));
            $color = '#' . ($m[0] . $m[0]) . ($m[1] . $m[1]) . ($m[2] . $m[2]);
        }
        return $color;
    }
    return null; // Invalid color string!
}

function content($value) {
    return \is_array($value) ? new \HTML($value) : (string) $value;
}

function description($value) {
    $out = (string) \x\panel\type\description(['content' => $value], 0);
    return "" !== $out ? $out : null;
}

function field($value, $key, $type = 'textarea') {
    if (!\array_key_exists('id', $value)) {
        $value['id'] = 'f:' . \dechex(\crc32($key));
    }
    $state = $value['state'] ?? [];
    unset($value['tags']);
    $content = \fire("\\x\\panel\\type\\" . $type, [$value, $key]);
    $content['data-state'] = $state ? \json_encode($state) : null;
    $value['field'] = [$content[0], $content[1], $content[2]]; // Extract!
    return $value;
}

function icon($value) {
    return \x\panel\type\icon(['lot' => $value], 0);
}

function link($value) {
    \extract($GLOBALS, \EXTR_SKIP);
    $v = \array_replace_recursive([
        'base' => $_['base'] ?? "",
        'hash' => $_['hash'] ?? "",
        'path' => $_['path'] ?? "",
        'query' => $_['query'] ?? [],
        'task' => $_['task'] ?? 'get'
    ], $value);
    $base = $v['base'];
    $hash = $v['hash'];
    $path = $v['path'];
    $query = $v['query'];
    $task = $v['task'];
    return $base . ("" !== $task ? '/' . $task : "") . ("" !== $path ? '/' . $path : "") . ($query ? \To::query($query) : "") . ($hash ? '#' . $hash : "");
}

function lot($lot, &$count = 0, $sort = true) {
    if (!\is_array($lot)) {
        return;
    }
    if ($sort) {
        if (true === $sort) {
            $sort = [1, 'stack', 10];
        }
        $lot = (new \Anemone($lot))->sort($sort, true);
    }
    $out = "";
    foreach ($lot as $k => $v) {
        if (false === $v || null === $v || !empty($v['skip'])) {
            continue;
        }
        if ($v = \x\panel\type($v, $k)) {
            $out .= $v;
            ++$count;
        }
    }
    return $out;
}

function pager($current, $count, $chunk, $peek, $fn, $first = 'First', $prev = 'Previous', $next = 'Next', $last = 'Last') {
    $begin = 1;
    $end = (int) \ceil($count / $chunk);
    $out = "";
    if ($end <= 1) {
        return $out;
    }
    if ($current <= $peek + $peek) {
        $min = $begin;
        $max = \min($begin + $peek + $peek, $end);
    } else if ($current > $end - $peek - $peek) {
        $min = $end - $peek - $peek;
        $max = $end;
    } else {
        $min = $current - $peek;
        $max = $current + $peek;
    }
    if ($prev = \i($prev)) {
        $out = '<span>';
        if ($current === $begin) {
            $out .= '<b title="' . $prev . '">' . $prev . '</b>';
        } else {
            $out .= '<a href="' . \call_user_func($fn, $current - 1) . '" title="' . $prev . '" rel="prev">' . $prev . '</a>';
        }
        $out .= '</span> ';
    }
    if (($first = \i($first)) && ($last = \i($last))) {
        $out .= '<span>';
        if ($min > $begin) {
            $out .= '<a href="' . \call_user_func($fn, $begin) . '" title="' . $first . '" rel="prev">' . $begin . '</a>';
            if ($min > $begin + 1) {
                $out .= ' <span>&#x2026;</span>';
            }
        }
        for ($i = $min; $i <= $max; ++$i) {
            if ($current === $i) {
                $out .= ' <b title="' . $i . '">' . $i . '</b>';
            } else {
                $out .= ' <a href="' .\call_user_func($fn, $i) . '" title="' . $i . '" rel="' . ($current >= $i ? 'prev' : 'next') . '">' . $i . '</a>';
            }
        }
        if ($max < $end) {
            if ($max < $end - 1) {
                $out .= ' <span>&#x2026;</span>';
            }
            $out .= ' <a href="' . \call_user_func($fn, $end) . '" title="' . $last . '" rel="next">' . $end . '</a>';
        }
        $out .= '</span>';
    }
    if ($next = \i($next)) {
        $out .= ' <span>';
        if ($current === $end) {
            $out .= '<b title="' . $next . '">' . $next . '</b>';
        } else {
            $out .= '<a href="' . \call_user_func($fn, $current + 1) . '" title="' . $next . '" rel="next">' . $next . '</a>';
        }
        $out .= '</span>';
    }
    return $out;
}

function path($value) {
    return \strtr(\strtr($value, ["\\" => '/']), ['./' => \PATH . \D, '/' => \D]);
}

function title($value, $level = -1) {
    $out = (string) \x\panel\type\title([
        'content' => $value,
        'level' => $level
    ], 0);
    return "" !== $out ? $out : null;
}

function w($value, $extra = null) {
    return \w('<!--0-->' . $value, 'abbr,b,br,cite,code,del,dfn,em,i,img,ins,kbd,mark,q,small,span,strong,sub,sup,svg,time,u,var' . ($extra ? ',' . $extra : ""));
}