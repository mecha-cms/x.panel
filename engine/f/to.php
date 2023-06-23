<?php namespace x\panel\to;

function color($color) {
    if (!\is_array($color) && !\is_string($color)) {
        return null;
    }
    if (\is_array($color)) {
        [$r, $g, $b, $a] = \array_replace([0, 0, 0, null], $color);
        if (!\is_int($r) || !\is_int($g) || !\is_int($b) || (!\is_float($a) && !\is_int($a) && null !== $a)) {
            return null; // Invalid color array!
        }
        if ($r < 0 || $r > 255 || $g < 0 || $g > 255 || $b < 0 || $b > 255 || (null !== $a && ($a < 0 || $a > 1))) {
            return null;
        }
        return '#' . \sprintf('%02x%02x%02x' . (null !== $a ? '%02x' : ""), $r, $g, $b, $a * 255);
    }
    $color = \trim($color);
    // `rgba(255, 255, 255, 0.5)`
    $pattern_1 = '/^rgba?\s*\(\s*([01]?[0-9]?[0-9]|2[0-4][0-9]|25[0-5])\s*,\s*([01]?[0-9]?[0-9]|2[0-4][0-9]|25[0-5])\s*,\s*([01]?[0-9]?[0-9]|2[0-4][0-9]|25[0-5])(?:\s*,\s*([01]|0?\.\d+))?\s*\)$/';
    // `rgba(255 255 255 / 0.5)`
    $pattern_2 = '/^rgba?\s*\(\s*([01]?[0-9]?[0-9]|2[0-4][0-9]|25[0-5])\s+([01]?[0-9]?[0-9]|2[0-4][0-9]|25[0-5])\s+([01]?[0-9]?[0-9]|2[0-4][0-9]|25[0-5])(?:\s*\/\s*([01]|0?\.\d+))?\s*\)$/';
    // Convert RGB color string into HEX color string
    if (0 === \strpos($color, 'rgb')) {
        $rgba = 0 === \strpos($color, 'rgba');
        if (\preg_match($pattern_1, $color, $m) || \preg_match($pattern_2, $color, $m)) {
            if (!isset($m[4]) && $rgba) {
                return null;
            }
            return '#' . \sprintf('%02x%02x%02x' . ($rgba ? '%02x' : ""), (int) $m[1], (int) $m[2], (int) $m[3], ((float) ($m[4] ?? 1)) * 255);
        }
    }
    // Validate HEX color string
    $count = \strlen($color);
    // `#fff`, `#ffff`, `#ffffff`, `#ffffffff`
    if ((
        4 === $count ||
        5 === $count ||
        7 === $count ||
        9 === $count
    ) && '#' === $color[0] && (
        \function_exists("\\ctype_xdigit") && \ctype_xdigit(\substr($color, 1)) ||
        \preg_match('/^#([a-f\d]{3,4}){1,2}$/i', $color)
    )) {
        // Convert short HEX color string into long HEX color string
        if (4 === $count || 5 === $count) {
            $m = \str_split(\substr($color, 1));
            return '#' . ($m[0] . $m[0]) . ($m[1] . $m[1]) . ($m[2] . $m[2]) . (($m[3] ?? "") . ($m[3] ?? ""));
        }
        return \strtolower($color);
    }
    return null; // Invalid color string!
}

function content($value) {
    if (null === $value || \is_object($value)) {
        if (!($value instanceof \XML)) {
            return null;
        }
        return $value;
    }
    if (\is_array($value)) {
        return new \HTML($value, true);
    }
    return "" !== ($value = (string) $value) ? $value : null;
}

function description($value) {
    $value = (string) \x\panel\lot\type\description(\x\panel\lot\_value_set(['content' => $value], 0), 0);
    return "" !== $value ? $value : null;
}

