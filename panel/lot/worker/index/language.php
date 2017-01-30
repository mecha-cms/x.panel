<?php

$__kins = [[], []];
foreach (glob(LANGUAGE . DS . '*.page') as $v) {
    $__kins[0][] = new Page($v, [], '__language');
    $__kins[1][] = new Page($v, [], 'language');
}

Lot::set('__kins', $__kins);

if (!$__file = File::exist([
    LOT . DS . $__path . '.page',
    LANGUAGE . DS . $site->language . '.page'
])) {
    Shield::abort(PANEL_404);
}

if ($__sgr === 'g' && Path::N($__file) === 'en-us' && isset($__chops[1]) && $__chops[1] !== 'en-us') {
    Shield::abort(PANEL_404);
}

$__page = [
    new Page($__file, [], '__language'),
    new Page($__file, [], 'language')
];

Lot::set('__page', $__page);

if (Request::is('post') && !Message::$x) {
    $n = Path::N($__file);
    if (Request::post('x') === 'trash') {
        if ($n === 'en-us') {
            Shield::abort(PANEL_404); // you can’t delete the default language
        }
        Hook::NS('on.language.reset', [$__file]);
        if (!Message::$x) {
            File::open($__file)->renameTo($n . '.trash');
            Message::success(To::sentence($language->deleteed) . ' ' . HTML::a($language->restore, $__state->path . '/::r::/' . $__path . HTTP::query(['token' => $__token, 'abort' => 1]), false, ['classes' => ['right']]));
            Guardian::kick(Path::D($url->path));
        }
    }
    $s = Request::post('slug');
    if ($s === 'en-us' || ($s !== $n && File::exist(LANGUAGE . DS . $s . '.page'))) {
        Request::save('post');
        Message::error('exist', [$language->locale, '<em>' . $s . '</em>']);
    }
    Hook::NS('on.language.set', [$f]);
    if (!Message::$x) {
        $headers = [
            'title' => false,
            'description' => false,
            'author' => false,
            'type' => 'YAML',
            'version' => '0.0.0',
            'content' => false
        ];
        foreach ($headers as $k => $v) {
            $headers[$k] = Request::post($k, $v);
        }
        $f = LANGUAGE . DS . $s . '.page';
        Page::data($headers)->saveTo($f, 0600);
        Message::success(To::sentence($language->{($__sgr === 'g' ? 'update' : 'create') . 'ed'}));
        Guardian::kick($__state->path . '/::g::/' . $__chops[0] . '/' . $s);
    }
}

if ($__sgr === 's') {
    if (isset($__chops[1])) {
        Shield::abort(PANEL_404);
    }
    Lot::set('__page', [
        new Page(null, [
            'type' => 'YAML',
            'content' => $__page[0]->content
        ], '__language'),
        $__page[1]
    ]);
} else if ($__sgr === 'r') {
    if (!Request::get('token')) {
        Shield::abort(PANEL_404);
    }
    $s = Path::B($url->path);
    if (!$__file = File::exist(LANGUAGE . DS . $s . '.trash')) {
        Shield::abort(PANEL_404);
    }
    File::open($__file)->renameTo($s . '.page');
    Message::success(To::sentence($language->restoreed));
    Guardian::kick($__state->path . '/::g::/' . $__chops[0] . '/' . $s);
}


/**
 * Field(s)
 * --------
 */

function panel_s_language() {
    extract(Lot::get(null, []));
    echo '<section class="secondary-language">';
    echo '<h3>' . $language->{count($__kins[0]) === 1 ? 'language' : 'languages'} . '</h3>';
    echo '<ul>';
    foreach ($__kins[0] as $k => $v) {
        echo '<li class="language-' . $v->slug . '">';
        echo HTML::a($__kins[1][$k]->title($v->slug), $__state->path . '/::g::/' . $__chops[0] . '/' . $v->slug);
        echo '</li>';
    }
    echo '<li>';
    echo HTML::a('&#x2795;', $__state->path . '/::s::/' . $__chops[0], false, ['title' => $language->add]);
    echo '</li>';
    echo '</ul>';
    echo '</section>';
}

