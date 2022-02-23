<?php namespace x\panel\type;

function bar($value, $key) {
    $tags = $value['tags'] ?? [];
    if (!\array_key_exists('p', $tags)) {
        $tags['p'] = false;
    }
    $value['tags'] = $tags;
    if (\array_key_exists('title', $value) && !\array_key_exists('level', $value)) {
        $value['level'] = 1;
    }
    if (isset($value['content'])) {
        $out = \x\panel\type\content($value, $key);
    } else if (isset($value['lot'])) {
        foreach ($value['lot'] as &$v) {
            $v['tags']['p'] = false;
            // If `type` is not defined, the default value will be `links`
            if (!\array_key_exists('type', $v)) {
                $v['type'] = 'links';
            }
        }
        unset($v);
        $out = \x\panel\type\lot($value, $key);
    }
    $out['tabindex'] = -1;
    $out[0] = 'nav';
    return $out;
}

function button($value, $key) {
    $value['tags']['not:active'] = $not_active = isset($value['active']) && !$value['active'];
    $out = \x\panel\type\link($value, $key);
    $out[0] = 'button';
    $out['disabled'] = $not_active;
    $out['name'] = $value['name'] ?? $key;
    $out['value'] = $value['value'] ?? null;
    unset($out['href'], $out['target']);
    return $out;
}

function card($value, $key) {
    $value['tags']['lot:card'] = true;
    $value['tags']['lot:page'] = false;
    return \x\panel\type\page($value, $key);
}

function cards($value, $key) {
    $value['tags']['lot:cards'] = true;
    $value['tags']['lot:pages'] = false;
    return \x\panel\type\pages($value, $key);
}

function column($value, $key) {
    $tags = $value['tags'] ?? [];
    $out = [
        0 => $value[0] ?? 'div',
        1 => $value[1] ?? "",
        2 => $value[2] ?? []
    ];
    if (!empty($value['size'])) {
        $size = $value['size'];
        $tags['size:' . $size] = true;
    }
    if (!isset($value[1])) {
        if (!\array_key_exists('p', $tags)) {
            $tags['p'] = false;
        }
        $value['tags'] = $tags;
        if (isset($value['content'])) {
            return \x\panel\type\content($value, $key);
        }
        if (isset($value['lot'])) {
            foreach ($value['lot'] as &$v) {
                if (!\array_key_exists('type', $v)) {
                    $v['type'] = 'content';
                }
            }
            unset($v);
            return \x\panel\type\lot($value, $key);
        }
    }
    return "" !== $out[1] ? new \HTML($out) : null;
}

function columns($value, $key) {
    $has_gap = !\array_key_exists('gap', $value) || $value['gap'];
    $tags = \array_replace(['has:gap' => $has_gap], $value['tags'] ?? []);
    $out = [
        0 => $value[0] ?? 'div',
        1 => $value[1] ?? "",
        2 => $value[2] ?? []
    ];
    if (!isset($value[1])) {
        if (!\array_key_exists('p', $tags)) {
            $tags['p'] = false;
        }
        $value['tags'] = $tags;
        if (isset($value['content'])) {
            return \x\panel\type\content($value, $key);
        }
        if (isset($value['lot'])) {
            foreach ($value['lot'] as &$v) {
                if (!\array_key_exists('type', $v)) {
                    $v['type'] = 'column';
                }
            }
            unset($v);
            return \x\panel\type\lot($value, $key);
        }
    }
    return "" !== $out[1] ? new \HTML($out) : null;
}

function content($value, $key) {
    $count = 0;
    $type = $value['type'] ?? null;
    if ($title = \x\panel\to\title($value['title'] ?? "", $value['level'] ?? 2)) {
        ++$count;
    }
    if ($description = \x\panel\to\description($value['description'] ?? "")) {
        ++$count;
    }
    $out = [
        0 => $value[0] ?? 'div',
        1 => $value[1] ?? $title . $description,
        2 => $value[2] ?? []
    ];
    if (!isset($value[1])) {
        if (isset($value['content'])) {
            $out[1] .= \x\panel\to\content($value['content']);
            ++$count;
        }
        $tags = [
            'content' => true,
            'count:' . $count => true,
            'p' => true
        ];
        if (isset($type)) {
            foreach (\step(\strtr($type, '/', '.')) as $v) {
                $tags['content:' . $v] = true;
            }
        }
        $out[2]['id'] = $value['id'] ?? null;
        \x\panel\_class_set($out[2], \array_replace($tags, $value['tags'] ?? []));
    }
    return "" !== $out[1] ? new \HTML($out) : null;
}

function description($value, $key) {
    $description = $value[1] ?? $value['content'] ?? "";
    $description = \w('<!--0-->' . \i(...((array) $description)), ['a', 'abbr', 'b', 'code', 'del', 'em', 'i', 'ins', 'span', 'strong', 'sub', 'sup']);
    if ('0' !== $description && !$description) {
        return null;
    }
    $out = [
        0 => $value[0] ?? 'p',
        1 => $description,
        2 => $value[2] ?? []
    ];
    \x\panel\_class_set($out[2], \array_replace([
        'description' => true
    ], $value['tags'] ?? []));
    return new \HTML($out);
}

function desk($value, $key) {
    $out = [
        0 => $value[0] ?? 'main',
        1 => $value[1] ?? "",
        2 => \array_replace(['tabindex' => -1], $value[2] ?? [])
    ];
    if (!isset($value[1])) {
        $styles = [];
        $tags = $value['tags'] ?? [];
        if (!\array_key_exists('p', $tags)) {
            $tags['p'] = false;
        }
        if (isset($value['width']) && false !== $value['width']) {
            $tags['has:width'] = true;
            if (true !== $value['width']) {
                $styles['width'] = $value['width'];
            }
        }
        if (!isset($value[2])) {
            $value[2] = [];
        }
        \x\panel\_class_set($value[2], $tags);
        \x\panel\_style_set($value[2], $styles);
        $value['tags'] = $tags;
        if (isset($value['content'])) {
            if ($v = \x\panel\type\content($value, $key)) {
                $v[0] = $value[0] ?? 'main';
                $v['tabindex'] = $v['tabindex'] ?? -1;
                return $v;
            }
        }
        if (isset($value['lot'])) {
            foreach ($value['lot'] as &$v) {
                if (!\array_key_exists('type', $v)) {
                    $v['type'] = 'section';
                }
            }
            unset($v);
            if ($v = \x\panel\type\lot($value, $key)) {
                $v[0] = $value[0] ?? 'main';
                $v['tabindex'] = $v['tabindex'] ?? -1;
                return $v;
            }
        }
    }
    return "" !== $out[1] ? new \HTML($out) : null;
}

