<?php namespace x\panel\lot\type;

function bar($value, $key) {
    $value['level'] = $value['level'] ?? 1;
    $value['tags']['p'] = $value['tags']['p'] ?? false;
    if (isset($value['content'])) {
        $out = \x\panel\lot\type\content($value, $key);
    } else if (isset($value['lot'])) {
        foreach ($value['lot'] as &$v) {
            if (\is_array($v)) {
                $v['tags']['p'] = $v['tags']['p'] ?? false;
                $v[0] = $v[0] ?? 'nav';
                // If `type` is not defined, the default value will be `links`
                $v['type'] = $v['type'] ?? 'links';
            }
        }
        unset($v);
        $out = \x\panel\lot\type\lot($value, $key);
    }
    $out['tabindex'] = -1; // Allow focus but remove from the default tab order!
    return $out;
}

function button($value, $key) {
    $not_active = isset($value['active']) && !$value['active'];
    $value['not']['active'] = $value['not']['active'] ?? $not_active;
    $value['tags']['button'] = true;
    $out = \x\panel\lot\type\link($value, $key);
    $out['disabled'] = $not_active;
    $out['id'] = $value['id'] ?? 'f:' . \substr(\uniqid(), 6);
    $out['name'] = $value['name'] ?? $key;
    $out['value'] = $value['value'] ?? null;
    $out[0] = 'button';
    unset($out['href'], $out['rel'], $out['target']);
    return $out;
}

function column($value, $key) {
    $value['tags']['p'] = $value['tags']['p'] ?? false;
    // Allow user to set column size using real fraction syntax in PHP
    if (isset($value['size']) && (\is_float($value['size']) || \is_int($value['size']))) {
        $value['size'] = \round($value['size'] * 12) . '/12';
    }
    if (!empty($value['size']) && !isset($value['tags']['size:' . $value['size']])) {
        $value['tags']['size:' . $value['size']] = true;
    }
    if (isset($value['content'])) {
        return \x\panel\lot\type\content($value, $key);
    }
    if (isset($value['lot'])) {
        foreach ($value['lot'] as &$v) {
            if (\is_array($v)) {
                $v['type'] = $v['type'] ?? 'content'; // Set default type to `content`
            }
        }
        unset($v);
        return \x\panel\lot\type\lot($value, $key);
    }
    return null;
}

function columns($value, $key) {
    $value['has']['gap'] = $value['has']['gap'] ?? (!\array_key_exists('gap', $value) || $value['gap']);
    $value['tags']['p'] = $value['tags']['p'] ?? false;
    $value[0] = $value[0] ?? 'div';
    $value[1] = $value[1] ?? "";
    $value[2] = $value[2] ?? [];
    if (isset($value['content'])) {
        return \x\panel\lot\type\content($value, $key);
    }
    if (isset($value['lot'])) {
        foreach ($value['lot'] as &$v) {
            if (\is_array($v)) {
                $v['type'] = $v['type'] ?? 'column'; // Set default type to `column`
            }
        }
        unset($v);
        return \x\panel\lot\type\lot($value, $key);
    }
    return null;
}

function content($value, $key) {
    $tags = \array_replace([
        'content' => true,
        'p' => true
    ], $value['tags'] ?? []);
    $value = \x\panel\lot\_value_set($value);
    $count = 0;
    if ($description = \x\panel\to\description($value['description'] ?? "")) {
        $value['has']['description'] = $value['has']['description'] ?? true;
    }
    if ($title = \x\panel\to\title($value['title'] ?? "", $value['level'] ?? 2)) {
        $value['has']['title'] = $value['has']['title'] ?? true;
    }
    $value[0] = $value[0] ?? 'div';
    $value[1] = $value[1] ?? "";
    $value[2] = $value[2] ?? [];
    if (isset($value['content'])) {
        // Add `description` and `title` only if `content` exists
        $value[1] .= $title . $description . \x\panel\to\content($value['content']);
        ++$count;
    }
    $tags['count:' . $count] = $tags['count:' . $count] ?? true;
    if ($type = $value['type'] ?? "") {
        foreach (\step($type, '/') as $v) {
            $tags['content:' . $v] = true;
        }
    }
    $value['tags'] = $tags;
    $value[2] = \x\panel\lot\_decor_set($value[2], $value);
    $value[2] = \x\panel\lot\_tag_set($value[2], $value);
    return new \HTML($value, true);
}

function description($value, $key) {
    $description = $value[1] ?? $value['content'] ?? "";
    $description = \trim(\w(\i(...((array) $description)), ['a', 'abbr', 'b', 'code', 'del', 'em', 'i', 'ins', 'mark', 'span', 'strong', 'sub', 'sup']) ?? "");
    if (!$description && '0' !== $description) {
        return null;
    }
    $value['tags']['description'] = $value['tags']['description'] ?? true;
    $value[0] = $value[0] ?? 'p';
    $value[1] = $description;
    $value[2] = $value[2] ?? [];
    $value[2] = \x\panel\lot\_decor_set($value[2], $value);
    $value[2] = \x\panel\lot\_tag_set($value[2], $value);
    return new \HTML($value, true);
}

function desk($value, $key) {
    $value['tags']['p'] = $value['tags']['p'] ?? false;
    $value[0] = $value[0] ?? 'main';
    $value[1] = $value[1] ?? "";
    $value[2]['tabindex'] = $value[2]['tabindex'] ?? -1;
    if (isset($value['width']) && false !== $value['width']) {
        $value['has']['width'] = $value['has']['width'] ?? true;
        if (true !== $value['width']) {
            $value['decors']['width'] = $value['width'];
        }
    }
    $value[2] = \x\panel\lot\_decor_set($value[2], $value);
    $value[2] = \x\panel\lot\_tag_set($value[2], $value);
    if (isset($value['content'])) {
        return \x\panel\lot\type\content($value, $key);
    }
    if (isset($value['lot'])) {
        foreach ($value['lot'] as &$v) {
            if (\is_array($v)) {
                $v['type'] = $v['type'] ?? 'section'; // Set default type to `section`
            }
        }
        unset($v);
        return \x\panel\lot\type\lot($value, $key);
    }
    return null;
}

