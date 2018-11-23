<?php

// Only user with status `1` can create/update user’s `status` data
if ($file && is_file($file)) {
    if ($data = File::exist(Path::F($file) . DS . 'status.data')) {
        $status = (int) file_get_contents($data);
        if ($status !== $user->status) {
            File::put($user->status)->saveTo($data, 0600);
        }
    } else {
        $status = Page::apart($file, 'status', null, true);
        if ($status !== $user->status) {
            Page::open($file)->set('status', $user->status)->save(0600);
        }
    }
}

// Force `view` value to `page`
require __DIR__ . DS . '..' . DS . ($panel->v = $panel->view = 'page') . DS . 'user.php';