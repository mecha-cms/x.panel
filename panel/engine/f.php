<?php

namespace {}

namespace _\lot\x {
    function panel($in, $k, $type) {
        $out = "";
        $type = \strtr($type, '.-', "\\_");
        if (\function_exists($fn = \rtrim(__NAMESPACE__ . "\\panel\\type\\" . $type, "\\"))) {
            $out .= \call_user_func($fn, $in, $k, $type);
        } else if (isset($in['content'])) {
            if (\function_exists($fn = \rtrim(__NAMESPACE__ . "\\panel\\content\\" . $type, "\\"))) {
                $out .= \call_user_func($fn, $in['content'], $k, $type);
            } else {
                $out .= panel\content($in['content'], $k, $type);
            }
        } else if (isset($in['lot'])) {
            if (\function_exists($fn = \rtrim(__NAMESPACE__ . "\\panel\\lot\\" . $type, "\\"))) {
                $out .= \call_user_func($fn, $in['lot'], $k, $type);
            } else {
                $out .= panel\lot($in['lot'], $k, $type);
            }
        } else {
            $out .= panel\type($in, $k, $fn);
        }
        return $out;
    }
}

namespace _\lot\x\panel {
    function type($in, $k, $fn) {
        \Guard::abort('Unable to convert data <code>' . \json_encode($in) . '</code> because function <code>' . $fn . '</code> does not exist.');
    }
    function content($in, $k, $type) {
        return new \HTML([
            0 => 'div',
            1 => \is_array($in) ? new \HTML($in) : $in,
            2 => [
                'class' => 'content' . ($type !== "" ? ' content:' . \c2f($type) : "")
            ]
        ]);
    }
    function lot($in, $k, $type) {
        $out = [
            0 => 'div',
            1 => "",
            2 => [
                'class' => 'lot' . ($type !== "" ? ' lot:' . \c2f($type) : "")
            ]
        ];
        if (!empty($in) && \is_array($in)) {
            foreach (\Anemon::from($in)->sort([1, 'stack', 10], true) as $k => $v) {
                $out[1] .= \call_user_func(__NAMESPACE__, $v, $k, $v['type'] ?? '#');
            }
        }
        return new \HTML($out);
    }
}

// [content]
namespace _\lot\x\panel\content {
    function desk($in, $k, $type) {
        $out = \call_user_func(__NAMESPACE__, $in, $k, $type);
        $out['class'] .= ' desk';
        return $out;
    }
    function li($in, $k, $type) {
        $out = \call_user_func(__NAMESPACE__, $in, $k, $type);
        $out[0] = 'li';
        return $out;
    }
    function nav($in, $k, $type) {
        $out = \call_user_func(__NAMESPACE__, $in, $k, $type);
        $out[0] = 'nav';
        $out['class'] .= ' nav';
        return $out;
    }
    function ol($in, $k, $type) {
        $out = \call_user_func(__NAMESPACE__, $in, $k, $type);
        $out[0] = 'ol';
        return $out;
    }
    function ul($in, $k, $type) {
        $out = \call_user_func(__NAMESPACE__, $in, $k, $type);
        $out[0] = 'ul';
        return $out;
    }
}

// [lot]
namespace _\lot\x\panel\lot {
    function desk($in, $k, $type) {
        $out = \call_user_func(__NAMESPACE__, $in, $k, $type);
        $out['class'] .= ' desk';
        return $out;
    }
    function li($in, $k, $type) {
        $out = \call_user_func(__NAMESPACE__, $in, $k, $type);
        $out[0] = 'li';
        return $out;
    }
    function nav($in, $k, $type) {
        $out = \call_user_func(__NAMESPACE__, $in, $k, $type);
        $out[0] = 'nav';
        $out['class'] .= ' nav';
        return $out;
    }
    function ol($in, $k, $type) {
        $out = \call_user_func(__NAMESPACE__, $in, $k, $type);
        $out[0] = 'ol';
        return $out;
    }
    function ul($in, $k, $type) {
        $out = \call_user_func(__NAMESPACE__, $in, $k, $type);
        $out[0] = 'ul';
        return $out;
    }
}