function field($value, $key) {
    $out = [
        0 => $value[0] ?? 'div',
        1 => $value[1] ?? "",
        2 => $value[2] ?? []
    ];
    if (!isset($value[1])) {
        $is_active = !isset($value['active']) || $value['active'];
        $is_fix = !empty($value['fix']);
        $is_vital = !empty($value['vital']);
        $tags = [
            'has:title' => !empty($value['title']),
            'lot:field' => true,
            'p' => true
        ];
        $tags_status = [
            'has:pattern' => !empty($value['pattern']),
            'is:active' => $is_active,
            'is:fix' => $is_fix,
            'is:vital' => $is_vital,
            'not:active' => !$is_active,
            'not:fix' => !$is_fix,
            'not:vital' => !$is_vital
        ];
        if (isset($value['type'])) {
            if (0 === \strpos($value['type'], 'field/')) {
                $tags[\strtr($value['type'], ['field/' => 'type:'])] = true;
            }
        }
        $id = $value['id'] ?? 'f:' . \dechex(\time());
        $value[2]['id'] = $value[2]['id'] ?? \strtr($id, ['f:' => 'field:']);
        if (!\array_key_exists('title', $value) || false !== $value['title']) {
            $title = \x\panel\to\title($value['title'] ?? \To::title($key), -2);
            $out[1] .= '<label' . ("" === \strip_tags($title ?? "") ? ' class="count:0"' : "") . ' for="' . $id . '">' . $title . '</label>';
            $tags['has:title'] = true;
        }
        $tags['has:description'] = !empty($value['description']);
        $before = $after = "";
        foreach (['before', 'after'] as $v) {
            if (isset($value['value-' . $v])) {
                $vv = $value['value-' . $v];
                if (\is_string($vv)) {
                    ${$v} = '<span class="fix"><span>' . $vv . '</span></span>';
                } else if (\is_array($vv)) {
                    $icon = \x\panel\to\icon($vv['icon'] ?? []);
                    \x\panel\_class_set($icon[0], ['fix' => true]);
                    ${$v} = $icon[0];
                }
            }
        }
        $styles = $tags_status_extra = [];
        if (isset($value['height']) && false !== $value['height']) {
            $tags_status_extra['has:height'] = true;
            if (true !== $value['height']) {
                $styles['height'] = $value['height'];
            }
        }
        if (isset($value['width']) && false !== $value['width']) {
            $tags_status_extra['has:width'] = true;
            if (true !== $value['width']) {
                $styles['width'] = $value['width'];
            }
        }
        $out[1] .= '<div>';
        // Special value returned by `x\panel\to\field()`
        if (isset($value['field'])) {
            if (\is_array($value['field'])) {
                \x\panel\_class_set($value['field'][2], \array_replace($tags_status, $tags_status_extra));
                \x\panel\_style_set($value['field'][2], $styles);
            }
            $r = \x\panel\to\content($value['field']);
            $out[1] .= '<div class="count:' . ($r ? '1' : '0') . (!empty($value['width']) ? ' has:width' : "") . ' with:fields" role="group">';
            $out[1] .= $before . $r . $after;
            $out[1] .= '</div>';
        } else if (isset($value['content'])) {
            if (\is_array($value['content'])) {
                \x\panel\_class_set($value['content'][2], \array_replace($tags_status, $tags_status_extra));
                \x\panel\_style_set($value['content'][2], $styles);
            }
            $r = \x\panel\to\content($value['content']);
            $out[1] .= '<div class="content count:' . ($r ? '1' : '0') . '">';
            $out[1] .= $before . $r . $after;
            $out[1] .= '</div>';
        } else if (isset($value['lot'])) {
            $count = 0;
            $r = \x\panel\to\lot($value['lot'], $count, $value['sort'] ?? true);
            $out[1] .= '<div class="count:' . $count . ' lot">' . $r . '</div>';
        }
        $out[1] .= \x\panel\to\description($value['description'] ?? "");
        $out[1] .= '</div>';
        \x\panel\_class_set($out[2], \array_replace($tags, $tags_status, $value['tags'] ?? []));
        if (isset($value['data']) && \is_array($value['data']) && $data = \To::query($value['data'])) {
            foreach (\explode('&', \substr($data, 1)) as $v) {
                $vv = \explode('=', $v, 2);
                $out[1] .= new \HTML(['input', false, [
                    'name' => \urldecode($vv[0]),
                    'type' => 'hidden',
                    'value' => \urldecode($vv[1] ?? 'true')
                ]]);
            }
        }
    }
    return "" !== $out[1] ? new \HTML($out) : null;
}

function fields($value, $key) {
    $out = [
        0 => $value[0] ?? 'div',
        1 => $value[1] ?? "",
        2 => $value[2] ?? []
    ];
    if (!isset($value[1])) {
        $tags = \array_replace([
            'lot' => true,
            'lot:fields' => true,
            'p' => true
        ], $value['tags'] ?? []);
        $append = "";
        $title = \x\panel\to\title($value['title'] ?? "", $value['level'] ?? 3);
        $description = \x\panel\to\description($value['description'] ?? "");
        if (isset($value['content'])) {
            $out[1] .= \x\panel\to\content($value['content']);
        } else if (isset($value['lot'])) {
            \x\panel\_type_parent_set($value['lot'], 'field');
            foreach ((new \Anemone($value['lot']))->sort([1, 'stack', 10], true) as $k => &$v) {
                if (false === $v || null === $v || !empty($v['skip'])) {
                    continue;
                }
                $type = \strtolower(\f2p(\strtr($v['type'] ?? "", '-', '_')));
                if ("" !== $type && \function_exists($fn = __NAMESPACE__ . "\\" . $type)) {
                    // Put all hidden field(s) at the bottom
                    if ('field/hidden' !== $type) {
                        $out[1] .= \call_user_func($fn, $v, $k);
                    } else {
                        $append .= \x\panel\type\field\hidden($v, $k);
                    }
                } else {
                    $append .= \x\panel\_abort($value, $key, $fn);
                }
                unset($v);
            }
            $out[1] .= $append;
        }
        $out[1] = $title . $description . $out[1];
        \x\panel\_class_set($out[2], $tags);
    }
    return "" !== $out[1] ? new \HTML($out) : null;
}

function file($value, $key) {
    $out = [
        0 => $value[0] ?? 'li',
        1 => $value[1] ?? "",
        2 => \array_replace(['tabindex' => 0], $value[2] ?? [])
    ];
    if (!isset($value[1])) {
        $is_active = !isset($value['active']) || $value['active'];
        $is_current = !empty($value['current']);
        $tags = \array_replace([
            'is:active' => $is_active,
            'is:current' => $is_current,
            'is:file' => true,
            'lot' => true,
            'lot:file' => true,
            'not:active' => !$is_active
        ], $value['tags'] ?? []);
        $out[1] .= '<h3 class="title">' . \x\panel\type\link([
            'description' => $value['description'] ?? null,
            'icon' => $value['icon'] ?? [],
            'info' => $value['info'] ?? null,
            'link' => $value['link'] ?? null,
            'title' => $value['title'] ?? null,
            'url' => $value['url'] ?? null
        ], $key) . '</h3>';
        $out[1] .= \x\panel\type\tasks\link([
            '0' => 'p',
            'lot' => $value['tasks'] ?? []
        ], $key);
        \x\panel\_class_set($out[2], $tags);
        if (!$is_active) {
            unset($out[2]['tabindex']);
        }
    }
    return "" !== $out[1] ? new \HTML($out) : null;
}