foreach ([
    10 => 'panel_s_language'
] as $k => $v) {
    Hook::set('panel.s.left', $v, $k);
}

function panel_s_left() {
    echo '<aside class="secondary">';
    Hook::fire('panel.s.left');
    echo '</aside>';
}

function panel_f_title() {
    extract(Lot::get(null, []));
    echo '<p class="f expand">';
    echo '<label for="f-title">';
    echo $language->title;
    echo '</label>';
    echo ' <span>';
    echo Form::text('title', $__page[0]->title, $__page[1]->title, [
        'classes' => ['input', 'block'],
        'id' => 'f-title',
        'data' => ['slug-i' => 'locale']
    ]);
    echo '</span>';
    echo '</p>';
}

function panel_f_content() {
    extract(Lot::get(null, []));
    echo '<div class="f expand p">';
    echo '<label for="f-content">' . $language->content . '</label>';
    echo '<div>';
    echo Form::textarea('content', $__page[0]->content, null, [
        'classes' => ['textarea', 'block', 'expand', 'code'],
        'id' => 'f-content',
        'data' => ['type' => $__page[0]->type]
    ]);
    echo '</div>';
    echo '</div>';
}

function panel_f_description() {
    extract(Lot::get(null, []));
    echo '<div class="f p">';
    echo '<label for="f-description">' . $language->description . '</label>';
    echo '<div>';
    echo Form::textarea('description', $__page[0]->description, $__page[1]->description, [
        'classes' => ['textarea', 'block'],
        'id' => 'f-description'
    ]);
    echo '</div>';
    echo '</div>';
}

function panel_f_version() {
    extract(Lot::get(null, []));
    echo '<p class="f">';
    echo '<label for="f-version">' . $language->version . '</label>';
    echo ' <span>';
    echo Form::text('version', $__page[0]->version, $__page[1]->version, [
        'classes' => ['input'],
        'id' => 'f-version'
    ]);
    echo '</span>';
    echo '</p>';
}

function panel_f_slug() {
    extract(Lot::get(null, []));
    echo '<p class="f">';
    echo '<label for="f-slug">' . $language->locale . '</label>';
    echo ' <span>';
    echo Form::text('slug', $__page[0]->slug, $__page[1]->slug, [
        'classes' => ['input'],
        'id' => 'f-slug',
        'pattern' => '^[a-z\\d-]+$',
        'data' => ['slug-o' => 'locale']
    ]);
    echo '</span>';
    echo '</p>';
}

function panel_f_state() {
    extract(Lot::get(null, []));
    echo '<p class="f expand">';
    echo '<label for="f-x">' . $language->state . '</label>';
    echo ' <span>';
    echo Form::submit('x', 'page', $language->{$__sgr === 's' ? 'create' : 'update'}, [
        'classes' => ['button', 'x-page'],
        'id' => 'f-x:page'
    ]);
    if ($__page[0]->slug !== 'en-us') {
        if ($__sgr === 'g') {
            echo ' ' . Form::submit('x', 'trash', $language->delete, [
                'classes' => ['button', 'x-trash'],
                'id' => 'f-x:trash'
            ]);
        }
    }
    echo '</span>';
    echo '</p>';
}

foreach ([
    10 => 'panel_f_title',
    20 => 'panel_f_content',
    30 => 'panel_f_description',
    40 => 'panel_f_version',
    50 => 'panel_f_slug'
] as $k => $v) {
    Hook::set('panel.m.editor', $v, $k);
}

function panel_m_page() {
    extract(Lot::get(null, []));
    echo '<fieldset>';
    echo '<legend>' . $language->editor . '</legend>';
    Hook::fire('panel.m.editor');
    echo '</fieldset>';
    panel_f_state();
}

Hook::set('panel.m', 'panel_m_' . $site->type, 10);

function panel_m() {
    extract(Lot::get(null, []));
    echo '<main class="main">';
    echo $__message;
    Hook::fire('panel.m');
    echo Form::token();
    echo '</main>';
}

Hook::set('panel', 'panel_s_left', 10);
Hook::set('panel', 'panel_m', 20);