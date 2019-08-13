<?php

namespace {}

namespace _\lot\x {
    function panel($in, $key, $type) {
        $out = "";
        $type = \strtr($type, '.-', "\\_");
        if (\function_exists($fn = \rtrim(__NAMESPACE__ . "\\panel\\" . $type, "\\"))) {
            $out .= \call_user_func($fn, $in, $key, $type);
        } else if (isset($in['content'])) {
            if (\function_exists($fn = \rtrim(__NAMESPACE__ . "\\panel\\content\\" . $type, "\\"))) {
                $out .= \call_user_func($fn, $in['content'], $key, $type);
            } else {
                $out .= panel\content($in['content'], $key, $type);
            }
        } else if (isset($in['lot']) && \is_array($in['lot'])) {
            if (\function_exists($fn = \rtrim(__NAMESPACE__ . "\\panel\\lot\\" . $type, "\\"))) {
                $out .= \call_user_func($fn, $in['lot'], $key, $type);
            } else {
                $out .= panel\lot($in['lot'], $key, $type);
            }
        } else {
            $out .= panel\abort($in, $key, $fn);
        }
        return $out;
    }
}

namespace _\lot\x\panel {
    function a($in) {
        $out = [
            0 => $in[0] ?? 'a',
            1 => $in[1] ?? "",
            2 => []
        ];
        if ($out[1] === "") {
            $icon = \_\lot\x\panel\h\icon($in['icon'] ?? [null, null]);
            if ($title = $in['title'] ?? "") {
                $title = '<span>' . $title . '</span>';
            }
            $out[1] = $icon[0] . $title . $icon[1];
        }
        if (!$href = $in['link'] ?? $in['url'] ?? \_\lot\x\panel\h\url($in['/'] ?? null)) {
            $in['tags'][] = 'disabled';
        }
        $out[2] = \_\lot\x\panel\h\c($in);
        $out[2]['href'] = $href === null ? 'javascript:;' : $href;
        $out[2]['target'] = $in[2]['target'] ?? (isset($in['link']) ? '_blank' : null);
        return new \HTML($out);
    }
    function abort($in, $key, $fn) {
        if (\defined('DEBUG') && DEBUG) {
            \Guard::abort('Unable to convert data <code>' . \htmlspecialchars(\strtr(\json_encode($in, \JSON_PRETTY_PRINT), [' ' => '&nbsp;', "\n" => '<br>'])) . '</code> because function <code>' . $fn . '</code> does not exist.');
        }
    }
    function button($in, $key, $type) {
        $out = \_\lot\x\panel\a($in);
        $out[0] = 'button';
        $out['class'] = 'button';
        $out['disabled'] = isset($in['active']) && !$in['active'];
        $out['name'] = $in['name'] ?? $key;
        $out['type'] = 'button';
        $out['value'] = $in['value'] ?? null;
        unset($out['href'], $out['target']);
        return $out;
    }
    function content($in, $key, $type) {
        return new \HTML([
            0 => 'div',
            1 => \is_array($in) ? new \HTML($in) : $in,
            2 => ['class' => 'content' . ($type !== '#' ? ' content:' . \implode(' content:', \step(\c2f($type))) : "")]
        ]);
    }
    function fields($in) {
        $in['tags'][] = 'lot';
        $in['tags'][] = 'lot:field';
        $out = [
            0 => $in[0] ?? 'div',
            1 => $in[1] ?? "",
            2 => $in[2] ?? []
        ];
        $append = "";
        if (isset($in['content'])) {
            $out[1] .= \is_array($in['content']) ? new \HTML($in['content']) : $in['content'];
        } else if (isset($in['lot']) && \is_array($in['lot'])) {
            foreach (\Anemon::from($in['lot'])->sort([1, 'stack', 10], true) as $k => $v) {
                $type = $v['type'] ?? '#';
                if ($type === 'field.hidden') {
                    $append .= \_\lot\x\panel($v, $k, $type);
                    continue;
                }
                $out[1] .= \_\lot\x\panel($v, $k, $type);
            }
            $out[1] .= $append;
        }
        $out[2] = \_\lot\x\panel\h\c($in);
        return new \HTML($out);
    }
    function field($in, $key) {
        $in['tags'][] = 'field';
        $in['tags'][] = 'p';
        $id = $in['id'] ?? \uniqid();
        $in[2]['id'] = $in[2]['id'] ?? $id . '.0';
        $out = [
            0 => $in[0] ?? 'div',
            1 => $in[1] ?? "",
            2 => $in[2] ?? []
        ];
        if (isset($in['title'])) {
            $out[1] .= '<label for="' . $id . '">' . \w($in['title'] ?? $key, 'abbr,b,br,cite,code,del,dfn,em,i,ins,kbd,mark,q,span,strong,sub,sup,svg,time,u,var') . '</label>';
        }
        if (isset($in['content'])) {
            $out[1] .= '<div>' . (\is_array($in['content']) ? new \HTML($in['content']) : $in['content']) . '</div>';
        } else if (isset($in['lot']) && \is_array($in['lot'])) {
            // TODO
        }
        $out[2] = \_\lot\x\panel\h\c($in);
        return new \HTML($out);
    }
    function form($in, $key, $type) {
        $out = [
            0 => $in[0] ?? 'form',
            1 => $in[1] ?? "",
            2 => []
        ];
        if (isset($in['active']) && empty($in['active'])) {
            $out[0] = false;
        }
        if (isset($in['content'])) {
            $out[1] .= \is_array($in['content']) ? new \HTML($in['content']) : $in['content'];
        } else if (isset($in['lot']) && \is_array($in['lot'])) {
            foreach (\Anemon::from($in['lot'])->sort([1, 'stack', 10], true) as $k => $v) {
                $out[1] .= \_\lot\x\panel($v, $k, $v['type'] ?? '#');
            }
        }
        $href = $in['link'] ?? $in['url'] ?? \_\lot\x\panel\h\url($in['/'] ?? null);
        $out[2] = \_\lot\x\panel\h\c($in);
        $out[2]['action'] = $href;
        $out[2]['name'] = $in['name'] ?? $key;
        return new \HTML($out);
    }
    function lot($in, $key, $type) {
        $out = [
            0 => $in[0] ?? 'div',
            1 => $in[1] ?? "",
            2 => \array_replace(['class' => 'lot' . ($type !== '#' ? ' lot:' . \implode(' lot:', \step(\c2f($type))) : "")], $in[2] ?? [])
        ];
        if (!empty($in) && \is_array($in)) {
            foreach (\Anemon::from($in)->sort([1, 'stack', 10], true) as $k => $v) {
                $out[1] .= \_\lot\x\panel($v, $k, $v['type'] ?? '#');
            }
        }
        return new \HTML($out);
    }
    function tab($in, $key, $type) {
        $out = [
            0 => $in[0] ?? 'div',
            1 => $in[1] ?? "",
            2 => $in[2] ?? []
        ];
        if (isset($in['content'])) {
            $out[1] .= \is_array($in['content']) ? new \HTML($in['content']) : $in['content'];
        } else if (isset($in['lot']) && \is_array($in['lot'])) {
            $name = $in['name'] ?? $key;
            $nav = [];
            $section = [];
            $in['tags'][] = 'lot';
            $in['tags'][] = 'lot:tab';
            $active = \Get::get('tab.' . $name) ?? $in['active'] ?? \array_keys($in['lot'])[0] ?? null;
            foreach (\Anemon::from($in['lot'])->sort([1, 'stack'], true) as $k => $v) {
                if (\is_array($v)) {
                    if ($k === $active) {
                        $v['tags'][] = 'active';
                    }
                    $v['/'] = '?tab[' . \urlencode($name) . ']=' . \urlencode($k);
                }
                $nav[$k] = $v;
                unset($nav[$k]['lot']); // Disable dropdown menu view
                $section[$k] = \_\lot\x\panel($v, $k, 'tab.pane');
            }
            $out[1] = '<nav>' . \_\lot\x\panel(['lot' => $nav], $name, 'nav.ul') . '</nav>';
            $out[1] .= \implode("", $section);
        }
        $out[2] = \_\lot\x\panel\h\c($in);
        return new \HTML($out);
    }
    function task($in, $key, $type) {
        $out = [
            0 => $in[0] ?? 'div',
            1 => $in[1] ?? "",
            2 => $in[2] ?? []
        ];
        if (isset($in['content'])) {
            $out[1] .= \is_array($in['content']) ? new \HTML($in['content']) : $in['content'];
        } else if (isset($in['lot']) && \is_array($in['lot'])) {
            $in['tags'][] = 'lot';
            $in['tags'][] = 'lot:task';
            foreach (\Anemon::from($in['lot'])->sort([1, 'stack'], true) as $k => $v) {
                if (isset($v['url']) || isset($v['link']) || isset($v['/'])) {
                    $out[1] .= \_\lot\x\panel\a($v);
                } else {
                    $out[1] .= \_\lot\x\panel\button($v, $k, $v['type'] ?? '#');
                }
            }
        }
        $out[2] = \_\lot\x\panel\h\c($in);
        return new \HTML($out);
    }
}