function files($value, $key) {
    $out = [
        0 => $value[0] ?? 'ul',
        1 => $value[1] ?? "",
        2 => \array_replace(['tabindex' => -1], $value[2] ?? [])
    ];
    if (!isset($value[1])) {
        $lot = [];
        if (isset($value['lot'])) {
            foreach ($value['lot'] as $k => $v) {
                if (null === $v || false === $v || !empty($v['skip'])) {
                    continue;
                }
                $lot[$k] = $v;
            }
            if (!empty($value['sort'])) {
                $sort = $value['sort'];
                if (true === $sort) {
                    $sort = [1, 'stack', 10];
                }
                $lot = (new \Anemone($lot))->sort($sort)->get();
            }
        }
        $count = 0;
        $count_files = 0;
        $count_folders = 0;
        foreach ($lot as $k => $v) {
            if (\is_array($v) && !\array_key_exists('type', $v)) {
                $v['type'] = 'file';
                ++$count_files;
            } else if ('file' === $v['type']) {
                ++$count_files;
            } else if ('folder' === $v['type']) {
                ++$count_folders;
            }
            $out[1] .= \x\panel\type($v, $k);
            ++$count;
        }
        unset($lot);
        \x\panel\_class_set($out[2], \array_replace([
            'count:' . $count => true,
            'lot' => true,
            'lot:files' => !!$count_files,
            'lot:folders' => !!$count_folders
        ], $value['tags'] ?? []));
    }
    return "" !== $out[1] ? new \HTML($out) : null;
}

function flex($value, $key) {
    $lot = (array) ($value['lot'] ?? []);
    $value['lot'] = [
        'title' => [
            'type' => 'title',
            'content' => $value['title'] ?? null,
            'level' => $value['level'] ?? 2, // Same with the default level of `x\panel\type\content`
            'stack' => 10
        ],
        'description' => [
            'type' => 'description',
            'content' => $value['description'] ?? null,
            'stack' => 20
        ],
        'lot' => [
            'type' => 'lot',
            'lot' => $lot,
            'tags' => [
                'flex' => true
            ],
            'stack' => 30
        ]
    ];
    unset($value['description'], $value['title']);
    return \x\panel\type\lot($value, $key);
}

function folder($value, $key) {
    $out = [
        0 => $value[0] ?? 'li',
        1 => $value[1] ?? "",
        2 => \array_replace(['tabindex' => 0], $value[2] ?? [])
    ];
    if (!isset($value[1])) {
        $is_active = !isset($value['active']) || $value['active'];
        $is_current = !empty($value['current']);
        $tags = \array_replace([
            'is:active' => $is_active,
            'is:current' => $is_current,
            'is:folder' => true,
            'lot' => true,
            'lot:folder' => true,
            'not:active' => !$is_active
        ], $value['tags'] ?? []);
        $out[1] .= '<h3 class="title">' . \x\panel\type\link([
            'description' => $value['description'] ?? \i('Open folder'),
            'icon' => $value['icon'] ?? [],
            'info' => $value['info'] ?? null,
            'link' => $value['link'] ?? null,
            'title' => $value['title'] ?? null,
            'url' => $value['url'] ?? null
        ], $key) . '</h3>';
        $out[1] .= \x\panel\type\tasks\link([
            '0' => 'p',
            'lot' => $value['tasks'] ?? []
        ], $key);
        \x\panel\_class_set($out[2], $tags);
        if (!$is_active) {
            unset($out[2]['tabindex']);
        }
    }
    return "" !== $out[1] ? new \HTML($out) : null;
}

// NOTE: `folders` is actually just an alias of `files`. Thus, declaring a `folders` block would not give
// any difference from `files`. It’s just that the default value of item’s `type` will be automatically set
// to `folder` instead of `file`. Otherwise, they will be treated the same. Both will get a class `lot:files`
// and/or `lot:folders`, according to the available item(s). Use those class(es) to detect whether this block
// contains only file(s) or only folder(s), or if both are present.
function folders($value, $key) {
    if (isset($value['lot'])) {
        foreach ($value['lot'] as &$v) {
            if (\is_array($v) && !\array_key_exists('type', $v)) {
                $v['type'] = 'folder';
            }
        }
        unset($v);
    }
    return \x\panel\type\files($value, $key);
}

function form($value, $key) {
    $out = [
        0 => $value[0] ?? 'form',
        1 => $value[1] ?? "",
        2 => $value[2] ?? []
    ];
    if (!isset($value[1])) {
        if (isset($value['active']) && empty($value['active'])) {
            // Set node name to `false` to remove the `<form>` element
            $out[0] = false;
        }
        if (isset($value['content'])) {
            $out[1] .= \x\panel\to\content($value['content']);
        } else if (isset($value['lot'])) {
            $count = 0;
            $out[1] .= \x\panel\to\lot($value['lot'], $count, $value['sort'] ?? true);
        }
        if (isset($value['data']) && \is_array($value['data']) && $data = \To::query($value['data'])) {
            foreach (\explode('&', \substr($data, 1)) as $v) {
                $vv = \explode('=', $v, 2);
                $out[1] .= new \HTML(['input', false, [
                    'name' => \urldecode($vv[0]),
                    'type' => 'hidden',
                    'value' => \urldecode($vv[1] ?? 'true')
                ]]);
            }
        }
        $href = $value['link'] ?? $value['url'] ?? null;
        if (\is_array($href)) {
            $href = \x\panel\to\link($href);
        }
        if (!isset($out[2]['action'])) {
            $out[2]['action'] = $href;
        }
        if (!isset($out[2]['name'])) {
            $out[2]['name'] = $value['name'] ?? $key;
        }
        \x\panel\_class_set($out[2], $value['tags'] ?? []);
    }
    return "" !== $out[1] ? new \HTML($out) : null;
}

