<?php

namespace _\lot\x\panel {
    function bar($in, $key) {
        if (isset($in['lot'])) {
            \_\lot\x\panel\h\p($in['lot'], 'bar');
        }
        $out = \_\lot\x\panel\lot($in, $key);
        $out[0] = 'nav';
        return $out;
    }
    function button($in, $key) {
        $out = \_\lot\x\panel\link($in, $key);
        $out[0] = 'button';
        $out['class'] = 'button';
        $out['disabled'] = isset($in['active']) && !$in['active'];
        $out['name'] = $in['name'] ?? $key;
        $out['value'] = $in['value'] ?? null;
        unset($out['href'], $out['target']);
        return $out;
    }
    function field($in, $key) {
        $tags = ['field', 'p'];
        if (isset($in['type'])) {
            $tags[] = \strtr($in['type'], '/', ':');
        }
        $id = $in['id'] ?? 'f:' . \dechex(\time());
        $in[2]['id'] = $in[2]['id'] ?? \str_replace('f:', 'field:', $id);
        $out = [
            0 => $in[0] ?? 'div',
            1 => $in[1] ?? "",
            2 => $in[2] ?? []
        ];
        if (!\array_key_exists('title', $in) || false !== $in['title']) {
            $title = \_\lot\x\panel\h\title($in, -2, \To::title($key));
            $out[1] .= '<label' . ("" === \strip_tags($title) ? ' class="count:0"' : "") . ' for="' . $id . '">' . $title . '</label>';
        }
        $before = "";
        $after = "";
        foreach (['before', 'after'] as $v) {
            if (isset($in[$v])) {
                if (\is_string($in[$v])) {
                    ${$v} = '<span class="fix"><span>' . $in[$v] . '</span></span>';
                } else if (\is_array($in[$v])) {
                    $icon = \_\lot\x\panel\h\icon($in[$v]['icon'] ?? [null, null]);
                    ${$v} = \str_replace('<svg ', '<svg class="fix" ', $icon[0]);
                }
            }
        }
        if (isset($in['content'])) {
            if (\is_array($in['content'])) {
                $style = "";
                $in['content'][2]['class'] = $in['content'][2]['class'] ?? "";
                if (isset($in['height']) && false !== $in['height']) {
                    if (true === $in['height']) {
                        $in['content'][2]['class'] .= ' height';
                    } else {
                        $style .= 'height:' . (\is_numeric($in['height']) ? $in['height'] . 'px' : $in['height']) . ';';
                    }
                }
                if (isset($in['width']) && false !== $in['width']) {
                    if (true === $in['width']) {
                        $in['content'][2]['class'] .= ' width';
                    } else {
                        $style .= 'width:' . (\is_numeric($in['width']) ? $in['width'] . 'px' : $in['width']) . ';';
                    }
                }
                $in['content'][2]['style'] = "" !== $style ? $style : null;
            }
            $out[1] .= '<div><div class="lot' . ($before || $after ? ' lot:input' . (!empty($in['width']) ? ' width' : "") : "") . '">' . $before . \_\lot\x\panel\h\content($in['content']) . $after . '</div>' . \_\lot\x\panel\h\description($in) . '</div>';
        } else if (isset($in['lot'])) {
            $out[1] .= '<div>' . \_\lot\x\panel\h\lot($in['lot']) . '</div>';
        }
        \_\lot\x\panel\h\c($out[2], $in, $tags);
        return new \HTML($out);
    }
    function fields($in) {
        $tags = ['lot', 'lot:field', 'p'];
        $out = [
            0 => $in[0] ?? 'div',
            1 => $in[1] ?? "",
            2 => $in[2] ?? []
        ];
        $append = "";
        $title = \_\lot\x\panel\h\title($in, 3);
        $description = \_\lot\x\panel\h\description($in);
        if (isset($in['content'])) {
            $out[1] .= \_\lot\x\panel\h\content($in['content']);
        } else if (isset($in['lot'])) {
            \_\lot\x\panel\h\p($in['lot'], 'field');
            foreach ((new \Anemon($in['lot']))->sort([1, 'stack', 10], true) as $k => &$v) {
                if (null === $v || false === $v || !empty($v['hidden'])) {
                    continue;
                }
                $type = $v['type'] ?? null;
                if ($type && \function_exists($fn = __NAMESPACE__ . "\\" . \strtr($type, [
                    '/' => "\\",
                    '-' => '_',
                    '.' => '__'
                ]))) {
                    if ('field/hidden' !== $type) {
                        $out[1] .= \call_user_func($fn, $v, $k);
                    } else {
                        $append .= \_\lot\x\panel\field\hidden($v, $k);
                    }
                } else {
                    $append .= \_\lot\x\panel\field\_($v, $k); // Unknown `field` type
                }
                unset($v);
            }
            $out[1] .= $append;
        }
        $out[1] = $title . $description . $out[1];
        \_\lot\x\panel\h\c($out[2], $in, $tags);
        return "" !== $out[1] ? new \HTML($out) : null;
    }
    function file($in, $key) {
        $tags = ['is:file'];
        if (isset($in['active']) && !$in['active']) {
            $tags[] = 'not:active';
        }
        $out = [
            0 => 'li',
            1 => "",
            2 => []
        ];
        \_\lot\x\panel\h\c($out[2], $in, $tags);
        $out[1] .= '<h3>' . \_\lot\x\panel\Link([
            'description' => $in['description'] ?? null,
            'link' => $in['link'] ?? null,
            'title' => $in['title'] ?? null,
            'url' => $in['url'] ?? null
        ], $key) . '</h3>';
        $out[1] .= \_\lot\x\panel\tasks\link([
            0 => 'p',
            'lot' => $in['tasks'] ?? []
        ], 0);
        return new \HTML($out);
    }
    function files($in, $key) {
        $out = [
            0 => 'ul',
            1 => "",
            2 => []
        ];
        $lot = [];
        if (isset($in['lot'])) {
            foreach ($in['lot'] as $k => $v) {
                if (null === $v || false === $v || !empty($v['hidden'])) {
                    continue;
                }
                $lot[$k] = $v;
            }
        }
        $chunk = $in['chunk'] ?? 0;
        $current = $in['current'] ?? 1;
        $lot = 0 === $chunk ? [$lot] : \array_chunk($lot, $chunk, false);
        $count = 0;
        foreach ($lot[$current - 1] ?? [] as $k => $v) {
            $path = \rtrim($v['path'] ?? "", \DS);
            if (!empty($v['current']) || $path && (
                isset($_SESSION['_']['file'][$path]) ||
                isset($_SESSION['_']['folder'][$path])
            )) {
                $v['tags'][] = 'is:active';
                unset($_SESSION['_']['file'][$path]);
                unset($_SESSION['_']['folder'][$path]);
            }
            $out[1] .= \_\lot\x\panel($v, $k);
            ++$count;
        }
        unset($lot);
        \_\lot\x\panel\h\c($out[2], $in, ['count:' . $count, 'lot', 'lot:file']);
        return new \HTML($out);
    }
    function folder($in, $key) {
        $tags = ['is:folder'];
        if (isset($in['active']) && !$in['active']) {
            $tags[] = 'not:active';
        }
        $out = [
            0 => 'li',
            1 => "",
            2 => []
        ];
        \_\lot\x\panel\h\c($out[2], $in, $tags);
        $out[1] .= '<h3>' . \_\lot\x\panel\link([
            'description' => $in['description'] ?? \i('Open folder'),
            'link' => $in['link'] ?? null,
            'title' => $in['title'] ?? null,
            'url' => $in['url'] ?? null
        ], $key) . '</h3>';
        $out[1] .= \_\lot\x\panel\tasks\link([
            0 => 'p',
            'lot' => $in['tasks'] ?? []
        ], 0);
        return new \HTML($out);
    }
    function folders($in, $key) {
        return \_\lot\x\panel\files($in, $key);
    }
    function form($in, $key) {
        $out = [
            0 => $in[0] ?? 'form',
            1 => $in[1] ?? "",
            2 => $in[2] ?? []
        ];
        if (isset($in['active']) && empty($in['active'])) {
            $out[0] = false;
        }
        if (isset($in['content'])) {
            $out[1] .= \_\lot\x\panel\h\content($in['content']);
        } else if (isset($in['lot'])) {
            $out[1] .= \_\lot\x\panel\h\lot($in['lot']);
        }
        $href = $in['link'] ?? $in['url'] ?? null;
        \_\lot\x\panel\h\c($out[2], $in);
        if (!isset($out[2]['action'])) {
            $out[2]['action'] = $href;
        }
        if (!isset($out[2]['name'])) {
            $out[2]['name'] = $in['name'] ?? $key;
        }
        return new \HTML($out);
    }
    function link($in, $key) {
        $out = [
            0 => $in[0] ?? 'a',
            1 => $in[1] ?? "",
            2 => $in[2] ?? []
        ];
        if ("" === $out[1]) {
            $out[1] = \_\lot\x\panel\h\title($in, -1, \To::title($key));
        }
        $tags = [];
        $href = $in['link'] ?? $in['url'] ?? \P;
        $href = false === $href ? \P : (string) $href;
        if (\P === $href || (isset($in['active']) && !$in['active'])) {
            $tags[] = 'not:active';
        }
        \_\lot\x\panel\h\c($out[2], $in, $tags);
        $out[2]['href'] = \P === $href ? null : $href;
        if (isset($in['id'])) {
            $out[2]['id'] = $in['id'];
        }
        $out[2]['target'] = $in[2]['target'] ?? (isset($in['link']) ? '_blank' : null);
        $out[2]['title'] = \i(...((array) ($in['description'] ?? [])));
        return new \HTML($out);
    }
    function menu($in, $key, int $i = 0) {
        $out = [
            0 => $in[0] ?? 'ul',
            1 => $in[1] ?? "",
            2 => $in[2] ?? []
        ];
        $tags = $i < 0 ? [] : ['lot', 'lot:menu'];
        if (!empty($in['static'])) {
            $tags[] = 'is:static';
        }
        if (isset($in['content'])) {
            $tags[] = 'count:1';
            $out[1] .= \_\lot\x\panel\h\content($in['content']);
        } else if (isset($in['lot'])) {
            $count = 0;
            foreach ((new \Anemon($in['lot']))->sort([1, 'stack', 10], true) as $k => $v) {
                if (null === $v || false === $v || !empty($v['hidden'])) {
                    continue;
                }
                ++$count;
                $li = [
                    0 => 'li',
                    1 => $v[1] ?? "",
                    2 => $v[2] ?? []
                ];
                if (isset($v['type'])) {
                    $li[1] .= \_\lot\x\panel($v, $k);
                } else if (\is_array($v)) {
                    $v['icon'] = \_\lot\x\panel\h\icon($v['icon'] ?? [null, null]);
                    if (!empty($v['lot']) && (!empty($v['caret']) || !\array_key_exists('caret', $v))) {
                        $v['icon'][1] = '<svg class="caret" height="12" viewBox="0 0 24 24" width="12"><path d="' . ($v['caret'] ?? ($i < 0 ? 'M7,10L12,15L17,10H7Z' : 'M10,17L15,12L10,7V17Z')) . '"></path></svg>';
                    }
                    $ul = "";
                    $a = (array) ($v['tags'] ?? []);
                    if (isset($v['active']) && !$v['active']) {
                        $a[] = 'not:active';
                    }
                    if (!empty($v['current'])) {
                        $a[] = 'is:current';
                    }
                    if (!isset($v[1])) {
                        if (!empty($v['lot'])) {
                            $ul = \_\lot\x\panel\menu($v, $k, $i + 1); // Recurse
                            $ul['class'] = 'lot lot:menu';
                            $li[1] = $ul;
                            if ($i < 0) {
                                $a[] = 'has:menu';
                            }
                        }
                        unset($v['tags']);
                        $li[1] = \_\lot\x\panel\link($v, $k) . $ul;
                    }
                    \_\lot\x\panel\h\c($li[2], $v, $a);
                } else {
                    $li[1] = \_\lot\x\panel\link(['title' => $v], $k);
                }
                $out[1] .= new \HTML($li);
            }
            $tags[] = 'count:' . $count;
        }
        \_\lot\x\panel\h\c($out[2], $in, $tags);
        return new \HTML($out);
    }
    function page($in, $key) {
        $tags = ['is:file'];
        if (isset($in['active']) && !$in['active']) {
            $tags[] = 'not:active';
        }
        $out = [
            0 => 'li',
            1 => "",
            2 => []
        ];
        \_\lot\x\panel\h\c($out[2], $in, $tags);
        $path = $in['path'] ?? $key;
        foreach (['title', 'description', 'image'] as $k) {
            if (isset($in[$k])) {
                // Delay `page.*` hook execution with closure(s)
                if (\is_callable($in[$k])) {
                    $in[$k] = \call_user_func($in[$k], $path);
                }
            }
        }
        $date = isset($in['time']) ? \strtr($in['time'], '-', '/') : null;
        $out[1] .= '<div' . (isset($in['image']) && false === $in['image'] ? ' hidden' : "") . '>' . (!empty($in['image']) ? '<img alt="" height="72" src="' . $in['image'] . '" width="72">' : '<span class="img" style="background: #' . \substr(\md5(\strtr($path, [
            \ROOT => "",
            \DS => '/'
        ])), 0, 6) . ';"></span>') . '</div>';
        $out[1] .= '<div><h3>' . \_\lot\x\panel\link([
            'link' => $in['link'] ?? null,
            'title' => $in['title'] ?? $date,
            'url' => $in['url'] ?? null
        ], $key) . '</h3>' . \_\lot\x\panel\h\description($in, $date) . '</div>';
        $out[1] .= '<div>' . \_\lot\x\panel\tasks\link([
            0 => 'p',
            'lot' => $in['tasks'] ?? []
        ], 0) . '</div>';
        return new \HTML($out);
    }
    function pager($in, $key) {
        $in['tags'][] = 'lot';
        $in['tags'][] = 'lot:pager';
        $pager = function($current, $count, $chunk, $peek, $fn, $first, $prev, $next, $last) {
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
            if ($prev) {
                $out = '<span>';
                if ($current === $begin) {
                    $out .= '<b title="' . $prev . '">' . $prev . '</b>';
                } else {
                    $out .= '<a href="' . \call_user_func($fn, $current - 1) . '" title="' . $prev . '" rel="prev">' . $prev . '</a>';
                }
                $out .= '</span> ';
            }
            if ($first && $last) {
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
            if ($next) {
                $out .= ' <span>';
                if ($current === $end) {
                    $out .= '<b title="' . $next . '">' . $next . '</b>';
                } else {
                    $out .= '<a href="' . \call_user_func($fn, $current + 1) . '" title="' . $next . '" rel="next">' . $next . '</a>';
                }
                $out .= '</span>';
            }
            return $out;
        };
        $in['content'] = $content = $pager($in['current'] ?? 1, $in['count'] ?? 0, $in['chunk'] ?? 20, $in['peek'] ?? 2, function($i) {
            extract($GLOBALS, \EXTR_SKIP);
            return $url . $_['/'] . '/::g::/' . $_['path'] . '/' . $i . $url->query . $url->hash;
        }, \i('First'), \i('Previous'), \i('Next'), \i('Last'));
        $out = \_\lot\x\panel\content($in, $key);
        $out[0] = 'p';
        return "" !== $content ? $out : null;
    }
    function pages($in, $key) {
        $out = [
            0 => 'ul',
            1 => "",
            2 => []
        ];
        $lot = [];
        if (isset($in['lot'])) {
            foreach ($in['lot'] as $k => $v) {
                if (null ===$v || false === $v || !empty($v['hidden'])) {
                    continue;
                }
                $lot[$k] = $v;
            }
        }
        $chunk = $in['chunk'] ?? 0;
        $current = $in['current'] ?? 1;
        $lot = 0 === $chunk ? [$lot] : \array_chunk($lot, $chunk, false);
        $count = 0;
        foreach ($lot[$current - 1] ?? [] as $k => $v) {
            $path = \rtrim($v['path'] ?? "", \DS);
            if (!empty($v['current']) || $path && isset($_SESSION['_']['file'][$path])) {
                $v['tags'][] = 'is:active';
                unset($_SESSION['_']['file'][$path]);
            }
            $out[1] .= \_\lot\x\panel($v, $k);
            ++$count;
        }
        unset($lot);
        \_\lot\x\panel\h\c($out[2], $in, ['count:' . $count, 'lot', 'lot:page']);
        return new \HTML($out);
    }
    function tab($in, $key) {
        $out = [
            0 => $in[0] ?? 'section',
            1 => $in[1] ?? "",
            2 => \array_replace(['data-name' => $key], $in[2] ?? [])
        ];
        if (isset($in['content'])) {
            $out[1] .= \_\lot\x\panel\h\content($in['content']);
        } else if (isset($in['lot'])) {
            $out[1] .= \_\lot\x\panel\h\lot($in['lot']);
        }
        \_\lot\x\panel\h\c($out[2], $in);
        return "" !== $out[1] ? new \HTML($out) : null;
    }
    function tabs($in, $key) {
        $name = $in['name'] ?? $key;
        $out = [
            0 => $in[0] ?? 'div',
            1 => $in[1] ?? "",
            2 => \array_replace(['data-name' => $name], $in[2] ?? [])
        ];
        if (isset($in['content'])) {
            $out[1] .= \_\lot\x\panel\h\content($in['content']);
        } else if (isset($in['lot'])) {
            $nav = $section = [];
            $tags = ['lot', 'lot:tab', 'p'];
            $lot = (new \Anemon($in['lot']))->sort([1, 'stack'], true)->get();
            $count = 0;
            foreach ($lot as $k => $v) {
                if (null === $v || false === $v || !empty($v['hidden'])) {
                    continue;
                }
                $kk = $v['name'] ?? $k;
                if (\is_array($v)) {
                    if (empty($v['url']) && empty($v['link'])) {
                        $v['url'] = $GLOBALS['url']->query('&', [
                            'tab' => [$name => $kk]
                        ]);
                    } else {
                        $v['tags'][] = 'has:link';
                    }
                }
                $v[2]['data-name'] = $kk;
                $v[2]['target'] = $v[2]['target'] ?? 'tab:' . $kk;
                // If `type` is not defined, the default value will be `tab`
                if (!\array_key_exists('type', $v)) {
                    $v['type'] = 'tab';
                }
                $nav[$kk] = $v;
                $section[$kk] = $v;
                // Disable dropdown menu view
                unset(
                    $nav[$kk]['content'],
                    $nav[$kk]['lot'],
                    $nav[$kk]['type']
                );
            }
            // TODO: Do not activate tab (activate the first tab) if current tab content is empty
            $first = \array_keys($nav)[0] ?? null; // The first tab
            $active = $_GET['tab'][$name] ?? $in['active'] ?? $first ?? null;
            if (null !== $active && isset($nav[$active]) && \is_array($nav[$active])) {
                $nav[$active]['tags'][] = 'is:active';
                $section[$active]['tags'][] = 'is:active';
            } else if (null !== $first && isset($nav[$first]) && \is_array($nav[$first])) {
                $nav[$first]['tags'][] = 'is:active';
                $section[$first]['tags'][] = 'is:active';
            }
            foreach ($section as $k => $v) {
                $vv = (string) \_\lot\x\panel($v, $k);
                if ("" === $vv) {
                    unset($nav[$k]);
                } else {
                    ++$count;
                }
                $section[$k] = $vv;
            }
            $out[1] = '<nav>' . \_\lot\x\panel\bar\menu(['lot' => $nav], $name) . '</nav>';
            $out[1] .= \implode("", $section);
        }
        $tags[] = 'count:' . $count;
        \_\lot\x\panel\h\c($out[2], $in, $tags);
        return new \HTML($out);
    }
    function tasks($in, $key) {
        $tags = ['lot', 'lot:task'];
        $out = [
            0 => $in[0] ?? 'div',
            1 => $in[1] ?? "",
            2 => $in[2] ?? []
        ];
        $count = 0;
        if (isset($in['content'])) {
            $tags[] = 'count:' . ($count = 1);
            $out[1] .= \_\lot\x\panel\h\content($in['content']);
        } else if (isset($in['lot'])) {
            $out[1] .= \_\lot\x\panel\h\lot($in['lot'], null, $count);
            $tags[] = 'count:' . $count;
        }
        if ($count > 0) {
            \_\lot\x\panel\h\c($out[2], $in, $tags);
            return new \HTML($out);
        }
        return null;
    }
    function abort($in, $key, $fn) {
        if (\defined("\\DEBUG") && \DEBUG) {
            \Guard::abort('Unable to convert data <code>' . \strtr(\htmlspecialchars(\json_encode($in, \JSON_PRETTY_PRINT)), [' ' => '&nbsp;', "\n" => '<br>']) . '</code> because function <code>' . $fn . '</code> does not exist.');
        }
    }
    function content($in, $key) {
        $type = $in['type'] ?? null;
        $title = \_\lot\x\panel\h\title($in, 2);
        $description = \_\lot\x\panel\h\description($in);
        $out = [
            0 => $in[0] ?? 'div',
            1 => $in[1] ?? $title . $description,
            2 => $in[2] ?? []
        ];
        if (isset($in['content'])) {
            $out[1] .= \_\lot\x\panel\h\content($in['content']);
        }
        $out[2] = \array_replace([
            'class' => 'count:1 lot' . (isset($type) ? ' lot:' . \implode(' lot:', \step(\strtr($type, '/', '.'))) : ""),
            'id' => $in['id'] ?? null
        ], $out[2]);
        return new \HTML($out);
    }
    function lot($in, $key) {
        $type = $in['type'] ?? null;
        $title = \_\lot\x\panel\h\title($in, 2);
        $description = \_\lot\x\panel\h\description($in);
        $count = 0;
        $out = [
            0 => $in[0] ?? 'div',
            1 => $in[1] ?? $title . $description,
            2 => $in[2] ?? []
        ];
        if (isset($in['lot'])) {
            $out[1] .= \_\lot\x\panel\h\lot($in['lot'], null, $count);
        }
        $out[2] = \array_replace([
            'class' => 'count:' . $count . ' lot' . (isset($type) ? ' lot:' . \implode(' lot:', \step(\strtr($type, '/', '.'))) : ""),
            'id' => $in['id'] ?? null
        ], $out[2]);
        return new \HTML($out);
    }
}

namespace _\lot\x {
    function panel($in, $key) {
        if (\is_string($in)) {
            return $in;
        }
        if (!empty($in['hidden'])) {
            return "";
        }
        $out = "";
        if ($type = isset($in['type']) ? \strtr($in['type'], [
            '/' => "\\",
            '-' => '_',
            '.' => '__'
        ]) : null) {
            if ($type && \function_exists($fn = __NAMESPACE__ . "\\panel\\" . $type)) {
                $out .= \call_user_func($fn, $in, $key);
            } else if (isset($in['content'])) {
                if ($type && \function_exists($fn = __NAMESPACE__ . "\\panel\\content\\" . $type)) {
                    $out .= \call_user_func($fn, $in, $key);
                } else {
                    if (\defined("\\DEBUG") && \DEBUG) {
                        $in['title'] = ['Function %s does not exist.', ['<code>' . $fn . '</code>']];
                    }
                    $out .= \_\lot\x\panel\content($in, $key);
                }
            } else if (isset($in['lot'])) {
                if ($type && \function_exists($fn = __NAMESPACE__ . "\\panel\\lot\\" . $type)) {
                    $out .= \call_user_func($fn, $in, $key);
                } else {
                    if (\defined("\\DEBUG") && \DEBUG) {
                        $in['title'] = ['Function %s does not exist.', ['<code>' . $fn . '</code>']];
                    }
                    $out .= \_\lot\x\panel\lot($in, $key);
                }
            } else {
                $out .= \_\lot\x\panel\abort($in, $key, $fn);
            }
        } else {
            if (isset($in['content'])) {
                $out .= \_\lot\x\panel\content($in, $key);
            } else if (isset($in['lot'])) {
                $out .= \_\lot\x\panel\lot($in, $key);
            } else {
                // Skip!
            }
        }
        return $out;
    }
}

namespace {
    require __DIR__ . DS . 'f' . DS . 'bar.php';
    require __DIR__ . DS . 'f' . DS . 'button.php';
    require __DIR__ . DS . 'f' . DS . 'content.php';
    require __DIR__ . DS . 'f' . DS . 'field.php';
    require __DIR__ . DS . 'f' . DS . 'form.php';
    require __DIR__ . DS . 'f' . DS . 'h.php';
    require __DIR__ . DS . 'f' . DS . 'link.php';
    require __DIR__ . DS . 'f' . DS . 'lot.php';
    require __DIR__ . DS . 'f' . DS . 'route.php';
    require __DIR__ . DS . 'f' . DS . 'tasks.php';
}