namespace _\lot\x\panel\form {
    function get($in, $key, $type) {
        $out = \_\lot\x\panel\form($in, $key, $type);
        $out['method'] = 'get';
        return $out;
    }
    function post($in, $key, $type) {
        $out = \_\lot\x\panel\form($in, $key, $type);
        $out['method'] = 'post';
        return $out;
    }
}

// [field]
namespace _\lot\x\panel\field {
    function content($in, $key, $type) {
        $out = \_\lot\x\panel\h\field($in, $key, $type);
        $out['content'][2]['class'] = \trim('textarea ' . ($out['content'][2]['class'] ?? ""));
        return \_\lot\x\panel\field($out, $key);
    }
    function hidden($in, $key, $type) {
        return new \HTML([
            0 => 'input',
            1 => false,
            2 => [
                'id' => $in['id'] ?? 'f:' . \dechex(\crc32($key)),
                'name' => $in['name'] ?? $key,
                'type' => 'hidden',
                'value' => $in['value'] ?? null
            ]
        ]);
    }
    function item($in, $key, $type) {
        if (isset($in['lot'])) {
            $out = \_\lot\x\panel\h\field($in, $key, $type);
            $o = [];
            $value = $in['value'] ?? null;
            if (\count($in['lot']) > 5) {
                $out['content'][0] = 'select';
                unset($out['placeholder'], $out['value']);
                foreach ($in['lot'] as $k => $v) {
                    $oo = new \HTML(['option', "", [
                        'selected' => $value !== null && (string) $value === (string) $k,
                        'value' => $k
                    ]]);
                    if (\is_array($v)) {
                        $oo[1] = $t = \strip_tags($v['title'] ?? $k);
                        $oo['disabled'] = isset($v['active']) && !$v['active'];
                    } else {
                        $oo[1] = $t = $v;
                    }
                    $o[$t] = $oo;
                }
                \asort($o);
                $out['content'][1] = \implode("", \array_values($o));
                $out['content'][2]['class'] = \trim('select ' . ($out['content'][2]['class'] ?? ""));
            } else {
                $out['content'][0] = 'div';
                unset($out['name'], $out['placeholder'], $out['value']);
                $n = $in['name'] ?? $key;
                foreach ($in['lot'] as $k => $v) {
                    $oo = new \HTML(['input', false, [
                        'checked' => $value !== null && (string) $value === (string) $k,
                        'name' => $n,
                        'type' => 'radio',
                        'value' => $k
                    ]]);
                    if (\is_array($v)) {
                        $t = \w($v['title'] ?? $k, 'abbr,b,br,cite,code,del,dfn,em,i,ins,kbd,mark,q,span,strong,sub,sup,svg,time,u,var');
                        $oo['disabled'] = isset($v['active']) && !$v['active'];
                    } else {
                        $t = $v;
                    }
                    $o[$t] = '<label>' . $oo . '&nbsp;<span>' . $t . '</span></label>';
                }
                \ksort($o);
                $out['content'][1] = \implode("", \array_values($o));
                $out['content'][2]['class'] = 'lot lot:item';
            }
            return \_\lot\x\panel\field($out, $key);
        }
        return \_\lot\x\panel\field\text($in, $key, $type);
    }
    function items($in, $key, $type) {
        
    }
    function source($in, $key, $type) {
        $out = \_\lot\x\panel\h\field($in, $key, $type);
        $out['content'][2]['class'] = \trim('textarea code ' . ($out['content'][2]['class'] ?? ""));
        $out['content'][2]['data-type'] = $in['syntax'] ?? null;
        return \_\lot\x\panel\field($out, $key);
    }
    function text($in, $key, $type) {
        $out = \_\lot\x\panel\h\field($in, $key, $type);
        $out['content'][0] = 'input';
        $out['content'][1] = false;
        $out['content'][2]['class'] = \trim('input ' . ($out['content'][2]['class'] ?? ""));
        $out['content'][2]['type'] = 'text';
        $out['content'][2]['value'] = $in['value'] ?? null;
        return \_\lot\x\panel\field($out, $key);
    }
    function toggle($in, $key, $type) {
        
    }
}

