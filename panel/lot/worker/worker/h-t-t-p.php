<?php

// Only user with status `1` can create `htaccess` and `php` file
if ($user->status !== 1) {
    File::$config['extension'] = array_diff(File::$config['extension'], ['htaccess', 'php']);
}

$candy = [
    'date' => new Date,
    'hash' => Guardian::hash(),
    'id' => sprintf('%u', time()),
    'uid' => uniqid()
];

$c = $panel->c;
$r = $panel->r;
$a = HTTP::post('a', HTTP::get('a'));
$id = $panel->id;
$tab = HTTP::get('tab.0');
$view = HTTP::post('view', HTTP::get('view', $panel->view));
$gate = File::exist(__DIR__ . DS . 'h-t-t-p' . DS . $view . '.php', false);

$path = strtr(rtrim($id . '/' . $panel->path, '/'), '/', DS);
$directory = trim(strtr(HTTP::post('directory', ""), '/', DS), DS);

$consent = HTTP::post('file.consent', null, false);

// <https://stackoverflow.com/q/28672096>
if ($consent !== null) {
    $consent = octdec($consent);
}

$_date = date('_Y-m-d-H-i-s');
$is_file = is_file($previous = $file = LOT . DS . $path);

if ($c !== 's' && !file_exists($file)) {
    Guardian::abort('File <code>' . $file . '</code> does not exist.');
}

$any = $c === 's' || $is_file ? 'file' : 'folder';

// `GET`, `POST`
if ($c !== 'r') {
    // `GET`
    if ($c === 'a' && HTTP::is('get')) {
        $gate !== false && require $gate;
        // Run task
        if (!$a) {
            Guardian::abort('Missing task ID.');
        } else if (function_exists($task = '_' . $a)) {
            $lot = (array) HTTP::get('lot', []);
            array_unshift($lot, $file);
            $def = str_replace('::a::', '::g::', dirname($url->path) . '/1');
            if ($return = call_user_func($task, ...$lot)) {
                Guardian::kick($return['kick'] ?? $def);
            }
            Guardian::kick($def);
        } else {
            Guardian::abort('Task <code>' . $task . '</code> not found.');
        }
    // `POST`
    } else if (!HTTP::is('post')) {
        Guardian::abort('Method not allowed.');
    }
// `GET`
} else /* if ($c === 'r') */ {
    if (
        // Only user with status `1` that has delete access
        $user->status !== 1 ||
        // Prevent user(s) from deleting the root folder(s)
        strpos($path, DS) === false
    ) {
        Message::error(($is_file ? 'file' : 'folder') . '_delete');
        Guardian::kick(str_replace('::r::', '::g::', ($is_file ? dirname($url->path) : $url->path) . '/1'));
    }
    $gate !== false && require $gate;
    $trash = LOT . DS . 'trash' . DS . $_date;
    $trashy = str_replace(LOT . DS, $trash . DS, dirname($file));
    Hook::fire('on.file.reset', [$previous], new File($a === -2 ? $trashy : $file));
    // Move to trash
    if ($a === -2) {
        File::open($file)->moveTo($trashy);
        Session::set('panel.file.active', $trash);
        Message::success('move', [$language->trash, $url . '/' . $r . '/::g::/trash/1']);
    // Restore
    } else if ($a === 1) {
        $res = str_replace(LOT . DS . 'trash' . DS, "", $file);
        $kk = strpos($res, DS);
        $kk = $kk !== false ? substr($res, 0, $kk) : $res;
        $o = [];
        if ($is_file) {
            $o = File::open($file)->moveTo(rtrim(str_replace(LOT . DS . 'trash' . DS . $kk . DS, LOT . DS, dirname($file) . DS), DS));
        } else {
            foreach (glob($file . DS . '{,.}[!.,!..]*', GLOB_BRACE | GLOB_NOSORT) as $v) {
                $o = extend($o, File::open($v)->moveTo(rtrim(str_replace(LOT . DS . 'trash' . DS . $kk . DS, LOT . DS, dirname($v) . DS), DS)), false);
            }
            rmdir($file);
        }
        Session::set('panel.file.active', array_values($o));
        Message::success(To::sentence($language->restoreed));
    } else {
        File::open($file)->delete();
        Message::success('file_delete', ['<code>' . str_replace(ROOT, '.', $file) . '</code>']);
    }
    Guardian::kick(str_replace('::r::', '::g::', dirname($url->path)) . '/1');
}