function elapse($date, $all = false) {
    $current = new \DateTime;
    $diff = (array) $current->diff(new \DateTime(\is_int($date) ? \date('Y-m-d H:i:s', $date) : $date));
    $diff['w'] = \floor($diff['d'] / 7);
    $diff['d'] -= $diff['w'] * 7;
    $alter = [
        // It has to be in this order, please do not sort the array!
        'y' => 'year',
        'm' => 'month',
        'w' => 'week',
        'd' => 'day',
        'h' => 'hour',
        'i' => 'minute',
        's' => 'second'
    ];
    foreach ($alter as $k => &$v) {
        if ($i = $diff[$k] ?? 0) {
            $v = \i('%d ' . $alter[$k] . ($i > 1 ? 's' : ""), $i);
        } else {
            unset($alter[$k]);
        }
        unset($v);
    }
    if (false === $all) {
        $alter = \array_slice($alter, 0, 1);
    } else if (\is_int($all)) {
        $alter = \array_slice($alter, 0, $all);
    }
    return $alter ? \i('%s ago', \implode(', ', $alter)) : \i('just now');
}

function field($value, $key, $type = 'textarea') {
    $value['id'] = $value['id'] ?? 'f:' . \substr(\uniqid(), 6);
    $state = $value['state'] ?? [];
    unset($value['tags']);
    $out = \fire("\\x\\panel\\lot\\type\\" . $type, [$value, $key]);
    unset($value[2]['autocapitalize']);
    $out['data-state'] = $state ? \json_encode($state) : null;
    $value['field'] = [$out[0], $out[1], $out[2]]; // Extract!
    return $value;
}

function icon($value) {
    // Maybe an `Anemone`
    if ($value instanceof \Traversable) {
        $value = \iterator_to_array($value);
    }
    // Maybe an icon string
    if (!\is_array($value) || !\array_is_list($value)) {
        $value = [$value];
    }
    return \x\panel\lot\type\icon($value, 0);
}

function link($value) {
    \extract($GLOBALS, \EXTR_SKIP);
    $v = \array_replace_recursive([
        'base' => $_['base'] ?? "",
        'hash' => $_['hash'] ?? "",
        'part' => $_['part'] ?? 0,
        'path' => $_['path'] ?? "",
        'query' => $_['query'] ?? [],
        'task' => $_['task'] ?? 'get'
    ], $value);
    $base = \rtrim($v['base'] ?? "", '/');
    $hash = \ltrim($v['hash'] ?? "", '#');
    $part = $v['part'] ?? "";
    $path = \trim($v['path'] ?? "", '/');
    $query = $v['query'];
    $task = \trim($v['task'] ?? 'get', '/');
    return $base . \strtr(("" !== $task ? '/' . $task : "") . ("" !== $path ? '/' . $path : ""), "\\", '/') . (!\is_int($part) || 0 === $part ? "" : '/' . $part) . ($query ? \To::query($query) : "") . ($hash ? '#' . $hash : "");
}

function lot($lot, &$count = 0, $sort = true) {
    if (!\is_array($lot) || !$lot) {
        return null;
    }
    if ($sort) {
        if (true === $sort) {
            $sort = [1, 'stack', 10];
        }
        $lot = (new \Anemone($lot))->sort($sort, true);
    }
    $values = [];
    foreach ($lot as $k => $v) {
        if (false === $v || null === $v || !empty($v['skip'])) {
            continue;
        }
        if ($v = \x\panel\lot\type($v, $k)) {
            $values[$k] = $v;
            ++$count;
        }
    }
    return new \Anemone($values, "");
}

