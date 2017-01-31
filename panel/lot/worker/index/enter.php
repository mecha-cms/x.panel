<?php

if ($__sgr !== 'g') {
    Shield::abort(PANEL_404);
}

if ($__is_enter) {
    Guardian::kick($__state->path . '/::g::/page');
}

if (Request::is('post')) {
    $__user = Request::post('user');
    $__pass = Request::post('pass');
    $__token = Request::post('token');
    $f = ENGINE . DS . 'log' . DS . 'user' . DS . $__user;
    if (!$__user) {
        Message::error('void_field', $language->user, true);
    } else if (!$__pass) {
        Message::error('void_field', $language->pass, true);
    } else if (file_exists($f . '.page')) {
        if (!file_exists($f . DS . 'pass.data')) {
            // Reset password by deleting `pass.data` manually
            File::write(password_hash($__pass . $__user, PASSWORD_DEFAULT))->saveTo($f . DS . 'pass.data');
        }
        if (password_verify($__pass . $__user, File::open($f . DS . 'pass.data')->get(0, ""))) {
            File::write($__token)->saveTo($f . DS . 'token.data');
            $c = [
                'expire' => 30,
                'http_only' => true
            ];
            Cookie::set('Mecha\Panel.user', $__user, $c);
            Cookie::set('Mecha\Panel.token', $__token, $c);
            Message::success('user_enter');
            Hook::NS('on.enter');
            Guardian::kick(Request::post('kick', ""));
        } else {
            Message::error('user_or_pass');
        }
    } else {
        Message::error('user_or_pass');
    }
    if (Message::$x) {
        Request::save('post', 'user', $__user);
    }
}

function panel_f_user($__lot) {
    extract($__lot);
    echo '<p class="f">';
    echo '<label for="f-user">' . $language->user . '</label>';
    echo ' <span>';
    echo Form::text('user', null, null, ['classes' => ['input', 'block'], 'id' => 'f-user', 'autofocus' => true]);
    echo '</span>';
    echo '</p>';
    return $__lot;
}

function panel_f_pass($__lot) {
    extract($__lot);
    echo '<p class="f">';
    echo '<label for="f-pass">' . $language->pass . '</label>';
    echo ' <span>';
    echo Form::password('pass', null, null, ['classes' => ['input', 'block'], 'id' => 'f-pass']);
    echo '</span>';
    echo '</p>';
    return $__lot;
}

function panel_f_kick($__lot) {
    extract($__lot);
    echo Form::hidden('kick', Request::get('kick', $__state->path . '/::g::/page'));
    return $__lot;
}

function panel_f_enter($__lot) {
    extract($__lot);
    echo '<p class="f expand">';
    echo '<label for="f-enter">' . $language->enter . '</label>';
    echo ' <span>';
    echo Form::submit('enter', 1, $language->enter, ['classes' => ['button', 'set'], 'id' => 'f-enter']);
    echo '</span>';
    echo '</p>';
    return $__lot;
}

foreach ([
    10 => 'panel_f_user',
    20 => 'panel_f_pass',
    30 => 'panel_f_kick'
] as $k => $v) {
    Hook::set('panel.m.editor', $v, $k);
}

function panel_m_page($__lot) {
    extract($__lot);
    echo '<fieldset>';
    echo '<legend>' . $language->log_in . '</legend>';
    Hook::fire('panel.m.editor', [$__lot]);
    echo '</fieldset>';
    panel_f_enter($__lot);
    return $__lot;
}

Hook::set('panel.m', 'panel_m_' . $site->type, 10);

function panel_m($__lot) {
    extract($__lot);
    echo $__message;
    Hook::fire('panel.m', [$__lot]);
    echo Form::token();
    return $__lot;
}

Hook::set('panel', 'panel_m', 10);

if (Route::is($__state->path . '/::g::/enter')) {
    Hook::set('shield.path', function($path) use($site) {
        $base = Path::B($path);
        if ($base === $site->type . '.php') {
            return PANEL . DS . 'lot' . DS . 'worker' . DS . $site->type . DS . 'enter.php';
        }
        return $path;
    });
}