<?php namespace x\panel\type;

function bar($value, $key) {
    $value['level'] = $value['level'] ?? 1;
    $value['tags']['p'] = $value['tags']['p'] ?? false;
    if (isset($value['content'])) {
        $out = \x\panel\type\content($value, $key);
    } else if (isset($value['lot'])) {
        foreach ($value['lot'] as &$v) {
            if (\is_array($v)) {
                $v['tags']['p'] = $v['tags']['p'] ?? false;
                // If `type` is not defined, the default value will be `links`
                $v['type'] = $v['type'] ?? 'links';
            }
        }
        unset($v);
        $out = \x\panel\type\lot($value, $key);
    }
    $out['tabindex'] = -1;
    $out[0] = $out[0] ?? 'nav';
    return $out;
}

function button($value, $key) {
    $not_active = isset($value['active']) && !$value['active'];
    $value['not']['active'] = $value['not']['active'] ?? $not_active;
    $out = \x\panel\type\link($value, $key);
    $out['disabled'] = $not_active;
    $out['name'] = $value['name'] ?? $key;
    $out['value'] = $value['value'] ?? null;
    $out[0] = 'button';
    unset($out['href'], $out['target']);
    return $out;
}

function card($value, $key) {
    $value['tags']['lot:card'] = $value['tags']['lot:card'] ?? true;
    $value['tags']['lot:page'] = $value['tags']['lot:page'] ?? false;
    $value['tasks']['title'] = \array_replace($value['tasks']['title'] ?? [], [
        'description' => $value['description'] ?? null,
        'link' => $value['link'] ?? null,
        'stack' => -1,
        'title' => $value['title'] ?? null,
        'url' => $value['url'] ?? null,
        'with' => ['title' => true]
    ]);
    $value['description'] = $value['title'] = false;
    unset($value['link'], $value['url']);
    return \x\panel\type\page($value, $key);
}

function cards($value, $key) {
    $value['tags']['lot:cards'] = true;
    $value['tags']['lot:pages'] = false;
    return \x\panel\type\pages($value, $key);
}

function column($value, $key) {
    $value['tags']['p'] = $value['tags']['p'] ?? false;
    if (!empty($value['size']) && !isset($value['tags']['size:' . $value['size']])) {
        $value['tags']['size:' . $value['size']] = true;
    }
    if (isset($value['content'])) {
        return \x\panel\type\content($value, $key);
    }
    if (isset($value['lot'])) {
        foreach ($value['lot'] as &$v) {
            if (\is_array($v)) {
                $v['type'] = $v['type'] ?? 'content';
            }
        }
        unset($v);
        return \x\panel\type\lot($value, $key);
    }
}

function columns($value, $key) {
    $value['has']['gap'] = $value['has']['gap'] ?? (!\array_key_exists('gap', $value) || $value['gap']);
    $value['tags']['p'] = $value['tags']['p'] ?? false;
    $value[0] = $value[0] ?? 'div';
    $value[1] = $value[1] ?? "";
    if (isset($value['content'])) {
        return \x\panel\type\content($value, $key);
    }
    if (isset($value['lot'])) {
        foreach ($value['lot'] as &$v) {
            if (\is_array($v)) {
                $v['type'] = $v['type'] ?? 'column';
            }
        }
        unset($v);
        return \x\panel\type\lot($value, $key);
    }
}

function content($value, $key) {
    $value = \x\panel\_value_set($value);
    $count = 0;
    if ($description = \x\panel\to\description($value['description'] ?? "")) {
        ++$count;
    }
    if ($title = \x\panel\to\title($value['title'] ?? "", $value['level'] ?? 2)) {
        ++$count;
    }
    $value[0] = $value[0] ?? 'div';
    $value[1] = $value[1] ?? $title . $description;
    if (isset($value['content'])) {
        $value[1] .= \x\panel\to\content($value['content']);
        ++$count;
    }
    $tags = [
        'content' => true,
        'count:' . $count => true,
        'p' => true
    ];
    if ($type = $value['type'] ?? null) {
        foreach (\step($type, '/') as $v) {
            $tags['content:' . $v] = true;
        }
    }
    $value['tags'] = \array_replace($tags, $value['tags'] ?? []);
    $value[2] = \x\panel\_style_set($value[2], $value);
    $value[2] = \x\panel\_tag_set($value[2], $value);
    return new \HTML($value);
}

function description($value, $key) {
    $description = $value[1] ?? $value['content'] ?? "";
    $description = \w('<!--0-->' . \i(...((array) $description)), ['a', 'abbr', 'b', 'code', 'del', 'em', 'i', 'ins', 'span', 'strong', 'sub', 'sup']);
    if ('0' !== $description && !$description) {
        return null;
    }
    $value['tags']['description'] = $value['tags']['description'] ?? true;
    $value[0] = $value[0] ?? 'p';
    $value[1] = $description;
    $value[2] = \x\panel\_style_set($value[2], $value);
    $value[2] = \x\panel\_tag_set($value[2], $value);
    return new \HTML($value);
}

