<?php if (substr($__path, -2) === '/+' || strpos($__path, '/+/') !== false): ?>
<?php else: ?>
<?php

$__a = [
    'title' => 1,
    'description' => 1,
    'author' => 1,
    'type' => 1,
    'link' => 1,
    'content' => 1,
    'time' => 1,
    'kind' => 1,
    'slug' => 1,
    'state' => 1
];

$__aparts = Page::apart($__sgr === 'g' ? file_get_contents($__page[0]->path) : "");
foreach ($__aparts as $__k => $__v) {
    if (isset($__a[$__k])) {
        unset($__aparts[$__k]);
        continue;
    }
    $__aparts[$__k] = is_array($__v) ? json_encode($__v) : s($__v);
}

?>
<?php echo __panel_s__('data', [
    'title' => $language->{count($__datas[0]) + count($__aparts) === 1 ? 'data' : 'datas'},
    'content' => $__sgr === 'g' ? $__datas : [[], []],
    'after' => '<p>' . Form::textarea('__data', To::yaml($__aparts), $language->f_yaml, ['classes' => ['textarea', 'block', 'code']]) . '</p>',
    'a' => [
        HTML::a('&#x2795;', $__state->path . '/::s::/' . rtrim(explode('/+/', $__path . '/')[0], '/') . '/+', false, ['title' => $language->add])
    ],
    'if' => $__sgr !== 's'
]); ?>
<?php echo __panel_s__('child', [
    'content' => $__childs,
    'a' => [
        HTML::a('&#x2795;', $__state->path . '/::s::/' . $__path, false, ['title' => $language->add]),
        $__is_has_step_child ? ' ' . HTML::a('&#x22EF;', $__state->path . '/::g::/' . $__path . '/2', false, ['title' => $language->more]) : null
    ],
    'if' => count($__chops) > 1
]); ?>
<?php endif; ?>