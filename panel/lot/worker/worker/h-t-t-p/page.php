<?php

// `POST`
$page = HTTP::post('page', [], false);

if ($a < 0) {
    // `GET`
    // Only user with status `1` that has delete access
    if ($c === 'r' && $user->status === 1) {
        // Delete folder
        $d = Path::F($file);
        if ($a === -2) {
            File::open($d)->moveTo(str_replace(LOT . DS, LOT . DS . 'trash' . DS . $_date . DS, dirname($d)));
        } else if ($a === -1) {
            File::open($d)->delete();
        }
    // `POST` … a user click the submit button with name `a`
    } else if (HTTP::is('post')) {
        // Redirect to `GET`
        Guardian::kick(str_replace('::' . $c . '::', '::r::', $url->path) . HTTP::query([
            'a' => $a,
            'token' => HTTP::post('token'),
            'view' => 'page'
        ], '&'));
    }
}

if ($layout = HTTP::post('page:view')) {
    if ($layout === 'page') {
        File::put("")->saveTo(Path::F($file) . DS . '$.' . Path::X($file));
    } else if ($layout === 'pages') {
        foreach (['page', 'archive'] as $v) {
            File::open(Path::F($file) . DS . '$.' . $v)->delete();
        }
    }
}

// Process page image
if (Extend::exist('image') && !array_key_exists('image', $page) && $blob = HTTP::files('image')) {
    call_user_func(function() use($blob, $language, &$page, $state, $user) {
        // Detect image by MIME type
        if (!empty($blob['type']) && strpos($blob['type'], 'image/') !== 0) {
            Message::error('page_image_blob');
        } else if (!empty($blob['name'])) {
            $b = To::file($blob['name']);
            $n = Path::N($b);
            $x = Path::X($b);
            // Detect image by extension
            if ($x && strpos(',' . IMAGE_X . ',', ',' . $x . ',') === false) {
                Message::error('page_image_blob');
            } else {
                $candy = [
                    'date' => new Date,
                    'extension' => $x,
                    'hash' => Guardian::hash(),
                    'id' => sprintf('%u', time()),
                    'name' => $n,
                    'uid' => uniqid()
                ];
                $blob['name'] = candy(($state['page']['image']['name'] ?? $b) ?: $b, $candy);
                $path = ASSET . ($user->status === 1 ? DS : DS . $user->key . DS);
                $path = rtrim($path . DS . strtr(candy($state['page']['image']['directory'] ?? "", $candy), '/', DS), DS);
                $response = File::push($blob, $path);
                // File already exists
                if ($response === false) {
                    Message::info($language->message_error_file_exist([str_replace(ROOT, '.', $path . DS . $blob['name'])]));
                    $page['image'] = To::URL($path . DS . $blob['name']);
                // Trigger error
                } else if (is_int($response)) {
                    // But `4` (no file was uploaded)
                    if ($response !== 4) {
                        Message::error($language->message_info_file_push[$response] ?? $language->error . ': ' . $response);
                    }
                } else {
                    // Resize image
                    $width = HTTP::post('image.width');
                    $height = HTTP::post('image.height');
                    if ($width !== null) {
                        $width = b($width, 72, 1600);
                        $height = b($height ?? $width, 72, 1600);
                        Image::open($response)->crop($width, $height)->save();
                    }
                    // Create thumbnail
                    Image::open($response)->crop(72, 72)->saveTo(Path::F($response) . DS . '72.' . $x);
                    Reset::post('image'); // Just in case
                    $page['image'] = To::URL($response);
                    Message::success('file_push', ['<code>' . str_replace(ROOT, '.', $response) . '</code>']);
                }
            }
        }
    });
} else if (!empty($page['image:x'])) {
    call_user_func(function() use(&$page) {
        $image = To::path(URL::long($page['image']));
        // Delete image if image is stored in the asset folder
        if (strpos($image, ASSET . DS) === 0) {
            File::open($image)->delete();
            // Delete thumbnail
            $folder = Path::F($image);
            File::open($folder . DS . '72.' . Path::X($image))->delete();
            // Delete folder if empty
            if (Folder::size($folder) === '0 B') {
                Folder::open($folder)->delete();
            }
        }
        $page['image'] = false; // Remove `image` property
        unset($page['image:x']);
        Message::success('file_delete', ['<code>' . str_replace(ROOT, '.', $image) . '</code>']);
    });
}