function field($value, $key) {
    $tags = \array_replace([
        'lot' => true,
        'lot:field' => true,
        'p' => true
    ], $value['tags'] ?? []);
    $has_description = !empty($value['description']);
    $has_pattern = !empty($value['pattern']);
    $has_title = !empty($value['title']);
    $is_active = !isset($value['active']) || $value['active'];
    $is_fix = !empty($value['fix']);
    $is_vital = !empty($value['vital']);
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
    $value[1] = $value[1] ?? [];
    if ($type = $value['type'] ?? "") {
        if (0 === \strpos($type, 'field/')) {
            $tags['type:' . \substr($type, 6)] = true;
        }
    }
    $id = $value['id'] ?? 'f:' . \substr(\uniqid(), 6);
    $title = null;
    if (isset($value['title'])) {
        if (false !== $value['title']) {
            $title = (string) \x\panel\to\title($value['title'], -2);
            $value['has']['title'] = $value['has']['title'] ?? true;
        }
    } else {
        // Generate automatic title from `key`
        $title = (string) \x\panel\to\title(\To::title($value['key'] ?? $key), -2);
        $value['has']['title'] = $value['has']['title'] ?? true;
    }
    if (null !== $title) {
        $value[1]['title'] = [
            0 => 'label',
            1 => $title,
            2 => [
                'class' => 'count:' . ("" === \trim(\strip_tags($title ?? "")) ? '0' : '1'),
                'for' => $id
            ]
        ];
    }
    $icon = \x\panel\to\icon($value['icon'] = (array) ($value['icon'] ?? [null, null])); // Default as prefix
    $unit = \x\panel\to\unit($value['unit'] = isset($value['unit']) && \is_string($value['unit']) ? [null, $value['unit']] : ((array) ($value['unit'] ?? [null, null]))); // Default as suffix
    foreach ($unit as $k => $v) {
        if (\is_string($v) && "" !== $v) {
            $unit[$k] = new \HTML(['span', $v, ['class' => 'fix']]);
        } else if ($v instanceof \HTML) {
            $unit[$k][2] = \x\panel\lot\_tag_set($v[2], ['tags' => ['fix' => true]]);
        }
    }
    foreach ($icon as $k => $v) {
        if (\is_string($v) && "" !== $v) {
            $v = new \HTML($v);
            $v[2] = \x\panel\lot\_tag_set($v[2], ['tags' => ['fix' => true]]);
        } else if ($v instanceof \XML) {
            $v[2] = \x\panel\lot\_tag_set($v[2], ['tags' => ['fix' => true]]);
        }
        if (isset($value['icon'][$k]['link'])) {
            if (\is_array($link = $value['icon'][$k]['link'])) {
                $link = \x\panel\to\link($link);
            }
            $description = \strip_tags(\i(...((array) ($value['icon'][$k]['description'] ?? ""))) ?? "");
            $v = new \HTML(['a', ['icon' => $v], [
                'href' => $link,
                'target' => $value['icon'][$k]['target'] ?? '_blank',
                'title' => "" !== $description ? $description : null
            ]], true);
        } else if (isset($value['icon'][$k]['url'])) {
            if (\is_array($url = $value['icon'][$k]['url'])) {
                $url = \x\panel\to\link($value['icon'][$k]['url']);
            }
            $description = \strip_tags(\i(...((array) ($value['icon'][$k]['description'] ?? ""))) ?? "");
            $v = new \HTML(['a', ['icon' => $v], [
                'href' => $url,
                'target' => $value['icon'][$k]['target'] ?? null,
                'title' => "" !== $description ? $description : null
            ]], true);
        }
        $icon[$k] = $v;
    }
    $decors_field = $tags_field = [];
    if (isset($value['height']) && false !== $value['height']) {
        $tags_field['has:height'] = true;
        if (true !== $value['height']) {
            $decors_field['height'] = \is_int($value['height']) ? $value['height'] . 'px' : $value['height'];
        }
    }
    if (isset($value['width']) && false !== $value['width']) {
        $tags_field['has:width'] = true;
        if (true !== $value['width']) {
            $decors_field['width'] = \is_int($value['width']) ? $value['width'] . 'px' : $value['width'];
        }
    }
    $value[1]['field'] = [
        0 => 'div',
        1 => [],
        2 => []
    ];
    // These data are useful for inserting HTML markup before and after the HTML markup of standard fields. Currently
    // supports closure and string value(s) only. The naming of these data is currently non-standard so it is subject to
    // change. These data should only be used internally when you create a new field type. The API consumer(s) are only
    // in charge of determining the field type and providing some other data that has become a common standard.
    $field_enter = $value['field-enter'] ?? "";
    $field_exit = $value['field-exit'] ?? "";
    if ("" !== $field_enter && (\is_callable($field_enter) || \is_string($field_enter))) {
        $value[1]['field'][1]['enter'] = $field_enter;
    }
    // Special value returned by `x\panel\to\field()`
    if (isset($value['field'])) {
        if (\is_array($value['field'])) {
            $value['field'][2] = \x\panel\lot\_decor_set($value['field'][2] ?? [], ['decors' => $decors_field]);
            $value['field'][2] = \x\panel\lot\_tag_set($value['field'][2] ?? [], ['tags' => $tags_field]);
        }
        $content = (string) \x\panel\to\content($value['field']);
        $value[1]['field'][1][] = [
            0 => 'div',
            1 => [
                '(' => $icon[0] ?? $unit[0] ?? "",
                'content' => $content,
                ')' => $icon[1] ?? $unit[1] ?? ""
            ],
            2 => [
                'class' => \implode(' ', \array_keys(\array_filter([
                    'count:' . ("" === $content ? '0' : '1') => true,
                    'has:height' => !empty($value['height']),
                    'has:width' => !empty($value['width']),
                    'p' => true,
                    'with:fields' => true
                ]))),
                'role' => 'group'
            ]
        ];
    } else if (isset($value['content'])) {
        if (\is_array($value['content'])) {
            $value['content'][2] = \x\panel\lot\_decor_set($value['content'][2] ?? [], ['decors' => $decors_field]);
            $value['content'][2] = \x\panel\lot\_tag_set($value['content'][2] ?? [], ['tags' => $tags_field]);
        }
        $content = (string) \x\panel\to\content($value['content']);
        $value[1]['field'][1][] = [
            0 => 'div',
            1 => [
                '(' => $icon[0] ?? $unit[0] ?? "",
                'content' => $content,
                ')' => $icon[1] ?? $unit[1] ?? ""
            ],
            2 => [
                'class' => 'content count:' . ("" === $content ? '0' : '1')
            ]
        ];
    } else if (isset($value['lot'])) {
        $count = 0;
        $content = (string) \x\panel\to\lot($value['lot'], $count, $value['sort'] ?? true);
        $value[1]['field'][1][] = [
            0 => 'div',
            1 => $content,
            2 => [
                'class' => 'count:' . $count . ' lot'
            ]
        ];
    }
    $value['tags'] = $tags;
    $value[1]['field'][1]['description'] = \x\panel\to\description($value['description'] ?? "");
    if ("" !== $field_exit && (\is_callable($field_exit) || \is_string($field_exit))) {
        $value[1]['field'][1]['exit'] = $field_exit;
    }
    $value[2] = \x\panel\lot\_decor_set($value[2], $value);
    $value[2] = \x\panel\lot\_tag_set($value[2], $value);
    if (isset($value['values']) && \is_array($value['values']) && $data = \To::query($value['values'])) {
        foreach (\explode('&', \substr($data, 1)) as $v) {
            $vv = \explode('=', $v, 2);
            $value[1]['input[name="' . ($n = \urldecode($vv[0])) . '"]'] = [
                0 => 'input',
                1 => false,
                2 => [
                    'name' => $n,
                    'type' => 'hidden',
                    'value' => \urldecode($vv[1] ?? 'true')
                ]
            ];
        }
    }
    // Return the string value as late as possible
    if (isset($value[1]['field'][1]['enter']) && \is_callable($value[1]['field'][1]['enter'])) {
        $value[1]['field'][1]['enter'] = \call_user_func($value[1]['field'][1]['enter'], $value, $key);
    }
    if (isset($value[1]['field'][1]['exit']) && \is_callable($value[1]['field'][1]['exit'])) {
        $value[1]['field'][1]['exit'] = \call_user_func($value[1]['field'][1]['exit'], $value, $key);
    }
    return new \HTML($value, true);
}