function icon($value, $key) {
    $icon = \array_replace([null, null], (array) ($value['content'] ?? $value['lot'] ?? []));
    $icons = $GLOBALS['_']['icon'] ?? [];
    $v = [
        'class' => 'icon',
        'height' => 12,
        'width' => 12
    ];
    if ($icon[0] && false === \strpos($icon[0], '<')) {
        // Named icon(s)
        if (isset($icons[$icon[0]])) {
            $icon[0] = new \HTML(['svg', '<use href="#i:' . $icon[0] . '"></use>', $v]);
        // Inline icon(s)
        } else {
            if (!isset($icons[$id = \dechex(\crc32($icon[0]))])) {
                $GLOBALS['_']['icon'][$id] = $icon[0];
            }
            $icon[0] = new \HTML(['svg', '<use href="#i:' . $id . '"></use>', $v]);
        }
    }
    if ($icon[1] && false === \strpos($icon[1], '<')) {
        // Named icon(s)
        if (isset($icons[$icon[1]])) {
            $icon[1] = new \HTML(['svg', '<use href="#i:' . $icon[1] . '"></use>', $v]);
        // Inline icon(s)
        } else {
            if (!isset($icons[$id = \dechex(\crc32($icon[1]))])) {
                $GLOBALS['_']['icon'][$id] = $icon[1];
            }
            $icon[1] = new \HTML(['svg', '<use href="#i:' . $id . '"></use>', $v]);
        }
    }
    return $icon;
}

function input($value, $key) {
    $out = [
        0 => $value[0] ?? 'input',
        1 => false,
        2 => $value[2] ?? []
    ];
    $has_pattern = \array_key_exists('pattern', $value);
    $is_active = !isset($value['active']) || $value['active'];
    $is_fix = !empty($value['fix']);
    $is_vital = !empty($value['vital']);
    $tags = \array_replace([
        'has:pattern' => $has_pattern,
        'is:active' => $is_active,
        'is:fix' => $is_fix,
        'is:vital' => $is_vital,
        'not:active' => !$is_active,
        'not:fix' => !$is_fix,
        'not:vital' => !$is_vital
    ], $value['tags'] ?? []);
    if ($has_pattern) {
        $out[2]['pattern'] = $value['pattern'];
    }
    $out[2]['autofocus'] = !empty($value['focus']);
    $out[2]['disabled'] = !$is_active;
    $out[2]['id'] = $value['id'] ?? null;
    $out[2]['maxlength'] = $value['max'] ?? null;
    $out[2]['minlength'] = $value['min'] ?? null;
    $out[2]['name'] = $value['name'] ?? $key;
    $out[2]['placeholder'] = \i(...((array) ($value['hint'] ?? [])));
    $out[2]['readonly'] = $is_fix;
    $out[2]['required'] = $is_vital;
    $out[2]['value'] = $value['value'] ?? null;
    \x\panel\_class_set($out[2], $tags);
    return new \HTML($out);
}

function link($value, $key) {
    $out = [
        0 => $value[0] ?? 'a',
        1 => $value[1] ?? "",
        2 => $value[2] ?? []
    ];
    if (!isset($value[1])) {
        if ("" === $out[1]) {
            $info = $value['info'] ?? "";
            $out[1] = (string) \x\panel\type\title([
                'content' => $value['title'] ?? \To::title($key),
                'description' => $value['description'] ?? null,
                'icon' => $value['icon'] ?? [],
                'info' => $info,
                'level' => -1
            ], $key);
        }
        $href = $value['link'] ?? $value['url'] ?? \P;
        if (\is_array($href)) {
            $href = \x\panel\to\link($href);
        }
        $tags = \array_replace([
            'not:active' => \P === $href || (isset($value['active']) && !$value['active'])
        ], $value['tags'] ?? []);
        if (false !== $href) {
            $out[2]['href'] = \P === $href ? null : $href;
            $out[2]['target'] = $value[2]['target'] ?? $value['target'] ?? (isset($value['link']) ? '_blank' : null);
        } else {
            $out[0] = $value[0] ?? false; // Unwrap!
        }
        if (isset($value['id'])) {
            $out[2]['id'] = $value['id'];
        }
        \x\panel\_class_set($out[2], $tags);
    }
    return "" !== $out[1] ? new \HTML($out) : null;
}

function links($value, $key) {
    $value['tags'] = \array_replace([
        'lot:links' => true,
        'lot:menu' => false
    ], $value['tags'] ?? []);
    if (!\array_key_exists('tabindex', $value[2] ?? [])) {
        $value[2]['tabindex'] = -1;
    }
    $out = \x\panel\type\menu($value, $key, -1);
    return $out;
}

function lot($value, $key) {
    $count = 0;
    $type = $value['type'] ?? null;
    if ($title = \x\panel\to\title($value['title'] ?? "", $value['level'] ?? 2)) {
        ++$count;
    }
    if ($description = \x\panel\to\description($value['description'] ?? "")) {
        ++$count;
    }
    $out = [
        0 => $value[0] ?? 'div',
        1 => $value[1] ?? $title . $description,
        2 => $value[2] ?? []
    ];
    if (isset($value['lot'])) {
        $out[1] .= \x\panel\to\lot($value['lot'], $count, $value['sort'] ?? true);
    }
    $tags = [
        'count:' . $count => true,
        'lot' => true,
        'p' => true
    ];
    if (isset($type)) {
        foreach (\step(\strtr($type, '/', '.')) as $v) {
            $tags['lot:' . $v] = true;
        }
    }
    $out[2]['id'] = $value['id'] ?? null;
    \x\panel\_class_set($out[2], \array_replace($tags, $value['tags'] ?? []));
    return "" !== $out[1] ? new \HTML($out) : null;
}

