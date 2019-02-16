<?php

$user_void = !glob(USER . DS . '*.page', GLOB_NOSORT);
$user_create = Extend::state('panel', 'path') . '/::s::/user';
if (!Extend::exist('user')) {
    Guardian::abort('Missing <code>user</code> extension.');
} else if ($user_void) {
    $uid = uniqid();
    Page::set([
        '$' => $language->user,
        'description' => 'Delete me!',
        'status' => 1
    ])->saveTo(USER . DS . $uid . '.page', 0600);
    $token = Guardian::token('panel');
    File::put($token)->saveTo(USER . DS . $uid . DS . 'token.data', 0600);
    Cookie::set(URL::session . '.user', '@' . $uid);
    Cookie::set(URL::session . '.token', $token);
    Session::set(URL::session . '.user', '@' . $uid);
    Session::set(URL::session . '.token', $token);
    Guardian::kick($user_create);
} else if ($url->path === $user_create) {
    Hook::set('on.ready', function() use($language, $user) {
        if ($user->pass) return;
        Config::set('panel.nav', "");
        Config::set('panel.desk.footer.tool.page', [
            'title' => $language->create,
            'icon' => [['M16.56,5.44L15.11,6.89C16.84,7.94 18,9.83 18,12A6,6 0 0,1 12,18A6,6 0 0,1 6,12C6,9.83 7.16,7.94 8.88,6.88L7.44,5.44C5.36,6.88 4,9.28 4,12A8,8 0 0,0 12,20A8,8 0 0,0 20,12C20,9.28 18.64,6.88 16.56,5.44M13,3H11V13H13']]
        ]);
        Config::set('panel.desk.footer.tool.draft.hidden', true);
        Config::set('panel.desk.body.tab.data.hidden', true);
        Config::set('panel.desk.body.tab.file.field.page[email].hidden', true);
        Config::set('panel.desk.body.tab.file.field.page[content].hidden', true);
        Config::set('panel.desk.body.tab.file.field.page[type].hidden', true);
    });
    Hook::set('on.file.set', function($previous) use($language, $user) {
        if ($previous || $user->pass) return;
        Message::reset();
        Message::info('is', [$language->pass, '<em>' . (new User($this->path, [], false))->pass . '</em>']);
        File::open($user->path)->delete();
        File::open(Path::F($user->path))->delete();
        if (defined('DEBUG') && DEBUG) {
            Message::info('<strong>DEBUG:</strong> Delete <code>' . __FILE__ . '</code>');
            File::open(__FILE__)->renameTo(Path::N(__FILE__) . '.x');
        } else {
            unlink(__FILE__);
        }
        $state = Extend::state('user');
        Guardian::kick($state['_path'] ?? $state['path']);
    }, 0);
}