function fields($value, $key) {
    $value[0] = $value[0] ?? 'div';
    $value[1] = $value[1] ?? "";
    $value[2] = $value[2] ?? [];
    $bottom = "";
    $count = 0;
    if ($description = \x\panel\to\description($value['description'] ?? "")) {
        ++$count;
    }
    if ($title = \x\panel\to\title($value['title'] ?? "", $value['level'] ?? 4)) {
        ++$count;
    }
    if (isset($value['content'])) {
        $value[1] .= \x\panel\to\content($value['content']);
        ++$count;
    } else if (isset($value['lot'])) {
        $value['lot'] = \x\panel\lot\_type_parent_set($value['lot'], 'field');
        foreach ((new \Anemone($value['lot']))->sort([1, 'stack', 10], true) as $k => &$v) {
            if (false === $v || null === $v || !empty($v['skip'])) {
                continue;
            }
            $v = \x\panel\lot\_value_set($v, $k);
            $type = \strtolower(\f2p(\strtr($v['type'] ?? "", '-', '_')));
            if ("" !== $type && \function_exists($fn = __NAMESPACE__ . "\\" . $type)) {
                if ('field/hidden' !== $type) {
                    $value[1] .= \call_user_func($fn, $v, $k);
                    ++$count;
                } else {
                    $bottom .= \x\panel\lot\type\field\hidden($v, $k);
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
        'count:' . $count => true,
        'lot' => true,
        'lot:fields' => true,
        'p' => true
    ], $value['tags'] ?? []);
    $value[1] = $title . $description . $value[1];
    $value[2] = \x\panel\lot\_decor_set($value[2], $value);
    $value[2] = \x\panel\lot\_tag_set($value[2], $value);
    return new \HTML($value);
}

function file($value, $key) {
    $has_description = !empty($value['description']);
    $has_icon = !empty($value['icon']);
    $has_title = !empty($value['title']);
    $is_active = !isset($value['active']) || $value['active'];
    $is_current = !empty($value['current']);
    $value['has']['description'] = $value['has']['description'] ?? $has_description;
    $value['has']['icon'] = $value['has']['icon'] ?? $has_icon;
    $value['has']['title'] = $value['has']['title'] ?? $has_title;
    $value['is']['active'] = $value['is']['active'] ?? $is_active;
    $value['is']['current'] = $value['is']['current'] ?? $is_current;
    $value['is']['file'] = $value['is']['file'] ?? true;
    $value['not']['active'] = $value['not']['active'] ?? !$is_active;
    $value['tags'] = \array_replace([
        'lot' => true,
        'lot:file' => true
    ], $value['tags'] ?? []);
    $value[0] = $value[0] ?? 'li';
    $value[1] = $value[1] ?? "";
    $value[2]['tabindex'] = $value[2]['tabindex'] ?? 0;
    $value[1] .= '<h3 class="title">' . \x\panel\lot\type\link(\x\panel\lot\_value_set([
        'description' => $value['description'] ?? null,
        'icon' => $value['icon'] ?? [],
        'link' => $value['link'] ?? null,
        'status' => $value['status'] ?? null,
        'target' => $value['target'] ?? null,
        'title' => $value['title'] ?? null,
        'url' => $value['url'] ?? null
    ], $key), $key) . '</h3>';
    $value[1] .= \x\panel\lot\type\tasks\link(\x\panel\lot\_value_set([
        '0' => 'p',
        'lot' => $value['tasks'] ?? [],
        'tags' => ['p' => false]
    ], $key), $key);
    $value[2] = \x\panel\lot\_decor_set($value[2], $value);
    $value[2] = \x\panel\lot\_tag_set($value[2], $value);
    if (!$is_active) {
        unset($value[2]['tabindex']);
    }
    return new \HTML($value);
}

function files($value, $key) {
    $value[0] = $value[0] ?? 'ul';
    $value[1] = $value[1] ?? "";
    $value[2]['role'] = $value[2]['role'] ?? 'directory';
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
            $value[1] .= \x\panel\lot\type($v, $k);
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
    $value[2] = \x\panel\lot\_decor_set($value[2], $value);
    $value[2] = \x\panel\lot\_tag_set($value[2], $value);
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
            'level' => $value['level'] ?? 2, // Same with the default level of `x\panel\lot\type\content()`
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
    return \x\panel\lot\type\lot($value, $key);
}

function folder($value, $key) {
    $has_description = !empty($value['description']);
    $has_icon = !empty($value['icon']);
    $has_title = !empty($value['title']);
    $is_active = !isset($value['active']) || $value['active'];
    $is_current = !empty($value['current']);
    $value['has']['description'] = $value['has']['description'] ?? $has_description;
    $value['has']['icon'] = $value['has']['icon'] ?? $has_icon;
    $value['has']['title'] = $value['has']['title'] ?? $has_title;
    $value['is']['active'] = $value['is']['active'] ?? $is_active;
    $value['is']['current'] = $value['is']['current'] ?? $is_current;
    $value['is']['folder'] = $value['is']['folder'] ?? true;
    $value['not']['active'] = $value['not']['active'] ?? !$is_active;
    $value['tags'] = \array_replace([
        'lot' => true,
        'lot:folder' => true
    ], $value['tags'] ?? []);
    $value[0] = $value[0] ?? 'li';
    $value[1] = $value[1] ?? "";
    $value[2]['tabindex'] = $value[2]['tabindex'] ?? 0;
    $value[1] .= '<h3 class="title">' . \x\panel\lot\type\link(\x\panel\lot\_value_set([
        'description' => $value['description'] ?? \i('Open %s', 'Folder'),
        'icon' => $value['icon'] ?? [],
        'link' => $value['link'] ?? null,
        'status' => $value['status'] ?? null,
        'target' => $value['target'] ?? null,
        'title' => $value['title'] ?? null,
        'url' => $value['url'] ?? null
    ], $key), $key) . '</h3>';
    $value[1] .= \x\panel\lot\type\tasks\link(\x\panel\lot\_value_set([
        '0' => 'p',
        'lot' => $value['tasks'] ?? [],
        'tags' => ['p' => false]
    ], $key), $key);
    $value[2] = \x\panel\lot\_decor_set($value[2], $value);
    $value[2] = \x\panel\lot\_tag_set($value[2], $value);
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
    return \x\panel\lot\type\files($value, $key);
}

function form($value, $key) {
    $value[0] = $value[0] ?? 'form';
    $value[1] = $value[1] ?? "";
    $value[2] = $value[2] ?? [];
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
    if (isset($value['values']) && \is_array($value['values']) && $data = \To::query($value['values'])) {
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
    $value[2] = \x\panel\lot\_decor_set($value[2], $value);
    $value[2] = \x\panel\lot\_tag_set($value[2], $value);
    return new \HTML($value);
}

function icon($value, $key) {
    $icons = [null, null];
    // Maybe a `HTML`
    if (isset($value['content'])) {
        $icons[0] = \x\panel\to\content($value['content']);
    // Maybe an `Anemone`
    } else if (isset($value['lot'])) {
        $v = \x\panel\to\lot($value['lot']);
        $icons[0] = $v[0] ?? null;
        $icons[1] = $v[1] ?? null;
    } else {
        // Must be an array or string, force it to array!
        if (!\is_array($value) || !\array_is_list($value)) {
            $value = [$value];
        }
        $icons = \array_replace($icons, $value);
    }
    $ref = $GLOBALS['_']['icon'] ?? [];
    $attr = [
        'aria-hidden' => 'true',
        'class' => 'icon',
        'height' => 24,
        'width' => 24
    ];
    foreach ($icons as $k => $v) {
        if (!$v) {
            continue;
        }
        if (\is_array($v)) {
            if (\is_array($rect = $v['rect'] ?? '0 0 24 24')) {
                $rect = \implode(' ', $rect);
            }
            $attr = \array_replace($attr, $v[2] ?? []);
            $attr = \x\panel\lot\_decor_set($attr, $v);
            $attr = \x\panel\lot\_tag_set($attr, $v);
            $attr['viewBox'] = $v[2]['view-box'] ?? $v[2]['viewBox'] ?? $rect;
            $content = \x\panel\to\content($v[1] ?? $v['content'] ?? null);
            $d = $v['d'] ?? $v['path'] ?? "";
            $description = \strip_tags(\i(...((array) ($v['description'] ?? ""))) ?? "");
            $id = $v['id'] ?? $v['name'] ?? \dechex(\crc32($d));
            $title = \strip_tags(\i(...((array) ($v['title'] ?? ""))) ?? "");
            // Inline icon(s)
            if ("" !== $d) {
                $v = new \XML(['svg', $content ?? [
                    'path' => ['path', [
                        'title' => $title ? ['title', $title, []] : "",
                        'description' => $description ? ['desc', $description, []] : ""
                    ], [
                        'd' => $d,
                        'fill' => $v['fill'] ?? null
                    ]]
                ], $attr], true);
            // Inline icon(s)
            } else if ($content) {
                $v = new \XML(['svg', $content, $attr], true);
            // Named icon(s)
            } else if (isset($ref[$id])) {
                $v = new \XML(['svg', [
                    'use' => ['use', [
                        'title' => $title ? ['title', $title, []] : "",
                        'description' => $description ? ['desc', $description, []] : ""
                    ], ['href' => '#icon:' . $id]]
                ], $attr], true);
            }
        } else if (\is_string($v)) {
            if (false === \strpos($v, '<')) {
                // Named icon(s)
                if (isset($ref[$v])) {
                    $v = new \XML(['svg', ['use' => ['use', "", ['href' => '#icon:' . $v]]], $attr], true);
                // Inline icon(s)
                } else {
                    if (!isset($ref[$id = \dechex(\crc32($v))])) {
                        $GLOBALS['_']['icon'][$id] = $v;
                    }
                    $v = new \XML(['svg', ['use' => ['use', "", ['href' => '#icon:' . $id]]], $attr], true);
                }
            } else if ('</svg>' !== \substr($v, -6)) {
                $v = new \XML(['svg', $v, $attr]);
            }
        }
        $icons[$k] = $v;
    }
    return new \Anemone($icons, "");
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
    $value['tags']['input'] = true;
    $value[0] = $value[0] ?? 'input';
    $value[1] = $value[1] ?? false;
    $value[2] = $value[2] ?? [];
    if ($has_pattern) {
        $value[2]['pattern'] = $value['pattern'];
    }
    $id = $value['id'] ?? 'f:' . \substr(\uniqid(), 6);
    if (!empty($value['lot']) && \is_array($value['lot'])) {
        $GLOBALS['_']['data-list'][$id] = $value['lot'];
        $value[2]['list'] = 'l:' . $id;
    }
    $value[2]['autofocus'] = !empty($value['focus']);
    $value[2]['disabled'] = !$is_active;
    $value[2]['id'] = $id;
    $value[2]['maxlength'] = $value['max'] ?? null;
    $value[2]['minlength'] = $value['min'] ?? null;
    $value[2]['name'] = $value['name'] ?? $key;
    $value[2]['placeholder'] = \i(...((array) ($value['hint'] ?? "")));
    $value[2]['readonly'] = $is_fix;
    $value[2]['required'] = $is_vital;
    $value[2]['value'] = $value['value'] ?? null;
    $value[2] = \x\panel\lot\_decor_set($value[2], $value);
    $value[2] = \x\panel\lot\_tag_set($value[2], $value);
    return new \HTML($value);
}

function link($value, $key) {
    $href = $value['link'] ?? $value['url'] ?? \P;
    if (\is_array($href)) {
        $href = \x\panel\to\link($href);
    }
    $status = $value['status'] ?? "";
    $value['not']['active'] = $not_active = $value['not']['active'] ?? (\P === $href || (isset($value['active']) && !$value['active']));
    $value[0] = $value[0] ?? 'a';
    $value[1] = $value[1] ?? (string) \x\panel\lot\type\title(\x\panel\lot\_value_set([
        'content' => $value['title'] ?? \To::title($key),
        'description' => $value['description'] ?? null,
        'icon' => $value['icon'] ?? [],
        'level' => -1,
        'status' => $status
    ], $key), $key);
    $value[2]['id'] = $value[2]['id'] ?? $value['id'] ?? null;
    if (!$not_active && \P !== $href) {
        $value[2]['href'] = $href;
        $value[2]['target'] = $value[2]['target'] ?? $value['target'] ?? (isset($value['link']) ? '_blank' : null);
    }
    $value[2] = \x\panel\lot\_decor_set($value[2], $value);
    $value[2] = \x\panel\lot\_tag_set($value[2], $value);
    return new \HTML($value);
}

function links($value, $key) {
    $value['tags']['lot:links'] = $value['tags']['lot:links'] ?? true;
    $value['tags']['lot:menu'] = $value['tags']['lot:menu'] ?? false;
    $value['tags']['p'] = $value['tags']['p'] ?? true;
    $value[2]['tabindex'] = $value[2]['tabindex'] ?? -1;
    return \x\panel\lot\type\menu(\x\panel\lot\_value_set($value), $key, -2);
}

function lot($value, $key) {
    $tags = \array_replace([
        'lot' => true,
        'p' => true
    ], $value['tags'] ?? []);
    $value = \x\panel\lot\_value_set($value);
    $count = 0;
    if ($description = \x\panel\to\description($value['description'] ?? "")) {
        ++$count;
    }
    if ($title = \x\panel\to\title($value['title'] ?? "", $value['level'] ?? 2)) {
        ++$count;
    }
    $value[0] = $value[0] ?? 'div';
    $value[1] = $value[1] ?? $title . $description;
    $value[2] = $value[2] ?? [];
    if (isset($value['lot'])) {
        $value[1] .= \x\panel\to\lot($value['lot'], $count, $value['sort'] ?? true);
    }
    $tags['count:' . $count] = $tags['count:' . $count] ?? true;
    if ($type = $value['type'] ?? null) {
        foreach (\step($type, '/') as $v) {
            $tags['lot:' . $v] = true;
        }
    }
    $value['tags'] = $tags;
    $value[2] = \x\panel\lot\_decor_set($value[2], $value);
    $value[2] = \x\panel\lot\_tag_set($value[2], $value);
    return new \HTML($value, true);
}

function menu($value, $key, int $i = 0) {
    $tags = \array_replace([
        'level:' . ($i + 2) => true,
        'lot' => true,
        'lot:menu' => true
    ], $value['tags'] ?? []);
    $value[0] = $value[0] ?? 'div';
    $value[1] = $value[1] ?? "";
    $value[2]['tabindex'] = $value[2]['tabindex'] ?? 0;
    $count_parent = 0;
    if (isset($value['with']['description'])) {
        if (false === $value['with']['description']) {
            $description = "";
        } else if (\is_string($value['with']['description'])) {
            $description = $value['with']['description'];
        } else /* if (true === $value['with']['description']) */ {
            $description = $value['description'] ?? "";
        }
    } else {
        $description = $value['description'] ?? "";
    }
    if (isset($value['with']['title'])) {
        if (false === $value['with']['title']) {
            $title = "";
        } else if (\is_string($value['with']['title'])) {
            $title = $value['with']['title'];
        } else /* if (true === $value['with']['title']) */ {
            $title = $value['title'] ?? "";
        }
    } else {
        $title = $value['title'] ?? "";
    }
    if ($description = \x\panel\to\description($description)) {
        $value['has']['description'] = true;
        unset($value['with']['description']);
        ++$count_parent;
    }
    if ($title = \x\panel\to\title($title, $value['level'] ?? 4)) {
        $value['has']['title'] = true;
        unset($value['with']['title']);
        ++$count_parent;
    }
    if (isset($value['content'])) {
        $tags['count:' . ($c = $count_parent + 1)] = $tags['count:' . $c] ?? true;
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
                $vv[1] = \x\panel\lot\type\link(\x\panel\lot\_value_set([
                    '2' => [
                        'aria-disabled' => 'true',
                        'role' => 'menuitem',
                        'tabindex' => $i < 0 ? null : -1
                    ],
                    'title' => $v
                ], $k), $k);
                $vv['not']['active'] = true;
                $vv[2] = \x\panel\lot\_tag_set($vv[2], $vv);
                $value[1] .= new \HTML($vv);
                ++$count;
                continue;
            }
            $v = \x\panel\lot\_value_set($v, $k);
            $vv[0] = $v[0] ?? $vv[0];
            $vv[1] = $v[1] ?? $vv[1];
            $vv[2] = $v[2] ?? $vv[2];
            if ("" === $vv[1]) {
                if (\array_key_exists('icon', $v)) {
                    $v['icon'] = (array) $v['icon'];
                }
                // If `type` is not defined, the default value will be `menu`
                $v['type'] = $v['type'] ?? 'menu';
                if ('separator' === $v['type']) {
                    $v['as']['separator'] = true;
                    $vv[2] = \x\panel\lot\_decor_set($vv[2], $v);
                    $vv[2] = \x\panel\lot\_tag_set($vv[2], $v);
                    $vv[2]['aria-orientation'] = $i < -1 ? 'horizontal' : 'vertical';
                    $vv[2]['role'] = 'separator';
                    $value[1] .= new \HTML($vv);
                    ++$count;
                    continue;
                }
                $has_caret = false;
                if (!empty($v['lot']) && (!empty($v['caret']) || !\array_key_exists('caret', $v))) {
                    $v['icon'][0] = $v['icon'][0] ?? null;
                    $v['icon'][1] = isset($v['caret']) && \is_string($v['caret']) ? $v['caret'] : ($i < -1 ? 'M7,10L12,15L17,10H7Z' : 'M10,17L15,12L10,7V17Z');
                    $has_caret = true;
                }
                $v['icon'] = \x\panel\to\icon($v['icon'] ?? []);
                if ($has_caret && isset($v['icon'][1][2])) {
                    $v['icon'][1][2] = \x\panel\lot\_tag_set($v['icon'][1][2], ['tags' => ['caret' => true]]);
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
                    $vv[1] = \x\panel\lot\type\link(\x\panel\lot\_value_set($v, $k), $k);
                    if (!empty($v['lot'])) {
                        $vv[1]['aria-expanded'] = 'false';
                        $vv[1]['aria-haspopup'] = 'true';
                        $vv[1] .= \x\panel\lot\type\menu(\x\panel\lot\_value_set(\array_replace_recursive([
                            'with' => [
                                'description' => false,
                                'title' => false
                            ]
                        ], $v, [
                            '2' => [
                                'role' => null,
                                'tabindex' => -1
                            ]
                        ]), $k), $k, $i + 1); // Recurse!
                        $v['has']['menu'] = $v['has']['menu'] ?? true;
                    }
                } else {
                    if (0 === \strpos($v['type'] . '/', 'form/')) {
                        $v['has']['form'] = $v['has']['form'] = true;
                    }
                    $vv[1] = \x\panel\lot\type($v, $k);
                }
                $vv[2] = \x\panel\lot\_tag_set($vv[2], $v);
                $value[1] .= new \HTML($vv);
            }
            ++$count;
        }
        $tags['count:' . ($c = $count_parent + ($count ? 1 : 0))] = $tags['count:' . $c] ?? true;
    }
    $value['tags'] = $tags;
    $value[2] = \x\panel\lot\_decor_set($value[2], $value);
    $value[2] = \x\panel\lot\_tag_set($value[2], $value);
    if ("" !== $value[1]) {
        $value[1] = '<ul class="count:' . $count . '" role="' . ($value[3]['role'] ?? 'menu' . ($i < -1 ? 'bar' : "")) . '">' . $value[1] . '</ul>';
    }
    $value[1] = $title . $description . $value[1];
    return new \HTML($value);
}

function menus($value, $key) {
    $value['tags']['lot:menu'] = $value['tags']['lot:menu'] ?? false;
    $value['tags']['lot:menus'] = $value['tags']['lot:menus'] ?? true;
    $value['tags']['p'] = $value['tags']['p'] ?? true;
    $value[2]['tabindex'] = $value[2]['tabindex'] ?? -1;
    return \x\panel\lot\type\menu(\x\panel\lot\_value_set($value), $key, -2);
}

function page($value, $key) {
    $tags = \array_replace([
        'lot' => true,
        'lot:page' => true
    ], $value['tags'] ?? []);
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
    $div = new \HTML(['div', "", []]);
    $icon = $value['icon'] ?? false; // Prioritize `icon`, but remember that its default value is `false`
    $image = $value['image'] ?? "";
    $time = isset($value['time']) ? \strtr($value['time'], '-', '/') : null;
    if (false === $icon && false === $image) {
        $div['hidden'] = true;
    // Prioritize `icon` over `image`
    } else if (!empty($icon)) {
        $fill = $value['color'] ?? '#' . \substr(\md5($icon), 0, 6);
        $color = $value['stroke'] ?? '#fff';
        $div[1] = '<span class="image" role="img" style="background: ' . $fill . '; color: ' . $color . ';">' . \x\panel\to\icon($icon)[0] . '</span>';
    } else if (!empty($image)) {
        $div[1] = '<img alt="" class="image" height="72" loading="lazy" src="' . \htmlspecialchars($image) . '" width="72">';
    } else {
        $fill = $value['color'] ?? '#' . \substr(\md5(\strtr($key, [
            \PATH => "",
            \D => '/'
        ])), 0, 6);
        $color = $value['stroke'] ?? '#fff';
        $div[1] = '<span class="image" role="img" style="background: ' . $fill . '; color: ' . $color . ';"></span>';
    }
    $value[1] .= $div;
    $description = $value['description'] ?? $time;
    $title = $value['title'] ?? $time;
    if (false !== $description || false !== $title) {
        $value[1] .= '<div><h3 class="title">' . \x\panel\lot\type\link(\x\panel\lot\_value_set([
            'link' => $value['link'] ?? null,
            'status' => $value['status'] ?? null,
            'target' => $value['target'] ?? null,
            'title' => $title,
            'url' => $value['url'] ?? null
        ], $key), $key) . '</h3>' . \x\panel\to\description($description) . '</div>';
    }
    $value[1] .= '<div>' . \x\panel\lot\type\tasks\link(\x\panel\lot\_value_set([
        '0' => 'p',
        'lot' => $value['tasks'] ?? [],
        'tags' => ['p' => false]
    ], $key), $key) . '</div>';
    $value['tags'] = $tags;
    $value[2] = \x\panel\lot\_decor_set($value[2], $value);
    $value[2] = \x\panel\lot\_tag_set($value[2], $value);
    if (!$is_active) {
        unset($value[2]['tabindex']);
    }
    return new \HTML($value);
}

function pager($value, $key) {
    $route = $value['route'] ?? null;
    if (\is_string($route) && (false !== \strpos($route, '%d') || !\is_callable($route))) {
        $route = static function ($i) use ($route) {
            return \long(\sprintf($route, $i));
        };
    }
    $content = (string) \x\panel\to\pager($value['current'] ?? 1, $value['count'] ?? 0, $value['chunk'] ?? 20, $value['peek'] ?? 2, $route ?? static function ($i) use ($value) {
        return \x\panel\to\link([
            'part' => $i,
            'path' => $value['path'] ?? $GLOBALS['_']['path']
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
    $out = \x\panel\lot\type\lot(\x\panel\lot\_value_set($value, $key), $key);
    $out[0] = 'p';
    return "" !== $content ? $out : null;
}

function pages($value, $key) {
    $tags = \array_replace([
        'lot' => true,
        'lot:pages' => true
    ], $value['tags'] ?? []);
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
        $value[1] .= \x\panel\lot\type($v, $k);
        ++$count;
    }
    unset($lot);
    $tags['count:' . $count] = $tags['count:' . $count] ?? true;
    $value['tags'] = $tags;
    $value[2] = \x\panel\lot\_decor_set($value[2], $value);
    $value[2] = \x\panel\lot\_tag_set($value[2], $value);
    return new \HTML($value);
}

function row($value, $key) {
    $tags = $value['tags'] ?? [];
    $value[0] = $value[0] ?? 'div';
    $value[1] = $value[1] ?? "";
    $value[2] = $value[2] ?? [];
    if (!empty($value['size'])) {
        $size = $value['size'];
        $tags['size:' . $size] = true;
    }
    $tags['p'] = $value['tags']['p'] ?? false;
    $value['tags'] = $tags;
    if (isset($value['content'])) {
        return \x\panel\lot\type\content($value, $key);
    }
    if (isset($value['lot'])) {
        foreach ($value['lot'] as &$v) {
            if (\is_array($v)) {
                $v['type'] = $v['type'] ?? 'content';
            }
        }
        unset($v);
        return \x\panel\lot\type\lot($value, $key);
    }
    return new \HTML($value);
}

function rows($value, $key) {
    $tags = $value['tags'] ?? [];
    $has_gap = !\array_key_exists('gap', $value) || $value['gap'];
    $value['has']['gap'] = $value['has']['gap'] ?? $has_gap;
    $value[0] = $value[0] ?? 'div';
    $value[1] = $value[1] ?? "";
    $value[2] = $value[2] ?? [];
    $tags['p'] = $value['tags']['p'] ?? false;
    $value['tags'] = $tags;
    if (isset($value['content'])) {
        return \x\panel\lot\type\content($value, $key);
    }
    if (isset($value['lot'])) {
        foreach ($value['lot'] as &$v) {
            if (\is_array($v)) {
                $v['type'] = $v['type'] ?? 'row';
            }
        }
        unset($v);
        return \x\panel\lot\type\lot($value, $key);
    }
    return new \HTML($value);
}

function section($value, $key) {
    $tags = $value['tags'] ?? [];
    $tags['p'] = $value['tags']['p'] ?? false;
    $value['tags'] = $tags;
    if (isset($value['content'])) {
        $out = \x\panel\lot\type\content($value, $key);
        $out[0] = $value[0] ?? 'section';
    } else if (isset($value['lot'])) {
        $out = \x\panel\lot\type\lot($value, $key);
        $out[0] = $value[0] ?? 'section';
    } else {
        $out = new \HTML([
            0 => $value[0] ?? 'section',
            1 => $value[1] ?? "",
            2 => $value[2] ?? []
        ]);
    }
    return $out;
}

function select($value, $key) {
    $is_active = !isset($value['active']) || $value['active'];
    $is_vital = !empty($value['vital']);
    $value['is']['active'] = $value['is']['active'] ?? $is_active;
    $value['is']['vital'] = $value['is']['vital'] ?? $is_vital;
    $value['not']['active'] = $value['not']['active'] ?? !$is_active;
    $value['not']['vital'] = $value['not']['vital'] ?? !$is_vital;
    $value['tags']['select'] = true;
    $value[0] = $value[0] ?? 'select';
    $value[1] = $value[1] ?? "";
    $value[2] = $value[2] ?? [];
    $the_options = [];
    $the_value = $value['value'] ?? null;
    // $the_placeholder = \i(...((array) ($value['hint'] ?? "")));
    $is_options_flat = \array_is_list($value['lot']);
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
            $is_options_group_flat = \array_is_list($v['lot']);
            foreach ($v['lot'] as $kk => $vv) {
                $option = new \HTML(['option', "", [
                    'selected' => null !== $the_value && (string) $the_value === (string) $kk,
                    'value' => $is_options_group_flat ? null : $kk
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
                'value' => $is_options_flat ? null : $k
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
        $value[1] .= $v;
    }
    $value[2]['autofocus'] = !empty($value['focus']);
    $value[2]['disabled'] = !$is_active;
    $value[2]['id'] = $value['id'] ?? 'f:' . \substr(\uniqid(), 6);
    $value[2]['name'] = $value['name'] ?? $key;
    $value[2]['required'] = $is_vital;
    $value[2] = \x\panel\lot\_decor_set($value[2], $value);
    $value[2] = \x\panel\lot\_tag_set($value[2], $value);
    return new \HTML($value);
}

function separator($value, $key) {
    $value[0] = $value[0] ?? 'hr';
    $value[1] = $value[1] ?? false;
    $value[2] = \x\panel\lot\_decor_set($value[2], $value);
    $value[2] = \x\panel\lot\_tag_set($value[2], $value);
    return new \HTML($value);
}

function stack($value, $key) {
    $tags = \array_replace([
        'lot' => true,
        'lot:stack' => true
    ], $value['tags'] ?? []);
    $id = \substr(\uniqid(), 6);
    $can_toggle = !empty($value['toggle']);
    $is_active = !isset($value['active']) || $value['active'];
    $is_current = !empty($value['current']);
    $value['can']['toggle'] = $value['can']['toggle'] ?? $can_toggle;
    $value['is']['active'] = $value['is']['active'] ?? $is_active;
    $value['is']['current'] = $value['is']['current'] ?? $is_current;
    $value['not']['active'] = $value['not']['active'] ?? !$is_active;
    $value[0] = $value[0] ?? 'div';
    $value[1] = $value[1] ?? "";
    $value[2]['data-value'] = $value[2]['data-value'] ?? $value['value'] ?? $key;
    $value[2]['tabindex'] = $value[2]['tabindex'] ?? ($is_current ? 0 : -1);
    $value[1] .= '<h3 class="title">' . \x\panel\lot\type\link(\x\panel\lot\_value_set([
        '2' => [
            'aria-controls' => 'c:' . $id,
            'aria-expanded' => $is_current ? 'true' : 'false',
            'id' => 's:' . $id,
            'tabindex' => -1
        ],
        'description' => $value['description'] ?? null,
        'icon' => $value['icon'] ?? [],
        'link' => $value['link'] ?? null,
        'status' => $value['status'] ?? null,
        'is' => [
            'active' => $is_active,
            'current' => $is_current
        ],
        'not' => ['active' => !$is_active],
        'target' => $value['target'] ?? 'stack:' . ($value['value'] ?? $key),
        'title' => $value['title'] ?? null,
        'url' => $value['url'] ?? null
    ], $key), $key) . '</h3>';
    $count = 1;
    if (isset($value['content'])) {
        $value[1] .= new \HTML(['div', \x\panel\to\content($value['content']), [
            'aria-labelledby' => 's:' . $id,
            'class' => 'content',
            'id' => 'c:' . $id,
            'role' => 'region'
        ]]);
        $tags['count:' . ($count = 2)] = $tags['count:' . $count] ?? true;
    } else if (isset($value['lot'])) {
        $value[1] .= new \HTML(['div', \x\panel\to\lot($value['lot'], $count, $value['sort'] ?? true), [
            'aria-labelledby' => 's:' . $id,
            'class' => 'lot',
            'id' => 'c:' . $id,
            'role' => 'region'
        ]]);
        $tags['count:' . $count] = $tags['count:' . $count] ?? true;
    }
    $value[1] .= \x\panel\lot\type\tasks\link(\x\panel\lot\_value_set([
        '0' => 'p',
        'lot' => $value['tasks'] ?? [],
        'tags' => ['p' => false]
    ], $key), $key);
    $value['tags'] = $tags;
    $value[2] = \x\panel\lot\_decor_set($value[2], $value);
    $value[2] = \x\panel\lot\_tag_set($value[2], $value);
    if (!$is_active) {
        unset($value[2]['tabindex']);
    }
    return new \HTML($value);
}

function stacks($value, $key) {
    $tags = \array_replace([
        'lot' => true,
        'lot:stacks' => true,
        'p' => true
    ], $value['tags'] ?? []);
    $has_current = false;
    $has_gap = !empty($value['gap']);
    $value['has']['gap'] = $value['has']['gap'] ?? $has_gap;
    $name = $value['name'] ?? $key;
    $value[0] = $value[0] ?? 'div';
    $value[1] = $value[1] ?? "";
    $value[2]['data-name'] = $value[2]['data-name'] ?? 'query[stack][' . $name . ']';
    $value[2]['tabindex'] = $value[2]['tabindex'] ?? 0;
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
                $has_current = $has_current || !empty($v['current']);
                $v[2]['data-name'] = $value[2]['data-name'];
                $v[2]['data-value'] = $kk;
                if (null !== $current && $kk === $current && !\array_key_exists('current', $v)) {
                    $v['current'] = true;
                    $has_current = true;
                }
                if (empty($v['url']) && empty($v['link'])) {
                    $v['url'] = \strstr(\x\panel\to\link(['query' => ['stack' => [$name => $kk]]]), '?');
                } else {
                    $v['has']['link'] = $v['has']['link'] ?? true;
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
        $value[1] .= \x\panel\lot\type($v, $k);
        ++$count;
    }
    unset($lot);
    $value['has']['current'] = $value['has']['current'] ?? $has_current;
    $tags['count:' . $count] = $tags['count:' . $count] ?? true;
    $value['tags'] = $tags;
    $value[2] = \x\panel\lot\_decor_set($value[2], $value);
    $value[2] = \x\panel\lot\_tag_set($value[2], $value);
    return new \HTML($value);
}

function tab($value, $key) {
    unset($value['description'], $value['title']);
    $is_current = !empty($value['current']);
    $value['is']['current'] = $value['is']['current'] ?? $is_current;
    $out = \x\panel\lot\type\section($value, $key);
    return isset($out[1]) && "" !== $out[1] ? $out : null;
}

function tabs($value, $key) {
    $tags = \array_replace([
        'lot' => true,
        'lot:tabs' => true,
        'p' => true
    ], $value['tags'] ?? []);
    $has_current = false;
    $has_gap = !isset($value['gap']) || $value['gap'];
    $value['has']['gap'] = $value['has']['gap'] ?? $has_gap;
    $name = $value['name'] ?? $key;
    $value[0] = $value[0] ?? 'div';
    $value[1] = $value[1] ?? "";
    $value[2]['data-name'] = $value[2]['data-name'] ?? 'query[tab][' . $name . ']';
    $value[2]['tabindex'] = $value[2]['tabindex'] ?? 0;
    if (isset($value['content'])) {
        $value[1] .= \x\panel\to\content($value['content']);
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
                $v['can']['toggle'] = !empty($v['toggle']);
                $v[3]['aria-controls'] = 'c:' . $id;
                $v[3]['aria-selected'] = 'false';
                $v[3]['data-name'] = $value[2]['data-name'];
                $v[3]['data-value'] = $kk;
                $v[3]['id'] = 't:' . $id;
                $v[3]['role'] = 'tab';
                $v[3]['tabindex'] = -1;
                $v[3]['target'] = $v[2]['target'] ?? $v['target'] ?? 'tab:' . $kk;
                if (empty($v['url']) && empty($v['link']) && (!\array_key_exists('active', $v) || $v['active'])) {
                    $v['url'] = \strstr(\x\panel\to\link(['query' => ['tab' => [$name => $kk]]]), '?');
                } else {
                    $v['has']['link'] = $v['has']['link'] ?? (!\array_key_exists('active', $v) || $v['active']);
                    if (!\array_key_exists('content', $v) && !\array_key_exists('lot', $v)) {
                        // Make sure link tab has a content to preserve the tab title
                        $v['content'] = \P;
                    }
                }
            }
            $links[$kk] = $v;
            $sections[$kk] = $v;
            $sections[$kk][2]['aria-labelledby'] = 't:' . $id;
            $sections[$kk][2]['data-name'] = $v[3]['data-name'];
            $sections[$kk][2]['data-value'] = $v[3]['data-value'];
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
            $links[$current]['current'] = $sections[$current]['current'] = true;
            $links[$current][3]['aria-selected'] = 'true';
            $links[$current][3]['tabindex'] = 0;
            $has_current = true;
        } else if (null !== $first && isset($links[$first]) && \is_array($links[$first])) {
            $links[$first]['current'] = $sections[$first]['current'] = true;
            $links[$first][3]['aria-selected'] = 'true';
            $links[$first][3]['tabindex'] = 0;
            $has_current = true;
        }
        foreach ($sections as $k => $v) {
            // If `type` is not defined, the default value will be `tab`
            if (\is_array($v)) {
                $v['type'] = $v['type'] ?? 'tab';
            }
            // $v[2] = \x\panel\lot\_decor_set($v[2], $v);
            $v[2] = \x\panel\lot\_tag_set($v[2], $v);
            $vv = (string) \x\panel\lot\type($v, $k);
            if ("" === $vv) {
                unset($links[$k]);
            } else {
                ++$count;
            }
            $sections[$k] = $vv;
        }
        if ($links) {
            $value[1] .= \x\panel\lot\type\links(\x\panel\lot\_value_set([
                '0' => 'nav',
                '2' => ['tabindex' => false],
                '3' => ['role' => 'tablist'],
                'lot' => $links
            ], $name), $name);
        }
        if ($sections) {
            $value[1] .= \implode("", $sections);
        }
    }
    if ($count < 2) {
        unset($value[2]['tabindex']);
    }
    $value['has']['current'] = $has_current;
    $tags['count:' . $count] = $tags['count:' . $count] ?? true;
    $value['tags'] = $tags;
    $value[2] = \x\panel\lot\_decor_set($value[2], $value);
    $value[2] = \x\panel\lot\_tag_set($value[2], $value);
    return new \HTML($value);
}

function tasks($value, $key) {
    $tags = \array_replace([
        'lot' => true,
        'lot:tasks' => true,
        'p' => true
    ], $value['tags'] ?? []);
    $value[0] = $value[0] ?? 'div';
    $value[1] = $value[1] ?? "";
    $value[2]['role'] = $value[2]['role'] ?? 'group';
    $value[2]['tabindex'] = $value[2]['tabindex'] ?? -1;
    $count = 0;
    if (isset($value['content'])) {
        $value[1] .= \x\panel\to\content($value['content']);
        $tags['count:' . ($count = 1)] = $tags['count:' . $count] ?? true;
    } else if (isset($value['lot'])) {
        $value[1] .= \x\panel\to\lot($value['lot'], $count, $value['sort'] ?? true);
        $tags['count:' . $count] = $tags['count:' . $count] ?? true;
    }
    $value['tags'] = $tags;
    $value[2] = \x\panel\lot\_decor_set($value[2], $value);
    $value[2] = \x\panel\lot\_tag_set($value[2], $value);
    return new \HTML($value);
}

function textarea($value, $key) {
    $has_pattern = \array_key_exists('pattern', $value);
    $is_active = !isset($value['active']) || $value['active'];
    $is_fix = !empty($value['fix']);
    $is_vital = !empty($value['vital']);
    $value['has']['pattern'] = $value['has']['pattern'] ?? $has_pattern;
    $value['is']['active'] = $value['is']['active'] ?? $is_active;
    $value['is']['fix'] = $value['is']['fix'] ?? $is_fix;
    $value['is']['vital'] = $value['is']['vital'] ?? $is_vital;
    $value['not']['active'] = $value['not']['active'] ?? !$is_active;
    $value['not']['fix'] = $value['not']['fix'] ?? !$is_fix;
    $value['not']['vital'] = $value['not']['vital'] ?? !$is_vital;
    $value['tags']['textarea'] = true;
    $value[0] = $value[0] ?? 'textarea';
    $value[1] = \htmlspecialchars($value[1] ?? $value['value'] ?? "");
    $value[2] = $value[2] ?? [];
    if ($has_pattern) {
        $value[2]['pattern'] = $value['pattern'];
    }
    $value[2]['autofocus'] = !empty($value['focus']);
    $value[2]['disabled'] = !$is_active;
    $value[2]['id'] = $value['id'] ?? 'f:' . \substr(\uniqid(), 6);
    $value[2]['maxlength'] = $value['max'] ?? null;
    $value[2]['minlength'] = $value['min'] ?? null;
    $value[2]['name'] = $value['name'] ?? $key;
    $value[2]['placeholder'] = \i(...((array) ($value['hint'] ?? "")));
    $value[2]['readonly'] = $is_fix;
    $value[2]['required'] = $is_vital;
    $value[2] = \x\panel\lot\_decor_set($value[2], $value);
    $value[2] = \x\panel\lot\_tag_set($value[2], $value);
    return new \HTML($value);
}

function title($value, $key) {
    $value[0] = $value[0] ?? [
       -1 => 'span',
        0 => 'p',
        1 => 'h1',
        2 => 'h2',
        3 => 'h3',
        4 => 'h4',
        5 => 'h5',
        6 => 'h6'
    ][$level = $value['level'] ?? 1] ?? false;
    $value[1] = $value[1] ?? $value['content'] ?? "";
    $value[2] = $value[2] ?? [];
    $icon = $value['icon'] ?? [];
    $status = $value['status'] ?? "";
    $title = \trim(\w(\i(...((array) $value[1])), ['a', 'abbr', 'b', 'code', 'del', 'em', 'i', 'ins', 'mark', 'small', 'strong', 'sub', 'sup']) ?? "");
    if (!$icon && !$title && '0' !== $title) {
        return null;
    }
    $icon = \x\panel\to\icon($icon);
    if ("" !== $status) {
        $title = \trim($title . ' <small class="status" role="status">' . \i(...((array) $status)) . '</small>');
    }
    $value[1] = $icon[0] . ("" !== $title ? '<span>' . $title . '</span>' : "") . $icon[1];
    if (false !== $value[0] && isset($value['description'])) {
        $value[2]['title'] = \i(...((array) $value['description']));
    }
    $value['has']['icon'] = $value['has']['icon'] ?? !!($icon[0] || $icon[1]);
    $value['has']['status'] = $value['has']['status'] ?? !!$status;
    $value['has']['title'] = $value['has']['title'] ?? !!$title;
    $value['tags']['level:' . $level] = $value['tags']['level:' . $level] ?? $level >= 0;
    $value['tags']['title'] = $value['tags']['title'] ?? true;
    $value[2] = \x\panel\lot\_decor_set($value[2], $value);
    $value[2] = \x\panel\lot\_tag_set($value[2], $value);
    return new \HTML($value);
}

function unit($value, $key) {
    $units = [null, null];
    // Maybe a `HTML`
    if (isset($value['content'])) {
        $units[0] = \x\panel\to\content($value['content']);
    // Maybe an `Anemone`
    } else if (isset($value['lot'])) {
        $v = \x\panel\to\lot($value['lot']);
        $units[0] = $v[0] ?? null;
        $units[1] = $v[1] ?? null;
    } else {
        // Must be an array or string, force it to array!
        if (!\is_array($value) || !\array_is_list($value)) {
            $value = [null, $value];
        }
        $units = \array_replace($units, $value);
    }
    foreach ($units as $k => $v) {
        if (!$v) {
            continue;
        }
        if (\is_array($v)) {
            $attr = \array_replace($attr, $v[2] ?? []);
            $attr = \x\panel\lot\_decor_set($attr, $v);
            $attr = \x\panel\lot\_tag_set($attr, $v);
            $content = \x\panel\to\content($v[1] ?? $v['content'] ?? null);
            $description = \x\panel\to\description($v['description'] ?? "");
            $target = $v['target'] ?? null;
            $text = \x\panel\to\text($v['text'] ?? "");
            $title = \x\panel\to\title($v['title'] ?? "");
            $v = new \HTML(['span', $content ?? $title ?? $text, ['title' => $description]]);
            if (isset($v['link'])) {
                $link = \is_array($v['link']) ? \x\panel\to\link($v['link']) : $v['link'];
                $v['href'] = $link;
                $v['target'] = $target ?? '_blank';
                $v[0] = 'a';
            } else if (isset($v['url'])) {
                $url = \is_array($v['url']) ? \x\panel\to\link($v['url']) : $v['url'];
                $v['href'] = $url;
                $v['target'] = $target ?? '_blank';
                $v[0] = 'a';
            }
        }
        $units[$k] = $v;
    }
    return new \Anemone($units, "");
}

require __DIR__ . \D . 'type' . \D . 'button.php';
require __DIR__ . \D . 'type' . \D . 'field.php';
require __DIR__ . \D . 'type' . \D . 'form.php';
require __DIR__ . \D . 'type' . \D . 'input.php';
require __DIR__ . \D . 'type' . \D . 'link.php';
require __DIR__ . \D . 'type' . \D . 'select.php';
require __DIR__ . \D . 'type' . \D . 'tasks.php';
require __DIR__ . \D . 'type' . \D . 'textarea.php';