function desk($value, $key) {
    $value[0] = $value[0] ?? 'main';
    $value[1] = $value[1] ?? "";
    $value[2]['tabindex'] ?? $value[2]['tabindex'] ?? -1;
    $styles = $tags =[];
    $tags['p'] = $value['tags']['p'] ?? false;
    if (isset($value['width']) && false !== $value['width']) {
        $value['has']['width'] = $value['has']['width'] ?? true;
        if (true !== $value['width']) {
            $styles['width'] = \is_int($value['width']) ? $value['width'] . 'px' : $value['width'];
        }
    }
    $value['styles'] = \array_replace($styles, $value['styles'] ?? []);
    $value['tags'] = \array_replace($tags, $value['tags'] ?? []);
    $value[2] = \x\panel\_style_set($value[2], $value);
    $value[2] = \x\panel\_tag_set($value[2], $value);
    if (isset($value['content'])) {
        if ($out = \x\panel\type\content($value, $key)) {
            $out[0] = $out[0] ?? 'main';
            $out['tabindex'] = $out['tabindex'] ?? -1;
            return $out;
        }
    }
    if (isset($value['lot'])) {
        foreach ($value['lot'] as &$v) {
            if (\is_array($v)) {
                $v['type'] = $v['type'] ?? 'section';
            }
        }
        unset($v);
        if ($out = \x\panel\type\lot($value, $key)) {
            $out[0] = $out[0] ?? 'main';
            $out['tabindex'] = $out['tabindex'] ?? -1;
            return $out;
        }
    }
}

function field($value, $key) {
    $has_description = !empty($value['description']);
    $has_pattern = !empty($value['pattern']);
    $has_title = !empty($value['title']);
    $is_active = !isset($value['active']) || $value['active'];
    $is_fix = !empty($value['fix']);
    $is_vital = !empty($value['vital']);
    $styles = [];
    $tags = [
        'lot' => true,
        'lot:field' => true,
        'p' => true
    ];
    $value['has']['description'] = $value['has']['description'] ?? $has_description;
    $value['has']['pattern'] = $value['has']['pattern'] ?? $has_pattern;
    $value['has']['title'] = $value['has']['title'] ?? $has_title;
    $value['is']['active'] = $value['is']['active'] ?? $is_active;
    $value['is']['fix'] = $value['is']['fix'] ?? $is_fix;
    $value['is']['vital'] = $value['is']['vital'] ?? $is_vital;
    $value['not']['active'] = !$value['is']['active'];
    $value['not']['fix'] = !$value['is']['fix'];
    $value['not']['vital'] = !$value['is']['vital'];
    $value[0] = $value[0] ?? 'div';
    $value[1] = $value[1] ?? "";
    if ($type = $value['type'] ?? "") {
        if (0 === \strpos($type, 'field/')) {
            $tags['type:' . \substr($type, 6)] = true;
        }
    }
    $id = $value['id'] ?? 'f:' . \dechex(\time());
    if (!isset($value['title']) || false !== $value['title']) {
        $title = \x\panel\to\title($value['title'] ?? \To::title($key), -2);
        $value[1] .= new \HTML([
            0 => 'label',
            1 => $title,
            2 => [
                'class' => 'count:' . ("" === \trim(\strip_tags($title ?? "")) ? '0' : '1'),
                'for' => $id
            ]
        ]);
        $value['has']['title'] = true;
    }
    $after = $before = "";
    foreach (['after', 'before'] as $v) {
        if (isset($value['value-' . $v])) {
            $vv = $value['value-' . $v];
            if (\is_string($vv)) {
                ${$v} = '<span class="fix"><span>' . $vv . '</span></span>';
            } else if (\is_array($vv)) {
                $icon = \x\panel\to\icon($vv['icon'] ?? []);
                $icon[0][2] = \x\panel\_tag_set($icon[0][2], ['tags' => ['fix' => true]]);
                ${$v} = $icon[0];
            }
        }
    }
    $tags_field = [];
    if (isset($value['height']) && false !== $value['height']) {
        $tags_field['has:height'] = true;
        if (true !== $value['height']) {
            $styles['height'] = \is_int($value['height']) ? $value['height'] . 'px' : $value['height'];
        }
    }
    if (isset($value['width']) && false !== $value['width']) {
        $tags_field['has:width'] = true;
        if (true !== $value['width']) {
            $styles['width'] = \is_int($value['width']) ? $value['width'] . 'px' : $value['width'];
        }
    }
    $value[1] .= '<div>';
    // Special value returned by `x\panel\to\field()`
    if (isset($value['field'])) {
        if (\is_array($value['field'])) {
            $value['field'][2] = \x\panel\_style_set($value['field'][2], ['styles' => $styles]);
            $value['field'][2] = \x\panel\_tag_set($value['field'][2], ['tags' => $tags_field]);
        }
        $content = (string) \x\panel\to\content($value['field']);
        $value[1] .= new \HTML([
            0 => 'div',
            1 => $before . $content . $after,
            2 => [
                'class' => \implode(' ', \array_keys(\array_filter([
                    'count:' . ("" === $content ? '0' : '1') => true,
                    'has:height' => !empty($value['height']),
                    'has:width' => !empty($value['width']),
                    'with:fields' => true
                ]))),
                'role' => 'group'
            ]
        ]);
    } else if (isset($value['content'])) {
        if (\is_array($value['content'])) {
            $value['content'][2] = \x\panel\_style_set($value['content'][2], ['styles' => $styles]);
            $value['content'][2] = \x\panel\_tag_set($value['content'][2], ['tags' => $tags_field]);
        }
        $content = (string) \x\panel\to\content($value['content']);
        $value[1] .= new \HTML([
            0 => 'div',
            1 => $before . $content . $after,
            2 => ['class' => 'content count:' . ("" === $content ? '0' : '1')]
        ]);
    } else if (isset($value['lot'])) {
        $count = 0;
        $content = (string) \x\panel\to\lot($value['lot'], $count, $value['sort'] ?? true);
        $value[1] .= new \HTML([
            0 => 'div',
            1 => $content,
            2 => ['class' => 'count:' . $count . ' lot']
        ]);
    }
    $value['tags'] = $tags;
    $value[1] .= \x\panel\to\description($value['description'] ?? "");
    $value[1] .= '</div>';
    $value[2] = \x\panel\_style_set($value[2], $value);
    $value[2] = \x\panel\_tag_set($value[2], $value);
    if (isset($value['data']) && \is_array($value['data']) && $data = \To::query($value['data'])) {
        foreach (\explode('&', \substr($data, 1)) as $v) {
            $vv = \explode('=', $v, 2);
            $value[1] .= new \HTML(['input', false, [
                'name' => \urldecode($vv[0]),
                'type' => 'hidden',
                'value' => \urldecode($vv[1] ?? 'true')
            ]]);
        }
    }
    return new \HTML($value);
}