// [content]
namespace _\lot\x\panel\content\desk {
    function body($in, $k, $type) {
        $out = \call_user_func(\dirname(__NAMESPACE__), $in, $k, $type);
        $out[0] = 'main';
        return $out;
    }
    function footer($in, $k, $type) {
        $out = \call_user_func(\dirname(__NAMESPACE__), $in, $k, $type);
        $out[0] = 'footer';
        return $out;
    }
    function header($in, $k, $type) {
        $out = \call_user_func(\dirname(__NAMESPACE__), $in, $k, $type);
        $out[0] = 'header';
        return $out;
    }
}

// [lot]
namespace _\lot\x\panel\lot\desk {
    function body($in, $k, $type) {
        $out = \call_user_func(\dirname(__NAMESPACE__), $in, $k, $type);
        $out[0] = 'main';
        return $out;
    }
    function footer($in, $k, $type) {
        $out = \call_user_func(\dirname(__NAMESPACE__), $in, $k, $type);
        $out[0] = 'footer';
        return $out;
    }
    function header($in, $k, $type) {
        $out = \call_user_func(\dirname(__NAMESPACE__), $in, $k, $type);
        $out[0] = 'header';
        return $out;
    }
}

// [type]
namespace _\lot\x\panel\type {
    function a($in, $k, $type) {
        $out = new \HTML([$in[0] ?? 'a', $in[1] ?? $in['title'] ?? "", \array_replace($in[2] ?? [], [
            'href' => $in['link'] ?? $in['url'] ?? url($in['path']),
            'target' => isset($in['link']) ? '_blank' : ($in[2]['target'] ?? false)
        ])]);
        return $out;
    }
    function link(string $v, $in) {
        return url($v, $in);
    }
    function url(string $v, $in) {
        return \URL::long($v);
    }
}




