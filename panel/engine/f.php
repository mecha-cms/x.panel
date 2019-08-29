<?php

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
        if (\defined("\\DEBUG") && \DEBUG) {
            \Guard::abort('Unable to convert data <code>' . \htmlspecialchars(\strtr(\json_encode($in, \JSON_PRETTY_PRINT), [' ' => '&nbsp;', "\n" => '<br>'])) . '</code> because function <code>' . $fn . '</code> does not exist.');
        }
    }
    function button($in, $key, $type) {
        $out = \_\lot\x\panel\a($in);
        $out[0] = 'button';
        $out['class'] = 'button';
        $out['disabled'] = isset($in['active']) && !$in['active'];
        $out['name'] = $in['name'] ?? $key;
        $out['type'] = $in['type'] ?? 'button';
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
    function field($in, $key) {
        $in['tags'][] = 'field';
        $in['tags'][] = 'p';
        if (isset($in['type'])) {
            $in['tags'][] = \strtr($in['type'], '.', ':');
        }
        $id = $in['id'] ?? \uniqid();
        $description = \w($in['description'] ?? "", 'abbr,b,br,cite,code,del,dfn,em,i,img,ins,kbd,mark,q,span,strong,sub,sup,svg,time,u,var');
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
            $out[1] .= '<div>' . (\is_array($in['content']) ? new \HTML($in['content']) : $in['content']) . ($description !== "" ? '<span class="hint">' . $description . '</span>' : "") . '</div>';
        }
        $out[2] = \_\lot\x\panel\h\c($in);
        return new \HTML($out);
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
            foreach ((new \Anemon($in['lot']))->sort([1, 'stack', 10], true) as $k => $v) {
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
            foreach ((new \Anemon($in['lot']))->sort([1, 'stack', 10], true) as $k => $v) {
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
            foreach ((new \Anemon($in))->sort([1, 'stack', 10], true) as $k => $v) {
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
            $size = 0;
            foreach ((new \Anemon($in['lot']))->sort([1, 'stack'], true) as $k => $v) {
                if (\is_array($v)) {
                    if ($k === $active) {
                        $v['tags'][] = 'active';
                    }
                    $v['/'] = '?tab[' . \urlencode($name) . ']=' . \urlencode($k);
                }
                $nav[$k] = $v;
                unset($nav[$k]['lot']); // Disable dropdown menu view
                $section[$k] = \_\lot\x\panel($v, $k, 'tab.pane');
                ++$size;
            }
            $out[1] = '<nav>' . \_\lot\x\panel(['lot' => $nav], $name, 'nav.ul') . '</nav>';
            $out[1] .= \implode("", $section);
        }
        $in['tags'][] = 'size-' . $size;
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
            foreach ((new \Anemon($in['lot']))->sort([1, 'stack'], true) as $k => $v) {
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

namespace {
    require __DIR__ . DS . 'f' . DS . 'field.php';
    require __DIR__ . DS . 'f' . DS . 'form.php';
    require __DIR__ . DS . 'f' . DS . 'h.php';
    require __DIR__ . DS . 'f' . DS . 'nav.php';
    require __DIR__ . DS . 'f' . DS . 'tab.php';
}