function fields($value, $key) {
    $value[0] = $value[0] ?? 'div';
    $value[1] = $value[1] ?? "";
    $bottom = "";
    $description = \x\panel\to\description($value['description'] ?? "");
    $title = \x\panel\to\title($value['title'] ?? "", $value['level'] ?? 3);
    if (isset($value['content'])) {
        $value[1] .= \x\panel\to\content($value['content']);
    } else if (isset($value['lot'])) {
        \x\panel\_type_parent_set($value['lot'], 'field');
        foreach ((new \Anemone($value['lot']))->sort([1, 'stack', 10], true) as $k => &$v) {
            if (false === $v || null === $v || !empty($v['skip'])) {
                continue;
            }
            $v = \x\panel\_value_set($v, $k);
            $type = \strtolower(\f2p(\strtr($v['type'] ?? "", '-', '_')));
            if ("" !== $type && \function_exists($fn = __NAMESPACE__ . "\\" . $type)) {
                if ('field/hidden' !== $type) {
                    $value[1] .= \call_user_func($fn, $v, $k);
                } else {
                    $bottom .= \x\panel\type\field\hidden($v, $k);
                }
            } else {
                $bottom .= \x\panel\_abort($value, $key, $fn);
            }
            unset($v);
        }
        // Put all hidden field(s) at the bottom
        $value[1] .= $bottom;
    }
    $value['tags'] = \array_replace([
        'lot' => true,
        'lot:fields' => true,
        'p' => true
    ], $value['tags'] ?? []);
    $value[1] = $title . $description . $value[1];
    $value[2] = \x\panel\_style_set($value[2], $value);
    $value[2] = \x\panel\_tag_set($value[2], $value);
    return new \HTML($value);
}

function file($value, $key) {
    $has_description = !empty($value['description']);
    $has_icon = !empty($value['icon']);
    $has_title = !empty($value['title']);
    $is_active = !isset($value['active']) || $value['active'];
    $is_current = !empty($value['current']);
    $value['has']['description'] = $has_description;
    $value['has']['icon'] = $has_icon;
    $value['has']['title'] = $has_title;
    $value['is']['active'] = $is_active;
    $value['is']['current'] = $is_current;
    $value['is']['file'] = true;
    $value['not']['active'] = !$is_active;
    $value['tags'] = \array_replace([
        'lot' => true,
        'lot:file' => true
    ], $value['tags'] ?? []);
    $value[0] = $value[0] ?? 'li';
    $value[1] = $value[1] ?? "";
    $value[2]['tabindex'] ?? $value[2]['tabindex'] ?? 0;
    $value[1] .= '<h3 class="title">' . \x\panel\type\link(\x\panel\_value_set([
        'description' => $value['description'] ?? null,
        'icon' => $value['icon'] ?? [],
        'info' => $value['info'] ?? null,
        'link' => $value['link'] ?? null,
        'target' => $value['target'] ?? null,
        'title' => $value['title'] ?? null,
        'url' => $value['url'] ?? null
    ], $key), $key) . '</h3>';
    $value[1] .= \x\panel\type\tasks\link(\x\panel\_value_set([
        '0' => 'p',
        'lot' => $value['tasks'] ?? []
    ], $key), $key);
    $value[2] = \x\panel\_style_set($value[2], $value);
    $value[2] = \x\panel\_tag_set($value[2], $value);
    if (!$is_active) {
        unset($value[2]['tabindex']);
    }
    return new \HTML($value);
}

