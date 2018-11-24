<?php

if (!$chops) {
    $files = array_keys(File::explore([COMMENT, 'draft,page,archive'], true));
    usort($files, function($a, $b) {
        return basename($b) <=> basename($a);
    });
    $files = array_slice($files, 0, $state['page']['chunk']);
    Hook::set('page.image', function($image) {
        $comment = new Comment($this->path);
        return $image ?: $comment->avatar(72, 72, 'monsterid') ?: $GLOBALS['URL']['protocol'] . 'www.gravatar.com/avatar/' . md5($comment->email) . '?s=72&amp;d=monsterid';
    });
    Hook::set('page.url', function($title) {
        return (new Comment($this->path))->url . "";
    });
    Hook::set('page.title', function($title) {
        return (new Comment($this->path))->author . "";
    });
    Hook::set('page.description', function($title) use($state) {
        return To::snippet(Page::apart($this->path, 'content'), true, $state['page']['snippet']);
    });
    Config::set('panel.+.page.tool.s', [
        'if' => function($file): array {
            return [
                'path' => Path::R(dirname($file), LOT, '/'),
                'query' => [
                    'f' => ['data' => ['parent' => pathinfo($file, PATHINFO_FILENAME)]]
                ]
            ];
        },
        'description' => $language->do_reply,
        'icon' => [['M10,9V5L3,12L10,19V14.9C15,14.9 18.5,16.5 21,20C20,15 17,10 10,9Z']]
    ]);
    Config::reset('panel.desk.header');
    Hook::set('on.ready', function() use($files) {
        Config::set('panel.desk.body.tab.recent', [
            'content' => fn\panel\pages($files),
            'stack' => 9.9
        ]);
    }, .1);
}