<?php

// Create user-specific folder
if (!Folder::exist($personal = ASSET . DS . $user->key)) {
    Folder::create($personal, 0755);
}

// Only user with status `1` that has access to the root asset folder
if ($file && strpos($file . DS, DS . $user->key . DS) === false) {
    Guardian::kick($r . '/::g::/' . $id . '/' . $user->key . '/1');
}