// TODO: Convert to recursive `HTML` content
function pager(int $current, int $count, int $chunk, int $peek, callable $fn, string $first = 'First', string $prev = 'Previous', string $next = 'Next', string $last = 'Last') {
    $start = 1;
    $end = (int) \ceil($count / $chunk);
    $out = "";
    if ($end <= 1) {
        return $out;
    }
    if ($current <= $peek + $peek) {
        $min = $start;
        $max = \min($start + $peek + $peek, $end);
    } else if ($current > $end - $peek - $peek) {
        $min = $end - $peek - $peek;
        $max = $end;
    } else {
        $min = $current - $peek;
        $max = $current + $peek;
    }
    if ($prev = \i($prev)) {
        $out .= '<span>';
        if ($current === $start) {
            $out .= '<a aria-disabled="true" title="' . \i('Go to the %s page', [\l($prev)]) . '">' . $prev . '</a>';
        } else {
            $out .= '<a href="' . \call_user_func($fn, $current - 1) . '" rel="prev" title="' . \i('Go to the %s page', [\l($prev)]) . '">' . $prev . '</a>';
        }
        $out .= '</span>';
    }
    if (($first = \i($first)) && ($last = \i($last))) {
        $out .= '<span>';
        if ($min > $start) {
            $out .= '<a href="' . \call_user_func($fn, $start) . '" rel="prev" title="' . \i('Go to the %s page', [\l($first)]) . '">' . $start . '</a>';
            if ($min > $start + 1) {
                $out .= '<span aria-hidden="true">&#x2026;</span>';
            }
        }
        for ($i = $min; $i <= $max; ++$i) {
            if ($current === $i) {
                $out .= '<a aria-current="page" title="' . \i('Go to page %d (you are here)', [$i]) . '">' . $i . '</a>';
            } else {
                $out .= '<a href="' . \call_user_func($fn, $i) . '" rel="' . ($current >= $i ? 'prev' : 'next') . '" title="' . \i('Go to page %d', [$i]) . '">' . $i . '</a>';
            }
        }
        if ($max < $end) {
            if ($max < $end - 1) {
                $out .= '<span aria-hidden="true">&#x2026;</span>';
            }
            $out .= '<a href="' . \call_user_func($fn, $end) . '" rel="next" title="' . \i('Go to the %s page', [\l($last)]) . '">' . $end . '</a>';
        }
        $out .= '</span>';
    }
    if ($next = \i($next)) {
        $out .= '<span>';
        if ($current === $end) {
            $out .= '<a aria-disabled="true" title="' . \i('Go to the %s page', [\l($next)]) . '">' . $next . '</a>';
        } else {
            $out .= '<a href="' . \call_user_func($fn, $current + 1) . '" rel="next" title="' . \i('Go to the %s page', [\l($next)]) . '">' . $next . '</a>';
        }
        $out .= '</span>';
    }
    return $out;
}

function path($value) {
    return \strtr(\strtr($value ?? "", ["\\" => '/']), ['./' => \PATH . \D, '/' => \D]);
}

function text($value) {
    $value = \trim(\strip_tags((string) $value));
    return "" !== $value ? $value : null;
}

function title($value, $level = -1) {
    $value = (string) \x\panel\lot\type\title(\x\panel\lot\_value_set([
        'content' => $value,
        'level' => $level
    ], 0), 0);
    return "" !== $value ? $value : null;
}

function unit($value) {
    // Maybe an `Anemone`
    if ($value instanceof \Traversable) {
        $value = \iterator_to_array($value);
    }
    // Maybe a unit string
    if (!\is_array($value) || !\array_is_list($value)) {
        $value = [$value];
    }
    return \x\panel\lot\type\unit($value, 0);
}

function w($value, array $keep = []) {
    if ($keep && \array_is_list($keep)) {
        $keep = \array_fill_keys($keep, true);
    }
    return \w($value ?? "", \array_keys(\array_filter(\array_replace([
        'abbr' => true,
        'b' => true,
        'br' => true,
        'cite' => true,
        'code' => true,
        'del' => true,
        'dfn' => true,
        'em' => true,
        'i' => true,
        'img' => true,
        'ins' => true,
        'kbd' => true,
        'mark' => true,
        'q' => true,
        'small' => true,
        'span' => true,
        'strong' => true,
        'sub' => true,
        'sup' => true,
        'svg' => true,
        'time' => true,
        'u' => true,
        'var' => true
    ], $keep))));
}