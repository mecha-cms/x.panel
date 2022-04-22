<?php

return [
    'task' => [
        'user/*' => static function($path) use($_, $user) {
            if ($user->name(true) === $path) {
                return [
                    'l' => false // Disable delete current user (suicide)
                ];
            }
            return true;
        }
    ]
];