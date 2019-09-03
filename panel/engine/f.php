<?php

namespace _\lot\x\panel {
    function Bar($in, $key) {
        if (isset($in['lot'])) {
            foreach ($in['lot'] as &$v) {
                $type = $v['type'] ?? null;
                if ($type !== 'Bar' && \strpos($type, 'Bar_') !== 0) {
                    // Add prefix to `type`
                    $v['type'] = $type = 'Bar_' . $type;
                }
            }
            unset($v);
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
    function Button_Button($in, $key) {
        $out = \_\lot\x\panel\Button($in, $key);
        $out['type'] = 'button';
        return $out;
    }
    function Button_Link($in, $key) {
        $in['tags'][] = 'button';
        $out = \_\lot\x\panel\Link($in, $key);
        $content = $out[1];
        $content['class'] = \str_replace('button ', "", $content['class']);
        $out[1] = $content;
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
        $in['tags'][] = 'field';
        $in['tags'][] = 'p';
        if (isset($in['type'])) {
            $in['tags'][] = \strtr(\c2f($in['type'], '-', '.'), '_', ':');
        }
        $id = $in['id'] ?? \uniqid();
        $in[2]['id'] = $in[2]['id'] ?? $id . '.0';
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
            foreach ((new \Anemon($in['lot']))->sort([1, 'stack', 10], true) as $k => $v) {
                $out[1] .= \_\lot\x\panel($v, $k);
            }
        }
        $out[2]['class'] = \_\lot\x\panel\h\c($in);
        return new \HTML($out);
    }
    function Fields($in) {
        $in['tags'][] = 'lot';
        $in['tags'][] = 'lot:field';
        $out = [
            0 => $in[0] ?? 'div',
            1 => $in[1] ?? "",
            2 => $in[2] ?? []
        ];
        $append = "";
        if (isset($in['content'])) {
            $out[1] .= \_\lot\x\panel\h\content($in['content']);
        } else if (isset($in['lot'])) {
            foreach ((new \Anemon($in['lot']))->sort([1, 'stack', 10], true) as $k => &$v) {
                $type = $v['type'] ?? null;
                if ($type !== 'Field' && \strpos($type, 'Field_') !== 0) {
                    // Add prefix to `type`
                    $v['type'] = $type = 'Field_' . $type;
                }
                if (\function_exists($fn = \rtrim(__NAMESPACE__ . "\\" . $type, "\\"))) {
                    if ($type !== 'Field_Hidden') {
                        $out[1] .= \call_user_func($fn, $v, $k);
                    } else {
                        $append .= \_\lot\x\panel\Field_Hidden($v, $k);
                    }
                } else {
                    $append .= \_\lot\x\panel\Field_($v, $k); // Unknown field type
                }
                unset($v);
            }
            $out[1] .= $append;
        }
        $out[2]['class'] = \_\lot\x\panel\h\c($in);
        return new \HTML($out);
    }
    function File($in, $key) {
        $name = $x = null;
        $task = $in['task'] ?? null;
        if ($path = $in['path'] ?? null) {
            $name = \basename($path);
            $x = \pathinfo($path, \PATHINFO_EXTENSION);
        }
        if (isset($in['title'])) {
            $name = $in['title'];
        }
        if (isset($in['x'])) {
            $x = $in['x'];
        }
        $in['tags'][] = 'file';
        if ($x) {
            foreach ((array) $x as $k => $v) {
                $in['tags'][] = 'file:' . $k;
            }
        }
        if (isset($in['active']) && !$in['active']) {
            $in['tags'][] = 'disabled';
        }
        $out = [
            0 => 'li',
            1 => "",
            2 => []
        ];
        $out[2]['class'] = \_\lot\x\panel\h\c($in);
        $out[1] .= '<h3>' . \_\lot\x\panel\Link([
            'description' => $in['description'] ?? ($path ? (new \File($path))->size : null),
            'link' => $in['link'] ?? null,
            'title' => $name,
            'url' => $in['url'] ?? null
        ], $key) . '</h3>';
        if (\is_array($task)) {
            $out[1] .= \_\lot\x\panel\Task\Link([
                0 => 'p',
                'lot' => $task,
                'tags' => ['icons']
            ], 0);
        }
        return new \HTML($out);
    }
    function Files($in, $key) {
        $in['tags'][] = 'lot';
        $in['tags'][] = 'lot:file';
        $task = $in['task'] ?? null;
        $out = [
            0 => 'ul',
            1 => "",
            2 => []
        ];
        $out[2]['class'] = \_\lot\x\panel\h\c($in);
        $a = [[], []];
        $source = isset($in['from']) && \is_dir($in['from']);
        if ($raw = isset($in['lot'])) {
            foreach ($in['lot'] as $k => $v) {
                $a[\is_string($v) && \is_file($v) ? 1 : 0][$k] = $v;
            }
        } else if ($source) {
            foreach (\g($in['from']) as $k => $v) {
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
        if (isset($a[$current - 1])) {
            if ($source) {
                \array_unshift($a[$current - 1], [
                    'title' => '..',
                    'type' => 'Folder',
                    'url' => '/' // TODO
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
                        'url' => $f ? null : '/' // TODO
                    ];
                }
                $t = (array) ($v['task'] ?? []);
                if (\is_callable($task)) {
                    $v['task'] = \array_replace((array) \call_user_func($task, $v), $t);
                } else if (\is_array($task)) {
                    $v['task'] = \array_replace($task, $t);
                }
                if (!empty($v['current']) || isset($v['path']) && (
                    isset($_SESSION['panel']['file'][$v['path']]) ||
                    isset($_SESSION['panel']['folder'][$v['path']])
                )) {
                    $v['tags'][] = 'active';
                }
                $out[1] .= \_\lot\x\panel($v, $k);
            }
        }
        return new \HTML($out);
    }
    function Folder($in, $key) {
        $name = null;
        $task = $in['task'] ?? null;
        if ($path = $in['path'] ?? null) {
            $name = \basename($path);
        }
        if (isset($in['title'])) {
            $name = $in['title'];
        }
        $in['tags'][] = 'folder';
        if (isset($in['active']) && !$in['active']) {
            $in['tags'][] = 'disabled';
        }
        $out = [
            0 => 'li',
            1 => "",
            2 => []
        ];
        $out[2]['class'] = \_\lot\x\panel\h\c($in);
        $out[1] .= '<h3>' . \_\lot\x\panel\Link([
            'description' => $in['description'] ?? $GLOBALS['language']->doEnter,
            'link' => $in['link'] ?? null,
            'title' => $name,
            'url' => $in['url'] ?? null
        ], $key) . '</h3>';
        if (\is_array($task)) {
            $out[1] .= \_\lot\x\panel\Task\Link([
                0 => 'p',
                'lot' => $task,
                'tags' => ['icons']
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
            foreach ((new \Anemon($in['lot']))->sort([1, 'stack', 10], true) as $k => $v) {
                $out[1] .= \_\lot\x\panel($v, $k, $v['type'] ?? '#');
            }
        }
        $href = $in['link'] ?? $in['url'] ?? null;
        $out[2]['class'] = \_\lot\x\panel\h\c($in);
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
        $href = (string) ($in['link'] ?? $in['url'] ?? \P);
        if ($href === \P || (isset($in['active']) && !$in['active'])) {
            $in['tags'][] = 'disabled';
        }
        $out[2]['class'] = \_\lot\x\panel\h\c($in);
        $out[2]['href'] = $href === \P ? '#' : $href;
        $out[2]['target'] = $in[2]['target'] ?? (isset($in['link']) ? '_blank' : null);
        $out[2]['title'] = $in['description'] ?? null;
        return new \HTML($out);
    }
    function Menu($in, $key, int $i = 0) {
        $out = [
            0 => $in[0] ?? 'ul',
            1 => $in[1] ?? "",
            2 => $in[2] ?? []
        ];
        if (isset($in['content'])) {
            $out[1] .= \_\lot\x\panel\h\content($in['content']);
        } else if (isset($in['lot'])) {
            foreach ((new \Anemon($in['lot']))->sort([1, 'stack', 10], true) as $k => $v) {
                if (!empty($v['hidden'])) {
                    continue;
                }
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
                    if (!isset($v[1])) {
                        if (!empty($v['lot']) && (!\array_key_exists(0, $v) || \is_string($v[0]))) {
                            $ul = \_\lot\x\panel\Menu($v, $k, $i + 1); // Recurse
                            $ul['class'] = 'lot lot:menu';
                            $li[1] = $ul;
                            if ($i < 0) {
                                $v['tags'][] = 'drop';
                            }
                        }
                        $li[2]['class'] = \_\lot\x\panel\h\c($v);
                        unset($v['tags']);
                        $li[1] = \_\lot\x\panel\Link($v, $k) . $ul;
                    }
                } else {
                    $li[1] = \_\lot\x\panel\Link(['title' => $v], $k);
                }
                $out[1] .= new \HTML($li);
            }
        }
        $out[2]['class'] = \_\lot\x\panel\h\c($in);
        return new \HTML($out);
    }
    function Page($in, $key) {}
    function Pages($in, $key) {}
    function Tab($in, $key) {
        $out = [
            0 => $in[0] ?? 'section',
            1 => $in[1] ?? "",
            2 => \array_replace(['id' => $in['id'] ?? $key], $in[2] ?? [])
        ];
        if (isset($in['content'])) {
            $out[1] .= \_\lot\x\panel\h\content($in['content']);
        } else if (isset($in['lot'])) {
            foreach ((new \Anemon($in['lot']))->sort([1, 'stack', 10], true) as $k => $v) {
                $out[1] .= \_\lot\x\panel($v, $k);
            }
        }
        $out[2]['class'] = \_\lot\x\panel\h\c($in);
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
            $nav = [];
            $section = [];
            $in['tags'][] = 'p';
            $in['tags'][] = 'lot';
            $in['tags'][] = 'lot:tab';
            $active = \Get::get('tab.' . $name) ?? $in['active'] ?? \array_keys($in['lot'])[0] ?? null;
            $size = 0;
            foreach ((new \Anemon($in['lot']))->sort([1, 'stack'], true) as $k => $v) {
                if (\is_array($v)) {
                    if ($k === $active) {
                        $v['tags'][] = 'active';
                    }
                    if (!isset($v['link'])) {
                        $v['link'] = $GLOBALS['url']->query('&', [
                            'tab' => [
                                $name => $k
                            ]
                        ]);
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
        $in['tags'][] = 'size-' . $size;
        $out[2]['class'] = \_\lot\x\panel\h\c($in);
        return new \HTML($out);
    }
    function Task($in, $key) {
        $in['tags'][] = 'lot';
        $in['tags'][] = 'lot:task';
        $out = [
            0 => $in[0] ?? 'div',
            1 => $in[1] ?? "",
            2 => $in[2] ?? []
        ];
        if (isset($in['content'])) {
            $out[1] .= \_\lot\x\panel\h\content($in['content']);
        } else if (isset($in['lot'])) {
            foreach ((new \Anemon($in['lot']))->sort([1, 'stack'], true) as $k => $v) {
                $out[1] .= \_\lot\x\panel($v, $k);
            }
        }
        $out[2]['class'] = \_\lot\x\panel\h\c($in);
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
            2 => ['class' => 'content' . (isset($type) ? ' content:' . \implode(' content:', \step(\c2f($type))) : "")]
        ]);
    }
    function lot($in, $key) {
        $type = $in['type'] ?? null;
        $title = \_\lot\x\panel\h\title($in, 2);
        $description = \_\lot\x\panel\h\description($in);
        $out = [
            0 => $in[0] ?? 'div',
            1 => $in[1] ?? $title . $description,
            2 => \array_replace(['class' => 'lot' . (isset($type) ? ' lot:' . \implode(' lot:', \step(\c2f($type))) : "")], $in[2] ?? [])
        ];
        if (isset($in['lot'])) {
            foreach ((new \Anemon($in['lot']))->sort([1, 'stack', 10], true) as $k => $v) {
                $out[1] .= \_\lot\x\panel($v, $k);
            }
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
    require __DIR__ . DS . 'f' . DS . 'button.php';
    require __DIR__ . DS . 'f' . DS . 'content.php';
    require __DIR__ . DS . 'f' . DS . 'field.php';
    require __DIR__ . DS . 'f' . DS . 'form.php';
    require __DIR__ . DS . 'f' . DS . 'h.php';
    require __DIR__ . DS . 'f' . DS . 'lot.php';
    require __DIR__ . DS . 'f' . DS . 'task.php';
}