function menu($value, $key, int $i = 0) {
    $out = [
        0 => $value[0] ?? 'div',
        1 => $value[1] ?? "",
        2 => \array_replace(['tabindex' => 0], $value[2] ?? [])
    ];
    $count_parent = 0;
    if ($title = \x\panel\to\title($value['title'] ?? "", $value['level'] ?? 4)) {
        ++$count_parent;
    }
    if ($description = \x\panel\to\description($value['description'] ?? "")) {
        ++$count_parent;
    }
    if (!isset($value[1])) {
        $tags = \array_replace([
            'lot' => true,
            'lot:menu' => true,
            'p' => true
        ], $value['tags'] ?? []);
        if (isset($value['content'])) {
            $tags['count:' . ($count_parent + 1)] = true;
            $out[1] .= \x\panel\to\content($value['content']);
        } else if (isset($value['lot'])) {
            $count = 0;
            foreach ((new \Anemone($value['lot']))->sort([1, 'stack', 10], true) as $k => $v) {
                if (false === $v || null === $v || !empty($v['skip'])) {
                    continue;
                }
                $li = [
                    0 => $v[0] ?? 'li',
                    1 => $v[1] ?? "",
                    2 => $v[2] ?? ['role' => 'none']
                ];
                if (!isset($v[1])) {
                    if (\is_array($v)) {
                        if (\array_key_exists('menu', $v)) {
                            // TODO
                        }
                        // If `type` is not defined, the default value will be `menu`
                        if (!\array_key_exists('type', $v)) {
                            $v['type'] = 'menu';
                        } else if ('separator' === $v['type']) {
                            $li[2]['aria-orientation'] = $i < 0 ? 'horizontal' : 'vertical';
                            $li[2]['role'] = 'separator';
                            \x\panel\_class_set($li[2], \array_replace([
                                'as:separator' => true
                            ], $v['tags'] ?? []));
                            $out[1] .= new \HTML($li);
                            ++$count;
                            continue;
                        }
                        if (\array_key_exists('icon', $v)) {
                            $v['icon'] = (array) $v['icon'];
                        }
                        $has_caret = false;
                        if (!empty($v['lot']) && (!empty($v['caret']) || !\array_key_exists('caret', $v))) {
                            $v['icon'][1] = $v['caret'] ?? ($i < 0 ? 'M7,10L12,15L17,10H7Z' : 'M10,17L15,12L10,7V17Z');
                            $has_caret = true;
                        }
                        $v['icon'] = \x\panel\to\icon($v['icon'] ?? []);
                        if ($has_caret) {
                            \x\panel\_class_set($v['icon'][1], ['caret' => true]);
                        }
                        $is_active = !isset($v['active']) || $v['active'];
                        $is_current = !empty($v['current']);
                        $tags_li = \array_replace([
                            // 'is:active' => $is_active,
                            'is:current' => $is_current,
                            'not:active' => !$is_active
                        ], $v['tags'] ?? []);
                        if (!isset($v[1])) {
                            if ('menu' === $v['type']) {
                                $v[2] = \array_replace([
                                    'role' => 'menuitem',
                                    'tabindex' => $i < 0 ? null : -1
                                ], $v[3] ?? []);
                                $li[1] = \x\panel\type\link($v, $k);
                                if (!empty($v['lot'])) {
                                    $li[1]['aria-expanded'] = 'false';
                                    $li[1]['aria-haspopup'] = 'true';
                                    $li[1] .= \x\panel\type\menu(\array_replace($v, [
                                        '2' => [
                                            'role' => null,
                                            'tabindex' => -1
                                        ]
                                    ]), $k, $i + 1); // Recurse!
                                    if ($i < 0) {
                                        $tags_li['has:menu'] = true;
                                    }
                                }
                            } else {
                                if (0 === \strpos($v['type'] . '/', 'form/')) {
                                    $tags_li['has:form'] = true;
                                }
                                $li[1] = \x\panel\type($v, $k);
                            }
                        }
                        \x\panel\_class_set($li[2], $tags_li);
                    } else {
                        $li[1] = \x\panel\type\link([
                            '2' => [
                                'aria-disabled' => 'true',
                                'role' => 'none',
                                'tabindex' => $i < 0 ? null : -1
                            ],
                            'title' => $v
                        ], $k);
                    }
                    $out[1] .= new \HTML($li);
                }
                ++$count;
            }
            $tags['count:' . ($count_parent + ($count ? 1 : 0))] = true;
        }
        \x\panel\_class_set($out[2], $tags);
        if ("" !== $out[1]) {
            $out[1] = '<ul class="count:' . $count . '" role="' . ($value[3]['role'] ?? 'menu' . ($i < 0 ? 'bar' : "")) . '">' . $out[1] . '</ul>';
        }
    }
    $out[1] = $title . $description . $out[1];
    return "" !== $out[1] ? new \HTML($out) : null;
}

function page($value, $key) {
    $out = [
        0 => $value[0] ?? 'li',
        1 => $value[1] ?? "",
        2 => \array_replace(['tabindex' => 0], $value[2] ?? [])
    ];
    if (!isset($value[1])) {
        $is_active = !isset($value['active']) || $value['active'];
        $is_current = !empty($value['current']);
        $tags = \array_replace([
            'is:active' => $is_active,
            'is:current' => $is_current,
            'is:file' => true,
            'lot' => true,
            'lot:page' => true,
            'not:active' => !$is_active
        ], $value['tags'] ?? []);
        $div = new \HTML(['div', "", []]);
        $icon = $value['icon'] ?? "";
        $image = $value['image'] ?? "";
        $path = $value['path'] ?? $key;
        $time = isset($value['time']) ? \strtr($value['time'], '-', '/') : null;
        if (false === $icon && false === $image) {
            $div['hidden'] = true;
        // Prioritize `icon` over `image`
        } else if (!empty($icon)) {
            // TODO: Set color inversion automatically based on current background color
            $div[1] = '<span class="image" role="img" style="color: #fff; background: ' . ($value['color'] ?? '#' . \substr(\md5($icon), 0, 6)) . ';">' . \x\panel\to\icon($icon)[0] . '</span>';
        } else if (!empty($image)) {
            $div[1] = '<img alt="" class="image" height="72" loading="lazy" src="' . \htmlspecialchars($image) . '" width="72">';
        } else {
            $div[1] = '<span class="image" role="img" style="background: ' . ($value['color'] ?? '#' . \substr(\md5(\strtr($path, [
                \PATH => "",
                \D => '/'
            ])), 0, 6)) . ';"></span>';
        }
        $out[1] .= $div;
        $out[1] .= '<div><h3 class="title">' . \x\panel\type\link([
            'link' => $value['link'] ?? null,
            'title' => $value['title'] ?? $time,
            'url' => $value['url'] ?? null
        ], $key) . '</h3>' . \x\panel\to\description($value['description'] ?? $time) . '</div>';
        $out[1] .= '<div>' . \x\panel\type\tasks\link([
            '0' => 'p',
            'lot' => $value['tasks'] ?? []
        ], $key) . '</div>';
        \x\panel\_class_set($out[2], $tags);
        if (!$is_active) {
            unset($out[2]['tabindex']);
        }
    }
    return "" !== $out[1] ? new \HTML($out) : null;
}

function pager($value, $key) {
    $content = \x\panel\to\pager($value['current'] ?? 1, $value['count'] ?? 0, $value['chunk'] ?? 20, 2, $value['ref'] ?? static function($i) {
        return \x\panel\to\link([
            'part' => $i,
            'path' => $GLOBALS['_']['path']
        ]);
    });
    $value['lot'] = [
        'content' => [
            '0' => false, // Remove the `<div>` wrapper
            'type' => 'content',
            'content' => $content,
            'stack' => 10
        ]
    ];
    $out = \x\panel\type\lot($value, $key);
    $out[0] = 'p';
    return "" !== $content ? $out : null;
}

function pages($value, $key) {
    $out = [
        0 => $value[0] ?? 'ul',
        1 => $value[1] ?? "",
        2 => \array_replace(['tabindex' => -1], $value[2] ?? [])
    ];
    if (!isset($value[1])) {
        $lot = [];
        if (isset($value['lot'])) {
            foreach ($value['lot'] as $k => $v) {
                if (null === $v || false === $v || !empty($v['skip'])) {
                    continue;
                }
                $lot[$k] = $v;
            }
            if (!empty($value['sort'])) {
                $sort = $value['sort'];
                if (true === $sort) {
                    $sort = [1, 'stack', 10];
                }
                $lot = (new \Anemone($lot))->sort($sort)->get();
            }
        }
        $count = 0;
        foreach ($lot as $k => $v) {
            if (\is_array($v) && !\array_key_exists('type', $v)) {
                $v['type'] = 'page';
            }
            $out[1] .= \x\panel\type($v, $k);
            ++$count;
        }
        unset($lot);
        \x\panel\_class_set($out[2], \array_replace([
            'count:' . $count => true,
            'lot' => true,
            'lot:pages' => true
        ], $value['tags'] ?? []));
    }
    return "" !== $out[1] ? new \HTML($out) : null;
}