$query = HTTP::query(['token' => false]);
// `POST`
if ($tab === 'folder') {
    if (Is::void($directory)) {
        Message::error('void_field', ['<em>' . $language->path . '</em>'], true);
    }
    if (!Message::$x) {
        // Move file
        if ($is_file) {
            File::open($file)->moveTo($d = dirname($file) . DS . $directory);
            File::open($d)->consent(0755);
        // Create empty folder
        } else {
            Folder::create($d = LOT . DS . $path . DS . $directory, 0755);
        }
        Session::set('panel.file.active', LOT . DS . ($is_file ? dirname($path) : $path) . DS . explode(DS, $directory)[0]);
        Message::success('folder_create', ['<code>' . str_replace(ROOT, '.', $d) . '</code>']);
        HTTP::delete();
        Hook::fire('on.folder.set', [$c === 's' ? null : $previous], new Folder($file));
        Guardian::kick($r . '/::g::/' . strtr($is_file ? dirname($path) : $path, DS, '/') . '/1');
    } else {
        HTTP::save();
        Guardian::kick($url->path . $query);
    }
} else if ($tab === 'blob') {
    if ($blob = HTTP::files('blob')) {
        if (!empty($blob['name'])) {
            $blob['name'] = candy(To::file($blob['name']), $candy);
            $destination = LOT . DS . $path;
            $response = File::push($blob, $destination);
            $x = Path::X($blob['name']);
            // Missing file extension
            if (!$x) {
                if ($user->status !== 1) {
                    Message::error('file_void_x');
                }
            // Forbidden file extension
            } else if (!has(File::$config['extension'], $x)) {
                Message::error('file_x', ['<code>' . $x . '</code>']);
            }
            // File already exists
            if ($response === false) {
                Message::error('file_exist', ['<code>' . str_replace(ROOT, '.', $destination . DS . $blob['name']) . '</code>']);
            // Trigger error
            } else if (is_int($response)) {
                Message::error($language->message_error_file_push[$response] ?? $language->error . ': ' . $response);
            }
        } else {
            Message::error('file_void');
        }
        if (!Message::$x) {
            Session::set('panel.file.active', $response);
            Message::success('file_push', ['<code>' . str_replace(ROOT, '.', $response) . '</code>']);
            if (Extend::exist('package') && HTTP::post('package.extract')) {
                foreach (Package::explore($response, true, []) as $k => $v) {
                    $x = $v === 1 ? Path::X($k) : false;
                    // Check forbidden file in the package by its file extension
                    if ($x !== false && !has(File::$config['extension'], $x)) {
                        Message::error('file_x', ['<code>' . $x . '</code>']);
                    }
                    // Check if file already exists
                    if (file_exists($f = $file . DS . $k)) {
                        Message::error(($v === 1 ? 'file' : 'folder') . '_exist', ['<code>' . str_replace(ROOT, '.', $f) . '</code>']);
                    }
                }
                if (!Message::$x) {
                    Package::open($response)->extractAs(!!HTTP::post('package.bucket'));
                    File::open($response)->delete(); // Delete the package
                } else {
                    File::open($response)->delete(); // Abort
                    Message::info($language->message_success_file_delete('<code>' . str_replace(ROOT, '.', $response) . '</code>'));
                }
            }
            if (!Message::$x) {
                Hook::fire('on.file.set', [null], new File($response));
                Guardian::kick(str_replace('::' . $c . '::', '::g::', $url->path) . '/1');
            } else {
                Guardian::kick($url->path . HTTP::query(['token' => false], '&'));
            }
        } else {
            Guardian::kick($url->path . HTTP::query(['token' => false], '&'));
        }
    }
} else /* if ($tab === 'file') */ {
    $name = call_user_func('To::' . $any, basename(HTTP::post('name', "", false)));
    $n = null;
    if ($c === 'g') {
        $n = basename($path); // previous name
        $path = dirname($path);
        if ($a < 0) {
            $gate !== false && require $gate;
            // `GET`
            Guardian::kick(str_replace('::' . $c . '::', '::r::', $url->path) . HTTP::query(['a' => $a], '&'));
        }
    }
    $gate !== false && require $gate;
    if ($x = HTTP::post('x', "", false)) {
        if ($name[0] === '.') {
            $name = substr($name, 1);
        }
        $name .= '.' . $x;
    }
    if ($content = HTTP::post('file.+', "")) {
        $test_x = $x ?: Path::X($name);
        if (is_string($content)) {
            $content = From::YAML($content);
        }
        if ($test_x === 'json') {
            $content = json_encode($content);
        } else if ($test_x === 'php') {
            $content = '<?php return ' . z($content) . ';';
        } else if ($test_x === 'yaml') {
            $content = To::YAML($content);
        } else {
            $content = serialize($content); // Default to serial
        }
        Set::post('file.content', $content);
        Reset::post('file.+');
    } else {
        $content = HTTP::post('file.content', "", false);
    }
    $file = LOT . DS . $path . DS . ($directory ? $directory . DS . $name : $name);
    $file = candy($file, $candy);
    $test_x = Path::X($file);
    if ($any === 'file' && $test_x && !has(File::$config['extension'], $test_x)) {
        Message::error('file_x', ['<code>' . $test_x . '</code>']);
    }
    if (Is::void($name)) {
        Message::error('void_field', ['<em>' . $language->name . '</em>'], true);
    } else if (file_exists($file)) {
        if ($c === 's' || $name !== basename($panel->path)) {
            Message::error($any . '_exist', ['<code>' . str_replace(ROOT, '.', $file) . '</code>']);
        }
    }
    if (!Message::$x) {
        if ($c === 'g' && (!$is_file || HTTP::post('file.read-only'))) {
            File::open($previous)->renameTo($name);
        } else {
            File::put($content)->saveTo($file, $consent);
        }
        Session::set('panel.file.active', $file);
        if ($n && ($directory || $n !== $name)) {
            File::open(LOT . DS . $path . DS . $n)->delete();
        }
        Message::success($any . '_' . ($c === 's' ? 'create' : 'update'), ['<code>' . str_replace(ROOT, '.', $c === 's' ? $file : $previous) . '</code>']);
        HTTP::delete();
        $to = $r . '/::g::/' . $path . '/' . ($directory ? str_replace(DS, '/', $directory) . '/' . $name : $name);
        Hook::fire('on.' . $any . '.set', [$c === 's' ? null : $previous], $any === 'file' ? new File($file) : new Folder($file));
        // Redirect to file list if we are in `s` command
        Guardian::kick($c === 's' ? dirname($to) . '/1' : $to . $query);
    } else {
        HTTP::save();
        Guardian::kick($url->path . $query);
    }
}