/*

    function desk($in) {
        $out = '<div class="desk">';
        if (isset($in[0])) {
            $out .= desk\header($in[0]);
        }
        if (isset($in[1])) {
            $fn = \rtrim("\\_\\lot\\x\\panel\\lot\\" . ($in[1]['type'] ?? ""), "\\");
            if (\function_exists($fn)) {
                $out .= \call_user_func($fn, $in[1]['lot'] ?? []);
            } else {
                $out .= desk\body($in[1]);
            }
        }
        if (isset($in[2])) {
            $out .= desk\footer($in[2]);
        }
        return $out . '</div>';
    }
    function field($in) {
        $key = \strip_tags($in['key'] ?? \uniqid());
        $id = \strip_tags($in['id'] ?? 'field:' . $key);
        $title = \strip_tags($in['title'] ?? $key ?? "", '<a><b><code><em><i><kbd><span><strong><var>');
        $content = content($in);
        if ($content instanceof \HTML) {
            $content['id'] = $id;
            if (!isset($content['name']) && \strpos(',button,input,select,textarea,', ',' . $content[0] . ',') !== false) {
                $content['name'] = $key;
            }
            $content['placeholder'] = $in['placeholder'] ?? null;
            $style = "";
            $h = $in['height'] ?? null;
            $w = $in['width'] ?? null;
            if (isset($h) && !\is_bool($h)) {
                $style .= 'height:' . (\is_numeric($h) ? $h . 'px' : $h) . ';';
            }
            if (isset($w) && !\is_bool($w)) {
                $style .= 'width:' . (\is_numeric($w) ? $w . 'px' : $w) . ';';
            }
            $content['style'] = \trim($style) ?: null;
        }
        $out = new \HTML([$t = $in[0] ?? 'p', $in[1] ?? "", $in[2] ?? []]);
        $out['class'] = c([
            'field' => 1,
            'p' => 1
        ], $out['class']);
        $out[1] = '<label for="' . $id . '"' . (isset($in['title']) ? ' title="' . $key . '"' : "") . '>' . $title . '</label>' . ($t === 'p' ? '<span>' . $content . '</span>' : '<div>' . $content . '</div>');
        return $out;
    }
    function c($a = null, $b = null) {
        if (\is_string($a)) {
            $a = \array_count_values(\explode(' ', $a));
        }
        if (\is_string($b)) {
            $b = \array_count_values(\explode(' ', $b));
        }
        $out = \array_replace((array) $a, (array) $b);
        \ksort($out);
        return \implode(' ', \array_keys(\array_filter($out)));
    }

namespace _\lot\x\panel\field {
    function blob($in) {}
    function hidden($in) {
        return new \HTML(['input', false, [
            'type' => 'hidden',
            'value' => $in['value'] ?? null
        ]]);
    }
    function content($in) {
        $in[0] = $in[0] ?? 'div';
        if (!isset($in['content']) || \is_array($in['content'])) {
            $in['height'] = $in['height'] ?? true; // Default to `true`
            $in['width'] = $in['width'] ?? true; // Default to `true`
            $in['content'] = \array_replace($in['content'] ?? [], [
                0 => 'textarea',
                1 => \htmlspecialchars($in['value'] ?? ""),
                2 => [
                    'class' => \_\lot\x\panel\c([
                        'textarea' => 1,
                        'height' => !empty($in['height']),
                        'width' => !empty($in['width'])
                    ])
                ]
            ]);
        }
        return \_\lot\x\panel\field($in);
    }
    function description($in) {
        if (!isset($in['content']) || \is_array($in['content'])) {
            $in['height'] = $in['height'] ?? false; // Default to `false`
        }
        return content($in);
    }
    function source($in) {
        $out = content($in);
        $out['data-type'] = $in['syntax'] ?? 'text/plain';
        $out[1] = \preg_replace_callback('/<textarea(?:\s[^>]*)?>[\s\S]*?<\/textarea>/', function($m) use($in) {
            $out = new \HTML($m[0]);
            $out['class'] = \_\lot\x\panel\c($out['class'], ['code' => 1]);
            $out['data-type'] = $in['syntax'] ?? 'text/plain';
            return $out;
        }, $out[1]);
        return $out;
    }
    function slug($in) {}
    function title($in) {
        if (!isset($in['content']) || \is_array($in['content'])) {
            $in['content'] = \array_replace([
                0 => 'input',
                1 => false,
                2 => [
                    'class' => 'input width',
                    'type' => 'text',
                    'value' => $in['value'] ?? null
                ]
            ], $in['content'] ?? []);
        }
        return \_\lot\x\panel\field($in);
    }
}

namespace _\lot\x\panel\lot {
    function field($in) {
        $a = "";
        $b = "";
        foreach (\Anemon::from($in)->sort([1, 'stack', 10], true) as $k => $v) {
            $v['id'] = $v['id'] ?? 'field:' . \dechex(\crc32($k));
            $v['key'] = $v['key'] ?? $k;
            $v['type'] = $v['type'] ?? 'title';
            $fn = \rtrim("\\_\\lot\\x\\panel\\field\\" . $v['type'], "\\");
            if (\function_exists($fn)) {
                if ($v['type'] === 'hidden') {
                    $b .= \call_user_func($fn, $v);
                } else {
                    $a .= \call_user_func($fn, $v);
                }
            }
        }
        return '<div class="lot lot-field">' . $a . $b . '</div>';
    }
}

namespace _\lot\x\panel\lot\field {}

namespace _\lot\x\panel\lot\menu {}

namespace _\lot\x\panel\lot\nav {}

namespace _\lot\x\panel\lot\tab {}

namespace _\lot\x\panel\menu {}

namespace _\lot\x\panel\nav {}

namespace _\lot\x\panel\tab {}

*/