function row($value, $key) {
    $tags = $value['tags'] ?? [];
    $out = [
        0 => $value[0] ?? 'div',
        1 => $value[1] ?? "",
        2 => $value[2] ?? []
    ];
    if (!empty($value['size'])) {
        $size = $value['size'];
        $tags['size:' . $size] = true;
    }
    if (!isset($value[1])) {
        if (!\array_key_exists('p', $tags)) {
            $tags['p'] = false;
        }
        $value['tags'] = $tags;
        if (isset($value['content'])) {
            return \x\panel\type\content($value, $key);
        }
        if (isset($value['lot'])) {
            foreach ($value['lot'] as &$v) {
                if (!\array_key_exists('type', $v)) {
                    $v['type'] = 'content';
                }
            }
            unset($v);
            return \x\panel\type\lot($value, $key);
        }
    }
    return "" !== $out[1] ? new \HTML($out) : null;
}

function rows($value, $key) {
    $has_gap = !\array_key_exists('gap', $value) || $value['gap'];
    $tags = \array_replace(['has:gap' => $has_gap], $value['tags'] ?? []);
    $out = [
        0 => $value[0] ?? 'div',
        1 => $value[1] ?? "",
        2 => $value[2] ?? []
    ];
    if (!isset($value[1])) {
        if (!\array_key_exists('p', $tags)) {
            $tags['p'] = false;
        }
        $value['tags'] = $tags;
        if (isset($value['content'])) {
            return \x\panel\type\content($value, $key);
        }
        if (isset($value['lot'])) {
            foreach ($value['lot'] as &$v) {
                if (!\array_key_exists('type', $v)) {
                    $v['type'] = 'row';
                }
            }
            unset($v);
            return \x\panel\type\lot($value, $key);
        }
    }
    return "" !== $out[1] ? new \HTML($out) : null;
}

function section($value, $key) {
    $tags = $value['tags'] ?? [];
    if (!\array_key_exists('p', $tags)) {
        $tags['p'] = false;
    }
    $value['tags'] = $tags;
    if (isset($value['content'])) {
        $out = \x\panel\type\content($value, $key);
        $out[0] = $value[0] ?? 'section';
    } else if (isset($value['lot'])) {
        $out = \x\panel\type\lot($value, $key);
        $out[0] = $value[0] ?? 'section';
    } else {
        $out = new \HTML([
            0 => $value[0] ?? 'section',
            1 => $value[1] ?? "",
            2 => $value[2] ?? []
        ]);
    }
    return isset($out[1]) && "" !== $out[1] ? $out : null;
}

function select($value, $key) {
    $out = [
        0 => $value[0] ?? 'select',
        1 => $value[1] ?? "",
        2 => $value[2] ?? []
    ];
    if (!isset($value[1])) {
        $the_options = [];
        $the_value = $value['value'] ?? null;
        // $the_placeholder = \i(...((array) ($out['hint'] ?? [])));
        $seq0 = \array_keys($value['lot']) === \range(0, \count($value['lot']) - 1);
        $sort = !isset($value['sort']) || $value['sort'];
        foreach ($value['lot'] as $k => $v) {
            if (null === $v || false === $v || !empty($v['skip'])) {
                continue;
            }
            // Group
            if (isset($v['lot'])) {
                $the_options_group = [];
                $optgroup = new \HTML(['optgroup', "", [
                    'disabled' => isset($v['active']) && !$v['active'],
                    'label' => $t = \trim(\strip_tags(\i(...((array) ($v['title'] ?? $k)))))
                ]]);
                $seq1 = \array_keys($v['lot']) === \range(0, \count($v['lot']) - 1);
                foreach ($v['lot'] as $kk => $vv) {
                    $option = new \HTML(['option', "", [
                        'selected' => null !== $the_value && (string) $the_value === (string) $kk,
                        'value' => $seq1 ? null : $kk
                    ]]);
                    if (\is_array($vv) && \array_key_exists('title', $vv)) {
                        $tt = $vv['title'] ?? $kk;
                        $option['disabled'] = isset($vv['active']) && !$vv['active'];
                        $option['title'] = $vv['description'] ?? null;
                    } else {
                        $tt = $vv;
                    }
                    $option[1] = $tt = \trim(\strip_tags(\i(...((array) $tt))));
                    $the_options_group[$tt . $kk] = $option;
                }
                $sort && \ksort($the_options_group);
                foreach ($the_options_group as $vv) {
                    $optgroup[1] .= $vv;
                }
                // Add `0` to the end of the key so that option(s) group will come first
                $the_options[$t . $k . '0'] = $optgroup;
            // Flat
            } else {
                $option = new \HTML(['option', $k, [
                    'selected' => null !== $the_value && (string) $the_value === (string) $k,
                    'value' => $seq0 ? null : $k
                ]]);
                if (\is_array($v) && \array_key_exists('title', $v)) {
                    $t = $v['title'] ?? $k;
                    $option['disabled'] = isset($v['active']) && !$v['active'];
                    $option['title'] = $v['description'] ?? null;
                } else {
                    $t = $v;
                }
                $option[1] = \trim(\strip_tags(\i(...((array) $t))));
                // Add `1` to the end of the key so that bare option(s) will come last
                $the_options[$t . $k . '1'] = $option;
            }
        }
        $sort && \ksort($the_options);
        foreach ($the_options as $v) {
            $out[1] .= $v;
        }
        $is_active = !isset($value['active']) || $value['active'];
        $is_vital = !empty($value['vital']);
        $tags = \array_replace([
            'is:active' => $is_active,
            'is:vital' => $is_vital,
            'not:active' => !$is_active,
            'not:vital' => !$is_vital
        ], $value['tags'] ?? []);
        $out[2]['autofocus'] = !empty($value['focus']);
        $out[2]['disabled'] = !$is_active;
        $out[2]['id'] = $value['id'] ?? null;
        $out[2]['name'] = $value['name'] ?? $key;
        $out[2]['required'] = $is_vital;
        \x\panel\_class_set($out[2], $tags);
    }
    return "" !== $out[1] ? new \HTML($out) : null;
}

function separator($value, $key) {
    $out = [
        0 => $value[0] ?? 'hr',
        1 => $value[1] ?? false,
        2 => $value[2] ?? []
    ];
    \x\panel\_class_set($out[2], $value['tags'] ?? []);
    return new \HTML($out);
}