function files($value, $key) {
    $value[0] = $value[0] ?? 'ul';
    $value[1] = $value[1] ?? "";
    $value[2]['tabindex'] = $value[2]['tabindex'] ?? -1;
    if (isset($value['content'])) {
        $count = 1;
        $value[1] .= \x\panel\to\content($value['content']);
    } else if (isset($value['lot'])) {
        $lot = [];
        foreach ($value['lot'] as $k => $v) {
            if (false === $v || null === $v || !empty($v['skip'])) {
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
        $count = 0;
        $count_files = 0;
        $count_folders = 0;
        foreach ($lot as $k => $v) {
            if (\is_array($v)) {
                if (!isset($v['type'])) {
                    $v['type'] = 'file';
                    ++$count_files;
                } else if ('file' === $v['type']) {
                    ++$count_files;
                } else if ('folder' === $v['type']) {
                    ++$count_folders;
                }
            }
            $value[1] .= \x\panel\type($v, $k);
            ++$count;
        }
        unset($lot);
    }
    $value['tags'] = \array_replace([
        'count:' . $count => true,
        'lot' => true,
        'lot:files' => !!$count_files,
        'lot:folders' => !!$count_folders
    ], $value['tags'] ?? []);
    $value[2] = \x\panel\_style_set($value[2], $value);
    $value[2] = \x\panel\_tag_set($value[2], $value);
    return new \HTML($value);
}

function flex($value, $key) {
    $lot = (array) ($value['lot'] ?? []);
    foreach ($lot as &$v) {
        if (isset($v['title'])) {
            $v['level'] = $v['level'] ?? 3;
        }
    }
    unset($v);
    $value['lot'] = [
        'title' => [
            'content' => $value['title'] ?? null,
            'level' => $value['level'] ?? 2, // Same with the default level of `x\panel\type\content()`
            'stack' => 10,
            'type' => 'title'
        ],
        'description' => [
            'content' => $value['description'] ?? null,
            'stack' => 20,
            'type' => 'description'
        ],
        'lot' => [
            'lot' => $lot,
            'stack' => 30,
            'type' => 'lot'
        ]
    ];
    unset($value['description'], $value['title']);
    $value['lot']['lot']['can']['flex'] = $value['lot']['lot']['can']['flex'] ?? true;
    $value['lot']['lot']['has']['gap'] = $value['lot']['lot']['has']['gap'] ?? true;
    return \x\panel\type\lot($value, $key);
}

function folder($value, $key) {
    $has_description = !empty($value['description']);
    $has_icon = !empty($value['icon']);
    $has_title = !empty($value['title']);
    $is_active = !isset($value['active']) || $value['active'];
    $is_current = !empty($value['current']);
    $value['has']['description'] = $has_description;
    $value['has']['icon'] = $has_icon;
    $value['has']['title'] = $has_title;
    $value['is']['active'] = $is_active;
    $value['is']['current'] = $is_current;
    $value['is']['folder'] = true;
    $value['not']['active'] = !$is_active;
    $value['tags'] = \array_replace([
        'lot' => true,
        'lot:folder' => true
    ], $value['tags'] ?? []);
    $value[0] = $value[0] ?? 'li';
    $value[1] = $value[1] ?? "";
    $value[2]['tabindex'] = $value[2]['tabindex'] ?? 0;
    $value[1] .= '<h3 class="title">' . \x\panel\type\link(\x\panel\_value_set([
        'description' => $value['description'] ?? \i('Open %s', 'Folder'),
        'icon' => $value['icon'] ?? [],
        'info' => $value['info'] ?? null,
        'link' => $value['link'] ?? null,
        'target' => $value['target'] ?? null,
        'title' => $value['title'] ?? null,
        'url' => $value['url'] ?? null
    ], $key), $key) . '</h3>';
    $value[1] .= \x\panel\type\tasks\link(\x\panel\_value_set([
        '0' => 'p',
        'lot' => $value['tasks'] ?? []
    ], $key), $key);
    $value[2] = \x\panel\_style_set($value[2], $value);
    $value[2] = \x\panel\_tag_set($value[2], $value);
    if (!$is_active) {
        unset($value[2]['tabindex']);
    }
    return new \HTML($value);
}

// NOTE: `folders` is actually just an alias for `files`. Thus, declaring a `folders` block would not give
// any difference from `files`. It’s just that the default value of item’s `type` will be automatically set to
// `folder` instead of `file`. Otherwise, they will be treated the same. Both will get a class `lot:files`
// and/or `lot:folders`, according to the available item(s). Use those class(es) to detect whether this block
// contains only file(s) or only folder(s), or if both are present.
function folders($value, $key) {
    if (isset($value['lot'])) {
        foreach ($value['lot'] as &$v) {
            if (\is_array($v)) {
                $v['type'] = $v['type'] ?? 'folder';
            }
        }
        unset($v);
    }
    return \x\panel\type\files($value, $key);
}

function form($value, $key) {
    $value[0] = $value[0] ?? 'form';
    $value[1] = $value[1] ?? "";
    if (isset($value['active']) && empty($value['active'])) {
        // Set node name to `false` to remove the `<form>` element
        $value[0] = false;
    }
    if (isset($value['content'])) {
        $value[1] .= \x\panel\to\content($value['content']);
    } else if (isset($value['lot'])) {
        $count = 0;
        $value[1] .= \x\panel\to\lot($value['lot'], $count, $value['sort'] ?? true);
    }
    if (isset($value['data']) && \is_array($value['data']) && $data = \To::query($value['data'])) {
        foreach (\explode('&', \substr($data, 1)) as $v) {
            $vv = \explode('=', $v, 2);
            $value[1] .= new \HTML(['input', false, [
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
    if (!isset($value[2]['action'])) {
        $value[2]['action'] = $href;
    }
    if (!isset($value[2]['name'])) {
        $value[2]['name'] = $value['name'] ?? $key;
    }
    $value[2] = \x\panel\_style_set($value[2], $value);
    $value[2] = \x\panel\_tag_set($value[2], $value);
    return new \HTML($value);
}

function icon($value, $key) {
    $icons = \array_replace([null, null], (array) ($value['content'] ?? $value['lot'] ?? []));
    $ref = $GLOBALS['_']['icon'] ?? [];
    $attr = [
        'class' => 'icon',
        'height' => 24,
        'width' => 24
    ];
    foreach ($icons as $k => &$v) {
        if (!$v) {
            continue;
        }
        if (false === \strpos($v, '<')) {
            // Named icon(s)
            if (isset($ref[$v])) {
                $v = new \HTML(['svg', '<use href="#i:' . $v . '"></use>', $attr]);
            // Inline icon(s)
            } else {
                if (!isset($ref[$id = \dechex(\crc32($v))])) {
                    $GLOBALS['_']['icon'][$id] = $v;
                }
                $v = new \HTML(['svg', '<use href="#i:' . $id . '"></use>', $attr]);
            }
        } else if ('</svg>' !== \substr($v, -6)) {
            $v = new \HTML(['svg', $v, $attr]);
        }
    }
    unset($v);
    return $icons;
}

function input($value, $key) {
    $has_pattern = !empty($value['pattern']);
    $is_active = !isset($value['active']) || $value['active'];
    $is_fix = !empty($value['fix']);
    $is_vital = !empty($value['vital']);
    $value['has']['pattern'] = $has_pattern;
    $value['is']['active'] = $is_active;
    $value['is']['fix'] = $is_fix;
    $value['is']['vital'] = $is_vital;
    $value['not']['active'] = !$is_active;
    $value['not']['fix'] = !$is_fix;
    $value['not']['vital'] = !$is_vital;
    $value[0] = $value[0] ?? 'input';
    $value[1] = $value[1] ?? false;
    if ($has_pattern) {
        $value[2]['pattern'] = $value['pattern'];
    }
    $id = $value['id'] ?? \substr(\uniqid(), 6);
    if (0 === \strpos($id, 'f:')) {
        $id = \substr($id, 2);
    }
    if (!empty($value['lot']) && \is_array($value['lot'])) {
        $GLOBALS['_']['data-list'][$id] = $value['lot'];
        $value[2]['list'] = 'l:' . $id;
    }
    $value[2]['autofocus'] = !empty($value['focus']);
    $value[2]['disabled'] = !$is_active;
    $value[2]['id'] = 'f:' . $id;
    $value[2]['maxlength'] = $value['max'] ?? null;
    $value[2]['minlength'] = $value['min'] ?? null;
    $value[2]['name'] = $value['name'] ?? $key;
    $value[2]['placeholder'] = \i(...((array) ($value['hint'] ?? [])));
    $value[2]['readonly'] = $is_fix;
    $value[2]['required'] = $is_vital;
    $value[2]['value'] = $value['value'] ?? null;
    $value[2] = \x\panel\_style_set($value[2], $value);
    $value[2] = \x\panel\_tag_set($value[2], $value);
    return new \HTML($value);
}

function link($value, $key) {
    $href = $value['link'] ?? $value['url'] ?? \P;
    if (\is_array($href)) {
        $href = \x\panel\to\link($href);
    }
    $info = $value['info'] ?? "";
    $value['not']['active'] = $not_active = $value['not']['active'] ?? (\P === $href || (isset($value['active']) && !$value['active']));
    $value[0] = $value[0] ?? 'a';
    $value[1] = $value[1] ?? (string) \x\panel\type\title(\x\panel\_value_set([
        'content' => $value['title'] ?? \To::title($key),
        'description' => $value['description'] ?? null,
        'icon' => $value['icon'] ?? [],
        'info' => $info,
        'level' => -1
    ], $key), $key);
    $value[2]['id'] = $value[2]['id'] ?? $value['id'] ?? null;
    if (!$not_active && \P !== $href) {
        $value[2]['href'] = $href;
        $value[2]['target'] = $value[2]['target'] ?? $value['target'] ?? (isset($value['link']) ? '_blank' : null);
    }
    $value[2] = \x\panel\_style_set($value[2], $value);
    $value[2] = \x\panel\_tag_set($value[2], $value);
    return new \HTML($value);
}

function links($value, $key) {
    $value['tags']['lot:links'] = $value['tags']['lot:links'] ?? true;
    $value['tags']['lot:menu'] = $value['tags']['lot:menu'] ?? false;
    $value[2]['tabindex'] = $value[2]['tabindex'] ?? -1;
    return \x\panel\type\menu(\x\panel\_value_set($value), $key, -1);
}

function lot($value, $key) {
    $value = \x\panel\_value_set($value);
    $count = 0;
    if ($description = \x\panel\to\description($value['description'] ?? "")) {
        ++$count;
    }
    if ($title = \x\panel\to\title($value['title'] ?? "", $value['level'] ?? 2)) {
        ++$count;
    }
    $value[0] = $value[0] ?? 'div';
    $value[1] = $value[1] ?? $title . $description;
    if (isset($value['lot'])) {
        $value[1] .= \x\panel\to\lot($value['lot'], $count, $value['sort'] ?? true);
    }
    $tags = [
        'count:' . $count => true,
        'lot' => true,
        'p' => true
    ];
    if ($type = $value['type'] ?? null) {
        foreach (\step($type, '/') as $v) {
            $tags['lot:' . $v] = true;
        }
    }
    $value['tags'] = \array_replace($tags, $value['tags'] ?? []);
    $value[2] = \x\panel\_style_set($value[2], $value);
    $value[2] = \x\panel\_tag_set($value[2], $value);
    return new \HTML($value);
}

function menu($value, $key, int $i = 0) {
    $value[0] = $value[0] ?? 'div';
    $value[1] = $value[1] ?? "";
    $value[2]['tabindex'] = $value[2]['tabindex'] ?? 0;
    $count_parent = 0;
    $tags = \array_replace([
        'lot' => true,
        'lot:menu' => true,
        'p' => true
    ], $value['tags'] ?? []);
    if ($description = \x\panel\to\description($value['description'] ?? "")) {
        ++$count_parent;
    }
    if ($title = \x\panel\to\title($value['title'] ?? "", $value['level'] ?? 4)) {
        ++$count_parent;
    }
    if (isset($value['content'])) {
        $tags['count:' . ($count_parent + 1)] = true;
        $value[1] .= \x\panel\to\content($value['content']);
    } else if (isset($value['lot'])) {
        $count = 0;
        foreach ((new \Anemone($value['lot']))->sort([1, 'stack', 10], true) as $k => $v) {
            if (false === $v || null === $v || !empty($v['skip'])) {
                continue;
            }
            $vv = [
                0 => 'li',
                1 => "",
                2 => ['role' => 'none']
            ];
            if (!\is_array($v)) {
                $vv[1] = \x\panel\type\link(\x\panel\_value_set([
                    '2' => [
                        'aria-disabled' => 'true',
                        'role' => 'menuitem',
                        'tabindex' => $i < 0 ? null : -1
                    ],
                    'title' => $v
                ], $k), $k);
                $vv['not']['active'] = true;
                $vv[2] = \x\panel\_tag_set($vv[2], $vv);
                $value[1] .= new \HTML($vv);
                ++$count;
                continue;
            }
            $v = \x\panel\_value_set($v, $k);
            $vv[0] = $v[0] ?? $vv[0];
            $vv[1] = $v[1] ?? $vv[1];
            $vv[2] = $v[2] ?? $vv[2];
            if ("" === $vv[1]) {
                if (\array_key_exists('icon', $v)) {
                    $v['icon'] = (array) $v['icon'];
                }
                if (\array_key_exists('menu', $v)) {
                    // TODO
                }
                // If `type` is not defined, the default value will be `menu`
                $v['type'] = $v['type'] ?? 'menu';
                if ('separator' === $v['type']) {
                    $v['as']['separator'] = true;
                    $vv[2] = \x\panel\_style_set($vv[2], $v);
                    $vv[2] = \x\panel\_tag_set($vv[2], $v);
                    $vv[2]['aria-orientation'] = $i < 0 ? 'horizontal' : 'vertical';
                    $vv[2]['role'] = 'separator';
                    $value[1] .= new \HTML($vv);
                    ++$count;
                    continue;
                }
                $has_caret = false;
                if (!empty($v['lot']) && (!empty($v['caret']) || !\array_key_exists('caret', $v))) {
                    $v['icon'][1] = $v['caret'] ?? ($i < 0 ? 'M7,10L12,15L17,10H7Z' : 'M10,17L15,12L10,7V17Z');
                    $has_caret = true;
                }
                $v['icon'] = \x\panel\to\icon($v['icon'] ?? []);
                if ($has_caret) {
                    $v['icon'][1][2] = \x\panel\_tag_set($v['icon'][1][2], ['tags' => ['caret' => true]]);
                }
                $is_active = !isset($v['active']) || $v['active'];
                $is_current = !empty($v['current']);
                // $v['is']['active'] = $is_active;
                $v['is']['current'] = $is_current;
                $v['not']['active'] = !$is_active;
                if ('menu' === $v['type']) {
                    $v[2] = \array_replace([
                        'role' => 'menuitem',
                        'tabindex' => $i < 0 ? null : -1
                    ], $v[3] ?? []);
                    $vv[1] = \x\panel\type\link($v, $k);
                    if (!empty($v['lot'])) {
                        $vv[1]['aria-expanded'] = 'false';
                        $vv[1]['aria-haspopup'] = 'true';
                        $vv[1] .= \x\panel\type\menu(\array_replace($v, [
                            '2' => [
                                'role' => null,
                                'tabindex' => -1
                            ]
                        ]), $k, $i + 1); // Recurse!
                        if ($i < 0) {
                            $v['has']['menu'] = $v['has']['menu'] ?? true;
                        }
                    }
                } else {
                    if (0 === \strpos($v['type'] . '/', 'form/')) {
                        $v['has']['form'] = $v['has']['form'] = true;
                    }
                    $vv[1] = \x\panel\type($v, $k);
                }
                $vv[2] = \x\panel\_tag_set($vv[2], $v);
                $value[1] .= new \HTML($vv);
            }
            ++$count;
        }
        $tags['count:' . ($count_parent + ($count ? 1 : 0))] = true;
    }
    $value['tags'] = $tags;
    $value[2] = \x\panel\_style_set($value[2], $value);
    $value[2] = \x\panel\_tag_set($value[2], $value);
    if ("" !== $value[1]) {
        $value[1] = '<ul class="count:' . $count . '" role="' . ($value[3]['role'] ?? 'menu' . ($i < 0 ? 'bar' : "")) . '">' . $value[1] . '</ul>';
    }
    $value[1] = $title . $description . $value[1];
    return new \HTML($value);
}

function page($value, $key) {
    $has_description = !empty($value['description']);
    $has_icon = !empty($value['icon']);
    $has_image = !empty($value['image']);
    $has_title = !empty($value['title']);
    $is_active = !isset($value['active']) || $value['active'];
    $is_current = !empty($value['current']);
    $value['has']['description'] = $value['has']['description'] ?? $has_description;
    $value['has']['icon'] = $value['has']['icon'] ?? $has_icon;
    $value['has']['image'] = $value['has']['image'] ?? $has_image;
    $value['has']['title'] = $value['has']['title'] ?? $has_title;
    $value['is']['active'] = $value['is']['active'] ?? $is_active;
    $value['is']['current'] = $value['is']['current'] ?? $is_current;
    $value['is']['file'] = true;
    $value['not']['active'] = !$is_active;
    $value[0] = $value[0] ?? 'li';
    $value[1] = $value[1] ?? "";
    $value[2]['tabindex'] = $value[2]['tabindex'] ?? 0;
    $tags = \array_replace([
        'lot' => true,
        'lot:page' => true
    ], $value['tags'] ?? []);
    $div = new \HTML(['div', "", []]);
    $icon = $value['icon'] ?? false; // Prioritize `icon`, but remember that its default value is `false`
    $image = $value['image'] ?? "";
    $time = isset($value['time']) ? \strtr($value['time'], '-', '/') : null;
    if (false === $icon && false === $image) {
        $div['hidden'] = true;
    // Prioritize `icon` over `image`
    } else if (!empty($icon)) {
        $color = $value['color'] ?? '#fff';
        $fill = $value['fill'] ?? '#' . \substr(\md5($icon), 0, 6);
        $div[1] = '<span class="image" role="img" style="background: ' . $fill . '; color: ' . $color . ';">' . \x\panel\to\icon($icon)[0] . '</span>';
    } else if (!empty($image)) {
        $div[1] = '<img alt="" class="image" height="72" loading="lazy" src="' . \htmlspecialchars($image) . '" width="72">';
    } else {
        $color = $value['color'] ?? '#fff';
        $fill = $value['fill'] ?? '#' . \substr(\md5(\strtr($key, [
            \PATH => "",
            \D => '/'
        ])), 0, 6);
        $div[1] = '<span class="image" role="img" style="background: ' . $fill . '; color: ' . $color . ';"></span>';
    }
    $value[1] .= $div;
    $description = $value['description'] ?? $time;
    $title = $value['title'] ?? $time;
    if (false !== $description || false !== $title) {
        $value[1] .= '<div><h3 class="title">' . \x\panel\type\link(\x\panel\_value_set([
            'link' => $value['link'] ?? null,
            'target' => $value['target'] ?? null,
            'title' => $title,
            'url' => $value['url'] ?? null
        ], $key), $key) . '</h3>' . \x\panel\to\description($description) . '</div>';
    }
    $value[1] .= '<div>' . \x\panel\type\tasks\link(\x\panel\_value_set([
        '0' => 'p',
        'lot' => $value['tasks'] ?? []
    ], $key), $key) . '</div>';
    $value['tags'] = $tags;
    $value[2] = \x\panel\_style_set($value[2], $value);
    $value[2] = \x\panel\_tag_set($value[2], $value);
    if (!$is_active) {
        unset($value[2]['tabindex']);
    }
    return new \HTML($value);
}

function pager($value, $key) {
    $content = (string) \x\panel\to\pager($value['current'] ?? 1, $value['count'] ?? 0, $value['chunk'] ?? 20, 2, $value['ref'] ?? static function($i) {
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
    $out = \x\panel\type\lot(\x\panel\_value_set($value, $key), $key);
    $out[0] = 'p';
    return "" !== $content ? $out : null;
}

function pages($value, $key) {
    $value[0] = $value[0] ?? 'ul';
    $value[1] = $value[1] ?? "";
    $value[2]['tabindex'] = $value[2]['tabindex'] ?? -1;
    $lot = [];
    if (isset($value['lot'])) {
        foreach ($value['lot'] as $k => $v) {
            if (false === $v || null === $v || !empty($v['skip'])) {
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
        if (\is_array($v)) {
            $v['type'] = $v['type'] ?? 'page';
        }
        $value[1] .= \x\panel\type($v, $k);
        ++$count;
    }
    unset($lot);
    $value['tags'] = \array_replace([
        'count:' . $count => true,
        'lot' => true,
        'lot:pages' => true
    ], $value['tags'] ?? []);
    $value[2] = \x\panel\_style_set($value[2], $value);
    $value[2] = \x\panel\_tag_set($value[2], $value);
    return new \HTML($value);
}

function proxy($value, $key) {
    $out = [
        0 => $value[0] ?? 'div',
        1 => $value[1] ?? "",
        2 => \array_replace([
            'role' => 'dialog',
            'tabindex' => -1
        ], $value[2] ?? [])
    ];
    $count = 0;
    if (!isset($value[1])) {
        $title = \x\panel\to\title($value['title'] ?? "", $value['level'] ?? 4);
        // TODO: Allow to customize this
        $tasks = \x\panel\type\tasks\link(\x\panel\_value_set([
            'lot' => [
                'exit' => [
                    'description' => 'Close',
                    'icon' => 'M19,6.41L17.59,5L12,10.59L6.41,5L5,6.41L10.59,12L5,17.59L6.41,19L12,13.41L17.59,19L19,17.59L13.41,12L19,6.41Z',
                    // Ideally, a dialog should only contains at most of three button after the dialog title
                    'stack' => 30,
                    'title' => false,
                    'url' => '#exit'
                ]
            ]
        ], 0), 0);
        if ($title || $tasks) {
            $out[1] .= '<header role="group">';
            $out[1] .= $title;
            $out[1] .= $tasks;
            $out[1] .= '</header>';
            ++$count;
        }
        if (isset($value['content'])) {
            $out[1] .= '<div class="content">';
            $out[1] .= \x\panel\to\content($value['content']);
            $out[1] .= '</div>';
            ++$count;
        } else if (isset($value['lot'])) {
            $out[1] .= '<div class="lot">';
            $out[1] .= \x\panel\to\lot($value['lot'], $count, $value['sort'] ?? true);
            $out[1] .= '</div>';
            ++$count;
        }
        if (!empty($value['tasks'])) {
            $out[1] .= '<footer role="group">';
            $out[1] .= \x\panel\type\tasks\button(\x\panel\_value_set([
                '0' => 'p',
                'lot' => $value['tasks']
            ], $key), $key);
            $out[1] .= '</footer>';
            ++$count;
        }
        $value['tags'] = [
            'count:' . $count => true,
            'has:gap' => !\array_key_exists('gap', $value) || $value['gap'],
            'has:height' => !empty($value['height']),
            'has:modal' => !\array_key_exists('modal', $value) || $value['modal'],
            'has:width' => !empty($value['width']),
            'lot' => true,
            'lot:proxy' => true
        ];
        $out[2] = \x\panel\_tag_set($out[2], $value);
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
                if (\is_array($v)) {
                    $v['type'] = $v['type'] ?? 'content';
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
                if (\is_array($v)) {
                    $v['type'] = $v['type'] ?? 'row';
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
        foreach ($value['lot'] ?? [] as $k => $v) {
            if (false === $v || null === $v || !empty($v['skip'])) {
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
        $value['tags'] = $tags;
        $out[2] = \x\panel\_tag_set($out[2], $value);
    }
    return "" !== $out[1] ? new \HTML($out) : null;
}

function separator($value, $key) {
    $out = [
        0 => $value[0] ?? 'hr',
        1 => $value[1] ?? false,
        2 => $value[2] ?? []
    ];
    $out[2] = \x\panel\_tag_set($out[2], $value);
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
        $out[1] .= '<h3 class="title">' . \x\panel\type\link(\x\panel\_value_set([
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
            'target' => $value['target'] ?? 'stack:' . ($value['value'] ?? $key),
            'title' => $value['title'] ?? null,
            'url' => $value['url'] ?? null
        ], $key), $key) . '</h3>';
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
        $out[1] .= \x\panel\type\tasks\link(\x\panel\_value_set([
            '0' => 'p',
            'lot' => $value['tasks'] ?? []
        ], $key), $key);
        $value['tags'] = $tags;
        $out[2] = \x\panel\_tag_set($out[2], $value);
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
            if (\is_array($v)) {
                $v['type'] = $v['type'] ?? 'stack';
            }
            $out[1] .= \x\panel\type($v, $k);
            ++$count;
        }
        unset($lot);
        $value['tags'] = \array_replace([
            'count:' . $count => true,
            'has:current' => $has_current,
            'lot' => true,
            'lot:stacks' => true,
            'p' => true
        ], $value['tags'] ?? []);
        $out[2] = \x\panel\_tag_set($out[2], $value);
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
                    $v[3]['target'] = $v[2]['target'] ?? $v['target'] ?? 'tab:' . $kk;
                    $v['tags']['can:toggle'] = !empty($v['toggle']);
                    if (empty($v['url']) && empty($v['link']) && (!\array_key_exists('active', $v) || $v['active'])) {
                        $v['url'] = '?' . \explode('?', \x\panel\to\link(['query' => ['tab' => [$name => $kk]]]), 2)[1];
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
                $links[$current]['current'] = true;
                $links[$current][3]['aria-selected'] = 'true';
                $links[$current][3]['tabindex'] = 0;
                $sections[$current]['tags']['is:current'] = true;
                $has_current = true;
            } else if (null !== $first && isset($links[$first]) && \is_array($links[$first])) {
                $links[$first]['current'] = true;
                $links[$first][3]['aria-selected'] = 'true';
                $links[$first][3]['tabindex'] = 0;
                $sections[$first]['tags']['is:current'] = true;
                $has_current = true;
            }
            foreach ($sections as $k => $v) {
                // If `type` is not defined, the default value will be `tab`
                if (\is_array($v)) {
                    $v['type'] = $v['type'] ?? 'tab';
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
                $out[1] .= \x\panel\type\links(\x\panel\_value_set([
                    '0' => 'nav',
                    '2' => ['tabindex' => false],
                    '3' => ['role' => 'tablist'],
                    'lot' => $links
                ], $name), $name);
            }
            if ($sections) {
                $out[1] .= \implode("", $sections);
            }
        }
        if ($count < 2) {
            unset($out[2]['tabindex']);
        }
        $value['tags'] = \array_replace([
            'count:' . $count => true,
            'has:current' => $has_current,
            'has:gap' => $has_gap,
            'lot' => true,
            'lot:tabs' => true,
            'p' => true
        ], $value['tags'] ?? []);
        $out[2] = \x\panel\_tag_set($out[2], $value);
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
        $value['tags'] = $tags;
        if ($count > 0) {
            $out[2] = \x\panel\_tag_set($out[2], $value);
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
    $value['tags'] = $tags;
    $out[2] = \x\panel\_tag_set($out[2], $value);
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
    $value['tags'] = \array_replace([
        'has:icon' => !!($icon[0] || $icon[1]),
        'has:title' => !!$title,
        'level:' . $level => $level >= 0,
        'title' => true
    ], $value['tags'] ?? []);
    $out[2] = \x\panel\_tag_set($out[2], $value);
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