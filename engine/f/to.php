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
        } else if (\preg_match($pattern_2, $color, $m)) {
            $color = '#' . \sprintf('%02x%02x%02x', (int) $m[1], (int) $m[2], (int) $m[3]);
        }
    }
    // Validate HEX color string
    $count = \strlen($color);
    if ((4 === $count || 7 === $count) && '#' === $color[0] && (\function_exists("\\ctype_xdigit") && \ctype_xdigit(\substr($color, 1)) || \preg_match('/^#([a-f\d]{3}){1,2}$/i', $color))) {
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
    return \is_array($value) ? new \HTML($value, true) : (string) $value;
}

function description($value) {
    $out = (string) \x\panel\type\description(\x\panel\_value_set(['content' => $value], 0), 0);
    return "" !== $out ? $out : null;
}

function elapse($date, $all = false) {
    $current = new \DateTime;
    $diff = $current->diff(new \DateTime($date));
    $diff->w = \floor($diff->d / 7);
    $diff->d -= $diff->w * 7;
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
        if ($i = $diff->{$k} ?? 0) {
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
    $value['id'] = $value['id'] ?? \substr(\uniqid(), 6);
    $state = $value['state'] ?? [];
    unset($value['tags']);
    $content = \fire("\\x\\panel\\type\\" . $type, [$value, $key]);
    $content['data-state'] = $state ? \json_encode($state) : null;
    $value['field'] = [$content[0], $content[1], $content[2]]; // Extract!
    return $value;
}

function icon($value) {
    // Maybe an `Anemone`
    if ($value instanceof \Traversable) {
        $value = \iterator_to_array($value);
    }
    // Maybe an icon string
    $value = (array) $value;
    return \x\panel\type\icon(['lot' => $value], 0);
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
    $task = \trim($v['task'], '/');
    return $base . \strtr(("" !== $task ? '/' . $task : "") . ("" !== $path ? '/' . $path : ""), "\\", '/') . (!\is_int($part) || 0 === $part ? "" : '/' . $part) . ($query ? \To::query($query) : "") . ($hash ? '#' . $hash : "");
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
    $out = [];
    foreach ($lot as $k => $v) {
        if (false === $v || null === $v || !empty($v['skip'])) {
            continue;
        }
        if ($v = \x\panel\type($v, $k)) {
            $out[$k] = $v;
            ++$count;
        }
    }
    return new \Anemone($out, "");
}

function pager(int $current, int $count, int $chunk, int $peek, callable $fn, string $first = 'First', string $prev = 'Previous', string $next = 'Next', string $last = 'Last') {
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
        $out .= '<span>';
        if ($current === $begin) {
            $out .= '<a aria-disabled="true" title="' . \i('Go to the %s page', [\l($prev)]) . '">' . $prev . '</a>';
        } else {
            $out .= '<a href="' . \call_user_func($fn, $current - 1) . '" rel="prev" title="' . \i('Go to the %s page', [\l($prev)]) . '">' . $prev . '</a>';
        }
        $out .= '</span> ';
    }
    if (($first = \i($first)) && ($last = \i($last))) {
        $out .= '<span>';
        if ($min > $begin) {
            $out .= '<a href="' . \call_user_func($fn, $begin) . '" rel="prev" title="' . \i('Go to the %s page', [\l($first)]) . '">' . $begin . '</a>';
            if ($min > $begin + 1) {
                $out .= ' <span aria-hidden="true">&#x2026;</span>';
            }
        }
        for ($i = $min; $i <= $max; ++$i) {
            if ($current === $i) {
                $out .= ' <a aria-current="page" title="' . \i('Go to page %d (you are here)', [$i]) . '">' . $i . '</a>';
            } else {
                $out .= ' <a href="' . \call_user_func($fn, $i) . '" rel="' . ($current >= $i ? 'prev' : 'next') . '" title="' . \i('Go to page %d', [$i]) . '">' . $i . '</a>';
            }
        }
        if ($max < $end) {
            if ($max < $end - 1) {
                $out .= ' <span aria-hidden="true">&#x2026;</span>';
            }
            $out .= ' <a href="' . \call_user_func($fn, $end) . '" rel="next" title="' . \i('Go to the %s page', [\l($last)]) . '">' . $end . '</a>';
        }
        $out .= '</span>';
    }
    if ($next = \i($next)) {
        $out .= ' <span>';
        if ($current === $end) {
            $out .= '<a aria-disabled="true" title="' . \i('Go to the %s page', [\l($next)]) . '">' . $next . '</b>';
        } else {
            $out .= '<a href="' . \call_user_func($fn, $current + 1) . '" rel="next" title="' . \i('Go to the %s page', [\l($next)]) . '">' . $next . '</a>';
        }
        $out .= '</span>';
    }
    return $out;
}

function path($value) {
    return \strtr(\strtr($value, ["\\" => '/']), ['./' => \PATH . \D, '/' => \D]);
}

function title($value, $level = -1) {
    $out = (string) \x\panel\type\title(\x\panel\_value_set([
        'content' => $value,
        'level' => $level
    ], 0), 0);
    return "" !== $out ? $out : null;
}

function w($value, $extra = null) {
    return \w('<!--0-->' . $value, 'abbr,b,br,cite,code,del,dfn,em,i,img,ins,kbd,mark,q,small,span,strong,sub,sup,svg,time,u,var' . ($extra ? ',' . $extra : ""));
}