function stack($value, $key) {
    $out = [
        0 => $value[0] ?? 'div',
        1 => $value[1] ?? "",
        2 => \array_replace([
            'data-value' => $value['value'] ?? $key,
            'tabindex' => -1
        ], $value[2] ?? [])
    ];
    if (!isset($value[1])) {
        $is_active = !isset($value['active']) || $value['active'];
        $is_current = !empty($value['current']);
        $tags = \array_replace([
            'can:toggle' => !empty($value['toggle']),
            'is:active' => $is_active,
            'is:current' => $is_current,
            'lot' => true,
            'lot:stack' => true,
            'not:active' => !$is_active
        ], $value['tags'] ?? []);
        $out[1] .= '<h3 class="title">' . \x\panel\type\link([
            '2' => ['tabindex' => -1],
            'description' => $value['description'] ?? null,
            'icon' => $value['icon'] ?? [],
            'info' => $value['info'] ?? null,
            'link' => $value['link'] ?? null,
            'tags' => [
                'is:active' => $is_active,
                'is:current' => $is_current,
                'not:active' => !$is_active
            ],
            'target' => 'stack:' . ($value['value'] ?? $key),
            'title' => $value['title'] ?? null,
            'url' => $value['url'] ?? null
        ], $key) . '</h3>';
        $count = 1;
        if (isset($value['content'])) {
            $out[1] .= '<div class="content">';
            $out[1] .= \x\panel\to\content($value['content']);
            $out[1] .= '</div>';
            $tags['count:' . ($count = 2)] = true;
        } else if (isset($value['lot'])) {
            $out[1] .= '<div class="lot">';
            $out[1] .= \x\panel\to\lot($value['lot'], $count, $value['sort'] ?? true);
            $out[1] .= '</div>';
            $tags['count:' . $count] = true;
        }
        $out[1] .= \x\panel\type\tasks\link([
            '0' => 'p',
            'lot' => $value['tasks'] ?? []
        ], $key);
        \x\panel\_class_set($out[2], $tags);
        if (!$is_active) {
            unset($out[2]['tabindex']);
        }
    }
    return "" !== $out[1] ? new \HTML($out) : null;
}

function stacks($value, $key) {
    $name = $value['name'] ?? $key;
    $out = [
        0 => $value[0] ?? 'div',
        1 => $value[1] ?? "",
        2 => \array_replace([
            'data-name' => 'stack[' . $name . ']',
            'tabindex' => 0
        ], $value[2] ?? [])
    ];
    $has_current = false;
    if (!isset($value[1])) {
        $lot = [];
        if (isset($value['lot'])) {
            $first = \array_keys($value['lot'])[0] ?? null; // The first stack
            $current = $_GET['stack'][$name] ?? $value['current'] ?? $first ?? null;
            foreach ($value['lot'] as $k => $v) {
                if (false === $v || null === $v || !empty($v['skip'])) {
                    continue;
                }
                $kk = $v['value'] ?? $k;
                if (\is_array($v)) {
                    $v[2]['data-value'] = $kk;
                    if (null !== $current && $kk === $current && !\array_key_exists('current', $v)) {
                        $v['current'] = true;
                        $has_current = true;
                    }
                    if (empty($v['url']) && empty($v['link'])) {
                        $v['url'] = $GLOBALS['url']->query(['stack' => [$name => $kk]]);
                    } else {
                        $v['tags']['has:link'] = true;
                        if (!\array_key_exists('content', $v) && !\array_key_exists('lot', $v)) {
                            // Make sure link stack has a content to preserve the stack title
                            $v['content'] = \P;
                        }
                    }
                }
                $lot[$k] = $v;
            }
            if (!empty($value['sort'])) {
                $sort = $value['sort'];
                if (true === $sort) {
                    $sort = [1, 'stack', 10];
                }
                $lot = (new \Anemone($lot))->sort($sort)->get();
            }
        }
        $count = 0;
        foreach ($lot as $k => $v) {
            if (\is_array($v) && !\array_key_exists('type', $v)) {
                $v['type'] = 'stack';
            }
            $out[1] .= \x\panel\type($v, $k);
            ++$count;
        }
        unset($lot);
        \x\panel\_class_set($out[2], \array_replace([
            'count:' . $count => true,
            'has:current' => $has_current,
            'lot' => true,
            'lot:stacks' => true,
            'p' => true
        ], $value['tags'] ?? []));
    }
    return "" !== $out[1] ? new \HTML($out) : null;
}

function tab($value, $key) {
    unset($value['description'], $value['title']);
    $out = \x\panel\type\section($value, $key);
    if ($out && !isset($out['data-value'])) {
        $out['data-value'] = $value['value'] ?? $key;
    }
    return isset($out[1]) && "" !== $out[1] ? $out : null;
}

