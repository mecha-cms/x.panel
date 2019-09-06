<?php

namespace _\lot\x\panel {
    function Bar($in, $key) {
        if (isset($in['lot'])) {
            \_\lot\x\panel\h\p($in['lot'], 'Bar');
        }
        $out = \_\lot\x\panel\lot($in, $key);
        $out[0] = 'nav';
        return $out;
    }
    function Bar_List($in, $key) {
        return \_\lot\x\panel\Menu($in, $key, -1);
    }
    function Button($in, $key) {
        $out = \_\lot\x\panel\Link($in, $key);
        $out[0] = 'button';
        $out['class'] = 'button';
        $out['disabled'] = isset($in['active']) && !$in['active'];
        $out['name'] = $in['name'] ?? $key;
        $out['value'] = $in['value'] ?? null;
        unset($out['href'], $out['target']);
        return $out;
    }
    function Button_($in, $key) {
        return \_\lot\x\panel\Button($in, $key); // Unknown `Button` type
    }
    function Button_Button($in, $key) {
        $out = \_\lot\x\panel\Button($in, $key);
        $out['type'] = 'button';
        return $out;
    }
    function Button_Link($in, $key) {
        $out = \_\lot\x\panel\Link($in, $key);
        \_\lot\x\panel\h\c($out, $in, ['button']);
        return $out;
    }
    function Button_Reset($in, $key) {
        $out = \_\lot\x\panel\Button($in, $key);
        $out['type'] = 'reset';
        return $out;
    }
    function Button_Submit($in, $key) {
        $out = \_\lot\x\panel\Button($in, $key);
        $out['type'] = 'submit';
        return $out;
    }
    function Data($in, $key) {}
    function Datas($in, $key) {}
    function Field($in, $key) {
        $tags = ['field', 'p'];
        if (isset($in['type'])) {
            $tags[] = \strtr(\c2f($in['type'], '-', '.'), '_', ':');
        }
        $id = $in['id'] ?? \uniqid();
        $in[2]['id'] = $in[2]['id'] ?? \str_replace('f:', 'field:', $id);
        $out = [
            0 => $in[0] ?? 'div',
            1 => $in[1] ?? "",
            2 => $in[2] ?? []
        ];
        if (isset($in['title'])) {
            $out[1] .= '<label for="' . $id . '">' . \_\lot\x\panel\h\title($in, -2, $key) . '</label>';
        }
        $before = "";
        $after = "";
        foreach (['before', 'after'] as $v) {
            if (isset($in[$v])) {
                if (\is_string($in[$v])) {
                    ${$v} = '<span class="fix"><span>' . $in[$v] . '</span></span>';
                } else if (\is_array($in[$v])) {
                    $icon = \_\lot\x\panel\h\icon($in[$v]['icon'] ?? [null, null]);
                    ${$v} = \str_replace('<svg>', '<svg class="fix">', $icon[0]);
                }
            }
        }
        if (isset($in['content'])) {
            if (\is_array($in['content'])) {
                $style = "";
                $in['content'][2]['class'] = $in['content'][2]['class'] ?? "";
                if (isset($in['height']) && $in['height'] !== false) {
                    if ($in['height'] === true) {
                        $in['content'][2]['class'] .= ' height';
                    } else {
                        $style .= 'height:' . (\is_numeric($in['height']) ? $in['height'] . 'px' : $in['height']) . ';';
                    }
                }
                if (isset($in['width']) && $in['width'] !== false) {
                    if ($in['width'] === true) {
                        $in['content'][2]['class'] .= ' width';
                    } else {
                        $style .= 'width:' . (\is_numeric($in['width']) ? $in['width'] . 'px' : $in['width']) . ';';
                    }
                }
                $in['content'][2]['style'] = $style !== "" ? $style : null;
            }
            $out[1] .= '<div><div class="lot' . ($before || $after ? ' lot:input' . (!empty($in['width']) ? ' width' : "") : "") . '">' . $before . \_\lot\x\panel\h\content($in['content']) . $after . '</div>' . \_\lot\x\panel\h\description($in) . '</div>';
            if (isset($in['content'][2]['name'])) {
                \_\lot\x\panel\h\session($in['content'][2]['name'], $in);
            }
        } else if (isset($in['lot'])) {
            $out[1] .= \_\lot\x\panel\h\lot($in['lot']);
        }
        \_\lot\x\panel\h\c($out[2], $in, $tags);
        return new \HTML($out);
    }
    function Fields($in) {
        $tags = ['lot', 'lot:field'];
        $out = [
            0 => $in[0] ?? 'div',
            1 => $in[1] ?? "",
            2 => $in[2] ?? []
        ];
        $append = "";
        if (isset($in['content'])) {
            $out[1] .= \_\lot\x\panel\h\content($in['content']);
        } else if (isset($in['lot'])) {
            \_\lot\x\panel\h\p($in['lot'], 'Field');
            foreach ((new \Anemon($in['lot']))->sort([1, 'stack', 10], true) as $k => &$v) {
                $type = $v['type'] ?? null;
                if (\function_exists($fn = \rtrim(__NAMESPACE__ . "\\" . $type, "\\"))) {
                    if ($type !== 'Field_Hidden') {
                        $out[1] .= \call_user_func($fn, $v, $k);
                    } else {
                        $append .= \_\lot\x\panel\Field_Hidden($v, $k);
                    }
                } else {
                    $append .= \_\lot\x\panel\Field_($v, $k); // Unknown `Field` type
                }
                unset($v);
            }
            $out[1] .= $append;
        }
        \_\lot\x\panel\h\c($out[2], $in, $tags);
        return new \HTML($out);
    }
    function File($in, $key) {
        $name = $x = null;
        $tasks = $in['tasks'] ?? null;
        if ($path = $in['path'] ?? null) {
            $name = \basename($path);
            $x = [\pathinfo($path, \PATHINFO_EXTENSION) => 1];
        }
        if (isset($in['title'])) {
            $name = $in['title'];
        }
        if (isset($in['file']['x'])) {
            $x = $in['file']['x'];
        }
        $tags = ['is:file'];
        if ($x) {
            foreach ($x as $k => $v) {
                $tags[] = 'file:' . $k;
            }
        }
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
            'description' => $in['description'] ?? ($path ? (new \File($path))->size : null),
            'link' => $in['link'] ?? null,
            'title' => $name,
            'url' => $in['url'] ?? null
        ], $key) . '</h3>';
        if (\is_array($tasks)) {
            $out[1] .= \_\lot\x\panel\Tasks\Link([
                0 => 'p',
                'lot' => $tasks
            ], 0);
        }
        return new \HTML($out);
    }
    function Files($in, $key) {
        $tags = ['lot', 'lot:file'];
        $tasks = $in['tasks'] ?? null;
        $out = [
            0 => 'ul',
            1 => "",
            2 => []
        ];
        \_\lot\x\panel\h\c($out[2], $in, $tags);
        $a = [[], []];
        $source = isset($in['from']) && \is_dir($in['from']);
        if ($raw = isset($in['lot'])) {
            foreach ($in['lot'] as $k => $v) {
                $a[\is_string($v) && \is_file($v) ? 1 : 0][$k] = $v;
            }
        } else if ($source) {
            foreach (\g(\strtr($in['from'], '/', DS)) as $k => $v) {
                $a[$v][] = $k;
            }
        }
        // Do not sort if input is array
        if (!$raw) {
            \asort($a[0]);
            \asort($a[1]);
        }
        $chunk = $in['chunk'] ?? 0;
        $current = $in['current'] ?? 1;
        $a = \array_merge($a[0], $a[1]);
        $a = $chunk === 0 ? [$a] : \array_chunk($a, $chunk, false);
        $url = $GLOBALS['url'];
        if (isset($a[$current - 1])) {
            $clean = \dirname($url->clean);
            // Add parent directory if current directory level is greater than `.\lot`
            if ($source && \substr($clean, -2) !== '::') {
                \array_unshift($a[$current - 1], [
                    'title' => '..',
                    'type' => 'Folder',
                    'url' => $clean . '/1' . $url->query
                ]);
            }
            foreach ($a[$current - 1] as $k => $v) {
                if (\is_string($v)) {
                    $f = \is_file($v);
                    $n = \basename($v);
                    $x = \pathinfo($v, \PATHINFO_EXTENSION);
                    $v = [ // Treat as array for now
                        'active' => !($v && \strpos('._', $v[0]) !== false),
                        'file' => ['x' => [$x => 1]],
                        'link' => $f ? \To::URL($v) : null,
                        'path' => $v,
                        'title' => $n,
                        'type' => $f ? 'File' : 'Folder',
                        'url' => $f ? null : $url . $GLOBALS['PANEL']['//'] . '/::g::/' . \str_replace([\LOT . \DS, \DS], ["", '/'], $v) . '/1'
                    ];
                }
                $t = (array) ($v['tasks'] ?? []);
                if (\is_callable($tasks)) {
                    $v['tasks'] = \array_replace((array) \call_user_func($tasks, $v), $t);
                } else if (\is_array($tasks)) {
                    $v['tasks'] = \array_replace($tasks, $t);
                }
                if (!empty($v['current']) || isset($v['path']) && (
                    isset($_SESSION['PANEL']['file'][$v['path']]) ||
                    isset($_SESSION['PANEL']['folder'][$v['path']])
                )) {
                    $v['tags'][] = 'is:active';
                }
                $out[1] .= \_\lot\x\panel($v, $k);
            }
        }
        return new \HTML($out);
    }
    function Folder($in, $key) {
        $name = null;
        $tasks = $in['tasks'] ?? null;
        if ($path = $in['path'] ?? null) {
            $name = \basename($path);
        }
        if (isset($in['title'])) {
            $name = $in['title'];
        }
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
        $out[1] .= '<h3>' . \_\lot\x\panel\Link([
            'description' => $in['description'] ?? $GLOBALS['language']->doEnter,
            'link' => $in['link'] ?? null,
            'title' => $name,
            'url' => $in['url'] ?? null
        ], $key) . '</h3>';
        if (\is_array($tasks)) {
            $out[1] .= \_\lot\x\panel\Tasks\Link([
                0 => 'p',
                'lot' => $tasks
            ], 0);
        }
        return new \HTML($out);
    }
    function Form($in, $key) {
        $out = [
            0 => $in[0] ?? 'form',
            1 => $in[1] ?? "",
            2 => []
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
        $out[2]['action'] = $href;
        $out[2]['name'] = $in['name'] ?? $key;
        return new \HTML($out);
    }
    function Link($in, $key) {
        $out = [
            0 => $in[0] ?? 'a',
            1 => $in[1] ?? "",
            2 => []
        ];
        if ($out[1] === "") {
            $out[1] = \_\lot\x\panel\h\title($in);
        }
        $tags = [];
        $href = (string) ($in['link'] ?? $in['url'] ?? \P);
        if ($href === \P || (isset($in['active']) && !$in['active'])) {
            $tags[] = 'not:active';
        }
        \_\lot\x\panel\h\c($out[2], $in, $tags);
        $out[2]['href'] = $href === \P ? '#' : $href;
        $out[2]['target'] = $in[2]['target'] ?? (isset($in['link']) ? '_blank' : null);
        $out[2]['title'] = $in['description'] ?? null;
        return new \HTML($out);
    }
    function Link_($in, $key) {
        return \_\lot\x\panel\Link($in, $key); // Unknown `Link` type
    }
    function Menu($in, $key, int $i = 0) {
        $out = [
            0 => $in[0] ?? 'ul',
            1 => $in[1] ?? "",
            2 => $in[2] ?? []
        ];
        $tags = [];
        if (isset($in['content'])) {
            $tags[] = 'count:1';
            $out[1] .= \_\lot\x\panel\h\content($in['content']);
        } else if (isset($in['lot'])) {
            $size = 0;
            foreach ((new \Anemon($in['lot']))->sort([1, 'stack', 10], true) as $k => $v) {
                if (!empty($v['hidden'])) {
                    continue;
                }
                ++$size;
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
                        $v['icon'][1] = '<svg class="caret" viewBox="0 0 24 24"><path d="' . ($v['caret'] ?? ($i < 0 ? 'M7,10L12,15L17,10H7Z' : 'M10,17L15,12L10,7V17Z')) . '"></path></svg>';
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
                            $ul = \_\lot\x\panel\Menu($v, $k, $i + 1); // Recurse
                            $ul['class'] = 'lot lot:menu';
                            $li[1] = $ul;
                            if ($i < 0) {
                                $a[] = 'has:menu';
                            }
                        }
                        unset($v['tags']);
                        $li[1] = \_\lot\x\panel\Link($v, $k) . $ul;
                    }
                    \_\lot\x\panel\h\c($li[2], $v, $a);
                } else {
                    $li[1] = \_\lot\x\panel\Link(['title' => $v], $k);
                }
                $out[1] .= new \HTML($li);
            }
            $tags[] = 'count:' . $size;
        }
        \_\lot\x\panel\h\c($out[2], $in, $tags);
        return new \HTML($out);
    }
    function Page($in, $key) {}
    function Pager($in, $key) {
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
        $language = $GLOBALS['language'];
        $in['content'] = $pager($in['current'] ?? 1, $in['count'] ?? 0, $in['chunk'] ?? 20, $in['peek'] ?? 2, function($i) {
            extract($GLOBALS, \EXTR_SKIP);
            return $url . $PANEL['//'] . '/::g::' . $PANEL['path'] . '/' . $i;
        }, $language->first, $language->prev, $language->next, $language->last);
        $out = \_\lot\x\panel\content($in, $key);
        $out[0] = 'p';
        return $out;
    }
    function Pages($in, $key) {}
    function Tab($in, $key) {
        $out = [
            0 => $in[0] ?? 'section',
            1 => $in[1] ?? "",
            2 => $in[2] ?? []
        ];
        if (isset($in['content'])) {
            $out[1] .= \_\lot\x\panel\h\content($in['content']);
        } else if (isset($in['lot'])) {
            foreach ((new \Anemon($in['lot']))->sort([1, 'stack', 10], true) as $k => $v) {
                $out[1] .= \_\lot\x\panel\h\lot($in['lot']);
            }
        }
        \_\lot\x\panel\h\c($out[2], $in);
        return new \HTML($out);
    }
    function Tabs($in, $key) {
        $out = [
            0 => $in[0] ?? 'div',
            1 => $in[1] ?? "",
            2 => $in[2] ?? []
        ];
        if (isset($in['content'])) {
            $out[1] .= \_\lot\x\panel\h\content($in['content']);
        } else if (isset($in['lot'])) {
            $name = $in['name'] ?? $key;
            $nav = $section = [];
            $tags = ['lot', 'lot:tab', 'p'];
            $active = \Get::get('tab.' . $name) ?? $in['active'] ?? \array_keys($in['lot'])[0] ?? null;
            $size = 0;
            foreach ((new \Anemon($in['lot']))->sort([1, 'stack'], true) as $k => $v) {
                if (\is_array($v)) {
                    if ($k === $active) {
                        $v['tags'][] = 'is:active';
                    }
                    if (empty($v['url']) && empty($v['link'])) {
                        $v['url'] = $GLOBALS['url']->query('&', [
                            'tab' => [$name => $k]
                        ]);
                    } else {
                        $v['tags'][] = 'has:link';
                    }
                }
                $nav[$k] = $v;
                unset($nav[$k]['lot']); // Disable dropdown menu view
                $section[$k] = \_\lot\x\panel\Tab($v, $k);
                ++$size;
            }
            $out[1] = '<nav>' . \_\lot\x\panel\Bar_List(['lot' => $nav], $name) . '</nav>';
            $out[1] .= \implode("", $section);
        }
        $tags[] = 'count:' . $size;
        \_\lot\x\panel\h\c($out[2], $in, $tags);
        return new \HTML($out);
    }
    function Tasks($in, $key) {
        $tags = ['lot', 'lot:task'];
        $out = [
            0 => $in[0] ?? 'div',
            1 => $in[1] ?? "",
            2 => $in[2] ?? []
        ];
        if (isset($in['content'])) {
            $tags[] = 'count:1';
            $out[1] .= \_\lot\x\panel\h\content($in['content']);
        } else if (isset($in['lot'])) {
            $tags[] = 'count:' . \count(\array_filter($in['lot']));
            $out[1] .= \_\lot\x\panel\h\lot($in['lot']);
        }
        \_\lot\x\panel\h\c($out[2], $in, $tags);
        return new \HTML($out);
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
        return new \HTML([
            0 => 'div',
            1 => $title . $description . \_\lot\x\panel\h\content($in['content']),
            2 => ['class' => 'count:1 lot' . (isset($type) ? ' lot:' . \implode(' lot:', \step(\c2f($type))) : "")]
        ]);
    }
    function lot($in, $key) {
        $type = $in['type'] ?? null;
        $title = \_\lot\x\panel\h\title($in, 2);
        $description = \_\lot\x\panel\h\description($in);
        $out = [
            0 => $in[0] ?? 'div',
            1 => $in[1] ?? $title . $description,
            2 => \array_replace(['class' => 'count:' . \count(\array_filter($in['lot'] ?? [])) . ' lot' . (isset($type) ? ' lot:' . \implode(' lot:', \step(\c2f($type))) : "")], $in[2] ?? [])
        ];
        if (isset($in['lot'])) {
            $out[1] .= \_\lot\x\panel\h\lot($in['lot']);
        }
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
        if ($type = isset($in['type']) ? \strtr($in['type'], '.-', "\\_") : null) {
            if (\function_exists($fn = \rtrim(__NAMESPACE__ . "\\panel\\" . $type, "\\"))) {
                $out .= \call_user_func($fn, $in, $key);
            } else if (isset($in['content'])) {
                if (\function_exists($fn = \rtrim(__NAMESPACE__ . "\\panel\\content\\" . $type, "\\"))) {
                    $out .= \call_user_func($fn, $in, $key);
                } else {
                    $out .= \_\lot\x\panel\content($in, $key);
                }
            } else if (isset($in['lot'])) {
                if (\function_exists($fn = \rtrim(__NAMESPACE__ . "\\panel\\lot\\" . $type, "\\"))) {
                    $out .= \call_user_func($fn, $in, $key);
                } else {
                    $out .= \_\lot\x\panel\lot($in, $key);
                }
            } else {
                $out .= \_\lot\x\panel\abort($in, $key, $fn);
            }
        }
        return $out;
    }
}

namespace {
    require __DIR__ . DS . 'f' . DS . 'content.php';
    require __DIR__ . DS . 'f' . DS . 'field.php';
    require __DIR__ . DS . 'f' . DS . 'form.php';
    require __DIR__ . DS . 'f' . DS . 'h.php';
    require __DIR__ . DS . 'f' . DS . 'lot.php';
    require __DIR__ . DS . 'f' . DS . 'tasks.php';
}