// [h]: Helper function(s)
namespace _\lot\x\panel\h {
    function c($in) {
        $a = \implode(' ', (array) ($in[2]['class'] ?? []));
        $b = \implode(' ', (array) ($in['tags'] ?? []));
        $c = \implode(' ', \array_unique(\array_filter(\array_merge(\explode(' ', $a), \explode(' ', $b)))));
        $in[2]['class'] = $c !== "" ? $c : null;
        return $in[2];
    }
    function field($in, $key, $type) {
        $in['id'] = $in['id'] ?? 'f:' . \dechex(\crc32($key));
        $i = [
            0 => 'textarea',
            1 => \htmlspecialchars($in['value'] ?? ""),
            2 => [
                'class' => "",
                'disabled' => isset($in['active']) && !$in['active'],
                'id' => $in['id'],
                'name' => $in['name'] ?? $key,
                'pattern' => $in['pattern'] ?? null,
                'placeholder' => $in['placeholder'] ?? null,
                'readonly' => !empty($in['read-only']),
                'required' => !empty($in['required'])
            ]
        ];
        $style = "";
        if (isset($in['height']) && $in['height'] !== false) {
            if ($in['height'] === true) {
                $i[2]['class'] .= ' height';
            } else {
                $style .= 'height:' . (\is_numeric($in['height']) ? $in['height'] . 'px' : $in['height']) . ';';
            }
        }
        if (isset($in['width']) && $in['width'] !== false) {
            if ($in['width'] === true) {
                $i[2]['class'] .= ' width';
            } else {
                $style .= 'width:' . (\is_numeric($in['width']) ? $in['width'] . 'px' : $in['width']) . ';';
            }
        }
        $i[2]['class'] = isset($i[2]['class']) && $i[2]['class'] !== "" ? \trim($i[2]['class']) : null;
        $i[2]['style'] = $style !== "" ? $style : null;
        $in['content'] = $i;
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
    function link($value) {
        return url($value, $in);
    }
    function url($value) {
        return \is_string($value) ? \URL::long($value, false) : null;
    }
}

namespace _\lot\x\panel\nav {
    function ul($in, $key, $type, int $i = 0) {
        $out = [
            0 => $in[0] ?? 'ul',
            1 => $in[1] ?? "",
            2 => $in[2] ?? []
        ];
        if (isset($in['content'])) {
            $out[1] .= \is_array($in['content']) ? new \HTML($in['content']) : $in['content'];
        } else if (isset($in['lot'])&& \is_array($in['lot'])) {
            foreach (\Anemon::from($in['lot'])->sort([1, 'stack', 10], true) as $k => $v) {
                $li = [
                    0 => 'li',
                    1 => $v[1] ?? "",
                    2 => $v[2] ?? []
                ];
                if (\is_array($v)) {
                    $v['icon'] = \_\lot\x\panel\h\icon($v['icon'] ?? [null, null]);
                    if (!empty($v['lot']) && (!empty($v['caret']) || !\array_key_exists('caret', $v))) {
                        $v['icon'][1] = '<svg class="caret" viewBox="0 0 24 24"><path d="' . ($v['caret'] ?? ($i === 0 ? 'M7,10L12,15L17,10H7Z' : 'M10,17L15,12L10,7V17Z')) . '"></path></svg>';
                    }
                    $ul = "";
                    if (!isset($v[1])) {
                        if (!empty($v['lot']) && (!\array_key_exists(0, $v) || \is_string($v[0]))) {
                            $ul = ul($v, $k, $type, $i + 1); // Recurse
                            $ul['class'] = 'lot lot:menu';
                            $li[1] = $ul;
                            if ($i === 0) {
                                $v['tags'][] = 'drop';
                            }
                        }
                        $li[2] = \_\lot\x\panel\h\c($v);
                        unset($v['tags']);
                        $li[1] = \_\lot\x\panel\a($v) . $ul;
                    }
                } else {
                    $li[1] = \_\lot\x\panel\a(['title' => $v]);
                }
                $out[1] .= new \HTML($li);
            }
        }
        $out[2] = \_\lot\x\panel\h\c($in);
        return new \HTML($out);
    }
}

namespace _\lot\x\panel\tab {
    function pane($in, $key, $type) {
        $out = [
            0 => $in[0] ?? 'section',
            1 => $in[1] ?? "",
            2 => \array_replace(['id' => $in['id'] ?? $key], $in[2] ?? [])
        ];
        if (isset($in['content'])) {
            $out[1] .= \is_array($in['content']) ? new \HTML($in['content']) : $in['content'];
        } else if (isset($in['lot']) && \is_array($in['lot'])) {
            foreach (\Anemon::from($in['lot'])->sort([1, 'stack', 10], true) as $k => $v) {
                $out[1] .= \_\lot\x\panel($v, $k, $v['type'] ?? '#');
            }
        }
        $out[2] = \_\lot\x\panel\h\c($in);
        return new \HTML($out);
    }
}