// `POST` …
$headers = [
    'title' => function($s) {
        return w($s, HTML_WISE_I) ?: false;
    },
    '$' => function($s) {
        return w($s, HTML_WISE_I) ?: false;
    },
    'description' => function($s) {
        return w($s, HTML_WISE_I . ',p') ?: false;
    },
    'type' => false,
    'link' => false,
    'image' => false,
    'author' => function($s) {
        $s = trim($s);
        if (strpos($s, '@') === 0 && strlen($s) > 1) {
            return $s;
        }
        return w($s) ?: false;
    },
    'version' => function($s) {
        $a = explode('.', $s);
        $a = array_pad($a, 3, '0');
        return implode('.', $a); // #semver
    },
    'content' => ""
];

$o = (array) Config::get($id, [], true);
if (count($o) === 1 && isset($o[0])) {
    $o = false; // Numeric array, not a page configuration file
}

foreach ($headers as $k => $v) {
    if (!isset($page[$k])) continue;
    if (is_callable($v)) {
        $v = call_user_func($v, $page[$k]);
    } else {
        $v = $page[$k];
    }
    if ($o !== false && isset($o[$k]) && $o[$k] === $v) {
        $v = false;
    }
    $headers[$k] = $v;
    unset($page[$k]);
}

$headers = extend($headers, From::YAML(HTTP::post(':', "", false), '  ', false, false), $page);
$headers = is($headers, function($v) {
    return isset($v) && $v !== false && $v !== "" && !fn\is\instance($v);
});
$time = date(DATE_WISE);
$name = HTTP::post('slug', isset($headers['title']) ? $headers['title'] : "", false) ?: ($c === 'g' ? Path::F(basename($file)) : $time);
$name = To::slug($name);

if (!Message::$x) {
    if ($c === 'g') {
        $nn = Path::N($n);
        Folder::create($dd = LOT . DS . $path . DS . $nn, 0775);
        if ($nn !== $name) {
            File::open($dd)->renameTo($name); // Rename folder
        }
    } else if ($c === 's') {
        Folder::create($dd = LOT . DS . $path . DS . $name, 0775);
    }
    // Process page data
    $data = HTTP::post('data', [], false);
    if (!isset($data['time']) && $name !== strtr($time, '- :', '---')) {
        $data['time'] = $time;
    }
    foreach ($data as $k => $v) {
        $f = $dd . DS . To::slug($k) . '.data';
        if (Is::void($v)) {
            File::open($f)->delete();
        } else {
            File::put(is_array($v) ? json_encode($v) : $v)->saveTo($f, 0600);
        }
    }
}

// Process page tag(s)
if (Extend::exist('tag')) {
    // Can’t use `Get::tags()` here because the function is not ready yet
    require_once EXTEND . DS . 'tag' . DS . 'engine' . DS . 'plug' . DS . 'get.php';
    require_once EXTEND . DS . 'tag' . DS . 'engine' . DS . 'plug' . DS . 'from.php';
    require_once EXTEND . DS . 'tag' . DS . 'engine' . DS . 'plug' . DS . 'to.php';
    call_user_func(function() use($c, $language, $name, $path, $user) {
        $file = LOT . DS . $path . DS . $name . DS . 'kind.data';
        if (!$tags = HTTP::post('tags')) {
            File::open($file)->delete();
            return;
        }
        $i = Get::tags(TAG, 'page,archive', [1, 'id'])->last();
        $i += 1;
        $kinds = [];
        foreach (preg_split('#\s*,\s*#', $tags) as $tag) {
            $tag_id = From::tag($tag);
            if ($tag_id !== false) {
                $kinds[] = $tag_id;
            } else {
                // Create a new tag
                Page::set([
                    'title' => To::title($tag),
                    'author' => $user->key
                ])->saveTo($f = TAG . DS . $tag . '.page', 0600);
                File::put($i)->saveTo(TAG . DS . $tag . DS . 'id.data', 0600);
                File::put(date(DATE_WISE))->saveTo(TAG . DS . $tag . DS . 'time.data', 0600);
                Message::info($language->message_success_file_create(['<code>' . str_replace(ROOT, '.', $f) . '</code>']));
                $kinds[] = $i;
                ++$i;
            }
        }
        if ($kinds) {
            File::put(json_encode($kinds))->saveTo($file, 0600);
        }
    });
}

Set::post('name', $name);
Set::post('x', HTTP::post('x', 'draft'));
Set::post('file.content', Page::unite($headers) ?: "---\n...");
Set::post('file.consent', $consent = 0600);