function tabs($value, $key) {
    $name = $value['name'] ?? $key;
    $out = [
        0 => $value[0] ?? 'div',
        1 => $value[1] ?? "",
        2 => \array_replace([
            'data-name' => 'tab[' . $name . ']',
            'tabindex' => 0
        ], $value[2] ?? [])
    ];
    $has_current = false;
    $has_gap = !isset($value['gap']) || $value['gap'];
    if (!isset($value[1])) {
        if (isset($value['content'])) {
            $out[1] .= \x\panel\to\content($value['content']);
        } else if (isset($value['lot'])) {
            $links = $sections = [];
            $sort = $value['sort'] ?? true;
            if (true === $sort) {
                $sort = [1, 'stack', 10];
            }
            $lot = (new \Anemone($value['lot']))->sort($sort, true)->get();
            $count = 0;
            foreach ($lot as $k => $v) {
                if (false === $v || null === $v || !empty($v['skip'])) {
                    continue;
                }
                $id = \substr(\uniqid(), 6);
                $kk = $v['value'] ?? $k;
                if (\is_array($v)) {
                    $v[3]['aria-controls'] = 'c:' . $id;
                    $v[3]['aria-selected'] = 'false';
                    $v[3]['data-value'] = $kk;
                    $v[3]['id'] = 't:' . $id;
                    $v[3]['role'] = 'tab';
                    $v[3]['tabindex'] = -1;
                    $v[3]['target'] = $v[2]['target'] ?? 'tab:' . $kk;
                    $v['tags']['can:toggle'] = !empty($v['toggle']);
                    if (empty($v['url']) && empty($v['link'])) {
                        $v['url'] = $GLOBALS['url']->query(['tab' => [$name => $kk]]);
                    } else {
                        $v['tags']['has:link'] = true;
                        if (!\array_key_exists('content', $v) && !\array_key_exists('lot', $v)) {
                            // Make sure link tab has a content to preserve the tab title
                            $v['content'] = \P;
                        }
                    }
                }
                $links[$kk] = $v;
                $sections[$kk] = $v;
                $sections[$kk][2]['aria-labelledby'] = 't:' . $id;
                $sections[$kk][2]['id'] = 'c:' . $id;
                $sections[$kk][2]['role'] = 'tabpanel';
                unset(
                    $links[$kk]['content'],
                    $links[$kk]['lot'],
                    $links[$kk]['type'],
                    $sections[$kk][2]['aria-controls'],
                    $sections[$kk][2]['aria-selected']
                );
            }
            $first = \array_keys($links)[0] ?? null; // The first tab
            $current = $_GET['tab'][$name] ?? $value['current'] ?? $first ?? null;
            if (null !== $current && isset($links[$current]) && \is_array($links[$current])) {
                $links[$current]['tags']['is:current'] = true;
                $links[$current][3]['aria-selected'] = 'true';
                $links[$current][3]['tabindex'] = 0;
                $sections[$current]['tags']['is:current'] = true;
                $has_current = true;
            } else if (null !== $first && isset($links[$first]) && \is_array($links[$first])) {
                $links[$first]['tags']['is:current'] = true;
                $links[$first][3]['aria-selected'] = 'true';
                $links[$first][3]['tabindex'] = 0;
                $sections[$first]['tags']['is:current'] = true;
                $has_current = true;
            }
            foreach ($sections as $k => $v) {
                // If `type` is not defined, the default value will be `tab`
                if (!\array_key_exists('type', $v)) {
                    $v['type'] = 'tab';
                }
                $vv = (string) \x\panel\type($v, $k);
                if ("" === $vv) {
                    unset($links[$k]);
                } else {
                    ++$count;
                }
                $sections[$k] = $vv;
            }
            if ($links) {
                $out[1] .= \x\panel\type\links([
                    '0' => 'nav',
                    '2' => ['tabindex' => null],
                    '3' => ['role' => 'tablist'],
                    'lot' => $links
                ], $name);
            }
            if ($sections) {
                $out[1] .= \implode("", $sections);
            }
        }
        if ($count < 2) {
            unset($out[2]['tabindex']);
        }
        \x\panel\_class_set($out[2], \array_replace([
            'count:' . $count => true,
            'has:current' => $has_current,
            'has:gap' => $has_gap,
            'lot' => true,
            'lot:tabs' => true,
            'p' => true
        ], $value['tags'] ?? []));
    }
    return "" !== $out[1] ? new \HTML($out) : null;
}

function tasks($value, $key) {
    $out = [
        0 => $value[0] ?? 'div',
        1 => $value[1] ?? "",
        2 => \array_replace(['tabindex' => -1], $value[2] ?? [])
    ];
    if (!isset($value[1])) {
        $tags = \array_replace([
            'lot' => true,
            'lot:tasks' => true
        ], $value['tags'] ?? []);
        $count = 0;
        if (isset($value['content'])) {
            $out[1] .= \x\panel\to\content($value['content']);
            $tags['count:' . ($count = 1)] = true;
        } else if (isset($value['lot'])) {
            $out[1] .= \x\panel\to\lot($value['lot'], $count, $value['sort'] ?? true);
            $tags['count:' . $count] = true;
        }
        if ($count > 0) {
            \x\panel\_class_set($out[2], $tags);
            return new \HTML($out);
        }
    }
    return "" !== $out[1] ? new \HTML($out) : null;
}

function textarea($value, $key) {
    $out = [
        0 => $value[0] ?? 'textarea',
        1 => \htmlspecialchars($value[1] ?? $value['value'] ?? ""),
        2 => $value[2] ?? []
    ];
    $has_pattern = \array_key_exists('pattern', $value);
    $is_active = !isset($value['active']) || $value['active'];
    $is_fix = !empty($value['fix']);
    $is_vital = !empty($value['vital']);
    $tags = \array_replace([
        'has:pattern' => $has_pattern,
        'is:active' => $is_active,
        'is:fix' => $is_fix,
        'is:vital' => $is_vital,
        'not:active' => !$is_active,
        'not:fix' => !$is_fix,
        'not:vital' => !$is_vital
    ], $value['tags'] ?? []);
    if ($has_pattern) {
        $out[2]['pattern'] = $value['pattern'];
    }
    $out[2]['autofocus'] = !empty($value['focus']);
    $out[2]['disabled'] = !$is_active;
    $out[2]['id'] = $value['id'] ?? null;
    $out[2]['maxlength'] = $value['max'] ?? null;
    $out[2]['minlength'] = $value['min'] ?? null;
    $out[2]['name'] = $value['name'] ?? $key;
    $out[2]['placeholder'] = \i(...((array) ($value['hint'] ?? [])));
    $out[2]['readonly'] = $is_fix;
    $out[2]['required'] = $is_vital;
    \x\panel\_class_set($out[2], $tags);
    return new \HTML($out);
}

function title($value, $key) {
    $icon = $value['icon'] ?? [];
    $info = $value['info'] ?? "";
    $title = $value[1] ?? $value['content'] ?? "";
    $title = \w('<!--0-->' . \i(...((array) $title)), ['a', 'abbr', 'b', 'code', 'del', 'em', 'i', 'ins', 'small', 'strong', 'sub', 'sup']);
    if ('0' !== $title && !$title && !$icon) {
        return;
    }
    $out = [
        0 => $value[0] ?? [
           -1 => 'span',
            0 => 'p',
            1 => 'h1',
            2 => 'h2',
            3 => 'h3',
            4 => 'h4',
            5 => 'h5',
            6 => 'h6'
        ][$level = $value['level'] ?? 1] ?? false,
        1 => "",
        2 => $value[2] ?? []
    ];
    $icon = \x\panel\to\icon($value['icon'] ?? []);
    if ("" !== $info) {
        $title = \trim($title . ' <small class="info">' . \i(...((array) $info)) . '</small>');
    }
    $out[1] = $icon[0] . ("" !== $title ? '<span>' . $title . '</span>' : "") . $icon[1];
    if (false !== $out[0] && isset($value['description'])) {
        $out[2]['title'] = \i(...((array) $value['description']));
    }
    \x\panel\_class_set($out[2], \array_replace([
        'has:icon' => !!($icon[0] || $icon[1]),
        'has:title' => !!$title,
        'level:' . $level => $level >= 0,
        'title' => true
    ], $value['tags'] ?? []));
    return new \HTML($out);
}

require __DIR__ . \D . 'type' . \D . 'button.php';
require __DIR__ . \D . 'type' . \D . 'field.php';
require __DIR__ . \D . 'type' . \D . 'form.php';
require __DIR__ . \D . 'type' . \D . 'input.php';
require __DIR__ . \D . 'type' . \D . 'link.php';
require __DIR__ . \D . 'type' . \D . 'select.php';
require __DIR__ . \D . 'type' . \D . 'tasks.php';
require __DIR__ . \D . 'type' . \D . 'textarea.php';